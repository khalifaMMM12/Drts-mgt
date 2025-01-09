<?php
// delete_vehicle.php

// Database connection
include '../config/db.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    try {
        // Prepare and execute the delete statement
        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);

        echo json_encode([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ]);
        exit();
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Error deleting vehicle'
        ]);
        exit();
    }
} else {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Vehicle ID not specified'
    ]);
}