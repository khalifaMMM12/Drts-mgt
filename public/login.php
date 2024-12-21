<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $_SESSION['error'] = "Username and password required";
        header("Location: index.php");
        exit;
    }
    
    $valid_username = "admin";
    $valid_password = "password123";

    if ($username !== $valid_username) {
        $_SESSION['error'] = "Invalid username";
        header("Location: index.php");
        exit;
    }
    
    if ($username === $valid_username && $password !== $valid_password) {
        $_SESSION['error'] = "Invalid password";
        header("Location: index.php");
        exit;
    }

    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: vehicle_page.php");
        exit;
    }
}

header("Location: index.php");
exit;
?>