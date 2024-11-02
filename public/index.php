<?php
include '../config/db.php';

// Search logic
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body class="bg-gray-100">

    <!-- Navigation Bar -->
    <div class="bg-black text-yellow-400 p-4 flex flex-wrap justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="index.php" class="hover:text-white">All Vehicles</a>
            <a href="fixed_vehicles.php" class="hover:text-white">Fixed Vehicles</a>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="container mx-auto p-4 md:p-6 lg:px-8">
        <h1 class="text-3xl font-bold text-yellow-500 mb-6">Vehicle Inspection Status</h1>

        <!-- Search Bar and Add Vehicle Button -->
        <div class="flex flex-col md:flex-row items-center w-full gap-4 mb-6">
            <!-- Search Form -->
            <form method="GET" action="index.php" class="flex w-full max-w-md">
                <input type="text" name="search" placeholder="Search by registration, type, or location" value="<?php echo htmlspecialchars($search); ?>"
                    class="border border-yellow-400 p-2 flex-grow rounded-l focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                <button type="submit" class="bg-yellow-500 text-black font-semibold p-2 rounded-r hover:bg-yellow-600">Search</button>
            </form>

            <!-- Add Vehicle Button -->
            <button onclick="openModal()" class="rounded bg-gradient-to-b from-blue-500 to-indigo-600 hover:to-indigo-700 text-white px-4 py-2 shadow-lg">Add Vehicle</button>
        </div>

        <!-- Vehicle List Table -->
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-lg rounded overflow-hidden text-sm md:text-base">
                <thead class="bg-yellow-500 text-black">
                    <tr>
                        <th class="p-4 border-b">Reg No</th>
                        <th class="p-4 border-b">Type</th>
                        <th class="p-4 border-b">Make</th>
                        <th class="p-4 border-b">Location</th>
                        <th class="p-4 border-b">Status</th>
                        <th class="p-4 border-b">Inspection Date</th>
                        <th class="p-4 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vehicles as $vehicle): ?>
                        <tr>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['type']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['make']); ?></td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['location']); ?></td>
                            <td class="p-4 border-b">
                                <?php if ($vehicle['status'] === 'Fixed'): ?>
                                    <span class="text-green-500 font-bold">✔ Fixed</span>
                                <?php else: ?>
                                    <span class="text-yellow-600 font-bold">⚠ Needs Repairs</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['inspection_date']); ?></td>
                            <td class="p-4 border-b flex items-center justify-around space-x-2 text-lg">
                                <button onclick="showDetails(<?php echo $vehicle['id']; ?>)" class="text-blue-500 hover:text-blue-700">ℹ</button>
                                <a href="edit_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-yellow-500 hover:text-yellow-700">✏</a>
                                <a href="clear_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-green-500 hover:text-green-700">✔ Clear</a>
                                <a href="delete_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Vehicle Details Modal -->
    <div id="detailsModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md relative">
        <!-- Close Button -->
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700 text-2xl font-bold">
            &times;
        </button>
        <h2 class="text-2xl mb-4">Vehicle Details</h2>
        <!-- Image Gallery -->
        <div id="imageGallery" class="grid grid-cols-3 gap-2 mb-4">
            <!-- Thumbnails populated dynamically with JavaScript -->
        </div>
        <!-- Enlarged Image View with Carousel Controls -->
        <div id="carouselModal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center">
            <div class="relative w-11/12 md:w-3/4 lg:w-1/2">
                <button onclick="closeCarousel()" class="absolute top-2 right-2 text-white text-2xl font-bold">&times;</button>
                <button id="prevImage" onclick="showPrevImage()" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl font-bold">&larr;</button>
                <img id="enlargedImg" class="w-full h-auto rounded shadow-lg">
                <button id="nextImage" onclick="showNextImage()" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl font-bold">&rarr;</button>
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
                        <label>Inspection Date:</label>
                        <input type="date" name="inspection_date" required class="border p-2 w-full mb-4">
                    </div>

                    <div class="mb-4 col-span-2">
                        <label>Needs Repairs:</label>
                        <input type="checkbox" id="needsRepairs" name="needs_repairs" onclick="toggleRepairType()">
                        <div id="repairTypeField" class="hidden mt-4">
                            <label>Type of Repair:</label>
                            <textarea name="repair_type" class="border p-2 w-full"></textarea>
                        </div>
                    </div>

                    <div class="mb-4 col-span-2">
                        <label for="images" class="block font-semibold">Upload Vehicle Pictures</label>
                        <input type="file" name="images[]" id="images" onchange="previewImages()" class="border border-gray-300 p-2 w-full rounded" accept="image/*" multiple required>
                    </div>

                    <!-- Image Preview Section -->
                    <div id="imagePreview" class="col-span-2 grid grid-cols-2 gap-2 md:grid-cols-4">
                        <!-- Preview images will be dynamically inserted here by JavaScript -->
                    </div>
                </div>
                <button type="submit" name="submit" class="bg-yellow-500 text-black p-2 rounded mt-4 w-full md:w-auto">Add Vehicle</button>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script src="../scripts/vehicle.js"></script>
</body>

</html>
