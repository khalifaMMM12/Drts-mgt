<?php
session_start();
require '../config/db.php';

$from = $_SESSION['user_id'];
$to = $_POST['to'];
$message = trim($_POST['message']);

if ($message !== '') {
  $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, content) VALUES (?, ?, ?)");
  $stmt->execute([$from, $to, $message]);
}

header("Location: messages.php?user=$to");
exit;
