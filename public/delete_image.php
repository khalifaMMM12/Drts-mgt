<?php
session_start();
include '../config/db.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['vehicle_id']) || !isset($_GET['image'])) {
        throw new Exception('Missing parameters');
    }

    $vehicleId = $_GET['vehicle_id'];
    $imageToDelete = $_GET['image'];

    // Store image to delete in session
    if (!isset($_SESSION['images_to_delete'])) {
        $_SESSION['images_to_delete'] = [];
    }
    
    if (!in_array($imageToDelete, $_SESSION['images_to_delete'])) {
        $_SESSION['images_to_delete'][] = $imageToDelete;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Image marked for deletion'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
