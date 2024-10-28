<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];
    $currentDate = date('Y-m-d');

    // Update the vehicle status to "Fixed"
    $sql = "UPDATE vehicles SET status = 'Fixed', repair_completion_date = :repair_completion_date WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':repair_completion_date' => $currentDate, ':id' => $vehicleId]);

    // Redirect back to the main page
    header("Location: index.php");
    exit();
}
?>
