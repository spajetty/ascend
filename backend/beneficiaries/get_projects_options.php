<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

try {
    $sql = 'SELECT project_id, project_title, contractor FROM projects ORDER BY project_title ASC';
    $res = $conn->query($sql);
    $projects = [];
    while ($r = $res->fetch_assoc()) {
        $projects[] = $r;
    }
    echo json_encode(['success' => true, 'projects' => $projects]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
