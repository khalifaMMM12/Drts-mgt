<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    $_SESSION['error_message'] = "Admin access required";
    header('Location: vehicle_page.php');
    exit();
}

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta http-equiv="refresh" content="30">
    <meta name="description" content="DRTS Assests Management - add user page">
    <link href="../style/style.css" rel="stylesheet">    
    <link href="../style/output.css" rel="stylesheet">
</head>
<body class="bg-gray-200">
    <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-yellow-500 text-black">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

<div class="flex h-screen">
    <div class="hidden md:flex flex-col w-64  rounded-r-2xl shadow-2xl bg-yellow-500">
        <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen rounded-2xl shadow-2xl transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col flex-1">
                <nav class="flex flex-col flex-1 px-2 py-4 gap-10">
                    <div>
                        <div class="flex items-center text-gray-100">
                            <img class="w-20" src="../img/DRTS_logo.png" alt="DRTS Logo">
                            <h2 class="font-bold text-black text-lg">Directorate of Road Traffic Services</h2>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 gap-3">
                        <a href="equipment.php" class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                            <i class="fas fa-tools mr-2"></i> Equipment
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="vehicle_page.php" 
                                class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                                <i class="fa-solid fa-car mr-2"></i> Vehicles
                            </a>
                        <?php endif; ?>
                        <a href="profile.php" class="flex items-center px-4 py-2 text-gray-100 bg-gray-900 hover:bg-gray-500 rounded-2xl">
                            <i class="fa-solid fa-id-badge mr-2"></i>
                            Profile
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="flex flex-col flex-1 overflow-y-auto transition-margin duration-300 ease-in-out">
        
    <!-- Navigation Bar -->
    <div class="grid xl:grid-cols-1 grid-cols-1">
        <div class="p-2 md:p-5">
            <div class="py-2 md:py-3 px-2 md:px-3 rounded-xl border-yellow-400 border-4 md:border-8 bg-gray-900">
                <div class="flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4">
                    <div class="flex items-center gap-2 md:gap-4 w-full md:w-auto">
                        <h2 class="font-bold text-xl md:text-3xl text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                        <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                            <button onclick="openLogoutModal()" 
                                class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div id="logoutModal" class="modal-overlay items-center justify-center hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4">
            <div id="logoutModalcontent" class="modal-content relative mx-auto shadow-xl rounded-md bg-white max-w-md">
                <div class="p-6 text-center">
                    <h2 class="text-2xl text-red-600 font-bold mb-4">Confirm Logout</h2>
                    <p class="mb-6 text-xl text-black">Are you sure you want to logout?</p>
                    <div class="flex justify-center gap-4">
                        <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">No</button>
                        <a href="logout.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Yes</a>
                    </div>
                </div>
            </div>
        </div>


    <div class="container mx-auto md:p-5 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 m3r-4 mb-6">Add New User</h2>
        
    <div class="mt-7 bg-white rounded-xl shadow-lg border-2 border-yellow-400">
    <div class="p-4 sm:p-7">
        <?php if ($success_message): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="add_users.php" class="w-full" onsubmit="return validateForm()">
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column - User Details -->
                <div class="border-r pr-8 mr-2">
                    <h3 class="text-xl font-bold mb-6 pb-2 border-b">User Details</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block font-bold text-black-500" for="username">Username:</label>
                            <input type="text" name="username" id="username" required 
                                class="w-full p-2 mb-6 shadow-lg text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300">
                        </div>
                        <div>
                            <label class="block font-bold text-black-500" for="password">Password:</label>
                            <div class="relative">
                            <input type="password" name="password" id="password" required 
                                    class="w-full p-2 mb-6 shadow-lg text-black-700 border-b-4 border-amber-500 outline-none focus:bg-gray-300">
                                   <i class="fas fa-eye absolute right-3 top-3 cursor-pointer z-10" id="togglePassword"></i>
                            </div>       
                        </div>
                    </div>
                </div>

                <!-- Right Column - Permissions -->
                <div class="">
                    <h3 class="text-xl font-bold mb-6 pb-2 border-b">Permissions</h3>
                    <!-- Vehicle Permissions -->
                    <div class="mb-8">
                        <h4 class="font-bold mb-4">Vehicle Permissions</h4>
                        <div class="space-y-3">
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="delete_vehicle" id="delete_vehicle" class="w-4 h-4 mr-3">
                                Can Delete Vehicles
                            </label>
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="edit_vehicle" id="edit_vehicle" class="w-4 h-4 mr-3">
                                Can Edit Vehicles
                            </label>
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="add_vehicle" id="add_vehicle" class="w-4 h-4 mr-3">
                                Can Add Vehicles
                            </label>
                        </div>
                    </div>

                    <!-- Equipment Permissions -->
                    <div>
                        <h4 class="font-bold mb-4">Equipment Permissions</h4>
                        <div class="space-y-3">
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="delete_equipment" id="delete_equipment" class="w-4 h-4 mr-3">
                                Can Delete Equipment
                            </label>
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="edit_equipment" id="edit_equipment" class="w-4 h-4 mr-3">
                                Can Edit Equipment
                            </label>
                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" name="add_equipment" id="add_equipment" class="w-4 h-4 mr-3">
                                Can Add Equipment
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-8 pt-6 border-t">
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-black font-bold p-2 px-8 rounded">
                    Create User
                </button>
            </div>
        </form>
    </div>
</div>
    </div>
        
    </div>
    </div>

    <!-- <script src="../scripts/vehicle.js"></script> -->
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
    <script src="../scripts/adduser.js"></script>
</body>
</html>