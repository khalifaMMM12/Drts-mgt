<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
    $stmt->execute([':id' => $vehicleId]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$vehicle) {
        echo "Vehicle not found.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_no = $_POST['reg_no'];
    $type = $_POST['type'];
    $make = $_POST['make'];
    $location = $_POST['location'];
    $status = isset($_POST['needs_repairs']) ? 'Needs Repairs' : 'Fixed';
    $repair_type = isset($_POST['needs_repairs']) ? $_POST['repair_type'] : null;
    $inspection_date = $_POST['inspection_date'];
    $repair_completion_date = $_POST['repair_completion_date'];

    // Update the vehicle data
    $sql = "UPDATE vehicles SET reg_no = :reg_no, type = :type, make = :make, location = :location, 
            status = :status, repair_type = :repair_type, inspection_date = :inspection_date, 
            repair_completion_date = :repair_completion_date WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':reg_no' => $reg_no,
        ':type' => $type,
        ':make' => $make,
        ':location' => $location,
        ':status' => $status,
        ':repair_type' => $repair_type,
        ':inspection_date' => $inspection_date,
        ':repair_completion_date' => $repair_completion_date,
        ':id' => $vehicleId
    ]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Vehicle</title>
    <link href="../styles/tailwind.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Edit Vehicle</h1>

    <form method="POST" action="">
        <label>Registration No:</label>
        <input type="text" name="reg_no" value="<?php echo htmlspecialchars($vehicle['reg_no']); ?>" class="border p-2 w-full mb-4">

        <label>Type:</label>
        <input type="text" name="type" value="<?php echo htmlspecialchars($vehicle['type']); ?>" class="border p-2 w-full mb-4">

        <label>Make:</label>
        <input type="text" name="make" value="<?php echo htmlspecialchars($vehicle['make']); ?>" class="border p-2 w-full mb-4">

        <label>Location:</label>
        <input type="text" name="location" value="<?php echo htmlspecialchars($vehicle['location']); ?>" class="border p-2 w-full mb-4">

        <label>Inspection Date:</label>
        <input type="date" name="inspection_date" value="<?php echo htmlspecialchars($vehicle['inspection_date']); ?>" class="border p-2 w-full mb-4">

        <label>Needs Repairs:</label>
        <input type="checkbox" name="needs_repairs" <?php echo $vehicle['status'] === 'Needs Repairs' ? 'checked' : ''; ?>>

        <div class="mt-4">
            <label>Type of Repair:</label>
            <textarea name="repair_type" class="border p-2 w-full"><?php echo htmlspecialchars($vehicle['repair_type']); ?></textarea>
        </div>

        <label>Repair Completion Date:</label>
        <input type="date" name="repair_completion_date" value="<?php echo htmlspecialchars($vehicle['repair_completion_date']); ?>" class="border p-2 w-full mb-4">

        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Update Vehicle</button>
    </form>
</div>
</body>
</html>
