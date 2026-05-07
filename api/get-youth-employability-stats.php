<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $stats = [];

    // Total Youth Served (count unique beneficiaries in apply_benef)
    $result = $conn->query("SELECT COUNT(DISTINCT benef_id) as total FROM apply_benef");
    $stats['totalYouthServed'] = $result->fetch_assoc()['total'] ?? 0;

    // SPES Participants (count records in spes)
    $result = $conn->query("SELECT COUNT(*) as total FROM spes");
    $stats['spesParticipants'] = $result->fetch_assoc()['total'] ?? 0;

    // GIP Interns (count records in gip)
    $result = $conn->query("SELECT COUNT(*) as total FROM gip");
    $stats['gipInterns'] = $result->fetch_assoc()['total'] ?? 0;

    // Work Immersion Participants (count records in workImmersion_internshipReferral)
    $result = $conn->query("SELECT COUNT(*) as total FROM workImmersion_internshipReferral");
    $stats['workImmersionParticipants'] = $result->fetch_assoc()['total'] ?? 0;

    // Total Hired/Placed (count from empHistory with specific classification or status)
    $result = $conn->query("SELECT COUNT(*) as total FROM empHistory WHERE classification = 'Placed'");
    $stats['totalHired'] = $result->fetch_assoc()['total'] ?? 0;

    echo json_encode(['success' => true, 'data' => $stats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
