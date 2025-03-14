<?php
header('Content-Type: application/json');

include '../config/db.php';

try {
    $equipmentType = $_GET['type'] ?? 'solar';
    $equipmentId = $_GET['id'] ?? null;
    $data = [];

    switch ($equipmentType) {
        case 'solar':
            if ($equipmentId) {
                $stmt = $pdo->prepare("SELECT * FROM solar WHERE id = ?");
                $stmt->execute([$equipmentId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT * FROM solar");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'air_conditioners':
            if ($equipmentId) {
                $stmt = $pdo->prepare("SELECT * FROM air_conditioners WHERE id = ?");
                $stmt->execute([$equipmentId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT * FROM air_conditioners");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'fire_extinguishers':
            if ($equipmentId) {
                $stmt = $pdo->prepare("SELECT * FROM fire_extinguishers WHERE id = ?");
                $stmt->execute([$equipmentId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT * FROM fire_extinguishers");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'borehole':
            if ($equipmentId) {
                $stmt = $pdo->prepare("SELECT * FROM borehole WHERE id = ?");
                $stmt->execute([$equipmentId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT * FROM borehole");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        case 'generator':
            if ($equipmentId) {
                $stmt = $pdo->prepare("SELECT * FROM generator WHERE id = ?");
                $stmt->execute([$equipmentId]);
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $stmt = $pdo->query("SELECT * FROM generator");
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid equipment type']);
            exit;
    }
    if ($equipmentId && !$data) {
        http_response_code(404);
        echo json_encode(['error' => 'Equipment not found']);
        exit;
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
