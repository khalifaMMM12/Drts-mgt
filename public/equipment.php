<?php
session_start();

// Check if user is not logged in
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
    <title>Office Equipment</title>
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
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 bg-gray-900 hover:bg-gray-500 rounded-2xl">
                            <i class="fa-solid fa-user-plus mr-2"></i>
                            Add Users
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 bg-gray-900 hover:bg-gray-500 rounded-2xl">
                            <i class="fa-solid fa-id-badge mr-2"></i>
                            Profile
                        </a>
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
                        <!-- <h2 class="font-bold text-xl md:text-3xl text-white">DRTS</h2> -->
                        <div class="flex items-center gap-4">
                            <label for="equipmentSelect" class="text-white font-medium">Select Equipment:</label>
                            <select id="equipmentSelect" class="p-2 border rounded-lg text-black focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-transparent bg-white">
                                <option value="solar">Solar</option>
                                <option value="airConditioners">Air Conditioners</option>
                                <option value="fireExtinguishers">Fire Extinguishers</option>
                                <option value="borehole">Borehole</option>
                                <option value="generator">Generators</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                        <button id="addEquipmentButton" 
                            class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                            <i class="fas fa-plus"></i> Add Equipment
                        </button>
                        
                        <button onclick="openLogoutModal()" 
                            class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto p-4 md:p-6 lg:px-8">
        <h1 class="text-3xl font-bold text-black mb-6">DRTS Equipments</h1>

        <!-- Modal -->
        <div id="addEquipmentModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
            <div class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
                <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>
                <h2 id="modalTitle" class="text-xl font-bold mb-4"></h2>
                <form id="addEquipmentForm">
                    <input type="hidden" id="equipmentType" name="equipmentType">
                    <div id="fields" class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <!-- Fields input -->
                        
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="cancelButton" class="mr-4 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400">
                            Cancel
                        </button>
                        <button type="submit" class="bg-yellow-500 text-white py-2 px-4 rounded-lg shadow hover:bg-yellow-600">
                            Add Equipment
                        </button>
                    </div>
                </form>
            </div>
        </div>

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

        

        <!-- Table for Displaying Equipment Data -->
        <div id="equipmentTableContainer" class="overflow-x-auto">
            <table id="solarTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Capacity</th>
                        <th class="p-4 border-b">Battery Type</th>
                        <th class="p-4 border-b">No. of Batteries</th>
                        <th class="p-4 border-b">No. of Panels</th>
                        <th class="p-4 border-b">Date Added</th>
                        <th class="p-4 border-b">Action</th>
                        <!-- <th class="p-4 border-b">Service Rendered</th> -->
                    </tr>
                </thead>
                <tbody id="solarData"></tbody>
            </table>

            <table id="airConditionersTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Model</th>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">No. of Units</th>
                        <th class="p-4 border-b">Capacity</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="airConditionersData"></tbody>
            </table>

            <table id="fireExtinguishersTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">Weight</th>
                        <th class="p-4 border-b">Amount</th>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Last Service Date</th>
                        <th class="p-4 border-b">Expiration Date</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="fireExtinguishersData"></tbody>
            </table>

            <table id="boreholeTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Model</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="boreholeData"></tbody>
            </table>

            <table id="generatorTable" class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base hidden">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Model</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">no_of_units</th>
                        <th class="p-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody id="generatorData"></tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>

    <script src="../scripts/Equipments.js"></script>
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
</body>
</html>
