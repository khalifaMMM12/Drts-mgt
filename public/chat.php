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
            $stmt = $pdo->prepare("
                SELECT user_id, username, 'user' as role FROM users WHERE user_id != ?
                UNION
                SELECT id as user_id, username, 'admin' as role FROM admin WHERE id != ?
            ");
            $stmt->execute([$user_id, $user_id]);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($users) === 0): ?>
                <li class="list-group-item text-muted">No other users found.</li>
            <?php else:
                foreach ($users as $u): ?>
                    <li class="list-group-item">
                        <a href="messages.php?user=<?php echo $u['user_id']; ?>">
                            <?php echo htmlspecialchars($u['username']); ?>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($u['role']); ?></span>
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
    SELECT
        t.partner_id,
        t.partner_name,
        t.partner_role,
        m.content,
        m.timestamp
    FROM (
        SELECT 
            CASE 
                WHEN m.sender_id = :uid THEN m.receiver_id
                ELSE m.sender_id
            END AS partner_id,
            COALESCE(u.username, a.username) AS partner_name,
            CASE 
                WHEN u.user_id IS NOT NULL THEN 'user'
                WHEN a.id IS NOT NULL THEN 'admin'
                ELSE ''
            END AS partner_role,
            MAX(m.timestamp) AS last_time
        FROM messages m
        LEFT JOIN users u ON (u.user_id = CASE WHEN m.sender_id = :uid THEN m.receiver_id ELSE m.sender_id END)
        LEFT JOIN admin a ON (a.id = CASE WHEN m.sender_id = :uid THEN m.receiver_id ELSE m.sender_id END)
        WHERE m.sender_id = :uid OR m.receiver_id = :uid
        GROUP BY partner_id, partner_name, partner_role
    ) t
    JOIN messages m ON (
        (m.sender_id = :uid AND m.receiver_id = t.partner_id OR m.sender_id = t.partner_id AND m.receiver_id = :uid)
        AND m.timestamp = t.last_time
    )
    ORDER BY m.timestamp DESC
");
$stmt->execute(['uid' => $user_id]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h4>Your Chats</h4>
<ul class="list-group">
<?php foreach ($chats as $chat): ?>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    <a href="messages.php?user=<?php echo $chat['partner_id']; ?>&role=<?php echo $chat['partner_role']; ?>" class="text-decoration-none">
        <strong><?php echo htmlspecialchars($chat['partner_name']); ?></strong>
        <span class="badge bg-secondary"><?php echo htmlspecialchars($chat['partner_role']); ?></span>
        <span class="badge bg-primary"><?php echo htmlspecialchars($chat['content']); ?></span>
    </a>
  </li>
<?php endforeach; ?>
</ul>
