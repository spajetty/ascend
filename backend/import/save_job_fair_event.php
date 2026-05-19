<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$body = json_decode(file_get_contents('php://input'), true);
$eventId = intval($body['event_id'] ?? 0);
$type = trim($body['job_fair_type'] ?? 'LOCAL JOB FAIR');
$venue = trim($body['venue'] ?? '');
$dateStart = trim($body['date_start'] ?? '');
$companyIds = array_values(array_unique(array_filter(array_map('intval', $body['company_ids'] ?? []))));

if (empty($companyIds)) {
    echo json_encode(['success' => false, 'error' => 'Add at least one company participant.']);
    exit;
}

if ($eventId <= 0) {
    if (!$type || !$venue || !$dateStart) {
        echo json_encode(['success' => false, 'error' => 'Job fair type, venue, and date are required.']);
        exit;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateStart)) {
        echo json_encode(['success' => false, 'error' => 'Invalid date format. Use YYYY-MM-DD.']);
        exit;
    }
}

try {
    $conn->begin_transaction();

    if ($eventId <= 0) {
        $stmt = $conn->prepare(
            'INSERT INTO job_fair_events (job_fair_type, venue, date_start, date_end, created_at) VALUES (?, ?, ?, ?, NOW())'
        );
        $stmt->bind_param('ssss', $type, $venue, $dateStart, $dateStart);
        $stmt->execute();
        $eventId = (int)$conn->insert_id;
        $stmt->close();
    } else {
        $check = $conn->prepare('SELECT jobfairevent_id FROM job_fair_events WHERE jobfairevent_id = ? LIMIT 1');
        $check->bind_param('i', $eventId);
        $check->execute();
        if (!$check->get_result()->fetch_assoc()) {
            $check->close();
            throw new Exception('Job fair event not found.');
        }
        $check->close();
    }

    foreach ($companyIds as $companyId) {
        $stmt = $conn->prepare('INSERT IGNORE INTO jobfair_participants (jobfairevent_id, company_id) VALUES (?, ?)');
        $stmt->bind_param('ii', $eventId, $companyId);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare('
        SELECT jp.company_id, e.company_name
        FROM jobfair_participants jp
        LEFT JOIN employers e ON e.company_id = jp.company_id
        WHERE jp.jobfairevent_id = ?
        ORDER BY e.company_name ASC
    ');
    $stmt->bind_param('i', $eventId);
    $stmt->execute();
    $result = $stmt->get_result();
    $participants = [];
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
    $stmt->close();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'jobfairevent_id' => $eventId,
        'job_fair_type' => $type,
        'venue' => $venue,
        'date_start' => $dateStart,
        'participants' => $participants,
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
