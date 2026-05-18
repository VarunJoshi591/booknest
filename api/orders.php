<?php
error_reporting(0);
ini_set('display_errors', 0);
// ============================================================
//  api/orders.php  —  Orders API (requires login)
//
//  POST  /api/orders.php?action=place    Place order from cart
//  GET   /api/orders.php                 Get user's order history
//  GET   /api/orders.php?id=12           Single order details
// ============================================================

require_once __DIR__ . '/../includes/config.php';
startSession();
header('Content-Type: application/json');

$user   = requireAuth();          // Redirects with 401 if not logged in
$userId = (int) $user['id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$db     = getDB();

// ── PLACE ORDER ───────────────────────────────────────────
if ($method === 'POST' && $action === 'place') {
    // Fetch cart from DB
    $stmt = $db->prepare('
        SELECT c.book_id, c.qty, b.price, b.title, b.stock
        FROM   cart c JOIN books b ON b.id = c.book_id
        WHERE  c.user_id = ?
    ');
    $stmt->execute([$userId]);
    $items = $stmt->fetchAll();

    if (!$items) jsonFail('Your cart is empty.');

    // Check stock for all items first
    foreach ($items as $item) {
        if ($item['qty'] > $item['stock']) {
            jsonFail('"' . $item['title'] . '" has only ' . $item['stock'] . ' copies in stock.');
        }
    }

    $subtotal = array_sum(array_map(fn($i) => $i['price'] * $i['qty'], $items));
    $shipping = $subtotal >= 500 ? 0 : 49;
    $total    = $subtotal + $shipping;

    // ── Transaction ──────────────────────────────────────
    $db->beginTransaction();
    try {
        // Create order with PENDING payment status
        $db->prepare('INSERT INTO orders (user_id, total_amount, shipping, status, payment_status) VALUES (?, ?, ?, "pending", "pending")')
           ->execute([$userId, $total, $shipping]);
        $orderId = (int) $db->lastInsertId();

        // Insert order items (but don't reduce stock yet - wait for payment)
        $insItem = $db->prepare('INSERT INTO order_items (order_id, book_id, qty, price) VALUES (?, ?, ?, ?)');

        foreach ($items as $item) {
            $insItem->execute([$orderId, $item['book_id'], $item['qty'], $item['price']]);
        }

        // Clear cart after order is placed
        $db->prepare('DELETE FROM cart WHERE user_id = ?')->execute([$userId]);

        $db->commit();

        jsonOk(['order_id' => $orderId, 'total' => $total], 'Order placed successfully! Please complete payment.');
    } catch (Exception $e) {
        $db->rollBack();
        error_log('Order placement error: ' . $e->getMessage());
        jsonFail('Order could not be placed: ' . $e->getMessage());
    }
}

// ── SINGLE ORDER DETAILS ──────────────────────────────────
if ($method === 'GET' && isset($_GET['id'])) {
    $orderId = (int) $_GET['id'];
    $stmt    = $db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ?');
    $stmt->execute([$orderId, $userId]);
    $order   = $stmt->fetch();
    if (!$order) jsonFail('Order not found.', 404);

    $stmt2 = $db->prepare('
        SELECT oi.*, b.title, b.author, b.cover_color AS color
        FROM   order_items oi JOIN books b ON b.id = oi.book_id
        WHERE  oi.order_id = ?
    ');
    $stmt2->execute([$orderId]);
    $order['items'] = $stmt2->fetchAll();

    jsonOk($order);
}

// ── ORDER HISTORY ─────────────────────────────────────────
if ($method === 'GET') {
    $stmt = $db->prepare('
        SELECT o.id, o.total_amount, o.shipping, o.status, o.payment_status, 
               o.payment_method, o.payment_id, o.placed_at, o.paid_at
        FROM   orders o
        WHERE  o.user_id = ?
        ORDER  BY o.placed_at DESC
    ');
    $stmt->execute([$userId]);
    $orders = $stmt->fetchAll();
    
    // Get items for each order
    foreach ($orders as &$order) {
        $stmt2 = $db->prepare('
            SELECT oi.qty, oi.price, b.title, b.author
            FROM   order_items oi JOIN books b ON b.id = oi.book_id
            WHERE  oi.order_id = ?
        ');
        $stmt2->execute([$order['id']]);
        $order['items'] = $stmt2->fetchAll();
    }
    
    jsonOk($orders);
}

jsonFail('Invalid request.');
