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

    // Handle status and repairs
    $needs_repairs = isset($_POST['needs_repairs']) ? 1 : 0;
    $status = $needs_repairs ? 'Needs Repairs' : 'No Repairs';
    $repair_type = $needs_repairs ? ($_POST['repair_type'] ?? '') : '';

    // Handle file uploads
    $images = [];
    if (!empty($_FILES['new_images']['name'][0])) {
        foreach ($_FILES['new_images']['tmp_name'] as $key => $tmp_name) {
            $filename = uniqid() . '_' . $_FILES['new_images']['name'][$key];
            move_uploaded_file($tmp_name, "../assets/vehicles/" . $filename);
            $images[] = $filename;
        }
    }

    // Update vehicle data
    $stmt = $pdo->prepare("
        UPDATE vehicles 
        SET reg_no = ?, type = ?, make = ?, location = ?, 
            status = ?, repair_type = ?, inspection_date = ?,
            images = CASE 
                WHEN ? != '' THEN CONCAT(COALESCE(images, ''), ',', ?)
                ELSE images 
            END
        WHERE id = ?
    ");

    $newImages = !empty($images) ? implode(',', $images) : '';
    
    $stmt->execute([
        $_POST['reg_no'],
        $_POST['type'],
        $_POST['make'],
        $_POST['location'],
        $status,
        $repair_type,
        $_POST['inspection_date'],
        $newImages,
        $newImages,
        $vehicleId
    ]);

    // Fetch updated vehicle data
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->execute([$vehicleId]);
    $vehicle = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'message' => 'Vehicle updated successfully',
        'vehicle' => $vehicle
    ]);

} catch (Exception $e) {
    error_log("Error updating vehicle: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'details' => [
            'vehicleId' => $vehicleId,
            'postData' => $_POST
        ]
    ]);
}
?>