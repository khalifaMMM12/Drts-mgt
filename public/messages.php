
<?php
session_start();
require '../config/db.php';

$me = $_SESSION['user_id'];
$my_role = $_SESSION['role'];
$other = $_GET['user'] ?? 0;
$other_role = $_GET['role'] ?? 'user';

$pdo->prepare("UPDATE messages SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?")
    ->execute([$other, $me]);

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

$chatListStmt = $pdo->prepare("
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
$chatListStmt->execute(['uid' => $me]);
$chats = $chatListStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../style/ChatStyles.css">
    <script>
    </script>
</head>
<body>
<div class="chat-container">
    <div class="sidebar" id="sidebar"<?php if ($other && $other != 0) echo ' style="display:none;"'; ?>>
        <div class="sidebar-header" style="display:flex;align-items:center;justify-content:space-between;">
            <span>Chats</span>
            <button class="btn btn-primary btn-sm" style="margin-left:10px;" data-bs-toggle="modal" data-bs-target="#newChatModal">+ New Chat</button>
        </div>
        <ul class="chat-list">
            <?php foreach ($chats as $c): ?>
                <li>
                    <a href="messages.php?user=<?php echo $c['partner_id']; ?>&role=<?php echo $c['partner_role']; ?>"
                       <?php if($c['partner_id'] == $other && $c['partner_role'] == $other_role) echo 'class="active"'; ?> >
                        <strong><?php echo htmlspecialchars($c['partner_name']); ?></strong>
                        <span style="font-size:0.8em;color:#888;">(<?php echo htmlspecialchars($c['partner_role']); ?>)</span>
                        <div style="font-size:0.9em;color:#555;"><?php echo htmlspecialchars($c['content']); ?></div>
                        <div style="font-size:0.8em;color:#aaa;"><?php echo htmlspecialchars($c['timestamp']); ?></div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <!-- New Chat Modal -->
        <div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newChatModalLabel">Start a New Chat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                        <?php
                        $existing_ids = array_map(function($c) { return $c['partner_id']; }, $chats);
                        $existing_ids[] = $me;
                        $placeholders = implode(',', array_fill(0, count($existing_ids), '?'));
                        $userStmt = $pdo->prepare("SELECT user_id, username FROM users WHERE user_id NOT IN ($placeholders)");
                        $userStmt->execute($existing_ids);
                        $other_users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($other_users) === 0): ?>
                            <li class="list-group-item text-muted">No other users found.</li>
                        <?php else:
                            foreach ($other_users as $u): ?>
                                <li class="list-group-item">
                                    <a href="messages.php?user=<?php echo $u['user_id']; ?>&role=user" class="start-chat-link" data-bs-dismiss="modal">
                                        <?php echo htmlspecialchars($u['username']); ?>
                                    </a>
                                </li>
                        <?php endforeach; endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-panel<?php if ($other && $other != 0) echo ' active'; ?>" id="chatPanel"<?php if (!($other && $other != 0)) echo ' style="display:none;"'; ?>>
    <?php if ($other && $other != 0): ?>
        <div class="chat-main">
            <div class="chat-header">
                <button class="back-btn btn btn-secondary" onclick="goBack()" style="display:inline-block;">&larr; Back</button>
                <h4 style="margin:0;">Chat with <?php echo htmlspecialchars($partner_name); ?></h4>
            </div>
            <div class="chat-box">
                <?php foreach ($messages as $msg): ?>
                    <?php
                    if ($msg['sender_id'] == $me) {
                        $sender = $my_name . " (You)";
                        $align = 'text-end';
                    } else {
                        $sender = $partner_name;
                        $align = 'text-start';
                    }
                    ?>
                    <div class="chat-message <?php echo $align; ?>">
                        <div class="sender"><?php echo htmlspecialchars($sender); ?> <span style="font-size:0.8em;color:#aaa;\"><?php echo $msg['timestamp']; ?></span></div>
                        <div class="msg"><?php echo htmlspecialchars($msg['content']); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
            <form action="send_message.php" method="POST" class="chat-form">
                <input type="hidden" name="to" value="<?php echo $other; ?>">
                <input type="hidden" name="role" value="<?php echo htmlspecialchars($other_role); ?>">
                <div class="input-group">
                    <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                    <button class="btn btn-success">Send</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="chat-main" style="display:flex;align-items:center;justify-content:center;flex:1;">
            <div style="text-align:center;color:#888;font-size:1.1em;">Select a chat to start messaging</div>
        </div>
    <?php endif; ?>
    </div>
</div>
<script>
    function goBack() {
        document.getElementById('sidebar').style.display = '';
        document.getElementById('chatPanel').style.display = 'none';
        setTimeout(reattachNewChatModal, 100);
    }

    function reattachNewChatModal() {
        var modal = document.getElementById('newChatModal');
        if (modal && window.bootstrap) {
            var bsModal = window.bootstrap.Modal.getOrCreateInstance(modal);
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(btn) {
                btn.onclick = function(e) {
                    e.preventDefault();
                    bsModal.show();
                };
            });
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.start-chat-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                this.blur();
                e.preventDefault();
                var href = this.getAttribute('href');
                var modal = document.getElementById('newChatModal');
                if (modal && window.bootstrap) {
                    var bsModal = window.bootstrap.Modal.getOrCreateInstance(modal);
                    bsModal.hide();
                }
                window.location.href = href;
            });
        });
        reattachNewChatModal();
        if (window.location.search.match(/user=\d+/)) {
            document.getElementById('sidebar').style.display = 'none';
            document.getElementById('chatPanel').style.display = '';
        }
    });
</script>
</body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</html>
