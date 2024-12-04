<?php
include '../config/db.php';   

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Ensure JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $equipmentId = $data['equipmentId'] ?? null;
    $equipmentType = $data['equipmentType'] ?? null;

    if ($equipmentId && $equipmentType) {
        try {
            // Prepare the delete query
            $stmt = $conn->prepare("DELETE FROM `$equipmentType` WHERE id = :id");
            $stmt->bindParam(':id', $equipmentId);

            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete equipment']);
            }
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid input data']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
