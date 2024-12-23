<?php
include '../config/db.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['vehicle_id']) || !isset($_GET['image'])) {
        throw new Exception('Missing parameters');
    }

    $vehicleId = $_GET['vehicle_id'];
    $imageToDelete = $_GET['image'];
    $imagePath = "../assets/vehicles/" . $imageToDelete;

    // Delete file from filesystem
    if (file_exists($imagePath) && unlink($imagePath)) {
        // Update database
        $stmt = $pdo->prepare("SELECT images FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch();
        
        $images = explode(',', $vehicle['images']);
        $images = array_filter($images, fn($img) => trim($img) !== $imageToDelete);
        $newImages = implode(',', $images);

        $stmt = $pdo->prepare("UPDATE vehicles SET images = ? WHERE id = ?");
        $stmt->execute([$newImages, $vehicleId]);

        echo json_encode([
            'success' => true,
            'remaining_images' => $newImages
        ]);
    } else {
        throw new Exception('Failed to delete image file');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
