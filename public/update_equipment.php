<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');
session_start();

include '../config/db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(401);
    exit(json_encode(['success' => false, 'message' => 'Not authorized']));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['success' => false, 'message' => 'Invalid request method']));
}


try {
    error_log("UPDATE REQUEST - POST data: " . print_r($_POST, true));

    if (!isset($_POST['id']) || !isset($_POST['type'])) {
        throw new Exception('Missing required parameters: id=' . 
            (isset($_POST['id']) ? $_POST['id'] : 'missing') . 
            ', type=' . (isset($_POST['type']) ? $_POST['type'] : 'missing'));
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $type = $_POST['type'];
    
    if ($id === false) {
        throw new Exception('Invalid ID format: ' . $_POST['id']);
    }

    
    switch($type) {
        case 'solar':
            $sql = "UPDATE solar SET 
                    location = :location, 
                    capacity = :capacity, 
                    battery_type = :battery_type,
                    no_of_batteries = :no_of_batteries, 
                    no_of_panels = :no_of_panels
                    WHERE id = :id";
            $params = [
                ':location' => $_POST['location'],
                ':capacity' => $_POST['capacity'],
                ':battery_type' => $_POST['battery_type'],
                ':no_of_batteries' => $_POST['no_of_batteries'],
                ':no_of_panels' => $_POST['no_of_panels'],
                ':id' => $id
            ];
            break;
        case 'air_conditioners':
            $sql = "UPDATE air_conditioners SET 
                    location = ?, model = ?, ac_type = ?,
                    no_of_units = ?, capacity = ?, status = ?
                    WHERE id = ?";
            $params = [
                $_POST['location'],
                $_POST['model'],
                $_POST['ac_type'],
                $_POST['no_of_units'],
                $_POST['capacity'],
                $_POST['status'],
                $id
            ];
            break;
        case 'fire_extinguishers':
            $sql = "UPDATE fire_extinguishers SET 
                    fe_type = ?, weight = ?, amount = ?,
                    location = ?, status = ?, last_service_date = ?,
                    expiration_date = ?
                    WHERE id = ?";
            $params = [
                $_POST['fe_type'],
                $_POST['weight'],
                $_POST['amount'],
                $_POST['location'],
                $_POST['status'],
                $_POST['last_service_date'],
                $_POST['expiration_date'],
                $id
            ];
            break;
        case 'borehole':
            $sql = "UPDATE borehole SET 
                    location = ?, model = ?, status = ?
                    WHERE id = ?";
            $params = [
                $_POST['location'],
                $_POST['model'],
                $_POST['status'],
                $id
            ];
            break;
            
        case 'generator':
            $sql = "UPDATE generator SET 
                    location = ?, model = ?, status = ?, capacity = ?
                    WHERE id = ?";
            $params = [
                $_POST['location'],
                $_POST['model'],
                $_POST['status'],
                $_POST['capacity'],
                $id
            ];
            break;
            
        default:
            throw new Exception('Invalid equipment type');    
    }
    
   // Debug: Log SQL and params
   error_log("Executing SQL: $sql");
   error_log("Parameters: " . print_r($params, true));

   $stmt = $pdo->prepare($sql);
   if (!$stmt) {
       $error = $pdo->errorInfo();
       throw new Exception('Prepare failed: ' . $error[2]);
   }

   $result = $stmt->execute($params);
   if (!$result) {
       $error = $stmt->errorInfo();
       throw new Exception('Execute failed: ' . $error[2]);
   }

   echo json_encode([
       'success' => true,
       'message' => 'Equipment updated successfully',
       'affected_rows' => $stmt->rowCount()
   ]);

} catch(PDOException $e) {
   error_log("PDO Error: " . $e->getMessage());
   http_response_code(500);
   echo json_encode([
       'success' => false,
       'message' => 'Database error: ' . $e->getMessage(),
       'code' => $e->getCode()
   ]);
} catch(Exception $e) {
   error_log("General Error: " . $e->getMessage());
   http_response_code(400);
   echo json_encode([
       'success' => false,
       'message' => $e->getMessage()
   ]);
}