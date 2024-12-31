<?php
session_start();
require_once '../config/db.php';

// Check for admin access
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Admin access required";
    header('Location: vehicle_page.php');
    exit();
}

// Get messages
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

// Clear messages
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="../style/style.css" rel="stylesheet">    
    <link href="../style/output.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-bold mb-6">Add New User</h2>
        
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="add_users.php" class="max-w-lg" onsubmit="return validateForm()">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username:</label>
                <input type="text" name="username" id="username" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password:</label>
                <input type="password" name="password" id="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            </div>

            <div class="mb-6">
                <h3 class="text-lg font-bold mb-3">Permissions</h3>
                <div class="mb-4">
                    <h4 class="font-bold mb-2">Vehicle Permissions</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="delete_vehicle" id="delete_vehicle" class="mr-2"> Can Delete Vehicles
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="edit_vehicle" id="edit_vehicle" class="mr-2"> Can Edit Vehicles
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="add_vehicle" id="add_vehicle" class="mr-2"> Can Add Vehicles
                        </label>
                    </div>
                </div>

                <div class="mb-4">
                    <h4 class="font-bold mb-2">Equipment Permissions</h4>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" name="delete_equipment" id="delete_equipment" class="mr-2"> Can Delete Equipment
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="edit_equipment" id="edit_equipment" class="mr-2"> Can Edit Equipment
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="add_equipment" id="add_equipment" class="mr-2"> Can Add Equipment
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold py-2 px-4 rounded">
                Create User
            </button>
        </form>
    </div>

    <script src="../scripts/adduser.js"></script>
</body>
</html>