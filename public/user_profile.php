<?php
require_once '../config/db.php';

$user = null;
$error = null;
$success = null;

try {
    $table = ($_SESSION['role'] === 'admin') ? 'admin' : 'users';
    $fields = $table === 'admin' ? 
        ['id', 'username', 'role'] : 
        ['id', 'username', 'role', 'created_at', 'can_delete_vehicle', 'can_edit_vehicle', 'can_add_vehicle'];
    
    $query = "SELECT " . implode(', ', $fields) . ", password FROM $table WHERE username = ?";

    // Execute query
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_SESSION['username']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $table === 'admin') {
        $user['created_at'] = date('Y-m-d H:i:s');
    }

    if (!$user) {
        throw new Exception("User not found in database");
    }

    error_log("User data fetched from $table: " . print_r($user, true));

} catch(PDOException $e) {
    $error = $e->getMessage();
    error_log("User profile error: " . $error);
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user)) {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    try {
        if (!password_verify($currentPassword, $user['password'])) {
            $error = "Current password is incorrect";
        } else if ($newPassword !== $confirmPassword) {
            $error = "New passwords do not match";
        } else if (strlen($newPassword) < 6) {
            $error = "Password must be at least 6 characters";
        } else {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE $table SET password = ? WHERE username = ?");
            $stmt->execute([$hashedPassword, $_SESSION['username']]);
            $success = "Password updated successfully";
        }
    } catch(PDOException $e) {
        $error = "Failed to update password";
        error_log("Password Update Error: " . $e->getMessage());
    }
}
?>