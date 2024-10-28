<?php
include '../config/db.php';

if (isset($_POST['submit'])) {
    $reg_no = $_POST['reg_no'];
    $type = $_POST['type'];
    $make = $_POST['make'];
    $location = $_POST['location'];
    $inspection_date = $_POST['inspection_date'];
    $status = isset($_POST['needs_repairs']) ? 'Needs Repairs' : 'Fixed';
    $repair_type = isset($_POST['needs_repairs']) ? $_POST['repair_type'] : null;
    $picture = $_FILES['picture']['name'];

    // Upload vehicle image
    $targetDir = "../public/assets/";
    $targetFilePath = $targetDir . basename($picture);
    move_uploaded_file($_FILES['picture']['tmp_name'], $targetFilePath);

    $sql = "INSERT INTO vehicles (reg_no, type, make, location, status, repair_type, picture, inspection_date)
            VALUES (:reg_no, :type, :make, :location, :status, :repair_type, :picture, :inspection_date)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':reg_no' => $reg_no,
        ':type' => $type,
        ':make' => $make,
        ':location' => $location,
        ':status' => $status,
        ':repair_type' => $repair_type,
        ':picture' => $picture,
        ':inspection_date' => $inspection_date
    ]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>Add Vehicle</title>
</head>
<body>
    <!-- Navigation Bar -->
<div class="bg-gray-800 text-white p-4">
    <a href="index.php" class="mr-4">All Vehicles</a>
    <a href="fixed_vehicles.php" class="mr-4">Fixed Vehicles</a>
    <a href="add_vehicle.php" class="mr-4">Add New Vehicle</a>
</div>

    <!-- Add Vehicle Button to Open Modal -->
<button onclick="openModal()">Add Vehicle</button>

<!-- Vehicle Modal -->
<div id="vehicleModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
        <h2 class="text-2xl mb-4">Add Vehicle</h2>
        <form action="add_vehicle.php" method="POST" enctype="multipart/form-data">
            <label>Registration No:</label>
            <input type="text" name="reg_no" required class="border p-2 w-full mb-4">

           <!-- Vehicle Type (Dropdown) -->
            <div class="mb-4">
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
                    <!-- Add more vehicle types as needed -->
                </select>
            </div>

            <label>Make:</label>
            <input type="text" name="make" required class="border p-2 w-full mb-4">

            <label>Location:</label>
            <input type="text" name="location" required class="border p-2 w-full mb-4">

            <label>Inspection Date:</label>
            <input type="date" name="inspection_date" required class="border p-2 w-full mb-4">

            <label>Needs Repairs:</label>
            <input type="checkbox" id="needsRepairs" name="needs_repairs" onclick="toggleRepairType()">

            <div id="repairTypeField" class="hidden mt-4">
                <label>Type of Repair:</label>
                <textarea name="repair_type" class="border p-2 w-full"></textarea>
            </div>

            <label>Vehicle Image:</label>
            <input type="file" name="picture" class="border p-2 w-full mb-4">

            <button type="submit" name="submit" class="bg-blue-500 text-white p-2 rounded">Add Vehicle</button>
        </form>
    </div>
</div>

<script src="../scripts/vehicle.js"></script>
    

</body>
</html>