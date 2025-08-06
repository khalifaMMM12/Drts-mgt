<?php
session_start();
require '../config/db.php';

if (
    !isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true
) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#newChatModal">+ New Chat</button>

<!-- add new chat modal -->
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Start a New Chat</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <ul class="list-group">
           <?php
            $stmt = $pdo->prepare("SELECT user_id, username FROM users WHERE user_id != ?");
            $stmt->execute([$user_id]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) === 0): ?>
                <li class="list-group-item text-muted">No other users found.</li>
            <?php else:
                foreach ($users as $u): ?>
                    <li class="list-group-item">
                        <a href="messages.php?user=<?php echo $u['user_id']; ?>">
                            <?php echo htmlspecialchars($u['username']); ?>
                        </a>
                    </li>
            <?php endforeach; endif; ?>
            </ul>
        </div>
        </div>
    </div>
 </div>

 <?php
 $stmt = $pdo->prepare("
  SELECT u.user_id, u.username, m.content, MAX(m.timestamp) AS last_time
  FROM messages m
  JOIN users u ON u.user_id = IF(m.sender_id = ?, m.receiver_id, m.sender_id)
  WHERE m.sender_id = ? OR m.receiver_id = ?
  GROUP BY u.user_id
  ORDER BY last_time DESC
");
$stmt->execute([$user_id, $user_id, $user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Your Chats</h4>
<ul class="list-group">
<?php foreach ($chats as $chat): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <a href="messages.php?user=<?php echo $chat['user_id']; ?>">
      <?php echo htmlspecialchars($chat['username']); ?>
    </a>
    <span class="text-muted small"><?php echo substr($chat['content'], 0, 30); ?>...</span>
  </li>
<?php endforeach; ?>
</ul>
