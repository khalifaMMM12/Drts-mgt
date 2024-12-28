<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once '../../config/db.php';

try {
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'normal';
    
    $sql = "SELECT * FROM vehicles WHERE 1=1";
    
    switch($filter) {
        case 'cleared':
            $sql .= " AND status = 'Fixed'";
            break;
        case 'repairs':
            $sql .= " AND status = 'Needs Repairs'";
            break;
        case 'normal':
            // Show all vehicles
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'status' => 'success',
        'filter' => $filter,
        'data' => $vehicles
    ]);

} catch (PDOException $e) {
    error_log('Filter Error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred'
    ]);
}
?>