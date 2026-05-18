<?php
// ============================================================
//  api/cart.php — Clean Shopping Cart API
// ============================================================

require_once __DIR__ . '/../includes/config.php';
startSession();
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$body = json_decode(file_get_contents('php://input'), true) ?? [];
$userId = $_SESSION['user_id'] ?? null;

// ── GET CART ──────────────────────────────────────────────
if ($method === 'GET') {
    $items = [];
    $count = 0;
    $subtotal = 0;
    
    if ($userId) {
        // Logged in user - get from database
        $db = getDB();
        $stmt = $db->prepare('
            SELECT c.book_id as id, b.title, b.author, b.price, c.qty, b.cover_color
            FROM cart c 
            JOIN books b ON b.id = c.book_id 
            WHERE c.user_id = ?
        ');
        $stmt->execute([$userId]);
        $items = $stmt->fetchAll();
    } else {
        // Guest user - get from session
        $guestCart = $_SESSION['guest_cart'] ?? [];
        if (!empty($guestCart)) {
            $ids = array_keys($guestCart);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $db = getDB();
            $stmt = $db->prepare("SELECT id, title, author, price, cover_color FROM books WHERE id IN ($placeholders)");
            $stmt->execute($ids);
            $books = $stmt->fetchAll();
            
            foreach ($books as $book) {
                $book['qty'] = $guestCart[$book['id']];
                $items[] = $book;
            }
        }
    }
    
    // Calculate totals
    foreach ($items as $item) {
        $count += $item['qty'];
        $subtotal += $item['price'] * $item['qty'];
    }
    
    $shipping = ($subtotal >= 500 || $subtotal == 0) ? 0 : 49;
    $total = $subtotal + $shipping;
    
    jsonOk([
        'items' => $items,
        'count' => $count,
        'subtotal' => $subtotal,
        'shipping' => $shipping,
        'total' => $total
    ]);
}

// ── ADD TO CART ───────────────────────────────────────────
if ($method === 'POST' && $action === 'add') {
    $bookId = (int)($body['book_id'] ?? 0);
    $qty = (int)($body['qty'] ?? 1);
    
    if (!$bookId || $bookId <= 0) {
        jsonFail('Valid Book ID is required');
    }
    
    if ($qty <= 0) {
        jsonFail('Quantity must be greater than 0');
    }
    
    // Check if book exists
    $db = getDB();
    $stmt = $db->prepare('SELECT id, title, stock FROM books WHERE id = ?');
    $stmt->execute([$bookId]);
    $book = $stmt->fetch();
    
    if (!$book) {
        jsonFail('Book not found');
    }
    
    if ($book['stock'] < $qty) {
        jsonFail('Not enough stock available');
    }
    
    try {
        if ($userId) {
            // Verify user exists before adding to cart
            $stmt = $db->prepare('SELECT id FROM users WHERE id = ?');
            $stmt->execute([$userId]);
            if (!$stmt->fetch()) {
                jsonFail('Invalid user session. Please login again.');
            }
            
            // Logged in user - save to database
            $stmt = $db->prepare('
                INSERT INTO cart (user_id, book_id, qty) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)
            ');
            $stmt->execute([$userId, $bookId, $qty]);
        } else {
            // Guest user - save to session
            if (!isset($_SESSION['guest_cart'])) {
                $_SESSION['guest_cart'] = [];
            }
            $_SESSION['guest_cart'][$bookId] = ($_SESSION['guest_cart'][$bookId] ?? 0) + $qty;
        }
        
        jsonOk([], $book['title'] . ' added to cart!');
    } catch (Exception $e) {
        jsonFail('Failed to add item to cart: ' . $e->getMessage());
    }
}

// ── REMOVE FROM CART ──────────────────────────────────────
if ($method === 'POST' && $action === 'remove') {
    $bookId = (int)($body['book_id'] ?? 0);
    
    if (!$bookId || $bookId <= 0) {
        jsonFail('Valid Book ID is required');
    }
    
    try {
        if ($userId) {
            $db = getDB();
            $stmt = $db->prepare('DELETE FROM cart WHERE user_id = ? AND book_id = ?');
            $stmt->execute([$userId, $bookId]);
        } else {
            if (isset($_SESSION['guest_cart'][$bookId])) {
                unset($_SESSION['guest_cart'][$bookId]);
            }
        }
        
        jsonOk([], 'Item removed from cart');
    } catch (Exception $e) {
        jsonFail('Failed to remove item from cart: ' . $e->getMessage());
    }
}

// ── CLEAR CART ────────────────────────────────────────────
if ($method === 'POST' && $action === 'clear') {
    if ($userId) {
        $db = getDB();
        $stmt = $db->prepare('DELETE FROM cart WHERE user_id = ?');
        $stmt->execute([$userId]);
    } else {
        $_SESSION['guest_cart'] = [];
    }
    
    jsonOk([], 'Cart cleared');
}

jsonFail('Invalid request');
?>