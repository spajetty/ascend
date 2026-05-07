<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $query = "
        SELECT
            whip_id,
            month,
            year,
            male,
            female,
            (male + female) as total,
            project_name
        FROM whip
        ORDER BY
            year DESC,
            FIELD(LOWER(month),
                'january','february','march','april','may','june',
                'july','august','september','october','november','december'
            ) DESC,
            whip_id DESC
    ";

    $result = $conn->query($query);
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $statsQuery = "
        SELECT
            COALESCE(SUM(male), 0) as male_total,
            COALESCE(SUM(female), 0) as female_total,
            COALESCE(SUM(male + female), 0) as workers_hired,
            COUNT(DISTINCT project_name) as infrastructure_projects
        FROM whip
    ";
    $statsResult = $conn->query($statsQuery);
    $stats = $statsResult->fetch_assoc();

    echo json_encode([
        'success' => true,
        'data' => $data,
        'stats' => [
            'maleTotal' => (int) ($stats['male_total'] ?? 0),
            'femaleTotal' => (int) ($stats['female_total'] ?? 0),
            'workersHired' => (int) ($stats['workers_hired'] ?? 0),
            'infrastructureProjects' => (int) ($stats['infrastructure_projects'] ?? 0)
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
