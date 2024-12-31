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
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['error'] = "Invalid username";
            header("Location: index.php");
            exit;
        }
        
        if (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Invalid password";
            $_SESSION['temp_username'] = $_POST['username'];
            header("Location: index.php");
            exit;
        }

        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = 'admin'; 
        header("Location: vehicle_page.php");
        exit;
        
    } catch(PDOException $e) {
        $_SESSION['error'] = "Login error occurred";
        header("Location: index.php");
        exit;
    }
}

header("Location: index.php");
exit;
?>