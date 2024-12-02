<?php
header('Content-Type: application/json');
include '../config/db.php';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $equipmentType = $_GET['type'];
    $data = [];

    if ($equipmentType === 'solar') {
        $stmt = $pdo->query("SELECT * FROM solar");
    } elseif ($equipmentType === 'airConditioners') {
        $stmt = $pdo->query("SELECT * FROM air_conditioners");
    } elseif ($equipmentType === 'fireExtinguishers') {
        $stmt = $pdo->query("SELECT * FROM fire_extinguishers");
    }

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
