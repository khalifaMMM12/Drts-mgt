<?php
include '../config/db.php';

// Fetch all fixed vehicles
$sql = "SELECT * FROM vehicles WHERE status = 'Fixed'";
$stmt = $pdo->query($sql);
$fixedVehicles = $stmt->fetchAll();

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
    <title>Fixed Vehicles</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>
<body class="bg-gray-100">
    <!-- Navigation Bar -->
<div class="bg-gray-800 text-white p-4">
    <a href="vehicle_page.php" class="mr-4">All Vehicles</a>
    <a href="fixed_vehicles.php" class="mr-4">Fixed Vehicles</a>
</div>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Fixed Vehicles</h1>

        <div class="flex flex-col md:flex-row items-center w-full gap-4 mb-6">
            <!-- Search Form -->
            <form method="GET" action="vehicle_page.php" class="flex w-full max-w-md">
                <input type="text" name="search" placeholder="Search by registration, type, or location" value="<?php echo htmlspecialchars($search); ?>"
                    class="border border-yellow-400 p-2 flex-grow rounded-l focus:border-yellow-500 focus:ring focus:ring-yellow-200 focus:ring-opacity-50">
                <button type="submit" class="bg-yellow-500 text-black font-semibold p-2 rounded-r hover:bg-yellow-600">Search</button>
            </form>

            <!-- Add Vehicle Button -->
            <button onclick="openModal()" class="rounded bg-gradient-to-b from-blue-500 to-indigo-600 hover:to-indigo-700 text-white px-4 py-2 shadow-lg">Add Vehicles</button>
        </div>

    <!-- Fixed Vehicle List -->
    <table class="w-full bg-white rounded shadow">
        <thead>
            <tr>
                <th class="p-4 border-b">Reg No</th>
                <th class="p-4 border-b">Type</th>
                <th class="p-4 border-b">Make</th>
                <th class="p-4 border-b">Location</th>
                <th class="p-4 border-b">Repair Completion Date</th>
                <th class="p-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fixedVehicles as $vehicle): ?>
                <tr>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['reg_no']); ?></td>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['type']); ?></td>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['make']); ?></td>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['location']); ?></td>
                    <td class="p-4 border-b"><?php echo htmlspecialchars($vehicle['repair_completion_date']); ?></td>
                    <td class="p-4 border-b">
                        <!-- Info Icon for Viewing Full Details -->
                        <button onclick="showDetails(<?php echo $vehicle['id']; ?>)" class="text-blue-500">ℹ</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="../scripts/vehicle.js"></script>
</body>
</html>
