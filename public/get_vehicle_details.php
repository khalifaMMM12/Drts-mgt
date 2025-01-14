<?php 
include '../config/db.php';  

header('Content-Type: application/json');

if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $vehicleId = trim($_GET['id']);  

    try {
        if (!is_numeric($vehicleId)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid vehicle ID format']);
            exit;
        }

        // Prepare and execute the query
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
        $stmt->execute([':id' => $vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);  

        if ($vehicle) {
            // Handle null or empty images
            $images = $vehicle['images'] ? explode(',', $vehicle['images']) : [];

            $status = ($vehicle['needs_repairs'] == 1) ? 'Needs Repairs' : 'No Repairs';

            // Return the vehicle details
            echo json_encode([
                'id' => $vehicle['id'],
                'reg_no' => $vehicle['reg_no'],
                'type' => $vehicle['type'],
                'make' => $vehicle['make'],
                'location' => $vehicle['location'],
                'status' => $status, 
                'repair_type' => $vehicle['repair_type'] ?? '', 
                'needs_repairs' => (int)($vehicle['needs_repairs'] ?? 0), 
                'inspection_date' => $vehicle['inspection_date'],
                'images' => $images 
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Vehicle not found']);
        }
    } catch (PDOException $e) {
        // Handle database errors
        http_response_code(500);
        echo json_encode(['error' => 'Database error occurred']);
        error_log('Database error in get_vehicle_details.php: ' . $e->getMessage());
    }
} else {
    // Invalid or missing ID
    http_response_code(400);
    echo json_encode(['error' => 'No vehicle ID provided or ID is invalid']);
}
?>
