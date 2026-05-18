<?php
// Simple test API to check if PHP and database are working
require_once __DIR__ . '/../includes/config.php';
startSession();
header('Content-Type: application/json');

try {
    // Test database connection
    $db = getDB();
    $stmt = $db->prepare('SELECT COUNT(*) as count FROM books');
    $stmt->execute();
    $result = $stmt->fetch();
    
    // Test session
    $sessionId = session_id();
    
    echo json_encode([
        'success' => true,
        'message' => 'API is working!',
        'data' => [
            'books_count' => $result['count'],
            'session_id' => $sessionId,
            'user_logged_in' => !empty($_SESSION['user_id']),
            'user_id' => $_SESSION['user_id'] ?? null,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>