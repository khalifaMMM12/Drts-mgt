<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

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
    <title>Vehicle Inspection Status</title>
    <link href="../style/style.css" rel="stylesheet">
    <link href="../style/output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-200">
    <button id="mobile-menu-button" class="md:hidden fixed top-4 left-4 z-50 p-2 rounded-lg bg-yellow-500 text-black">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
        </svg>
    </button>

<div class="flex h-screen">
    <!-- Side Bar -->
     <div class="hidden md:flex flex-col w-64 bg-gray-800">
        <div id="sidebar" class="fixed left-0 top-0 w-64 h-screen bg-black transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-40">
            <div class="flex flex-col flex-1 overflow-y-auto">
                <nav class="flex flex-col flex-1 overflow-y-auto bg-gradient-to-b from-gray-700 to-blue-500 px-2 py-4 gap-10 rounded-2xl">
                    <div>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            Dashboard
                        </a>
                    </div>
                    <div class="flex flex-col flex-1 gap-3"> 
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 80 px">
                                <path fill="currentColor" fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6l2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2z" clip-rule="evenodd" />
                            </svg>
                            Home
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="margin-right: 8px">
                                <path fill="currentColor" d="M12 4a5 5 0 1 1-5 5a5 5 0 0 1 5-5m0-2a7 7 0 1 0 7 7a7 7 0 0 0-7-7m10 28h-2v-5a5 5 0 0 0-5-5H9a5 5 0 0 0-5 5v5H2v-5a7 7 0 0 1 7-7h6a7 7 0 0 1 7 7zm0-26h10v2H22zm0 5h10v2H22zm0 5h7v2h-7z" />
                            </svg>
                             Profile
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 8px">
                                <path fill="none" stroke="currentColor" stroke-width="2" d="M16 7h3v4h-3zm-7 8h11M9 11h4M9 7h4M6 18.5a2.5 2.5 0 1 1-5 0V7h5.025M6 18.5V3h17v15.5a2.5 2.5 0 0 1-2.5 2.5h-17" />
                            </svg>
                            Article
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 32 32" style="margin-right: 8px">
                                <path fill="currentColor" d="M21.053 20.8c-1.132-.453-1.584-1.698-1.584-1.698s-.51.282-.51-.51s.51.51 1.02-2.548c0 0 1.413-.397 1.13-3.68h-.34s.85-3.51 0-4.7c-.85-1.188-1.188-1.98-3.057-2.547s-1.188-.454-2.547-.396c-1.36.058-2.492.793-2.492 1.19c0 0-.85.056-1.188.396c-.34.34-.906 1.924-.906 2.32s.283 3.06.566 3.625l-.337.114c-.284 3.283 1.13 3.68 1.13 3.68c.51 3.058 1.02 1.756 1.02 2.548s-.51.51-.51.51s-.452 1.245-1.584 1.698c-1.132.452-7.416 2.886-7.927 3.396c-.512.51-.454 2.888-.454 2.888H29.43s.06-2.377-.452-2.888c-.51-.51-6.795-2.944-7.927-3.396zm-12.47-.172c-.1-.18-.148-.31-.148-.31s-.432.24-.432-.432s.432.432.864-2.16c0 0 1.2-.335.96-3.118h-.29s.144-.59.238-1.334a10.01 10.01 0 0 1 .037-.996l.038-.426c-.02-.492-.107-.94-.312-1.226c-.72-1.007-1.008-1.68-2.59-2.16c-1.584-.48-1.01-.384-2.16-.335c-1.152.05-2.112.672-2.112 1.01c0 0-.72.047-1.008.335c-.27.27-.705 1.462-.757 1.885v.28c.048.654.26 2.45.47 2.873l-.286.096c-.24 2.782.96 3.118.96 3.118c.43 2.59.863 1.488.863 2.16s-.432.43-.432.43s-.383 1.058-1.343 1.44l-.232.092v5.234h.575c-.03-1.278.077-2.927.746-3.594c.357-.355 1.524-.94 6.353-2.862zm22.33-9.056c-.04-.378-.127-.715-.292-.946c-.718-1.008-1.007-1.68-2.59-2.16c-1.583-.48-1.007-.384-2.16-.335c-1.15.05-2.11.672-2.11 1.01c0 0-.72.047-1.008.335c-.27.272-.71 1.472-.758 1.89h.033l.08.914c.02.23.022.435.027.644c.09.666.21 1.35.33 1.59l-.286.095c-.24 2.782.96 3.118.96 3.118c.432 2.59.863 1.488.863 2.16s-.43.43-.43.43s-.054.143-.164.34c4.77 1.9 5.927 2.48 6.28 2.833c.67.668.774 2.316.745 3.595h.48V21.78l-.05-.022c-.96-.383-1.344-1.44-1.344-1.44s-.433.24-.433-.43s.433.43.864-2.16c0 0 .804-.23.963-1.84V14.66c0-.018 0-.033-.003-.05h-.29s.216-.89.293-1.862z" />
                            </svg>
        
                            Users
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 mt-2 text-gray-100 hover:bg-gray-400 hover:bg-opacity-25 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="margin-right: 8px">
                                <path fill="currentColor" d="M12 2A10 10 0 0 0 2 12a9.89 9.89 0 0 0 2.26 6.33l-2 2a1 1 0 0 0-.21 1.09A1 1 0 0 0 3 22h9a10 10 0 0 0 0-20m0 18H5.41l.93-.93a1 1 0 0 0 0-1.41A8 8 0 1 1 12 20m5-9H7a1 1 0 0 0 0 2h10a1 1 0 0 0 0-2m-2 4H9a1 1 0 0 0 0 2h6a1 1 0 0 0 0-2M9 9h6a1 1 0 0 0 0-2H9a1 1 0 0 0 0 2" />
                            </svg>
                            comments
                        </a>
                    </div>
                </nav>
            445</div>
        </div>
    </div>

    <!-- Main Content -->

    <div class="flex flex-col flex-1 overflow-y-auto">
    <div class="flex-1 md:ml-64 transition-margin duration-300 ease-in-out">
        
    <!-- Navigation Bar -->
    <div class="grid xl:grid-cols-1 grid-cols-1">
        <div class="p-2 md:p-5">
            <div class="py-2 md:py-3 px-2 md:px-3 rounded-xl border-yellow-400 border-4 md:border-8 bg-black">
                <div class="flex flex-col md:flex-row items-center justify-between gap-2 md:gap-4">
                    <div class="flex items-center gap-2 md:gap-4 w-full md:w-auto">
                        <h2 class="font-bold text-xl md:text-3xl text-white">DRTS</h2>
                        
                        <div class="w-full md:w-96">
                            <form method="GET" action="vehicle_page.php" class="flex">
                                <input type="text" 
                                    name="search" 
                                    placeholder="Search by registration, type, or location" 
                                    value="<?php echo htmlspecialchars($search); ?>"
                                    class="border border-yellow-400 p-2 w-full rounded-l focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                                <button type="submit" 
                                    class="bg-yellow-500 text-black font-semibold px-4 py-2 rounded-r hover:bg-yellow-600">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="flex flex-wrap md:flex-nowrap gap-2 md:gap-4">
                        <button onclick="openModal()" 
                            class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                            <i class="fas fa-plus"></i> Add Vehicle
                        </button>
                        <a href="equipment.php" 
                            class="rounded bg-yellow-500 hover:bg-yellow-600 text-black px-4 py-2 shadow-lg flex items-center gap-2">
                            <i class="fas fa-tools"></i> Equipment
                        </a>
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
    <div class="container mx-auto md:p-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-6">Vehicle Inspection Status</h1>

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
        <div id="deleteModal" class="hidden deleteModal fixed z-50 inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full px-4">
            <div class="relative top-40 mx-auto shadow-xl rounded-md bg-white max-w-md">
            <div class="p-6 pt-0 text-center">
                <svg class="w-20 h-20 text-red-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              147  <h3 class="text-xl font-normal text-gray-500 mt-5 mb-6">Are you sure you want to delete vehicle with registration number: 
                    <span id="deleteVehicleRegNo" class="font-bold"></span>
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
                        <tr data-vehicle-id="<?php echo $vehicle['id']; ?>">
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['type']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['make']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['location']); ?></td>
                            <td class="p-4 border-b">
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
                                    <!-- Disabled edit button for fixed vehicles -->
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
                                    <!-- Active edit button for non-fixed vehicles -->
                                <button 
                                    id="editButton-<?php echo $vehicle['id']; ?>" 
                                    onclick="editVehicle(<?php echo $vehicle['id']; ?>)" 
                                    class="text-yellow-500 hover:text-yellow-700 <?php echo $vehicle['status'] === 'fixed' ? 'cursor-not-allowed opacity-50' : ''; ?>" 
                                    <?php echo $vehicle['status'] === 'fixed' ? 'disabled' : ''; ?>><i class="fa-solid fa-pen-to-square"></i>
                                </button>   
                                <a href="clear_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-green-500 hover:text-green-700">✔ Clear</a>
                                <?php endif; ?>
                                <button class="text-red-500 hover:text-red-700 delete-button" data-vehicle-id="<?php echo $vehicle['id']; ?>" data-vehicle-regno="<?php echo $vehicle['reg_no']; ?>" onclick="openDeleteModal(<?php echo $vehicle['id']; ?>, '<?php echo $vehicle['reg_no']; ?>')">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>                            
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
                        <input type="checkbox" id="needsRepairs" name="needs_repairs" <?php echo $vehicle['status'] === 'Needs Repairs' ? 'checked' : ''; ?> onclick="toggleRepairType()">
                        <div id="repairTypeField" class="hidden mt-4">
                            <label>Type of Repair:</label>
                            <textarea name="repair_type" class="border p-2 w-full"></textarea>
                        </div>
                    </div>

                    <div class="relative">
                        <label title="Click to upload" for="images" class="cursor-pointer flex items-center gap-4 px-6 py-4 relative group">
                            <div class="w-max relative z-10">
                                <img class="w-12" src="https://www.svgrepo.com/show/485545/upload-cicle.svg" alt="file upload icon" width="512" height="512">
                            </div>
                            <div class="relative z-10">
                                <span class="block text-base font-semibold relative text-blue-900 group-hover:text-blue-500">
                                    Upload Images
                                </span>
                                <span class="mt-0.5 block text-sm text-gray-500">Max 2 MB</span>
                            </div>
                            <span class="absolute inset-0 border-dashed border-2 border-gray-400/60 rounded-3xl group-hover:border-gray-300 z-0"></span>
                            <span class="absolute inset-0 bg-gray-100 rounded-3xl transition-all duration-300 opacity-0 group-hover:opacity-100 group-hover:scale-105 active:scale-95 z-0"></span>
                        </label>

                        <input hidden="" type="file" name="images[]" id="images" onchange="previewImages()" accept="image/*" multiple>
                    </div>

                    <!-- Image Preview Section -->
                    <div id="imagePreview" class="col-span-2 grid grid-cols-2 gap-2 md:grid-cols-4">
                        <!-- Preview images will be dynamically inserted here by JavaScript -->
                    </div>
                </div>
                <button type="submit" name="submit" class="bg-yellow-500 text-black p-2 rounded mt-4 w-full md:w-auto">Submit</button>
            </form>
        </div>
    </div>


<!-- Edit vehicle Modal -->
    <div id="EditvehicleModal" class="modal-overlay hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
    <div id="EditvehicleContent" class="modal-content relative bg-white p-6 rounded-lg shadow-lg border-2 border-yellow-400 w-full max-w-lg md:max-w-2xl lg:max-w-3xl overflow-y-auto max-h-full">
        <button onclick="closeEditModal()" class="absolute top-2 right-2 text-gray-700 text-4xl">&times;</button>
        <h2 class="text-xl mb-4 text-yellow-500 font-bold">Edit Vehicle</h2>
        <form action="edit_vehicle.php" id="editVehicleForm" method="POST" enctype="multipart/form-data">
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
                    <label>Needs Repairs:</label>
                    <input type="checkbox" id="needsRepairs" name="needs_repairs" <?php echo ($vehicle['status'] === 'Needs Repairs' || $vehicle['needs_repairs'] == 1) ? 'checked' : ''; ?>>
                    <div id="repairTypeField" class="mt-4 <?php echo $vehicle['status'] !== 'Needs Repairs' ? 'hidden' : ''; ?>">
                        <label>Type of Repair:</label>
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
                                        onclick="deleteImage(<?php echo $vehicle['id']; ?>, '<?php echo htmlspecialchars(trim($image)); ?>')"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10">
                                    delete image <i class="fas fa-times text-xs"></i>
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
    <script src="https://kit.fontawesome.com/79a49acde1.js" crossorigin="anonymous"></script>
    <script src="../scripts/vehicle.js"></script>
    <script src="../scripts/editVehicle.js"></script>
    <script src="../scripts/delete.js"></script>
</body>
</html>
