<?php 
include '../config/db.php';  

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];  

    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
    $stmt->execute([':id' => $vehicleId]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);  

    if ($vehicle) {
        echo json_encode([
            'id' => $vehicle['id'],
            'reg_no' => $vehicle['reg_no'],
            'type' => $vehicle['type'],
            'make' => $vehicle['make'],
            'location' => $vehicle['location'],
            'status' => $vehicle['status'],
            'repair_type' => $vehicle['repair_type'],
            'inspection_date' => $vehicle['inspection_date'],
            'images' => $vehicle['images']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Vehicle not found']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No vehicle ID provided']);
}
?>