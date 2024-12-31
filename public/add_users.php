<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Admin access required";
    header('Location: vehicle_page.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['error_message'] = "Username and password are required";
        header('Location: add_UsersPage.php');
        exit();
    }

    // Check for at least one permission
    $permissions = [
        'delete_vehicle', 'edit_vehicle', 'add_vehicle',
        'delete_equipment', 'edit_equipment', 'add_equipment'
    ];
    
    $has_permission = false;
    foreach ($permissions as $permission) {
        if (isset($_POST[$permission])) {
            $has_permission = true;
            break;
        }
    }

    if (!$has_permission) {
        $_SESSION['error_message'] = "At least one permission is required";
        header('Location: add_UsersPage.php');
        exit();
    }

    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = trim($_POST['email']);
    $role = 'user';
    
    // Permissions
    $can_delete_vehicle = isset($_POST['delete_vehicle']) ? 1 : 0;
    $can_edit_vehicle = isset($_POST['edit_vehicle']) ? 1 : 0;
    $can_add_vehicle = isset($_POST['add_vehicle']) ? 1 : 0;
    $can_delete_equipment = isset($_POST['delete_equipment']) ? 1 : 0;
    $can_edit_equipment = isset($_POST['edit_equipment']) ? 1 : 0;
    $can_add_equipment = isset($_POST['add_equipment']) ? 1 : 0;

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role, 
            can_delete_vehicle, can_edit_vehicle, can_add_vehicle, 
            can_delete_equipment, can_edit_equipment, can_add_equipment) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([$username, $password, $email, $role, 
            $can_delete_vehicle, $can_edit_vehicle, $can_add_vehicle,
            $can_delete_equipment, $can_edit_equipment, $can_add_equipment]);

        $_SESSION['success_message'] = "User created successfully";
        header('Location: add_UsersPage.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error_message'] = $e->getCode() == 23000 ? 
            "Username or email already exists" : "Error creating user";
        header('Location: add_UsersPage.php');
        exit();
    }
}

// Redirect if accessed directly
header('Location: add_UsersPage.php');
exit();
?>