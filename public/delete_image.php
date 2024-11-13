<?php

include '../config/db.php';

if (isset($_GET['vehicle_id']) && isset($_GET['image'])) {
    $vehicleId = $_GET['vehicle_id'];
    $image = $_GET['image'];

    try {
        // Fetch the vehicle record to get the list of images
        $stmt = $pdo->prepare("SELECT images FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($vehicle) {
            // Remove the image from the images field
            $updatedImages = str_replace($image, '', $vehicle['images']);
            $updatedImages = trim($updatedImages, ',');  // Remove any leading/trailing commas

            // Update the database
            $stmt = $pdo->prepare("UPDATE vehicles SET images = ? WHERE id = ?");
            $stmt->execute([$updatedImages, $vehicleId]);

            // Delete the image file from the server
            $imagePath = "../assets/vehicles/" . $image;
            if (file_exists($imagePath)) {
                unlink($imagePath);  // Delete the file from the server
            }

            // Return success
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Vehicle not found']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting image: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Vehicle ID or image not specified']);
}
?>
