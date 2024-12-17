<?php 
include '../config/db.php';  

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = $_POST['vehicle_id'] ?? null;
    $needsRepairs = isset($_POST['needsRepairs']) ? 1 : 0;
    $status = $needsRepairs ? "Needs Repairs" : "No Repairs";

    try {
        // Update the vehicle in the database
        $stmt = $pdo->prepare("UPDATE vehicles 
                               SET reg_no = :reg_no, 
                                   type = :type, 
                                   make = :make, 
                                   location = :location, 
                                   inspection_date = :inspection_date, 
                                   repair_type = :repair_type, 
                                   status = :status 
                               WHERE id = :id");
        $stmt->execute([
            ':reg_no' => $_POST['reg_no'],
            ':type' => $_POST['type'],
            ':make' => $_POST['make'],
            ':location' => $_POST['location'],
            ':inspection_date' => $_POST['inspection_date'],
            ':repair_type' => $_POST['repair_type'],
            ':status' => $status,
            ':id' => $vehicleId
        ]);

        // Fetch the updated vehicle data
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
        $stmt->execute([':id' => $vehicleId]);
        $updatedVehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['success' => true, 'message' => 'Vehicle updated successfully', 'updatedVehicle' => $updatedVehicle]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
