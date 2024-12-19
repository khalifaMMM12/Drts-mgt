<?php 
include '../config/db.php';  

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add more robust error logging at the start
    error_log("Edit Vehicle POST Data: " . json_encode($_POST));

    $vehicleId = $_POST['vehicle_id'] ?? $_POST['id'] ?? $_POST['vehicleId'] ?? null;
    $needs_repairs = isset($_POST['needs_repairs']) ? intval($_POST['needs_repairs']) : 0;
    
    // Determine status, giving priority to explicit status sent from client
    $status = $_POST['status'] ?? ($needs_repairs ? 'Needs Repairs' : 'No Repairs');
    
    // Ensure repair_type is cleared if no repairs are needed
    $repair_type = $needs_repairs ? ($_POST['repair_type'] ?? '') : '';

    if (!$vehicleId) {
        error_log("No vehicle ID provided");
        echo json_encode([
            'success' => false, 
            'error' => 'No vehicle ID provided',
            'postData' => $_POST
        ]);
        exit;
    }

    try {
        // First, update the vehicle in the database
        $updateStmt = $pdo->prepare("UPDATE vehicles 
                               SET reg_no = :reg_no, 
                                   type = :type, 
                                   make = :make, 
                                   location = :location, 
                                   inspection_date = :inspection_date, 
                                   repair_type = :repair_type, 
                                   needs_repairs = :needs_repairs,
                                   status = :status,
                                   repair_completion_date = :repair_completion_date
                               WHERE id = :id");
        $updateResult = $updateStmt->execute([
            ':reg_no' => $_POST['reg_no'],
            ':type' => $_POST['type'],
            ':make' => $_POST['make'],
            ':location' => $_POST['location'],
            ':inspection_date' => $_POST['inspection_date'],
            ':repair_type' => $repair_type,
            ':needs_repairs' => $needs_repairs,
            ':status' => $status,
            ':repair_completion_date' => $_POST['repair_completion_date'] ?? null,
            ':id' => $vehicleId
        ]);

        // Check if update was successful
        if (!$updateResult) {
            throw new Exception("Failed to update vehicle");
        }

        // Fetch the updated vehicle data
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = :id");
        $stmt->execute([':id' => $vehicleId]);
        $updatedVehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        // Additional debugging
        if (!$updatedVehicle) {
            // Log additional information
            error_log("No vehicle found with ID: " . $vehicleId);
            
            // Return more detailed error
            echo json_encode([
                'success' => false, 
                'error' => 'No vehicle found after update',
                'vehicleId' => $vehicleId,
                'updateResult' => $updateResult
            ]);
            exit;
        }

        echo json_encode([
            'success' => true, 
            'message' => 'Vehicle updated successfully', 
            'updatedVehicle' => $updatedVehicle
        ]);
    } catch (Exception $e) {
        error_log("Error updating vehicle: " . $e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false, 
            'error' => $e->getMessage(),
            'details' => [
                'vehicleId' => $vehicleId,
                'postData' => $_POST
            ]
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>