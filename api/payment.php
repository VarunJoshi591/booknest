<?php
error_reporting(0);
ini_set('display_errors', 0);


require_once __DIR__ . '/../includes/config.php';
startSession();
header('Content-Type: application/json');

$user   = requireAuth();
$userId = (int) $user['id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$body   = json_decode(file_get_contents('php://input'), true) ?? [];
$db     = getDB();

if ($method === 'POST' && $action === 'process') {
    $orderId = (int) ($body['order_id'] ?? 0);
    $paymentMethod = $body['payment_method'] ?? '';
    $paymentData = $body['payment_data'] ?? [];

    if (!$orderId) jsonFail('Order ID is required.');
    if (!$paymentMethod) jsonFail('Payment method is required.');

    $stmt = $db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? AND payment_status IN ("pending", "failed")');
    $stmt->execute([$orderId, $userId]);
    $order = $stmt->fetch();
    
    if (!$order) {
        // Check if the order is already paid
        $checkStmt = $db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? AND payment_status = "completed"');
        $checkStmt->execute([$orderId, $userId]);
        $alreadyPaid = $checkStmt->fetch();

        if ($alreadyPaid) {
            jsonOk([
                'payment_id' => $alreadyPaid['payment_id'],
                'order_id' => $orderId,
                'amount' => $alreadyPaid['total_amount'],
                'method' => $alreadyPaid['payment_method'],
                'card_last4' => '****' 
            ], 'Order already paid. Redirecting to receipt...');
        }

        error_log("Payment attempt for non-existent, unauthorized, or already paid order: OrderID=$orderId, UserID=$userId");
        jsonFail('Order not found or already paid.');
    }

    $stmt = $db->prepare('
        SELECT oi.book_id, oi.qty, b.title, b.stock 
        FROM order_items oi 
        JOIN books b ON b.id = oi.book_id 
        WHERE oi.order_id = ?
    ');
    $stmt->execute([$orderId]);
    $orderItems = $stmt->fetchAll();

    foreach ($orderItems as $item) {
        if ($item['qty'] > $item['stock']) {
            jsonFail('"' . $item['title'] . '" has only ' . $item['stock'] . ' copies in stock.');
        }
    }

    
    $paymentId = 'PAY_' . strtoupper(uniqid()) . '_' . time();

    
    $success = false;
    $message = '';
    $cardLast4 = '';

    if ($paymentMethod === 'card') {
        $cardNumber = $paymentData['card_number'] ?? '';
        $cvv = $paymentData['cvv'] ?? '';
        $expiry = $paymentData['expiry'] ?? '';
        $name = $paymentData['name'] ?? '';
        
        if (strlen($cardNumber) >= 16 && strlen($cvv) >= 3 && $expiry && $name) {
            $success = true;
            $message = 'Card payment processed successfully';
            $cardLast4 = substr($cardNumber, -4);
        } else {
            $message = 'Invalid card details';
        }
    } elseif ($paymentMethod === 'upi') {
        $upiId = $paymentData['upi_id'] ?? '';
        
        if (strpos($upiId, '@') !== false) {
            $success = true;
            $message = 'UPI payment processed successfully';
        } else {
            $message = 'Invalid UPI ID';
        }
    }

    if ($success) {
        
        $db->beginTransaction();
        try {
        
            $stmt = $db->prepare('
                UPDATE orders 
                SET payment_status = "completed", 
                    payment_method = ?, 
                    payment_id = ?, 
                    paid_at = NOW(),
                    status = "confirmed"
                WHERE id = ?
            ');
            $stmt->execute([$paymentMethod, $paymentId, $orderId]);

            $decrStock = $db->prepare('UPDATE books SET stock = stock - ? WHERE id = ?');
            foreach ($orderItems as $item) {
                $decrStock->execute([$item['qty'], $item['book_id']]);
            }

            $db->commit();

            jsonOk([
                'payment_id' => $paymentId,
                'order_id' => $orderId,
                'amount' => $order['total_amount'],
                'method' => $paymentMethod,
                'card_last4' => $cardLast4
            ], $message);
        } catch (Exception $e) {
            $db->rollBack();
            error_log('Payment processing error: ' . $e->getMessage());
            jsonFail('Payment processing failed: ' . $e->getMessage());
        }
    } else {
    
        $stmt = $db->prepare('UPDATE orders SET payment_status = "failed" WHERE id = ?');
        $stmt->execute([$orderId]);
        
        jsonFail($message);
    }
}


if ($method === 'GET' && $action === 'status') {
    $orderId = (int) ($_GET['order_id'] ?? 0);
    
    if (!$orderId) jsonFail('Order ID is required.');
    
    $stmt = $db->prepare('
        SELECT payment_status, payment_method, payment_id, paid_at, total_amount 
        FROM orders 
        WHERE id = ? AND user_id = ?
    ');
    $stmt->execute([$orderId, $userId]);
    $payment = $stmt->fetch();
    
    if (!$payment) jsonFail('Payment not found.');
    
    jsonOk($payment);
}

jsonFail('Invalid request.');