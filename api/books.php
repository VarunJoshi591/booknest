<?php
// ============================================================
//  api/books.php  —  Books Catalog API
//
//  GET  /api/books.php                   All books
//  GET  /api/books.php?id=5              Single book
//  GET  /api/books.php?search=harry      Search
//  GET  /api/books.php?genre=Fiction     Filter by genre
//  GET  /api/books.php?sort=price-asc    Sort options:
//                                          price-asc | price-desc | rating
//  (params can be combined)
// ============================================================

require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');

$db = getDB();

// ── Single book ───────────────────────────────────────────
if (!empty($_GET['id'])) {
    $id   = (int) $_GET['id'];
    $stmt = $db->prepare('SELECT * FROM books WHERE id = ?');
    $stmt->execute([$id]);
    $book = $stmt->fetch();
    if (!$book) jsonFail('Book not found.', 404);
    jsonOk($book);
}

// ── Build dynamic query ───────────────────────────────────
$where  = ['1=1'];
$params = [];

if (!empty($_GET['search'])) {
    $q        = '%' . $_GET['search'] . '%';
    $where[]  = '(title LIKE ? OR author LIKE ?)';
    $params[] = $q;
    $params[] = $q;
}

if (!empty($_GET['genre'])) {
    $where[]  = 'genre = ?';
    $params[] = $_GET['genre'];
}

// Sort
$sortMap = [
    'price-asc'  => 'price ASC',
    'price-desc' => 'price DESC',
    'rating'     => 'rating DESC',
    ''           => 'id ASC',
];
$sort = $_GET['sort'] ?? '';
$orderBy = $sortMap[$sort] ?? 'id ASC';

$sql  = 'SELECT * FROM books WHERE ' . implode(' AND ', $where) . ' ORDER BY ' . $orderBy;
$stmt = $db->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();

jsonOk($books);
