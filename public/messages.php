<?php
session_start();
require '../config/db.php';

$me = $_SESSION['user_id'];
$my_role = $_SESSION['role'];
$other = $_GET['user'] ?? 0;
$other_role = $_GET['role'] ?? 'user';

// Mark messages as read
$pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?")
    ->execute([$other, $me]);

// Load messages
$stmt = $pdo->prepare("
  SELECT * FROM messages 
  WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))
  ORDER BY timestamp ASC
");
$stmt->execute([$me, $other, $other, $me]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($other_role === 'admin') {
    $partnerStmt = $pdo->prepare("SELECT username FROM admin WHERE id = ?");
} else {
    $partnerStmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
}
$partnerStmt->execute([$other]);
$partner_name = $partnerStmt->fetchColumn();

if($my_role === 'admin') {
    $meStmt = $pdo->prepare("SELECT username FROM admin WHERE id = ?");
} else {
    $meStmt = $pdo->prepare("SELECT username FROM users WHERE user_id = ?");
}
$meStmt->execute([$me]);
$my_name = $meStmt->fetchColumn();
?>

<h4>Chat with <?php echo htmlspecialchars($partner_name); ?></h4> 
<div class="chat-box border p-3 mb-3" style="height: 400px; overflow-y: scroll;">    
<?php foreach ($messages as $msg): ?>
    <?php
        if ($msg['sender_id'] == $me) {
            $sender = $my_name . " (You)";
            $badge = 'primary';
            $align = 'text-end';
        } else {
            $sender = $partner_name;
            $badge = 'secondary';
            $align = 'text-start';
        }
    ?>
    <div class="mb-2 <?php echo $align; ?>">
        <small class="d-block text-muted"><?php echo $msg['timestamp']; ?> - <?php echo htmlspecialchars($sender); ?></small>
        <span class="badge bg-<?php echo $badge; ?>">
            <?php echo htmlspecialchars($msg['content']); ?>
        </span>
    </div>
<?php endforeach; ?>
</div>

<form action="send_message.php" method="POST">
  <input type="hidden" name="to" value="<?php echo $other; ?>">
  <input type="hidden" name="role" value="<?php echo htmlspecialchars($other_role); ?>">
  <div class="input-group">
    <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
    <button class="btn btn-success">Send</button>
  </div>
</form>
