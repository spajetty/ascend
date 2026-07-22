<?php

function saveGipRow(mysqli $conn, array $row, array $ctx, array &$state): string {
    if (!tableExists($conn, 'gip')) {
        throw new RuntimeException('GIP table is not configured in the database.');
    }

    $sanitizedRow = $row;
    foreach (['DOB', 'Birthday', 'dob', 'birthday'] as $birthdayKey) {
        unset($sanitizedRow[$birthdayKey]);
    }
    $sanitizedRow['_parsed_dob'] = null;

    $benefId = isset($sanitizedRow['_sys_benef_id']) ? (int)$sanitizedRow['_sys_benef_id'] : 0;
    if ($benefId <= 0) {
        $benefId = (int)(ensurePersonBeneficiaryAndDocs($conn, $sanitizedRow, $ctx, $state) ?? 0);
    }

    if ($benefId <= 0) {
        return 'skipped';
    }

    $studentType = strtolower(s(rowValue($row, ['Student Type', 'Student/OSY', 'student_type'], 'student')));
    if (!in_array($studentType, ['student', 'osy', 'college graduate'])) {
        $studentType = 'student';
    }
    
    $school = s(rowValue($row, ['School Name', 'School', 'school'], ''));
    $course = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
    $highestEduc = s(rowValue($row, ['Highest Education Attained', 'Highest Educ. Attainment', 'Highest Education', 'highest_educ'], ''));
    
    $startOfContract = parseDateNullable(rowValue($row, ['Start of Contract', 'start_of_contract'], ''));
    $endOfContract = parseDateNullable(rowValue($row, ['End of Contract', 'end_of_contract'], ''));
    
    $days = parseIntNullable(rowValue($row, ['No. of Days', 'Days', 'days'], ''));
    $officeAssignment = s(rowValue($row, ['Office Assignment', 'office_assignment'], ''));

    // New fields
    $statusRaw = strtolower(s(rowValue($row, ['Status', 'status'], '')));
    $status = in_array($statusRaw, ['yes', 'no']) ? $statusRaw : null;
    
    $proponent = s(rowValue($row, ['Proponent', 'proponent'], ''));
    $gsisBeneficiary = s(rowValue($row, ['GSIS Beneficiary', 'gsis_beneficiary'], ''));
    $relationship = s(rowValue($row, ['Relationship', 'relationship'], ''));
    $rawGsisContact = s(rowValue($row, ['GSIS Benef. Contact No.', 'GSIS Benef. Contact No', 'gsis_benef_contact_no'], ''));
    $gsisContact = substr(trim(explode('/', $rawGsisContact)[0]), 0, 15);

    $gipType = strtoupper(trim((string)($ctx['gipCategory'] ?? '')));
    $batchId = isset($ctx['batchId']) ? (int)$ctx['batchId'] : null;

    $dupStmt = $conn->prepare('SELECT 1 FROM `gip` WHERE `benef_id` = ? AND `start_of_contract` = ? AND `end_of_contract` = ? AND `type` = ? LIMIT 1');
    $dupStmt->bind_param('isss', $benefId, $startOfContract, $endOfContract, $gipType);
    $dupStmt->execute();
    if ($dupStmt->get_result()->fetch_assoc()) {
        return 'skipped';
    }

    $columns = [
        '`benef_id`',
        '`student_type`',
        '`highest_educ`',
        '`course`',
        '`school`',
        '`start_of_contract`',
        '`end_of_contract`',
        '`days`',
        '`office_assignment`',
        '`type`',
        '`status`',
        '`proponent`',
        '`gsis_beneficiary`',
        '`relationship`',
        '`gsis_benef_contact_no`'
    ];
    $values = [
        $benefId,
        $studentType,
        $highestEduc,
        $course,
        $school,
        $startOfContract,
        $endOfContract,
        $days,
        $officeAssignment,
        $gipType,
        $status,
        $proponent,
        $gsisBeneficiary,
        $relationship,
        $gsisContact
    ];
    $types = 'issssssisssssss';

    if (tableHasColumn($conn, 'gip', 'batch_id')) {
        $columns[] = '`batch_id`';
        $values[] = $batchId;
        $types .= 'i';
    }

    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    $stmt = $conn->prepare('INSERT INTO `gip` (' . implode(', ', $columns) . ') VALUES (' . $placeholders . ')');
    $stmt->bind_param($types, ...$values);
    $stmt->execute();

    $state['insertedGipIds'][] = (int)$stmt->insert_id;

    return 'saved';
}
