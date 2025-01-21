<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}


// Initialize variables
$user = null;
$error = null;
$success = null;

// Fetch user data
try {
    $table = ($_SESSION['role'] === 'admin') ? 'admin' : 'users';
    $query = ($table === 'admin') 
        ? "SELECT id, username, role, password FROM admin WHERE username = ?"
        : "SELECT id, username, role, password, created_at FROM users WHERE username = ?";
    
    error_log("Using table: " . $table);
    error_log("Username: " . $_SESSION['username']);

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

    error_log("User data: " . print_r($user, true));

} catch(PDOException $e) {
    $error = $e->getMessage();
    error_log("Profile Error: " . $error);
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