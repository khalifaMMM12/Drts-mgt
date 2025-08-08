<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['unread' => 0]);
    exit;
}

require_once '../../config/db.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) AS unread
    FROM messages
    WHERE receiver_id = :uid AND is_read = 0
");
$stmt->execute(['uid' => $user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(['unread' => (int)($row['unread'] ?? 0)]);