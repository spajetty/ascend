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

    // Build contract period from import month/year + duration (months). Default duration is 3 months.
    $monthRaw = trim((string)($ctx['importMonthRaw'] ?? ''));
    $yearRaw = trim((string)($ctx['importYearRaw'] ?? ''));
    $durationMonths = isset($ctx['importDurationMonths']) ? (int)$ctx['importDurationMonths'] : 3;
    $contractPeriod = trim($monthRaw . ' ' . $yearRaw);
    $monthInt = monthToInt($monthRaw);
    $yearInt = is_numeric($yearRaw) ? (int)$yearRaw : null;
    if ($monthInt !== null && $yearInt !== null) {
        try {
            $start = new DateTime();
            $start->setDate($yearInt, $monthInt, 1);
            $end = clone $start;
            if ($durationMonths > 1) {
                $end->modify('+' . ($durationMonths - 1) . ' months');
            }
            $contractPeriod = $start->format('M Y') . ' - ' . $end->format('M Y') . ' (' . $durationMonths . ' months)';
        } catch (Throwable $e) {
            // fallback to raw text
            $contractPeriod = trim($monthRaw . ' ' . $yearRaw);
        }
    }
    $school = s(rowValue($row, ['School Name', 'School', 'school'], ''));
    $course = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
    $requiredHours = parseIntNullable(rowValue($row, ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours'], ''));
    $preferredOrgType = s(rowValue($row, ['Preferred Host Organization Type', 'preferred_org_type'], ''));
    $preferredIndustry = s(rowValue($row, ['Preferred Industry / Field of Internship', 'preferred_industry'], ''));
    $isWillingOutside = toBoolInt(rowValue($row, ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'], ''));
    $officeAssignment = s(rowValue($row, ['Office Assignment', 'office_assignment'], ''));
    $collegeOrShs = s(rowValue($row, ['College/SHS', 'College or SHS', 'college_or_shs'], ''));

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
        '`required_hours`',
        '`preferred_org_type`',
        '`preferred_industry`',
        '`is_willing_outside`',
        '`office_assignment`',
        '`college_or_shs`',
        '`type`',
    ];
    $values = [
        $benefId,
        $contractPeriod,
        $school,
        $course,
        $requiredHours,
        $preferredOrgType,
        $preferredIndustry,
        $isWillingOutside,
        $officeAssignment,
        $collegeOrShs,
        $gipType,
    ];
    $types = 'isssississs';

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
