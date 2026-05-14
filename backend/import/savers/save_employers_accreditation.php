<?php

function saveEmployersAccreditationRow(mysqli $conn, array $row, array $ctx, array &$state): string {
    $companyName = s(rowValue($row, ['COMPANY', 'Company', 'CompanyName'], ''));
    $estType = s(rowValue($row, ['EST. TYPE', 'Establishment Type', 'EstType', 'est_type'], '')) ?: null;
    $industry = s(rowValue($row, ['INDUSTRY', 'Industry', 'industry'], '')) ?: null;
    $city = s(rowValue($row, ['CITY/MUNICIPALITY/PROVINCE', 'City/Municipality/Province', 'City/Municipality', 'city'], '')) ?: null;
    $accStatus = strtolower(s(rowValue($row, ['ACCREDITATION', 'Accreditation', 'accreditation'], 'new')));
    $monthRaw = rowValue($row, ['MONTH', 'Month', 'month'], '');

    if ($companyName === '') {
        return 'skipped';
    }

    $accStatus = in_array($accStatus, ['new', 'renew'], true) ? $accStatus : 'new';

    $importMonthRaw = (string)($ctx['importMonthRaw'] ?? '');
    $importYearRaw = (string)($ctx['importYearRaw'] ?? '');

    $monthInt = monthToInt($monthRaw) ?? monthToInt($importMonthRaw);
    if ($monthInt === null) {
        return 'skipped';
    }

    $yearInt = (int)$importYearRaw;
    if ($yearInt < 1900 || $yearInt > 3000) {
        return 'skipped';
    }

    $existingEmpId = isset($row['_sys_employer_id']) && (int)$row['_sys_employer_id'] > 0
        ? (int)$row['_sys_employer_id']
        : null;

    if ($existingEmpId) {
        $employerId = $existingEmpId;
        $setClauses = [];
        $bindTypes = '';
        $bindValues = [];

        if ($estType !== null) {
            $setClauses[] = 'est_type = ?';
            $bindTypes .= 's';
            $bindValues[] = $estType;
        }
        if ($industry !== null) {
            $setClauses[] = 'industry = ?';
            $bindTypes .= 's';
            $bindValues[] = $industry;
        }
        if ($city !== null) {
            $setClauses[] = 'city = ?';
            $bindTypes .= 's';
            $bindValues[] = $city;
        }

        if (!empty($setClauses)) {
            $bindTypes .= 'i';
            $bindValues[] = $employerId;
            $upd = $conn->prepare('UPDATE employers SET ' . implode(', ', $setClauses) . ' WHERE company_id = ?');
            $upd->bind_param($bindTypes, ...$bindValues);
            $upd->execute();
        }
    } else {
        $employerResult = resolveEmployer($conn, $companyName, null, [
            'est_type' => $estType,
            'industry' => $industry,
            'city' => $city,
        ]);
        $employerId = (int)($employerResult['id'] ?? 0);
        if ($employerId > 0 && !empty($employerResult['created'])) {
            $state['createdEmployerIds'][] = $employerId;
            $state['warnings'][] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
        }
    }

    if (empty($employerId)) {
        return 'skipped';
    }

    $chk = $conn->prepare('
        SELECT accreditation_id FROM employers_accreditations
        WHERE company_id = ? AND month = ? AND year = ? LIMIT 1
    ');
    $chk->bind_param('iii', $employerId, $monthInt, $yearInt);
    $chk->execute();
    if ($chk->get_result()->fetch_assoc()) {
        return 'skipped';
    }

    $insAcc = $conn->prepare('
        INSERT INTO employers_accreditations (company_id, status, month, year)
        VALUES (?, ?, ?, ?)
    ');
    $insAcc->bind_param('isii', $employerId, $accStatus, $monthInt, $yearInt);
    $insAcc->execute();
    $state['insertedAccreditationIds'][] = (int)$conn->insert_id;

    return 'saved';
}
