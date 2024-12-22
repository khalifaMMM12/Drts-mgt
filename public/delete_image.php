<?php
include '../config/db.php';
header('Content-Type: application/json');

try {
    if (isset($_GET['vehicle_id']) && isset($_GET['image'])) {
        $vehicleId = $_GET['vehicle_id'];
        $image = $_GET['image'];
        $imagePath = "../assets/vehicles/" . $image;

        // Delete file from server
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Remove image from database
        $stmt = $pdo->prepare("
            UPDATE vehicles 
            SET images = TRIM(BOTH ',' FROM REPLACE(CONCAT(',', images, ','), CONCAT(',', :image, ','), ','))
            WHERE id = :vehicleId
        ");
        
        $stmt->execute([
            ':image' => $image,
            ':vehicleId' => $vehicleId
        ]);

        // Get updated vehicle data
        $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
