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
    if (!isset($_POST['id']) || !isset($_POST['type'])) {
        throw new Exception('Missing required parameters');
    }

    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $type = $_POST['type'];
    
    if ($id === false) {
        throw new Exception('Invalid ID format');
    }

    
    switch($type) {
        case 'solar':
            if (!isset($_POST['location']) || !isset($_POST['capacity']) || 
                !isset($_POST['battery_type']) || !isset($_POST['no_of_batteries']) || 
                !isset($_POST['no_of_panels'])) {
                throw new Exception('Missing required solar parameters');
            }
            $sql = "UPDATE solar SET 
                location = ?, 
                capacity = ?, 
                battery_type = ?,
                no_of_batteries = ?, 
                no_of_panels = ?
                WHERE id = ?";
            $params = [
                $_POST['location'],
                $_POST['capacity'],
                $_POST['battery_type'],
                $_POST['no_of_batteries'],
                $_POST['no_of_panels'],
                $id
            ];
            break;
        case 'airConditioners':
            $sql = "UPDATE airConditioners SET 
                    location = ?, model = ?, type = ?,
                    no_of_units = ?, capacity = ?, status = ?
                    WHERE id = ?";
            $params = [
                $_POST['location'],
                $_POST['model'],
                $_POST['type'],
                $_POST['no_of_units'],
                $_POST['capacity'],
                $_POST['status'],
                $id
            ];
            break;
        case 'fireExtinguishers':
            $sql = "UPDATE fireExtinguishers SET 
                    type = ?, weight = ?, amount = ?,
                    location = ?, status = ?, last_service_date = ?,
                    expiration_date = ?
                    WHERE id = ?";
            $params = [
                $_POST['type'],
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
    
    $stmt = $pdo->prepare($sql);
    if (!$stmt) {
        throw new Exception('Failed to prepare statement');
    }

    $stmt->execute($params);
    if (!$result) {
        throw new Exception('Failed to execute statement');
    }


    if ($stmt->rowCount() === 0) {
        throw new Exception('No equipment found with the given ID');
    }
    
    http_response_code(200);
    exit(json_encode([
        'success' => true, 
        'message' => 'Equipment updated successfully'
    ]));    

} catch(PDOException $e) {
        http_response_code(500);
        error_log("Database Error: " . $e->getMessage());
        exit(json_encode([
            'success' => false,
            'message' => 'Database error occurred'
        ]));
    } catch(Exception $e) {
        http_response_code(400);
        error_log("Update Error: " . $e->getMessage());
        exit(json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]));
    }
?>