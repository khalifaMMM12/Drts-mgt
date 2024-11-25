<?php
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Request received: " . print_r($_POST, true));
    error_log("Files received: " . print_r($_FILES, true));

    if (isset($_POST['vehicle_id']) && isset($_FILES['new_image'])) {
        $vehicleId = $_POST['vehicle_id'];
        $uploadedImages = $_FILES['new_image'];

        if (empty($uploadedImages['name'][0])) {
            echo json_encode(['success' => false, 'error' => 'No images selected']);
            exit;
        }

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $uploadedFiles = [];

        foreach ($uploadedImages['name'] as $key => $imageName) {
            $fileTmpPath = $uploadedImages['tmp_name'][$key];
            $fileSize = $uploadedImages['size'][$key];
            $fileExtension = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(['success' => false, 'error' => 'Invalid file type']);
                exit;
            }

            if ($fileSize > 2 * 1024 * 1024) {
                echo json_encode(['success' => false, 'error' => 'File size exceeds 2MB']);
                exit;
            }

            $uniqueImageName = uniqid('img_', true) . '.' . $fileExtension;
            $uploadDir = '../assets/vehicles/';
            $targetFilePath = $uploadDir . $uniqueImageName;

            if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
                $uploadedFiles[] = $uniqueImageName;
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
                exit;
            }
        }

        if (!empty($uploadedFiles)) {
            $newImages = implode(',', $uploadedFiles);

            $stmt = $pdo->prepare("
                UPDATE vehicles 
                SET images = CASE 
                    WHEN images IS NULL OR images = '' THEN ? 
                    ELSE CONCAT(images, ',', ?) 
                END 
                WHERE id = ?
            ");
            $stmt->execute([$newImages, $newImages, $vehicleId]);

            echo json_encode(['success' => true, 'new_images' => $uploadedFiles]);
        } else {
            echo json_encode(['success' => false, 'error' => 'No files uploaded']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
