<?php
session_start();
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password required";
        header("Location: index.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header("Location: vehicle_page.php");
            exit;
        }

        // If not admin, check users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'user';
            $_SESSION['permissions'] = [
                'delete_vehicle' => $user['can_delete_vehicle'],
                'edit_vehicle' => $user['can_edit_vehicle'],
                'add_vehicle' => $user['can_add_vehicle'],
                'delete_equipment' => $user['can_delete_equipment'],
                'edit_equipment' => $user['can_edit_equipment'],
                'add_equipment' => $user['can_add_equipment']
            ];
            header("Location: vehicle_page.php");
            exit;
        }

        // If no match found
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.php");
        exit;
        
    } catch(PDOException $e) {
        $_SESSION['error'] = "Login error occurred";
        header("Location: index.php");
        exit;
    }
}

// Redirect if accessed directly
header("Location: index.php");
exit;
?>