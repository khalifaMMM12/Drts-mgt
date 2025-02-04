<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
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
    <link href="../style/style.css" rel="stylesheet">
    <link href="../style/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-200">
    <button id="mobile-menu-button" class="block md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-yellow-500 text-black">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

<div class="flex h-screen">
    <!-- Side Bar -->
    <div class="hidden md:flex flex-col w-64  rounded-r-2xl shadow-2xl bg-yellow-500">
        <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-yellow-500 rounded-r-2xl shadow-2xl transform transition-transform duration-300 ease-in-out md:translate-x-0">
            <div class="flex flex-col flex-1">
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
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto md:p-5 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 m3r-4 mb-6">Vehicle Management</h1>
        
        <div class="flex items-center gap-2">
            <div class="flex items-center p-2 rounded-xl bg-gray-800 mb-6">
                <div class="w-full md:w-96 mr-4">
                    <form method="GET" action="vehicle_page.php" class="flex">
                        <input type="text" 
                            name="search" 
                            id="searchInput"
                            placeholder="Search by registration, type, or location" 
                            value="<?php echo htmlspecialchars($search); ?>"
                            class="border border-yellow-400 p-2 w-full rounded-l focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                        <button type="submit" 
                            class="bg-yellow-500 text-black font-semibold px-4 py-2 rounded-r hover:bg-yellow-600">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="relative flex items-center p-2 space-x-2">
                    <p class="text-white text-2xl font-bold">Filters:</p>
                    <div class="relative">
                        <input type="radio" id="noRepairs" name="vehicleFilter" value="no_repairs" class="hidden">
                        <label for="noRepairs" class="px-3 py-1 text-sm cursor-pointer rounded bg-gray-700 hover:bg-yellow-500 text-white transition-colors inline-block">
                            No Repairs
                        </label>
                    </div>
                    
                    <div class="relative">
                        <input type="radio" id="clearedView" name="vehicleFilter" value="cleared" class="hidden">
                        <label for="clearedView" class="px-3 py-1 text-sm cursor-pointer rounded bg-gray-700 hover:bg-yellow-500 text-white transition-colors inline-block">
                            Cleared
                        </label>
                    </div>
                    
                    <div class="relative">
                        <input type="radio" id="repairsView" name="vehicleFilter" value="repairs" class="hidden">
                        <label for="repairsView" class="px-3 py-1 text-sm cursor-pointer rounded bg-gray-700 hover:bg-yellow-500 text-white transition-colors inline-block">
                            Needs Repairs
                        </label>
                    </div>
                </div>
            </div>

            <style>
                input[type="radio"]:checked + label {
                    background-color: #F59E0B;
                    color: black;
                }
            </style>
        </div>

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

        <!-- Clear Modal -->
        <div id="clearModal" class="modal-overlay items-center justify-center hidden fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4">
            <div id="clearModalcontent" class="modal-content relative mx-auto shadow-xl rounded-md bg-white max-w-md">
                <div class="p-6 pt-0 text-center">
                    <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                        <h3 class="text-xl font-normal text-black mt-5 mb-6">Clearing vehicle with registration number: 
                        <span id="clearVehicleRegNo" class="font-bold text-green-800"></span>
                    </h3>
                    <a href="#" id="confirmClear" 
                    class="text-white bg-green-500 hover:bg-green-400 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-base inline-flex items-center px-3 py-2.5 text-center mr-2">
                    ✔ Clear
                    </a>
                    <button onclick="closeClearModal()" id="cancelClear"
                    class="text-gray-900 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-cyan-200 border border-gray-200 font-medium inline-flex items-center rounded-lg text-base px-3 py-2.5 text-center">
                    Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle List Table -->
        <div class="overflow-x-auto border-gray-500">
            <table class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Reg No</th>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">Make</th>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Last inspection date</th>
                        <th class="p-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr class="hover:bg-gray-500" data-vehicle-id="<?php echo $vehicle['id']; ?>">
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['type']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['make']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['location']); ?></td>
                            <td class="p-4 border-b" id="status-<?php echo $vehicle['id']; ?>">
                                <?php if ($vehicle['status'] === 'Fixed'): ?>
                                    <span class="text-green-500 font-bold">✔ Cleared</span>
                                <?php elseif ($vehicle['status'] === 'Needs Repairs'): ?>
                                    <span class="text-yellow-600 font-bold">⚠ Needs Repairs</span>
                                <?php else: ?>
                                    <span class="text-gray-500 font-bold">No Repairs</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['inspection_date']); ?></td>
                            <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
                                <button onclick="showDetails(<?php echo $vehicle['id']; ?>)" class="text-blue-500 hover:text-blue-700">ℹ</button>
                                
                                <?php if ($vehicle['status'] === 'Fixed'): ?>
                                        <button 
                                            class="text-yellow-500 opacity-50 cursor-not-allowed" disabled title="This vehicle is fixed and cannot be edited">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                    <button 
                                        class="text-green-500 opacity-50 cursor-not-allowed" 
                                        disabled 
                                        title="This vehicle is already cleared">✔ Clear
                                    </button>
                                <?php else: ?>
                                <?php if (hasPermission('edit_vehicle') || isAdmin()): ?>
                                    <button 
                                        id="editButton-<?php echo $vehicle['id']; ?>" 
                                        onclick="editVehicle(<?php echo $vehicle['id']; ?>)" 
                                        class="text-yellow-500 hover:text-yellow-700 <?php echo $vehicle['status'] === 'fixed' ? 'cursor-not-allowed opacity-50' : ''; ?>" 
                                        <?php echo $vehicle['status'] === 'fixed' ? 'disabled' : ''; ?>><i class="fa-solid fa-pen-to-square"></i>
                                    </button>  
                                <?php endif; ?>

                                <button data-vehicle-id="<?php echo $vehicle['id']; ?>" data-vehicle-regno="<?php echo $vehicle['reg_no']; ?>" onclick="openDeleteModal(<?php echo $vehicle['id']; ?>, '<?php echo $vehicle['reg_no']; ?>')" class="text-green-500 hover:text-green-700">✔ Clear
                                    
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

  <!-- Vehicle Details Modal -->
<div id="detailsModal" class="modal-overlay fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center p-4">
    <div id="detailsModalContent" class="modal-content relative bg-white p-8 rounded-lg shadow-2xl border-4 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl">
        <button onclick="closeDetailsModal()" id="closeDetails" class="absolute top-4 right-4 text-gray-600 text-3xl font-bold hover:text-gray-800">&times;</button>
        
        <h2 class="text-2xl mb-6 text-gray-800 font-semibold border-b-2 border-gray-200 pb-2">Vehicle Details</h2>
        
        <div id="vehicleDetails" class="text-gray-700 space-y-3 mb-6">
            <p><strong>Registration Number:</strong> <span id="detailRegNo"></span></p>
            <p><strong>Type:</strong> <span id="detailType"></span></p>
            <p><strong>Make:</strong> <span id="detailMake"></span></p>
            <p><strong>Location:</strong> <span id="detailLocation"></span></p>
            <p><strong>Status:</strong> <span id="detailStatus"></span></p>
            <p><strong>Repair Type:</strong> <span id="detailRepair"></span></p>
            <p><strong>Last inspection date:</strong> <span id="detailInspectionDate"></span></p>
            <p><strong>fixed Date:</strong> <span id="detailRepairDate"></span></p>
        </div>

        <!-- Image Gallery Thumbnails -->
        <div id="imageGallery" class="grid grid-cols-3 gap-4 mb-6">
            <!-- Thumbnails populated dynamically with JavaScript -->
        </div>
        
        <!-- Enlarged Image View with Carousel Controls -->
        <div id="carouselModal" class="hidden fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center p-6">
            <div class="carousel-content relative w-11/12 md:w-3/4 lg:w-1/2 bg-white rounded-lg shadow-lg p-4">
                <button onclick="closeCarousel()" id="closeCarousel" class="absolute top-3 right-3 text-gray-600 text-2xl font-bold hover:text-gray-800">&times;</button>
                
                <button id="prevImage" onclick="showPrevImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-yellow-400 text-4xl font-bold">&larr;</button>
                
                <img id="enlargedImg" class="w-full h-auto rounded-lg border-4 border-yellow-400 shadow-md">
                
                <button id="nextImage" onclick="showNextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-yellow-400 text-4xl font-bold">&rarr;</button>
            </div>
        </div>
    </div>
</div>

    <!-- Add Vehicle Modal -->
    <div id="vehicleModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
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
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Wagon">Wagon</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Convertible">Convertible</option>
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

                    <div>
                        <label>Last inspection date:</label>
                        <input type="date" name="inspection_date" required class="border p-2 w-full mb-4">
                    </div>
                    
                    <div>
                        <label>Needs Repairs:</label>
                        <input type="checkbox" id="needsRepairs" name="needs_repairs" value= 1 onclick="toggleRepairType()">
                        <div id="repairTypeField" class="hidden mt-4">
                            <label>Type of Repair:</label>
                            <textarea name="repair_type" class="border p-2 w-full"></textarea>
                        </div>
                    </div>

                    <!-- <div class="space-y-4"> -->
                    <div class="relative">
                        <label title="Click to upload" for="images" class="cursor-pointer flex items-center gap-4 px-6 py-4 relative group">
                            <div class="w-max relative z-10">
                                <img class="w-12" src="https://www.svgrepo.com/show/485545/upload-cicle.svg" alt="file upload icon" width="512" height="512">
                            </div>
                            <div class="relative z-10">
                                <span class="block text-base font-semibold relative text-blue-900 group-hover:text-blue-500">
                                    Upload Images
                                </span>
                                <span class="mt-0.5 block text-sm text-gray-500">Max 2 Images</span>
                            </div>
                            <span class="absolute inset-0 border-dashed border-2 border-gray-400/60 rounded-3xl group-hover:border-gray-300 z-0"></span>
                            <span class="absolute inset-0 bg-gray-100 rounded-3xl transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-105 active:scale-95 z-0"></span>
                        </label>

                        <input hidden="" type="file" name="images[]" id="images" onchange="previewImages()" accept="image/*" multiple>
                    </div>

                </div>
                <div id="imagePreview" class="grid grid-flow-col grid-rows-4 gap-4 mt-2"></div>
                    <!-- </div> -->
                <button type="submit" name="submit" class="bg-yellow-500 text-black p-2 rounded mt-4 w-full md:w-auto">Submit</button>
            </form>
        </div>
    </div>


<!-- Edit vehicle Modal -->
    <div id="EditvehicleModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
    <div id="EditvehicleContent" class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>
        <h2 class="text-xl mb-4 text-yellow-500 font-bold">Edit Vehicle</h2>
        <form action="edit_vehicle.php" id="editVehicleForm" onsubmit="submitEditForm(event)" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- Form Fields -->
                <div>
                    <label class="block">Registration No:</label>
                    <input type="text" name="reg_no" id="reg_no" value="<?php echo htmlspecialchars($vehicle['reg_no']); ?>" class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label class="block font-semibold">Vehicle Type</label>
                    <select name="type" id="type" class="border border-gray-300 p-2 w-full rounded">
                        <option value="" disabled>Select a type</option>
                        <option value="Sedan" <?php if ($vehicle['type'] === 'Sedan') echo 'selected'; ?>>Sedan</option>
                        <option value="SUV" <?php if ($vehicle['type'] === 'SUV') echo 'selected'; ?>>SUV</option>
                        <option value="Truck" <?php if ($vehicle['type'] === 'Truck') echo 'selected'; ?>>Truck</option>
                        <option value="Van" <?php if ($vehicle['type'] === 'Van') echo 'selected'; ?>>Van</option>
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
                    <label>Last inspection date:</label>
                    <input type="date" name="inspection_date" id="inspection_date" value="<?php echo htmlspecialchars($vehicle['inspection_date']); ?>" class="border p-2 w-full mb-4">
                </div>
                <div>
                    <label for="editNeedsRepairs" class="block">Needs Repairs:</label>
                    <input type="checkbox" id="editNeedsRepairs" name="needs_repairs" value="1" <?php echo ($vehicle['needs_repairs'] == 1) ? 'checked' : ''; ?>>
                    <div id="repairTypeField" class="mt-4">
                        <label class="block">Type of Repair:</label>
                        <textarea name="repair_type" id="repair_type" class="border p-2 w-full"><?php echo htmlspecialchars($vehicle['repair_type']); ?></textarea>
                    </div>
                </div>
                <input type="hidden" name="id" id="vehicleId" value="<?php echo htmlspecialchars($vehicle['id']); ?>">
                <div>
                    <label>fixed Date:</label>
                    <input type="date" name="repair_completion_date" id="repair_completion_date" value="<?php echo htmlspecialchars($vehicle['repair_completion_date']); ?>" class="border p-2 w-full mb-4">
                </div>

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
                                    class="w-32 h-32 object-cover rounded-lg shadow-lg">
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
    <script src="../scripts/clear.js"></script>

</body>
</html>
