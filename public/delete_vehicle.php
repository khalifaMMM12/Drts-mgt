<?php
// delete_vehicle.php

// Database connection
include '../config/db.php';

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    try {
        // Prepare and execute the delete statement
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);

        // Redirect back to the main page
        header("Location: vehicle_page.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting vehicle: " . $e->getMessage());
    }
} else {
    echo "Vehicle ID not specified.";
}