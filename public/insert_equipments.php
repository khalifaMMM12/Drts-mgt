<?php
include '../config/db.php';

// Connect to the database using PDO
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $equipmentType = $_POST['equipmentType'];
        $location = $_POST['location'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($equipmentType === 'solar') {
            $capacity = $_POST['capacity'];
            $batteryType = $_POST['batteryType'];
            $noOfBatteries = $_POST['noOfBatteries'];
            $noOfPanels = $_POST['noOfPanels'];

            $stmt = $pdo->prepare("INSERT INTO solar (location, capacity, battery_type, no_of_batteries, no_of_panels, date_added, service_rendered) VALUES (?, ?, ?, ?, ?, NOW(), ?)");
            $stmt->execute([$location, $capacity, $batteryType, $noOfBatteries, $noOfPanels, 'N/A']);
        } elseif ($equipmentType === 'airConditioners') {
            $model = $_POST['model'];
            $type = $_POST['type'];
            $noOfUnits = $_POST['noOfUnits'];
            $stmt = $pdo->prepare("INSERT INTO air_conditioners (location, model, type, no_of_units, capacity, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$location, $model, $type, $noOfUnits, $_POST['capacity'], $status]);
        } elseif ($equipmentType === 'fireExtinguishers') {
            $type = $_POST['type'];
            $weight = $_POST['weight'];
            $amount = $_POST['amount'];
            $lastServiceDate = $_POST['lastServiceDate'];
            $expirationDate = $_POST['expirationDate'];
            $stmt = $pdo->prepare("INSERT INTO fire_extinguishers (type, weight, amount, location, status, last_service_date, expiration_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$type, $weight, $amount, $location, $status, $lastServiceDate, $expirationDate]);
        }

        echo json_encode(['success' => true]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
