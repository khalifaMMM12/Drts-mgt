<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $valid_username = "admin";
    $valid_password = "password123";

    if ($username == $valid_username && $password == $valid_password) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: vehicle_page.php");
        exit;
    } else {
        $_SESSION['error'] = "Invalid username or password";
        header("Location: index.php");
        exit;
    }
}
?>
