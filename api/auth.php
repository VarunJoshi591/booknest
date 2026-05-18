<?php
// ============================================================
//  api/auth.php  —  User Authentication API
//
//  POST  /api/auth.php?action=register   Register new user
//  POST  /api/auth.php?action=login      Login user
//  POST  /api/auth.php?action=logout     Logout user
//  GET   /api/auth.php?action=me         Get current session user
// ============================================================

require_once __DIR__ . '/../includes/config.php';
startSession();
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];
$body   = json_decode(file_get_contents('php://input'), true) ?? [];

// ── REGISTER ──────────────────────────────────────────────
if ($action === 'register' && $method === 'POST') {
    $name     = trim($body['full_name'] ?? '');
    $email    = strtolower(trim($body['email'] ?? ''));
    $password = $body['password'] ?? '';

    // Validation
    if (!$name || !$email || !$password) {
        jsonFail('All fields are required.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonFail('Invalid email address.');
    }
    if (strlen($password) < 6) {
        jsonFail('Password must be at least 6 characters.');
    }

    $db = getDB();

    // Check duplicate
    $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonFail('Email already registered. Please login.');
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare('INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$name, $email, $hash]);

    jsonOk([], 'Account created successfully! Please login.');
}

// ── LOGIN ─────────────────────────────────────────────────
if ($action === 'login' && $method === 'POST') {
    $email    = strtolower(trim($body['email'] ?? ''));
    $password = $body['password'] ?? '';

    if (!$email || !$password) {
        jsonFail('Email and password are required.');
    }

    $db   = getDB();
    $stmt = $db->prepare('SELECT id, full_name, email, password, role, created_at FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        jsonFail('Invalid email or password.', 401);
    }

    // Store session
    $_SESSION['user_id']         = $user['id'];
    $_SESSION['user_name']       = $user['full_name'];
    $_SESSION['user_email']      = $user['email'];
    $_SESSION['user_role']       = $user['role'];
    $_SESSION['user_created_at'] = $user['created_at'];

    // Merge guest cart (if any) into DB
    if (!empty($_SESSION['guest_cart'])) {
        $dbCart = getDB();
        foreach ($_SESSION['guest_cart'] as $bookId => $qty) {
            $s = $dbCart->prepare('
                INSERT INTO cart (user_id, book_id, qty)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE qty = qty + VALUES(qty)
            ');
            $s->execute([$user['id'], $bookId, $qty]);
        }
        unset($_SESSION['guest_cart']);
    }

    jsonOk([
        'id'         => $user['id'],
        'full_name'  => $user['full_name'],
        'email'      => $user['email'],
        'role'       => $user['role'],
        'created_at' => $user['created_at'],
    ], 'Welcome back, ' . $user['full_name'] . '!');
}

// ── LOGOUT ────────────────────────────────────────────────
if ($action === 'logout' && $method === 'POST') {
    session_unset();
    session_destroy();
    jsonOk([], 'Logged out successfully.');
}

// ── ME (session check) ────────────────────────────────────
if ($action === 'me') {
    if (empty($_SESSION['user_id'])) {
        jsonFail('Not logged in.', 401);
    }
    jsonOk([
        'id'         => $_SESSION['user_id'],
        'full_name'  => $_SESSION['user_name'],
        'email'      => $_SESSION['user_email'],
        'role'       => $_SESSION['user_role'],
        'created_at' => $_SESSION['user_created_at'] ?? null,
    ]);
}

jsonFail('Unknown action.', 404);
