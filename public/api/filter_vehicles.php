<?php
header('Content-Type: application/json');
require_once '../../config/db.php';

try {
    $filter = $_GET['filter'] ?? 'all';
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT * FROM vehicles WHERE (reg_no LIKE :search OR type LIKE :search OR location LIKE :search)";
    
    switch($filter) {
        case 'cleared':
            $sql .= " AND status = 'Fixed'";
            break;
        case 'repairs':
            $sql .= " AND status = 'Needs Repairs'";
            break;
        case 'no_repairs':
            $sql .= " AND status = 'No Repairs'";
            break;
        case 'all':
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':search' => '%' . $search . '%']);
    
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