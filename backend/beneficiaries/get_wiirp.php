<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_GET['benef_id']) ? (int)$_GET['benef_id'] : 0;
if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $sql = "SELECT work_immersion_id, benef_id, contract_period, school, course, required_hours,
                   inquiry_type, preferred_org_type, preferred_industry, is_willing_outside,
                   internship_sched, start, year_level, type
            FROM wiirp
            WHERE benef_id = ?
            ORDER BY created_at DESC, work_immersion_id DESC
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $benef_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    $assignmentRecords = [];
    if ($record && !empty($record['work_immersion_id'])) {
        $assignmentSql = "SELECT id, work_immersion_id, start_date, end_date, required_hours,
                                 office_assignment, endorsement_1, endorsement_2
                          FROM wiirp_assignment_details
                          WHERE work_immersion_id = ?
                          ORDER BY start_date DESC, id DESC";

        $assignmentStmt = $conn->prepare($assignmentSql);
        $workImmersionId = (int)$record['work_immersion_id'];
        $assignmentStmt->bind_param('i', $workImmersionId);
        $assignmentStmt->execute();
        $assignmentResult = $assignmentStmt->get_result();

        while ($assignmentRow = $assignmentResult->fetch_assoc()) {
            $assignmentRecords[] = $assignmentRow;
        }
    }

    echo json_encode([
        'success' => true,
        'record' => $record ?: null,
        'assignments' => $assignmentRecords,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
