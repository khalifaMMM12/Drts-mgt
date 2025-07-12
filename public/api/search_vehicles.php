<?php
require_once '../../config/db.php';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sql = "SELECT * FROM vehicles WHERE reg_no LIKE :search OR type LIKE :search OR location LIKE :search";
$stmt = $pdo->prepare($sql);
$stmt->execute([':search' => '%' . $search . '%']);
$vehicles = $stmt->fetchAll();
header('Content-Type: application/json');
echo json_encode(['vehicles' => $vehicles]);
