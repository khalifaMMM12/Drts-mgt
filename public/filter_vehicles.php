<?php
include '../config/db.php';

header('Content-Type: application/json');

try {
    $filter = $_GET['filter'] ?? 'all';
    
    $sql = "
        SELECT v.*, 
            CASE 
                WHEN i.status = 'passed' THEN 'cleared'
                WHEN i.status = 'failed' THEN 'needs_repairs'
                ELSE 'pending'
            END as status
        FROM vehicles v
        LEFT JOIN inspections i ON v.id = i.vehicle_id
    ";

    if ($filter === 'cleared') {
        $sql .= " WHERE i.status = 'passed'";
    } elseif ($filter === 'repairs') {
        $sql .= " WHERE i.status = 'failed'";
    }
    
    $sql .= " ORDER BY v.registration_number";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    echo json_encode([
        'status' => 'success',
        'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
    error_log('Database error in filter_vehicles.php: ' . $e->getMessage());
}
?>