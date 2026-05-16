<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$work_immersion_id = isset($_POST['work_immersion_id']) ? (int)$_POST['work_immersion_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$contract_period = isset($_POST['contract_period']) ? trim($_POST['contract_period']) : '';
$school = isset($_POST['school']) ? trim($_POST['school']) : '';
$course = isset($_POST['course']) ? trim($_POST['course']) : '';
$required_hours = isset($_POST['required_hours']) && $_POST['required_hours'] !== '' ? (int)$_POST['required_hours'] : 0;
$inquiry_type = isset($_POST['inquiry_type']) ? trim($_POST['inquiry_type']) : '';
$preferred_org_type = isset($_POST['preferred_org_type']) ? trim($_POST['preferred_org_type']) : '';
$preferred_industry = isset($_POST['preferred_industry']) ? trim($_POST['preferred_industry']) : '';
$is_willing_outside = isset($_POST['is_willing_outside']) ? (int)$_POST['is_willing_outside'] : 0;
$internship_sched = isset($_POST['internship_sched']) ? trim($_POST['internship_sched']) : '';
$start = isset($_POST['start']) ? trim($_POST['start']) : '';
$year_level = isset($_POST['year_level']) ? trim($_POST['year_level']) : '';
// Assignment updates are not performed via this endpoint; assignment details are display-only in the WIIRP edit modal.

if ($work_immersion_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid WIIRP record id']);
    exit;
}

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $check = $conn->prepare('SELECT work_immersion_id FROM wiirp WHERE work_immersion_id = ? AND benef_id = ? LIMIT 1');
    if (!$check) {
        throw new Exception('Prepare check failed: ' . $conn->error);
    }

    $check->bind_param('ii', $work_immersion_id, $benef_id);
    if (!$check->execute()) {
        throw new Exception('Execute check failed: ' . $check->error);
    }

    $exists = $check->get_result()->fetch_assoc();
    $check->close();

    if (!$exists) {
        echo json_encode(['success' => false, 'error' => 'WIIRP record not found']);
        exit;
    }

    $conn->begin_transaction();

    $updateSql = "UPDATE wiirp
                  SET contract_period = ?,
                      school = ?,
                      course = ?,
                      required_hours = ?,
                      inquiry_type = ?,
                      preferred_org_type = ?,
                      preferred_industry = ?,
                      is_willing_outside = ?,
                      internship_sched = ?,
                      start = ?,
                      year_level = ?
                  WHERE work_immersion_id = ? AND benef_id = ?";

    $stmt = $conn->prepare($updateSql);
    if (!$stmt) {
        throw new Exception('Prepare WIIRP update failed: ' . $conn->error);
    }

    $stmt->bind_param(
        'sssisssisssii',
        $contract_period,
        $school,
        $course,
        $required_hours,
        $inquiry_type,
        $preferred_org_type,
        $preferred_industry,
        $is_willing_outside,
        $internship_sched,
        $start,
        $year_level,
        $work_immersion_id,
        $benef_id
    );

    if (!$stmt->execute()) {
        throw new Exception('Execute WIIRP update failed: ' . $stmt->error);
    }
    $stmt->close();

    // Intentionally not updating assignment details here.

    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'WIIRP record updated successfully']);
} catch (Exception $e) {
    if ($conn->errno === 0) {
        // no-op
    }
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
