<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (empty($_GET['book_id'])) {
        jsonFail('Book ID is required', 400);
    }
    
    $bookId = (int)$_GET['book_id'];
    
    $stmt = $db->prepare('
        SELECT r.*, u.full_name as user_name 
        FROM reviews r 
        JOIN users u ON r.user_id = u.id 
        WHERE r.book_id = ? 
        ORDER BY r.date DESC
    ');
    $stmt->execute([$bookId]);
    $reviews = $stmt->fetchAll();
    
    jsonOk($reviews);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['user_id'])) {
        jsonFail('You must be logged in to review a book.', 401);
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['book_id']) || empty($data['rating'])) {
        jsonFail('Book ID and rating are required', 400);
    }
    
    $userId = $_SESSION['user_id'];
    $bookId = (int)$data['book_id'];
    $rating = (int)$data['rating'];
    $comment = $data['comment'] ?? '';
    
    if ($rating < 1 || $rating > 5) {
        jsonFail('Rating must be between 1 and 5', 400);
    }
    
    // Check if user already reviewed this book
    $check = $db->prepare('SELECT id FROM reviews WHERE user_id = ? AND book_id = ?');
    $check->execute([$userId, $bookId]);
    if ($check->fetch()) {
        jsonFail('You have already reviewed this book.', 400);
    }
    
    try {
        $stmt = $db->prepare('INSERT INTO reviews (user_id, book_id, rating, comment) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $bookId, $rating, $comment]);
        
        jsonOk(['message' => 'Review added successfully']);
    } catch (PDOException $e) {
        jsonFail('Failed to add review: ' . $e->getMessage(), 500);
    }
}
