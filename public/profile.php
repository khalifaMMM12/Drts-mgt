<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../style/style.css" rel="stylesheet">
    <link href="../style/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Profile page</title>
</head>
<body class="bg-gray-100 ">
    <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-yellow-500 text-black">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

<div class="flex h-screen">

    <div class="hidden md:flex flex-col w-64 rounded-r-2xl shadow-2xl bg-yellow-500">
        <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen rounded-xl shadow-lg transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
            <div class="flex flex-col flex-1">
                <nav class="flex flex-col flex-1 px-2 py-4 gap-10">
                    <div>
                        <a href="#" class="flex items-center text-gray-100 hover:bg-gray-700">
                            <img class="w-20" src="../img/DRTS_logo.png" alt="DRTS Logo">
                            <h2 class="font-bold text-black text-lg">Directorate of Road Traffic Services</h2>
                        </a>
                    </div>
                    <div class="flex flex-col flex-1 gap-3">
                        <a href="vehicle_page.php" class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                            <i class="fa-solid fa-car mr-2"></i> Vehicles
                        </a>
                        <a href="equipment.php" class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                            <i class="fas fa-tools mr-2"></i> Equipment
                        </a>
                         <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="add_UsersPage.php" 
                                class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                                <i class="fas fa-user-plus mr-2"></i> Add User
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="flex flex-col flex-1 overflow-y-auto transition-margin duration-300 ease-in-out">

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

    
    <div class="container mx-auto p-4 md:p-6 lg:px-8">
        <h1 class="text-3xl font-bold text-black mb-6">User Profile</h1>

        <!-- LOGOUT MODAL -->
        <div id="logoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-xl">
                <h2 class="text-xl font-bold mb-4">Confirm Logout</h2>
                <p class="mb-6">Are you sure you want to logout?</p>
                <div class="flex justify-end gap-4">
                    <button onclick="closeLogoutModal()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Cancel</button>
                    <a href="logout.php" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Logout</a>
                </div>
            </div>
        </div>

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($user)): ?>
    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border-2 border-yellow-400">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- User Details -->
            <div>
                <h2 class="text-xl font-bold mb-4">User Information</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Username</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Role</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($user['role']); ?></p>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Account Created</label>
                        <p class="text-gray-900"><?php echo htmlspecialchars($user['created_at']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Change Password Form -->
            <div>
                <h2 class="text-xl font-bold mb-4">Change Password</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2" for="current_password">
                            Current Password
                        </label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" required
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                            <i class="fas fa-eye absolute right-3 top-3 cursor-pointer" id="toggleCurrentPassword"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2" for="new_password">
                            New Password
                        </label>
                        <div class="relative">
                            <input type="password" id="new_password" name="new_password" required
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                            <i class="fas fa-eye absolute right-3 top-3 cursor-pointer" id="toggleNewPassword"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2" for="confirm_password">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <input type="password" id="confirm_password" name="confirm_password" required
                                class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:border-yellow-500">
                            <i class="fas fa-eye absolute right-3 top-3 cursor-pointer" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                    <button type="submit" 
                        class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        User information not available
    </div>
<?php endif; ?>
    
        
    </div>
</div>
    <script src="../scripts/profile.js"></script>
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
</body>
</html>
