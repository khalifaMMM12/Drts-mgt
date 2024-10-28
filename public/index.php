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
    <a href="add_vehicle.php" class="mr-4">Add New Vehicle</a>
</div>


<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Vehicle Inspection Status</h1>

    <!-- Search Bar -->
    <form method="GET" action="index.php" class="mb-6">
        <input type="text" name="search" placeholder="Search by registration, type, or location"
               value="<?php echo htmlspecialchars($search); ?>"
               class="border p-2 w-1/3 rounded">
        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Search</button>
    </form>

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

                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Vehicle Details Modal -->
<div id="detailsModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl mb-4">Vehicle Details</h2>
        <div id="vehicleDetails">
            <!-- Vehicle details will be populated here dynamically -->
        </div>
        <button onclick="closeDetailsModal()" class="mt-4 bg-red-500 text-white p-2 rounded">Close</button>
    </div>
</div>


<script src="../scripts/vehicle.js"></script>
</body>
</html>
