<?php
include '../config/db.php';
header('Content-Type: application/json');

try {
    if (isset($_GET['id'])) {
        $vehicleId = $_GET['id'];
        $currentDate = date('Y-m-d');

        $sql = "UPDATE vehicles SET status = 'Fixed', repair_completion_date = :repair_completion_date WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':repair_completion_date' => $currentDate, ':id' => $vehicleId]);

        echo json_encode(['success' => true, 'message' => 'Vehicle cleared successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Vehicle ID not provided']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
