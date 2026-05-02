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

    $contractPeriod = trim((string)($ctx['importMonthRaw'] ?? '') . ' ' . (string)($ctx['importYearRaw'] ?? ''));
    $school = s(rowValue($row, ['School Name', 'School', 'school'], ''));
    $course = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
    $yearLevel = s(rowValue($row, ['Year Level', 'year_level'], ''));
    $requiredHours = parseIntNullable(rowValue($row, ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours'], ''));
    $preferredOrgType = s(rowValue($row, ['Preferred Host Organization Type', 'preferred_org_type'], ''));
    $preferredIndustry = s(rowValue($row, ['Preferred Industry / Field of Internship', 'preferred_industry'], ''));
    $isWillingOutside = toBoolInt(rowValue($row, ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'], ''));
    $internshipSched = s(rowValue($row, ['Internship Schedule / Availability', 'internship_sched'], ''));
    $startDate = !empty($row['_parsed_start_date'])
        ? (string)$row['_parsed_start_date']
        : parseDateNullable(rowValue($row, ['Internship Availability Date (Start of Internship)', 'Starting Date', 'start'], ''));

    $gipType = strtoupper(trim((string)($ctx['gipCategory'] ?? '')));
    $batchId = isset($ctx['batchId']) ? (int)$ctx['batchId'] : null;

    $dupStmt = $conn->prepare('SELECT 1 FROM `gip` WHERE `benef_id` = ? AND `contract_period` = ? AND `type` = ? LIMIT 1');
    $dupStmt->bind_param('iss', $benefId, $contractPeriod, $gipType);
    $dupStmt->execute();
    if ($dupStmt->get_result()->fetch_assoc()) {
        return 'skipped';
    }

    $columns = [
        '`benef_id`',
        '`contract_period`',
        '`school`',
        '`course`',
        '`year_level`',
        '`required_hours`',
        '`preferred_org_type`',
        '`preferred_industry`',
        '`is_willing_outside`',
        '`internship_sched`',
        '`start`',
        '`type`',
    ];
    $values = [
        $benefId,
        $contractPeriod,
        $school,
        $course,
        $yearLevel,
        $requiredHours,
        $preferredOrgType,
        $preferredIndustry,
        $isWillingOutside,
        $internshipSched,
        $startDate,
        $gipType,
    ];
    $types = 'issssississs';

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
