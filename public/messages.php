<?php
session_start();
require 'db.php';

$me = $_SESSION['user_id'];
$other = $_GET['user'] ?? 0;

// Mark messages as read
$pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?")
    ->execute([$other, $me]);

// Load messages
$stmt = $pdo->prepare("
  SELECT * FROM messages 
  WHERE (sender_id = ? AND receiver_id = ?) 
     OR (sender_id = ? AND receiver_id = ?)
  ORDER BY timestamp ASC
");
$stmt->execute([$me, $other, $other, $me]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Chat with <?php
$user = $pdo->prepare("SELECT username FROM users WHERE id = ?");
$user->execute([$other]);
echo htmlspecialchars($user->fetchColumn());
?></h4>

<div class="chat-box border p-3 mb-3" style="height: 400px; overflow-y: scroll;">
<?php foreach ($messages as $msg): ?>
  <div class="mb-2 <?php echo $msg['sender_id'] == $me ? 'text-end' : 'text-start'; ?>">
    <small class="d-block text-muted"><?php echo $msg['timestamp']; ?></small>
    <span class="badge bg-<?php echo $msg['sender_id'] == $me ? 'primary' : 'secondary'; ?>">
      <?php echo htmlspecialchars($msg['content']); ?>
    </span>
  </div>
<?php endforeach; ?>
</div>

<form action="send_message.php" method="POST">
  <input type="hidden" name="to" value="<?php echo $other; ?>">
  <div class="input-group">
    <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
    <button class="btn btn-success">Send</button>
  </div>
</form>
