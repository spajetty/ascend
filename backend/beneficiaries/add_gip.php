<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$benef_id = isset($_POST['benef_id']) ? (int) $_POST['benef_id'] : 0;
$type = isset($_POST['type']) ? trim($_POST['type']) : '';

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

$typeUpper = strtoupper($type);
if (!in_array($typeUpper, ['DOLE', 'LGU'], true)) {
    echo json_encode(['success' => false, 'error' => 'Type must be DOLE or LGU']);
    exit;
}

$student_type = trim($_POST['student_type'] ?? '');
$highest_educ = trim($_POST['highest_educ'] ?? '');
$course = trim($_POST['course'] ?? '');
$school = trim($_POST['school'] ?? '');
$start_of_contract = trim($_POST['start_of_contract'] ?? '');
$end_of_contract = trim($_POST['end_of_contract'] ?? '');
if ($end_of_contract === '') {
    $end_of_contract = null;
}
$days = isset($_POST['days']) && $_POST['days'] !== '' ? (int) $_POST['days'] : null;
$office_assignment = trim($_POST['office_assignment'] ?? '');
$proponent = trim($_POST['proponent'] ?? '');
$status = trim($_POST['status'] ?? '');
$gsis_beneficiary = trim($_POST['gsis_beneficiary'] ?? '');
$relationship = trim($_POST['relationship'] ?? '');
$gsis_contact = trim($_POST['gsis_benef_contact_no'] ?? '');

if ($start_of_contract === '') {
    echo json_encode(['success' => false, 'error' => 'Start of contract is required']);
    exit;
}

// Type-conditional required fields
if ($typeUpper === 'LGU') {
    if ($school === '' || $course === '' || $office_assignment === '' || $proponent === '') {
        echo json_encode(['success' => false, 'error' => 'School, course, office assignment, and proponent are required for an LGU GIP record']);
        exit;
    }
} else { // DOLE
    if ($gsis_beneficiary === '' || $relationship === '' || $gsis_contact === '') {
        echo json_encode(['success' => false, 'error' => 'GSIS beneficiary, relationship, and GSIS contact number are required for a DOLE GIP record']);
        exit;
    }
}

try {
    // Block a second active enrollment: no end date, or end date today/in the future.
    $overlapSql = "SELECT COUNT(*) AS cnt FROM gip
                    WHERE benef_id = ?
                      AND (end_of_contract IS NULL OR end_of_contract >= CURDATE())";
    $overlapStmt = $conn->prepare($overlapSql);
    $overlapStmt->bind_param('i', $benef_id);
    $overlapStmt->execute();
    $overlapResult = $overlapStmt->get_result()->fetch_assoc();
    $overlapStmt->close();

    if ((int) $overlapResult['cnt'] > 0) {
        echo json_encode([
            'success' => false,
            'error' => 'This beneficiary already has an active GIP enrollment. End that contract before adding a new one.'
        ]);
        exit;
    }

    $sql = "INSERT INTO gip
                (benef_id, student_type, highest_educ, course, school,
                 start_of_contract, end_of_contract, days, office_assignment, type,
                 proponent, status, gsis_beneficiary, relationship, gsis_benef_contact_no)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'issssssisssssss',
        $benef_id,
        $student_type,
        $highest_educ,
        $course,
        $school,
        $start_of_contract,
        $end_of_contract,
        $days,
        $office_assignment,
        $typeUpper,
        $proponent,
        $status,
        $gsis_beneficiary,
        $relationship,
        $gsis_contact
    );
    $stmt->execute();

    echo json_encode(['success' => true, 'gip_id' => $conn->insert_id]);
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>