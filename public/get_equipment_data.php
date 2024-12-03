<?php
header('Content-Type: application/json');

include '../config/db.php';

try {
    $equipmentType = $_GET['type'] ?? 'solar';
    $data = [];

    switch ($equipmentType) {
        case 'solar':
            $stmt = $pdo->query("SELECT * FROM solar");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'airConditioners':
            $stmt = $pdo->query("SELECT * FROM air_conditioners");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        case 'fireExtinguishers':
            $stmt = $pdo->query("SELECT * FROM fire_extinguishers");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            break;
        default:
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid equipment type']);
            exit;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
