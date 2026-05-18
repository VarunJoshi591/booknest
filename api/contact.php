<?php
// ============================================================
//  api/contact.php  —  Contact Form API
//
//  POST  /api/contact.php    Submit a contact message
//        Body: { name, email, subject, message }
// ============================================================

require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') jsonFail('Method not allowed.', 405);

$body    = json_decode(file_get_contents('php://input'), true) ?? [];
$name    = trim($body['name']    ?? '');
$email   = trim($body['email']   ?? '');
$subject = trim($body['subject'] ?? 'General Question');
$message = trim($body['message'] ?? '');

if (!$name || !$email || !$message) jsonFail('Name, email and message are required.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) jsonFail('Invalid email address.');
if (strlen($message) < 10) jsonFail('Message is too short.');

$db   = getDB();
$stmt = $db->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
$stmt->execute([$name, $email, $subject, $message]);

jsonOk([], "Message sent! We'll reply to $email soon.");
