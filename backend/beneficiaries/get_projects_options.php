<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

try {
    $sql = 'SELECT
                project_id,
                project_title,
                contractor,
                nature_of_project,
                duration,
                budget,
                fund_source,
                is_legitimate_contractor,
                persons_from_locality AS persons_locality,
                filled,
                unfilled,
                skills_required,
                skills_deficiencies
            FROM projects
            ORDER BY project_title ASC';
    $res = $conn->query($sql);
    $projects = [];
    while ($r = $res->fetch_assoc()) {
        // Frontend edit form uses a YES/NO select, DB stores a tinyint boolean.
        $r['legitimate_contractors'] = !empty($r['is_legitimate_contractor']) ? 'YES' : 'NO';
        unset($r['is_legitimate_contractor']);
        $projects[] = $r;
    }
    echo json_encode(['success' => true, 'projects' => $projects]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>