<?php
include '../config/db.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $vehicleId = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT images FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);
        $imageNames = explode(',', $vehicle['images']);

        $stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);

        foreach ($imageNames as $imageName) {
            if ($imageName) {
                $filePath = "../assets/vehicles/" . $imageName;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }
        
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