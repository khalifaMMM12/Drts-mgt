<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../index.php");
    exit;
}

require_once '../includes/auth_functions.php';
include '../config/db.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM vehicles WHERE reg_no LIKE :search OR type LIKE :search OR location LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute([':search' => '%' . $search . '%']);
$vehicles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
     
<head>
    <meta charset="UTF-8">
    <title>Vehicle page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="description" content="DRTS Assests Management - vehicle page">
    <link href="../style/style.css" rel="stylesheet">
    <link href="../style/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-200">

<div class="flex h-screen overflow-hidden">
    <!-- Side Bar -->
        <div id="sidebar" class="bg-yellow-500 rounded-r-2xl text-white w-64 min-h-screen overflow-y-auto transition-transform transform -translate-x-full md:translate-x-0 ease-in-out duration-300">
            <nav class="flex flex-col flex-1 px-2 py-4 gap-10">
                <div>
                    <a href="#" class="flex items-center text-gray-100">
                            <img class="w-20" src="../img/DRTS_logo.png" alt="DRTS Logo">
                            <h2 class="font-bold text-black text-lg">DRTS Assests Management</h2>
                    </a>
                </div>
                <div class="flex flex-col flex-1 gap-3">
                    <a href="equipment.php" class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                            <i class="fas fa-tools mr-2"></i> Equipment
                    </a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="add_UsersPage.php" 
                                class="hover:bg-opacity-25 rounded-2xl bg-gray-900  hover:bg-gray-400 text-white px-4 py-2 flex items-center">
                                <i class="fas fa-user-plus mr-2"></i> Add User
                        </a>
                    <?php endif; ?>
                    <a href="profile.php" class="flex items-center px-4 py-2 text-gray-100 bg-gray-900 hover:bg-gray-500 rounded-2xl">
                        <i class="fa-solid fa-id-badge mr-2"></i>
                            Profile
                    </a>
                </div>
            </nav>
        </div>

    <div class="flex-1 flex flex-col overflow-hidden ml-0 md:ml-64 p-4">
        
    <!-- Navigation Bar -->
        <div class="grid xl:grid-cols-1 grid-cols-1">
                <div class="p-2 md:p-5 py-2 md:py-3 px-2 md:px-3 rounded-xl border-yellow-400 border-4 md:border-8 bg-gray-900">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4">
                        <div class="flex items-center gap-2 md:gap-4 w-full md:w-auto">
                            <button id="open-sidebar" class="md:hidden top-4 left-4 z-50 p-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-black">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                                </svg>
                            </button>
                            <h2 class="font-bold text-xl md:text-3xl text-white"><?php echo htmlspecialchars($_SESSION['username']); ?></h2>
                        </div>

                        <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                            <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                                <?php if (hasPermission('add_vehicle') || isAdmin()): ?>
                                    <button onclick="openModal()" 
                                        class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                                        <i class="fas fa-plus"></i> Add Vehicle
                                    </button>
                                <?php endif; ?>
                                
                                <button onclick="openLogoutModal()" 
                                    class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Main Content Container -->
        <div class="">
            <h1 class="text-3xl font-bold text-gray-900 m3r-4 mt-4 mb-6">Vehicle Management</h1>
            
            <div class="grid xl:grid-cols-1 grid-cols-1">
                <div class="flex flex-wrap items-center p-2 rounded-xl bg-gray-800 mb-6 space-x-2">
                    <!-- Search Bar -->
                    <div class="w-auto flex">
                        <form method="GET" action="vehicle_page.php" class="flex">
                            <input type="text" 
                                name="search" 
                                id="searchInput"
                                placeholder="Search by registration, type, or location" 
                                value="<?php echo htmlspecialchars($search); ?>"
                                class="border border-yellow-400 p-2 text-xs sm:text-sm md:text-base w-40 sm:w-56 md:w-96 rounded-l focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                            <button type="submit" 
                                class="bg-yellow-500 shrink-0 text-black font-semibold px-2 sm:px-4 py-2 rounded-r hover:bg-yellow-600 text-xs sm:text-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <!-- Filters removed -->
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

        <!-- Delete Model  -->
        <div id="deleteModal" class="modal-overlay items-center justify-center hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4">
            <div id="deleteModalcontent" class="modal-content relative mx-auto shadow-xl rounded-md bg-white max-w-md">
                <div class="p-6 pt-0 text-center">
                    <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                        <h3 class="text-xl font-normal text-black mt-5 mb-6">Are you sure you want to delete vehicle with registration number: 
                        <span id="deleteVehicleRegNo" class="font-bold text-red-800"></span>
                    </h3>
                    <a href="#" id="confirmDelete" 
                    class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2">
                    Yes, I'm sure
                    </a>
                    <button onclick="closeDeleteModal()" id="cancelDelete"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center">
                    No, cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle List Table -->
        <div class="flex-1 h-full overflow-auto"> 
            <div class="border-gray-500">
                <table class="w-full bg-white shadow-lg rounded text-sm md:text-base">
                    <thead class="bg-yellow-500 text-black">
                        <tr>
                            <th class="p-4 border-b">S/N</th>
                            <th class="p-4 border-b">Reg No</th>
                            <th class="p-4 border-b">Make</th>
                            <th class="p-4 border-b">Type</th>
                            <th class="p-4 border-b">Location</th>
                            
                            <th class="p-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $counter = 1; foreach ($vehicles as $vehicle): ?>
                            <tr class="hover:bg-gray-500" data-vehicle-id="<?php echo $vehicle['id']; ?>">
                                <td class="p-4 border-b font-bold "><?php echo $counter++; ?></td>
                                <td class="p-4 border-b uppercase"><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                                <td class="p-4 border-b"><?php echo htmlspecialchars(ucwords(strtolower($vehicle['make']))); ?></td>
                                <td class="p-4 border-b"><?php echo htmlspecialchars(ucwords(strtolower($vehicle['type']))); ?></td>
                                <td class="p-4 border-b"><?php echo htmlspecialchars(ucwords(strtolower($vehicle['location']))); ?></td>
                                <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
                                    <button onclick="showDetails(<?php echo $vehicle['id']; ?>)" class="text-blue-500 hover:text-blue-700">ℹ</button>
                                    <?php if (hasPermission('edit_vehicle') || isAdmin()): ?>
                                        <button 
                                            id="editButton-<?php echo $vehicle['id']; ?>" 
                                            onclick="editVehicle(<?php echo $vehicle['id']; ?>)" 
                                            class="text-yellow-500 hover:text-yellow-700">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>  
                                    <?php endif; ?>
                                    <?php if (hasPermission('delete_vehicle') || isAdmin()): ?>
                                        <button class="text-red-500 hover:text-red-700 delete-button" data-vehicle-id="<?php echo $vehicle['id']; ?>" data-vehicle-regno="<?php echo $vehicle['reg_no']; ?>" onclick="openDeleteModal(<?php echo $vehicle['id']; ?>, '<?php echo $vehicle['reg_no']; ?>')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button> 
                                    <?php endif; ?>                           
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

  <!-- Vehicle Details Modal -->
<div id="detailsModal" class="modal-overlay fixed inset-0 bg-gray-500 bg-opacity-75 hidden items-center justify-center p-4">
    <div id="detailsModalContent" class="modal-content relative bg-white p-8 rounded-lg shadow-2xl border-4 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl">
        <button onclick="closeDetailsModal()" id="closeDetails" class="absolute top-4 right-4 text-gray-600 text-3xl font-bold hover:text-gray-800">&times;</button>
        
        <h2 class="text-2xl mb-6 text-gray-800 font-semibold border-b-2 border-gray-200 pb-2">Vehicle Details</h2>
        
        <div id="vehicleDetails" class="text-gray-700 space-y-3 mb-6">
            <p><strong>Registration Number:</strong> <span id="detailRegNo"></span></p>
            <p><strong>Type:</strong> <span id="detailType"></span></p>
            <p><strong>Make:</strong> <span id="detailMake"></span></p>
            <p><strong>Location:</strong> <span id="detailLocation"></span></p>
            
        </div>

        <!-- Image Gallery Thumbnails -->
        <div id="imageGallery" class="grid grid-cols-3 gap-4 mb-6">
            <!-- Thumbnails populated dynamically with JavaScript -->
        </div>
        
        <!-- Enlarged Image View with Carousel Controls -->
        <div id="carouselModal" class="hidden fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center">
            <div class="relative w-full h-full flex items-center justify-center p-4">

                <button onclick="closeCarousel()" class="absolute top-4 right-4 text-white text-4xl font-bold hover:text-yellow-400 z-50 transition-colors duration-200">&times;</button>
                
                <button id="prevImage" onclick="showPrevImage()" class="absolute left-4 text-white hover:text-yellow-400 text-6xl font-bold z-50 transition-colors duration-200 hidden md:block">&larr;</button>
                
                <div class="relative flex items-center justify-center w-full h-full">
                    <img id="enlargedImg" 
                        class="max-h-[90vh] max-w-[90vw] w-auto h-auto object-contain rounded-lg transition-transform duration-200 cursor-zoom-in"
                        alt="vehicle image">
                </div>
                
                <button id="nextImage" onclick="showNextImage()" class="absolute right-4 text-white hover:text-yellow-400 text-6xl font-bold z-50 transition-colors duration-200 hidden md:block">&rarr;</button>

                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black bg-opacity-50 px-4 py-2 rounded-full text-sm">
                    <span id="currentImageIndex">1</span> / <span id="totalImages">1</span>
                </div>
            </div>
        </div>

    </div>
</div>

    <!-- Add Vehicle Modal -->
    <div id="vehicleModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 items-center justify-center p-4">
        <div id="vehicleModalContent" class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
            <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>            
            <h2 class="text-xl mb-4 text-yellow-500 font-bold">Add Vehicle</h2>
            <form action="add_vehicle.php" id="addVehicleForm" method="POST" enctype="multipart/form-data">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block">Registration No:</label>
                        <input type="text" name="reg_no" required class="border p-2 w-full mb-4">
                    </div>
                    <div>
                        <label class="block font-semibold">Vehicle Type</label>
                        <select name="type" class="border border-gray-300 p-2 w-full rounded" required>
                            <option value="" disabled selected>Select a type</option>
                            <option value="Saloon">Saloon</option>
                            <option value="SUV">SUV</option>
                            <option value="Pickup">Pickup</option>
                            <option value="Bus">Bus</option>
                            <option value="Bike">Bike</option>
                            <option value="Van">Van</option>
                            <option value="Truck">Truck</option>
                            <option value="Tow Truck">Tow Truck</option>
                            <option value="Wagon">Wagon</option>
                        </select>
                    </div>
                    <div>
                        <label>Make:</label>
                        <input type="text" name="make" required class="border p-2 w-full mb-4">
                    </div>
                    <div>
                        <label>Location:</label>
                        <input type="text" name="location" required class="border p-2 w-full mb-4">
                    </div>
                    <div class="md:col-span-2">
                        <label title="Click to upload" for="add_images" class="cursor-pointer flex items-center gap-4 px-6 py-4 relative group">
                            <div class="w-max relative z-10">
                                <img class="w-12" src="https://www.svgrepo.com/show/485545/upload-cicle.svg" alt="file upload icon" width="512" height="512">
                            </div>
                            <div class="relative z-10">
                                <span class="block text-base font-semibold relative text-blue-900 group-hover:text-blue-500">
                                        Upload Vehicle Images
                                </span>
                                <span class="mt-0.5 block text-sm text-gray-500">Max 2 MB</span>
                            </div>
                            <span class="absolute inset-0 border-dashed border-2 border-gray-400/60 rounded-3xl group-hover:border-gray-300 z-0"></span>
                            <span class="absolute inset-0 bg-gray-100 rounded-3xl transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-105 active:scale-95 z-0"></span>
                        </label>
                        <input hidden type="file" name="images[]" id="add_images" accept="image/*" multiple>
                        <div id="addImageError" class="text-red-600 text-sm mt-1"></div>
                    </div>
                </div>
                <div id="imagePreview" class="col-span-2 grid grid-cols-2 gap-4 md:grid-cols-4 rounded-3xl mt-2"></div>
                <!-- Image preview script moved to vehicle.js -->
                <button type="submit" name="submit" class="bg-yellow-500 text-black p-2 rounded mt-4 w-full md:w-auto">Submit</button>
            </form>
        </div>
    </div>


<!-- Edit vehicle Modal -->
    <div id="EditvehicleModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 items-center justify-center p-4">
    <div id="EditvehicleContent" class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>
        <h2 class="text-xl mb-4 text-yellow-500 font-bold">Edit Vehicle</h2>
        <form action="edit_vehicle.php" id="editVehicleForm" onsubmit="submitEditForm(event)" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block">Registration No:</label>
                    <input type="text" name="reg_no" id="reg_no" value="<?php echo htmlspecialchars($vehicle['reg_no']); ?>" class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label class="block font-semibold">Vehicle Type</label>
                    <select name="type" id="type" class="border border-gray-300 p-2 w-full rounded">
                        <option value="" disabled>Select a type</option>
                        <option value="Saloon" <?php if ($vehicle['type'] === 'Saloon') echo 'selected'; ?>>Saloon</option>
                        <option value="SUV" <?php if ($vehicle['type'] === 'SUV') echo 'selected'; ?>>SUV</option>
                        <option value="Pickup" <?php if ($vehicle['type'] === 'Pickup') echo 'selected'; ?>>Pickup</option>
                        <option value="Bus" <?php if ($vehicle['type'] === 'Bus') echo 'selected'; ?>>Bus</option>
                        <option value="Bike" <?php if ($vehicle['type'] === 'Bike') echo 'selected'; ?>>Bike</option>
                        <option value="Van" <?php if ($vehicle['type'] === 'Van') echo 'selected'; ?>>Van</option>
                        <option value="Truck" <?php if ($vehicle['type'] === 'Truck') echo 'selected'; ?>>Truck</option>
                        <option value="Tow Truck" <?php if ($vehicle['type'] === 'Tow Truck') echo 'selected'; ?>>Tow Truck</option>
                        <option value="Wagon" <?php if ($vehicle['type'] === 'Wagon') echo 'selected'; ?>>Wagon</option>
                        <option value="Coupe" <?php if ($vehicle['type'] === 'Coupe') echo 'selected'; ?>>Coupe</option>
                        <option value="Convertible" <?php if ($vehicle['type'] === 'Convertible') echo 'selected'; ?>>Convertible</option>
                    </select>
                </div>
                <div>
                    <label>Make:</label>
                    <input type="text" name="make" id="make" value="<?php echo htmlspecialchars($vehicle['make']); ?>" class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label>Location:</label>
                    <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($vehicle['location']); ?>" class="border p-2 w-full mb-4">
                </div>
                <div>
                    
                </div>
                <input type="hidden" name="id" id="vehicleId" value="<?php echo htmlspecialchars($vehicle['id']); ?>">
                <div class="relative">
                    <label title="Click to upload" for="new_images" class="cursor-pointer flex items-center gap-4 px-6 py-4 relative group">
                        <div class="w-max relative z-10">
                            <img class="w-12" src="https://www.svgrepo.com/show/485545/upload-cicle.svg" alt="file upload icon" width="512" height="512">
                        </div>
                        <div class="relative z-10">
                            <span class="block text-base font-semibold relative text-blue-900 group-hover:text-blue-500">
                                    Upload New Images
                            </span>
                            <span class="mt-0.5 block text-sm text-gray-500">Max 2 MB</span>
                        </div>
                        <span class="absolute inset-0 border-dashed border-2 border-gray-400/60 rounded-3xl group-hover:border-gray-300 z-0"></span>
                        <span class="absolute inset-0 bg-gray-100 rounded-3xl transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-105 active:scale-95 z-0"></span>
                    </label>
                    <input hidden="" type="file" name="new_images[]" id="new_images" onchange="uploadNewImage(document.getElementById('vehicleId').value)" accept="image/*" multiple>
                </div>

                <!-- Image Preview Section -->
                <div id="editImagePreview" class="col-span-2 grid grid-cols-2 gap-4 md:grid-cols-4 rounded-3xl">
                    <?php if (!empty($vehicle['images'])): ?>
                        <?php foreach (explode(',', $vehicle['images']) as $index => $image): ?>
                            <div class="relative group">
                                <img src="../assets/vehicles/<?php echo htmlspecialchars(trim($image)); ?>"
                                    class="w-24 h-24 md:w-32 md:h-32 object-cover rounded-xl shadow-lg border border-gray-200">
                                <button type="button"
                                    onclick="deleteImage('<?php echo htmlspecialchars(trim($image)); ?>', <?php echo $vehicle['id']; ?>, this.parentElement)"
                                    class="absolute opacity-0 group-hover:opacity-100 flex items-center justify-center top-2 right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 z-10 transition-opacity duration-200">
                                ×
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            <button type="submit" name="submit" class="bg-yellow-500 text-black p-2 rounded mt-4 w-full md:w-auto">Update Vehicle</button>
        </form>
    </div>
</div>
</div>
</div>

    <!-- Script -->
    <script>
         window.userPermissions = <?php 
            echo json_encode($_SESSION['permissions'] ?? [], JSON_FORCE_OBJECT); 
        ?>;
        window.isAdmin = <?php 
            echo isset($_SESSION['role']) && $_SESSION['role'] === 'admin' ? 'true' : 'false'; 
        ?>;
    </script>
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
    <script src="../scripts/vehicle.js"></script>
    <script src="../scripts/editVehicle.js"></script>
    <script src="../scripts/delete.js"></script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('table tbody');
    let debounceTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            fetch(`api/search_vehicles.php?search=${encodeURIComponent(searchInput.value)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';
                    if (!data.vehicles || data.vehicles.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center p-4">No vehicles found</td></tr>';
                        return;
                    }
                    data.vehicles.forEach((vehicle, idx) => {
                        tableBody.innerHTML += `
                        <tr class="hover:bg-gray-500" data-vehicle-id="${vehicle.id}">
                            <td class="p-4 border-b font-bold ">${idx + 1}</td>
                            <td class="p-4 border-b uppercase">${vehicle.reg_no}</td>
                            <td class="p-4 border-b">${vehicle.type.charAt(0).toUpperCase() + vehicle.type.slice(1).toLowerCase()}</td>
                            <td class="p-4 border-b">${vehicle.make.charAt(0).toUpperCase() + vehicle.make.slice(1).toLowerCase()}</td>
                            <td class="p-4 border-">${vehicle.location.charAt(0).toUpperCase() + vehicle.location.slice(1).toLowerCase()}</td>
                            
                            <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
                                <button onclick="showDetails(${vehicle.id})" class="text-blue-500 hover:text-blue-700">ℹ</button>
                                ${(window.userPermissions && (window.userPermissions.edit_vehicle || window.isAdmin)) ? `<button id="editButton-${vehicle.id}" onclick="editVehicle(${vehicle.id})" class="text-yellow-500 hover:text-yellow-700"><i class=\"fa-solid fa-pen-to-square\"></i></button>` : ''}
                                ${(window.userPermissions && (window.userPermissions.delete_vehicle || window.isAdmin)) ? `<button class=\"text-red-500 hover:text-red-700 delete-button\" data-vehicle-id=\"${vehicle.id}\" data-vehicle-regno=\"${vehicle.reg_no}\" onclick=\"openDeleteModal(${vehicle.id}, '${vehicle.reg_no}')\"><i class=\"fa-solid fa-trash-can\"></i></button>` : ''}
                            </td>
                        </tr>`;
                    });
                });
        }, 300);
    });
});
    </script>

</body>
</html>
