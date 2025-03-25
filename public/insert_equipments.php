<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

ob_clean();
header('Content-Type: application/json');
include '../config/db.php';

try {
    // Validate request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate equipment type
    if (!isset($_POST['equipmentType'])) {
        throw new Exception('Equipment type is required');
    }

    $equipmentType = $_POST['equipmentType'];
    $location = $_POST['location'] ?? null;
    $status = $_POST['status'] ?? null;

    // Start transaction
    $pdo->beginTransaction();

    switch ($equipmentType) {
        case 'solar':
            // Validate required fields
            $requiredFields = ['capacity', 'batteryType', 'noOfBatteries', 'noOfPanels', 
                             'installationDate', 'serviceRendered', 'location'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO solar (location, capacity, battery_type, 
                    no_of_batteries, no_of_panels, installation_Date, service_rendered) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['location'],
                $_POST['capacity'],
                $_POST['batteryType'],
                $_POST['noOfBatteries'],
                $_POST['noOfPanels'],
                $_POST['installationDate'],
                $_POST['serviceRendered']
            ]);
            break;

        case 'air_conditioners':
            // Validate required fields
            $requiredFields = ['model', 'ac_type', 'noOfUnits', 'capacity', 'location', 'status'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO air_conditioners (location, model, ac_type, 
                    no_of_units, capacity, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['location'],
                $_POST['model'],
                $_POST['ac_type'],
                $_POST['noOfUnits'],
                $_POST['capacity'],
                $_POST['status']
            ]);
            break;

        case 'fire_extinguishers':
            // Validate required fields
            $requiredFields = ['fe_type', 'weight', 'amount', 'location', 'status', 
                             'lastServiceDate', 'expirationDate'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO fire_extinguishers (fe_type, weight, amount, 
                    location, status, last_service_date, expiration_date) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['fe_type'],
                $_POST['weight'],
                $_POST['amount'],
                $_POST['location'],
                $_POST['status'],
                $_POST['lastServiceDate'],
                $_POST['expirationDate']
            ]);
            break;

        case 'borehole':
            // Validate required fields
            $requiredFields = ['model', 'location', 'status'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO borehole (model, location, status) 
                    VALUES (?, ?, ?)");
            $stmt->execute([
                $_POST['model'],
                $_POST['location'],
                $_POST['status']
            ]);
            break;

        case 'generator':
            // Validate required fields
            $requiredFields = ['model', 'capacity', 'location', 'status'];
            foreach ($requiredFields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $stmt = $pdo->prepare("INSERT INTO generator (model, capacity, location, status) 
                    VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $_POST['model'],
                $_POST['capacity'],
                $_POST['location'],
                $_POST['status']
            ]);
            break;

        default:
            throw new Exception('Invalid equipment type: ' . $equipmentType);
    }

    // Commit transaction
    $pdo->commit();

    http_response_code(201); // Created
    echo json_encode([
        'success' => true,
        'message' => 'Equipment added successfully'
    ]);

} catch (PDOException $e) {
    // Rollback transaction on database error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Database Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error occurred',
        'error' => $e->getMessage()
    ]);

} catch (Exception $e) {
    // Rollback transaction on other errors
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    error_log("Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
