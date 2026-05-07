<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $cdspRows = [];
    $lmiRows = [];

    $cdspResult = $conn->query("
        SELECT
            cdsp_id,
            date,
            school,
            cdsp_m,
            cdsp_f,
            (cdsp_m + cdsp_f) as total
        FROM careerdev
        ORDER BY date DESC, cdsp_id DESC
    ");
    while ($row = $cdspResult->fetch_assoc()) {
        $cdspRows[] = $row;
    }

    $lmiResult = $conn->query("
        SELECT
            lmi_id,
            date,
            school,
            lmi_m,
            lmi_f,
            (lmi_m + lmi_f) as total
        FROM lmi
        ORDER BY date DESC, lmi_id DESC
    ");
    while ($row = $lmiResult->fetch_assoc()) {
        $lmiRows[] = $row;
    }

    $cdspStatsResult = $conn->query("
        SELECT
            COUNT(*) as sessions,
            COALESCE(SUM(cdsp_m), 0) as male_total,
            COALESCE(SUM(cdsp_f), 0) as female_total,
            COALESCE(SUM(cdsp_m + cdsp_f), 0) as total_participants
        FROM careerdev
    ");
    $cdspStats = $cdspStatsResult->fetch_assoc();

    $lmiStatsResult = $conn->query("
        SELECT
            COUNT(*) as sessions,
            COALESCE(SUM(lmi_m), 0) as male_total,
            COALESCE(SUM(lmi_f), 0) as female_total,
            COALESCE(SUM(lmi_m + lmi_f), 0) as total_participants
        FROM lmi
    ");
    $lmiStats = $lmiStatsResult->fetch_assoc();

    $totalParticipants = (int) ($cdspStats['total_participants'] ?? 0) + (int) ($lmiStats['total_participants'] ?? 0);

    echo json_encode([
        'success' => true,
        'stats' => [
            'totalParticipants' => $totalParticipants,
            'cdspSessions' => (int) ($cdspStats['sessions'] ?? 0),
            'lmiSessions' => (int) ($lmiStats['sessions'] ?? 0),
            'lmiParticipants' => (int) ($lmiStats['total_participants'] ?? 0)
        ],
        'data' => [
            'cdsp' => $cdspRows,
            'lmi' => $lmiRows
        ]
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
