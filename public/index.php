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
</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
<div class="bg-gray-800 text-white p-4">
    <a href="index.php" class="mr-4">All Vehicles</a>
    <a href="fixed_vehicles.php" class="mr-4">Fixed Vehicles</a>
    <!-- <a href="add_vehicle.php" class="mr-4">Add New Vehicle</a> -->
</div>


<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Vehicle Inspection Status</h1>

    <!-- Search Bar -->
    <div class="flex items-center w-full gap-4 mb-6"> 
        <!-- Search Form -->
        <form method="GET" action="index.php" class="flex w-full max-w-md">
            <input type="text" name="search" placeholder="Search by registration, type, or location"
                value="<?php echo htmlspecialchars($search); ?>"
                class="border p-2 flex-grow rounded-l">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded-r">Search</button>
        </form>
        
        <!-- Add Vehicle Button -->
        <button onclick="openModal()" class="bg-gray-500 text-white p-2 rounded w-auto">Add Vehicle</button>
    </div>

    <!-- Vehicle List -->
    <table class="w-full bg-white rounded shadow">
        <thead>
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
                            <span class="text-yellow-500 font-bold">⚠ Needs Repairs</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['inspection_date']); ?></td>
                    <td class="p-4 border-b flex items-center space-x-2">
                        <!-- Info Icon for Viewing Full Details -->
                        <button onclick="showDetails(<?php echo $vehicle['id']; ?>)" class="text-blue-500">ℹ</button>
                        
                        <!-- Edit Icon for Editing Vehicle -->
                        <a href="edit_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-yellow-500">✏</a>
                        <?php if ($vehicle['status'] === 'Needs Repairs'): ?>
                            <a href="clear_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-green-500">✔ Clear</a>
                        
                            <?php endif; ?>
                            <a href="delete_vehicle.php?id=<?php echo $vehicle['id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this vehicle?')">🗑</a>   
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
    <?php if (isset($_GET['message'])): ?>
        <div class="bg-green-100 text-green-700 p-2 mb-4 rounded">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

<!-- Vehicle Details Modal -->
<div id="detailsModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-700">
            <span class="text-2xl font-bold">&times;</span>
        </button>
        <h2 class="text-2xl mb-4">Vehicle Details</h2>
        <div id="vehicleDetails">
            <!-- Vehicle details will be populated here dynamically -->
        </div>
        <div id="imageGallery" class="flex flex-wrap gap-4 mb-4">
            <!-- Images will be added dynamically with JavaScript -->
        </div>
        <button onclick="closeDetailsModal()" class="mt-4 bg-red-500 text-white p-2 rounded">Close</button>
    </div>
</div>

<!-- Add Vehicle Modal -->
<div id="vehicleModal" class="hidden fixed inset-0 bg-gray-500 w-full bg-opacity-75 flex items-center justify-center">
    <div class="relative bg-white p-6 rounded shadow-lg w-full max-w-3xl"> 
        <button onclick="closeModal()" class="absolute p-2 top-2 right-2 text-gray-700 text-3xl">&times;</button>
        <h2 class="text-2xl mb-4">Add Vehicle</h2>
        <form action="add_vehicle.php" id="addVehicleForm" method="POST" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label>Registration No:</label>
                    <input type="text" name="reg_no" required class="border p-2 w-full mb-4">
                </div>
                
                <div>
                    <label for="type" class="block font-semibold">Vehicle Type</label>
                    <select name="type" id="type" class="border border-gray-300 p-2 w-full rounded" required>
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

                <div class="mb-4">
                    <label>Needs Repairs:</label>
                    <input type="checkbox" id="needsRepairs" name="needs_repairs" onclick="toggleRepairType()">
                    <div id="repairTypeField" class="hidden mt-4 col-span-2">
                        <label>Type of Repair:</label>
                        <textarea name="repair_type" class="border p-2 w-full"></textarea>
                    </div>
                </div>


                <div class="mb-4 col-span-2">
                    <label for="images" class="block font-semibold">Upload Vehicle Pictures</label>
                    <input type="file" name="images[]" id="images" class="border border-gray-300 p-2 w-full rounded" accept="image/*" multiple required>
                </div>
            </div>

            <button type="submit" name="submit" class="bg-blue-500 text-white p-2 rounded">Add Vehicle</button>
        </form>
    </div>
</div>



<script src="../scripts/vehicle.js"></script>
</body>
</html>
