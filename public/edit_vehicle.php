<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve all necessary fields
    $vehicleId = $_POST['id'];
    $reg_no = $_POST['reg_no'];
    $type = $_POST['type'];
    $make = $_POST['make'];
    $location = $_POST['location'];
    $status = isset($_POST['needs_repairs']) ? 'Needs Repairs' : 'Operational';
    $repair_type = $_POST['repair_type'] ?? null;
    $inspection_date = $_POST['inspection_date'];
    $repair_completion_date = $_POST['repair_completion_date'] ?? null;

    // Prepare the SQL statement
    $sql = "UPDATE vehicles SET reg_no = :reg_no, type = :type, make = :make, location = :location,
            status = :status, repair_type = :repair_type, inspection_date = :inspection_date,
            repair_completion_date = :repair_completion_date WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reg_no', $reg_no);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':make', $make);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':repair_type', $repair_type);
    $stmt->bindParam(':inspection_date', $inspection_date);
    $stmt->bindParam(':repair_completion_date', $repair_completion_date);
    $stmt->bindParam(':id', $vehicleId);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Update successful"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update"]);
    }

}
?>