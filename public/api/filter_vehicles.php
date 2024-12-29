<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

try {
    $filter = $_GET['filter'] ?? 'all';
    
    $sql = "SELECT * FROM vehicles";
    
    switch($filter) {
        case 'cleared':
            $sql .= " WHERE status = 'Fixed'";
            break;
        case 'repairs':
            $sql .= " WHERE status = 'Needs Repairs'";
            break;
        case 'no_repairs':
            $sql .= " WHERE status = 'No Repairs'";
            break;
        case 'all':
            // No WHERE clause - show all vehicles
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    echo json_encode([
        'status' => 'success',
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
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