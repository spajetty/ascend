<?php

function saveSPESRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    $school = s(rowValue($row, ['School', 'school'], ''));
    $studentType = s(rowValue($row, ['Student/OSY', 'student_type'], ''));
    $highestEduc = s(rowValue($row, ['HIGHEST EDUC. ATTAINMENT', 'highest_educ', 'Highest Educ Attainment'], '')) ?: null;
    $course = s(rowValue($row, ['Course', 'course'], '')) ?: null;
    $batchId = isset($ctx['batchId']) ? (int)$ctx['batchId'] : null;

    // Normalize student type to enum values
    $studentTypeLower = strtolower($studentType);
    $studentTypeNorm = in_array($studentTypeLower, ['student', 'osy'], true) ? $studentTypeLower : 'student';

    // Insert SPES record
    $spesHasBatchId = tableHasColumn($conn, 'spes', 'batch_id');
    if ($spesHasBatchId) {
        $insSPES = $conn->prepare('
            INSERT INTO spes
                (benef_id, student_type, highest_educ, course, school, batch_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ');
        $insSPES->bind_param('issssi', $benefId, $studentTypeNorm, $highestEduc, $course, $school, $batchId);
    } else {
        $insSPES = $conn->prepare('
            INSERT INTO spes
                (benef_id, student_type, highest_educ, course, school)
            VALUES (?, ?, ?, ?, ?)
        ');
        $insSPES->bind_param('issss', $benefId, $studentTypeNorm, $highestEduc, $course, $school);
    }
    $insSPES->execute();
    $spesId = (int)$insSPES->insert_id;

    // Sync 4Ps / PWD / OFW flags + spes_status to the beneficiaries row
    $is_pwd = (strtoupper(trim((string) rowValue($row, ['PWD', 'pwd'], 'NO')))                           === 'YES') ? 1 : 0;
    $is_4ps = (strtoupper(trim((string) rowValue($row, ['4Ps BENEFICIARY', '4ps_beneficiary'], 'NO')))  === 'YES') ? 1 : 0;
    $is_ofw = (strtoupper(trim((string) rowValue($row, ['OFW DEPENDENT',   'ofw_dependent'],   'NO')))  === 'YES') ? 1 : 0;
    $ps4_id = s(rowValue($row, ['4Ps HOUSEHOLD ID NO.', 'ps4_id_no'], '')) ?: null;

    // spes_status: 'SPES Baby' or 'New' from STATUS column, stored separately from classification
    $rawStatus   = strtolower(trim((string) rowValue($row, ['STATUS', 'Status', 'status'], 'new')));
    $spes_status = ($rawStatus === 'spes baby') ? 'SPES Baby' : 'New';

    $updFlags = $conn->prepare('
        UPDATE beneficiaries
        SET is_pwd = ?, is_4ps = ?, is_ofw_dependent = ?, ps4_id_no = ?, spes_status = ?
        WHERE benef_id = ?
    ');
    $updFlags->bind_param('iiissi', $is_pwd, $is_4ps, $is_ofw, $ps4_id, $spes_status, $benefId);
    $updFlags->execute();
    $updFlags->close();

    if ($spesId > 0) {
        $state['insertedSPESIds'][] = $spesId;
    }

    // Handle employment data
    $company = s(rowValue($row, ['Company', 'company'], ''));
    $category = s(rowValue($row, ['_spes_category'], ''));
    $storeAssignment = s(rowValue($row, ['Store Assignment', 'store_assignment'], ''));
    $startDate = null;
    $endDate = null;

    // Parse contract dates
    if (!empty($row['_parsed_start_of_contract'])) {
        $startDate = (string)$row['_parsed_start_of_contract'];
    } else {
        $startRaw = rowValue($row, ['Start of Contract', 'start_of_contract'], '');
        $startDate = parseDateNullable($startRaw);
    }

    if (!empty($row['_parsed_end_of_contract'])) {
        $endDate = (string)$row['_parsed_end_of_contract'];
    } else {
        $endRaw = rowValue($row, ['End of Contract', 'end_of_contract'], '');
        $endDate = parseDateNullable($endRaw);
    }

    $companyId = null;
    if (!empty($company)) {
        // Resolve employer
        $batchId = $ctx['batchId'] ?? null;
        $employerResult = resolveEmployer($conn, $company, $batchId, [
            'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
            'industry' => rowValue($row, ['Industry', 'industry'], ''),
            'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
        ]);
        $companyId = (int)($employerResult['id'] ?? 0) ?: null;

        if (!empty($employerResult['created']) && $companyId > 0) {
            $state['createdEmployerIds'][] = $companyId;
            $state['warnings'][] = 'New company created: ' . ($employerResult['matched_name'] ?? $company);
        }
    }

    // Calculate days between contract start and end if not provided
    $daysRaw = rowValue($row, ['Days', 'days'], '');
    $days = ($daysRaw !== '') ? (int)$daysRaw : null;
    
    if ($days === null && $startDate && $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $days = (int)$start->diff($end)->days;
    }

    // Validate category
    $categoryNorm = strtolower(trim($category));
    if (!in_array($categoryNorm, ['lgu', 'private'], true)) {
        $categoryNorm = 'private';
    }

    // Insert SPES employment record
    $empHasBatchId = tableHasColumn($conn, 'spes_employment', 'batch_id');
    if ($empHasBatchId) {
        $insEmp = $conn->prepare('
            INSERT INTO spes_employment
                (spes_id, company_id, store_assignment, start_of_contract, end_of_contract, days, category, batch_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $insEmp->bind_param('iisssisi', $spesId, $companyId, $storeAssignment, $startDate, $endDate, $days, $categoryNorm, $batchId);
    } else {
        $insEmp = $conn->prepare('
            INSERT INTO spes_employment
                (spes_id, company_id, store_assignment, start_of_contract, end_of_contract, days, category)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $insEmp->bind_param('iisssis', $spesId, $companyId, $storeAssignment, $startDate, $endDate, $days, $categoryNorm);
    }
    $insEmp->execute();
    $employmentId = (int)$insEmp->insert_id;

    if ($employmentId > 0) {
        $state['insertedSPESEmploymentIds'][] = $employmentId;
    }

    return 'saved';
}