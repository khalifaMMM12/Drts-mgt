<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vehicle_id']) && isset($_FILES['new_image'])) {
    $vehicleId = $_POST['vehicle_id'];
    $uploadedImage = $_FILES['new_image'];

    // Define the upload path
    $uploadDir = '../assets/vehicles/';
    $imageName = uniqid() . "_" . basename($uploadedImage['name']);
    $targetFile = $uploadDir . $imageName;

    if (move_uploaded_file($uploadedImage['tmp_name'], $targetFile)) {
        // Append new image to images column
        $stmt = $pdo->prepare("UPDATE vehicles SET images = CONCAT(images, ',', ?) WHERE id = ?");
        $stmt->execute([$imageName, $vehicleId]);

        echo json_encode(['success' => true, 'image' => $imageName]);
    } else {
        echo json_encode(['success' => false, 'error' => 'File upload failed']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}
?>