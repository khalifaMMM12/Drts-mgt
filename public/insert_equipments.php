<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
include '../config/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipmentType = $_POST['equipmentType'];
        $location = $_POST['location'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($equipmentType === 'solar') {
            $capacity = $_POST['capacity'];
            $batteryType = $_POST['batteryType'];
            $noOfBatteries = $_POST['noOfBatteries'];
            $noOfPanels = $_POST['noOfPanels'];

            $stmt = $pdo->prepare("INSERT INTO solar (location, capacity, battery_type, no_of_batteries, no_of_panels, date_added) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->execute([$location, $capacity, $batteryType, $noOfBatteries, $noOfPanels]);
        } elseif ($equipmentType === 'airConditioners') {
            $model = $_POST['model'];
            $type = $_POST['type'];
            $noOfUnits = $_POST['noOfUnits'];
            $capacity = $_POST['capacity'];

            $stmt = $pdo->prepare("INSERT INTO airConditioners (location, model, type, no_of_units, capacity, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$location, $model, $type, $noOfUnits, $capacity, $status]);
        } elseif ($equipmentType === 'fireExtinguishers') {
            $type = $_POST['type'];
            $weight = $_POST['weight'];
            $amount = $_POST['amount'];
            $lastServiceDate = $_POST['lastServiceDate'];
            $expirationDate = $_POST['expirationDate'];

            $stmt = $pdo->prepare("INSERT INTO fireExtinguishers (type, weight, amount, location, status, last_service_date, expiration_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$type, $weight, $amount, $location, $status, $lastServiceDate, $expirationDate]);
        } elseif ($equipmentType === 'borehole'){
            $model = $_POST['model'];

            $stmt = $pdo->prepare("INSERT INTO borehole (model, location, status) VALUES (?, ?, ?)");
            $stmt->execute([$model, $location, $status]);
        } elseif ($equipmentType === 'generator'){
            $model = $_POST['model'];
            $capacity = $_POST['capacity'];

            $stmt = $pdo->prepare("INSERT INTO generator (model, capacity, location, status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$model, $capacity, $location, $status]);
        }
        else {
            echo json_encode(['success' => false, 'error' => 'Invalid equipment type']);
            exit;
        }

        // Success response
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
}
?>
