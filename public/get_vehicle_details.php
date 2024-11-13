<?php
include '../config/db.php';

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
    $stmt->execute([':id' => $vehicleId]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($vehicle) {
        error_log("Vehicle found: " . json_encode($vehicle));
        echo json_encode($vehicle);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Vehicle not found']);
    }
    error_log("Received ID in GET request: " . $vehicleId);
}

// In get_vehicle_details.php

// Fetch vehicle data from the database
$sql = "SELECT * FROM vehicles";
$stmt = $pdo->query($sql);
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


