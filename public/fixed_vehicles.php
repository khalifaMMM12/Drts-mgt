<?php
include '../config/db.php';

// Fetch all fixed vehicles
$sql = "SELECT * FROM vehicles WHERE status = 'Fixed'";
$stmt = $pdo->query($sql);
$fixedVehicles = $stmt->fetchAll();
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
    <a href="index.php" class="mr-4">All Vehicles</a>
    <a href="fixed_vehicles.php" class="mr-4">Fixed Vehicles</a>
    <a href="add_vehicle.php" class="mr-4">Add New Vehicle</a>
</div>

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Fixed Vehicles</h1>

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
