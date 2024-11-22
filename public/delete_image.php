<?php
include '../config/db.php';

if (isset($_GET['vehicle_id']) && isset($_GET['image'])) {
    $vehicleId = $_GET['vehicle_id'];
    $image = $_GET['image'];

    // Path to the image
    $imagePath = "../assets/vehicles/" . $image;

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }

    $sql = "UPDATE vehicles SET images = REPLACE(images, :image, '') WHERE id = :vehicleId";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':image', $image);
    $stmt->bindParam(':vehicleId', $vehicleId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false]);
    }
}
?>
