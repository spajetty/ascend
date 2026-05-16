<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$benefId = (int)($data['benef_id'] ?? 0);

if ($benefId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
    exit;
}

// Accept parsed name parts or fallback to single full name
$first_name = trim($data['first_name'] ?? '');
$middle_name = trim($data['middle_name'] ?? '');
$last_name = trim($data['last_name'] ?? '');
$suffix = trim($data['suffix'] ?? '');
$dob = $data['dob'] ?? null;
$sex = $data['sex'] ?? null;
$civil_status = $data['civil_status'] ?? null;
// Address components
$house_no = trim($data['house_no'] ?? '');
$barangay = trim($data['barangay'] ?? '');
$district = trim($data['district'] ?? '');
$city = trim($data['city'] ?? '');

try {
    $stmt = $conn->prepare('UPDATE beneficiaries SET first_name = ?, middle_name = ?, last_name = ?, suffix = ?, dob = ?, sex = ?, civil_status = ?, house_no = ?, barangay = ?, district = ?, city = ? WHERE benef_id = ?');
    if ($stmt === false) throw new Exception($conn->error);
    $stmt->bind_param('sssssssssssi', $first_name, $middle_name, $last_name, $suffix, $dob, $sex, $civil_status, $house_no, $barangay, $district, $city, $benefId);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    echo json_encode(['success' => true, 'affected' => $affected]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

