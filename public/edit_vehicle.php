<?php 
include '../config/db.php';  
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $vehicleId = $_POST['id'] ?? null;
    if (!$vehicleId) {
        throw new Exception('Vehicle ID is required');
    }

    

    error_log("POST data: " . print_r($_POST, true));

    $needs_repairs = isset($_POST['needs_repairs']) ? (int)$_POST['needs_repairs'] : 0;
    $status = $needs_repairs === 1 ? 'Needs Repairs' : 'No Repairs';
    $repair_type = $_POST['repair_type'] ?? '';

    $imagesToDelete = json_decode($_POST['images_to_delete'] ?? '[]', true);
    if (!empty($imagesToDelete)) {
        $stmt = $pdo->prepare("SELECT images FROM vehicles WHERE id = ?");
        $stmt->execute([$vehicleId]);
        $currentVehicle = $stmt->fetch();
        $currentImages = explode(',', $currentVehicle['images']);

        foreach ($imagesToDelete as $imageToDelete) {
            $imagePath = "../assets/vehicles/" . $imageToDelete;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $currentImages = array_filter($currentImages, function($img) use ($imageToDelete) {
                return trim($img) !== trim($imageToDelete);
            });
        }

        $imageString = implode(',', array_filter($currentImages));
        
        $stmt = $pdo->prepare("UPDATE vehicles SET images = ? WHERE id = ?");
        $stmt->execute([$imageString, $vehicleId]);
    }

    $stmt = $pdo->prepare("SELECT images FROM vehicles WHERE id = ?");
    $stmt->execute([$vehicleId]);
    $currentVehicle = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $currentImages = $currentVehicle['images'] ? explode(',', $currentVehicle['images']) : [];
    if (isset($_SESSION['images_to_delete']) && !empty($_SESSION['images_to_delete'])) {
        foreach ($_SESSION['images_to_delete'] as $imageToDelete) {
            $imagePath = "../assets/vehicles/" . $imageToDelete;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $currentImages = array_filter($currentImages, function($img) use ($imageToDelete) {
                return trim($img) !== trim($imageToDelete);
            });
        }
        unset($_SESSION['images_to_delete']);
    }

    $images = [];
    if (!empty($_FILES['new_images']['name'][0])) {
        foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
            $filename = uniqid() . '_' . $_FILES['new_images']['name'][$key];
            move_uploaded_file($tmp_name, "../assets/vehicles/" . $filename);
            $images[] = $filename;
        }
    }

    $allImages = array_merge($currentImages, $images);
    $imageString = implode(',', array_filter($allImages));

    $stmt = $pdo->prepare("
        UPDATE vehicles 
        SET reg_no = ?, 
            type = ?, 
            make = ?, 
            location = ?, 
            status = ?, 
            repair_type = ?, 
            
            needs_repairs = ?,
            images = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST['reg_no'],
        $_POST['type'],
        $_POST['make'],
        $_POST['location'],
        $status,
        $repair_type,
        
        $needs_repairs,
        $imageString,
        $vehicleId
    ]);

    $updated = $pdo->query("SELECT * FROM vehicles WHERE id = {$_POST['id']}")->fetch();
    error_log("Updated vehicle: " . print_r($updated, true));

    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$vehicleId]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Vehicle updated successfully',
        'vehicle' => [
            'id' => $vehicle['id'],
            'reg_no' => $vehicle['reg_no'],
            'type' => $vehicle['type'],
            'make' => $vehicle['make'],
            'location' => $vehicle['location'],
            'status' => $status,
            'repair_type' => $repair_type,
            'needs_repairs' => $needs_repairs,
            
            'images' => $imageString
        ]
    ]);

} catch (Exception $e) {
    error_log("Error updating vehicle: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>