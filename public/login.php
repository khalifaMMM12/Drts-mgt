<?php
session_start();
echo "Session ID: " . session_id() . "<br>";
var_dump($_SESSION);
include '../config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password required";
        header("Location: ../index.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin';
            header("Location: vehicle_page.php");
            exit;
        }

        // If not admin, check users table
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        var_dump($password, $user['password'], password_verify($password, $user['password']));
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'user';
            $_SESSION['permissions'] = array(
                'delete_vehicle' => (int)$user['can_delete_vehicle'],
                'edit_vehicle' => (int)$user['can_edit_vehicle'],
                'add_vehicle' => (int)$user['can_add_vehicle'],
                'delete_equipment' => (int)$user['can_delete_equipment'],
                'edit_equipment' => (int)$user['can_edit_equipment'],
                'add_equipment' => (int)$user['can_add_equipment']
            );
            header("Location: vehicle_page.php");
            exit;
        }

        // If no match found
        $_SESSION['error'] = "Invalid username or password";
        header("Location: ../index.php");
        exit;
        
    } catch(PDOException $e) {
        $_SESSION['error'] = "Login error occurred";
        header("Location: ../index.php");
        exit;
    }
}

// Redirect if accessed directly
header("Location: ../index.php");
exit;
?>