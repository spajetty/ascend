<?php

function saveSPESRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    $school = s(rowValue($row, ['School', 'school'], ''));
    $studentType = s(rowValue($row, ['Student/OSY', 'student_type'], ''));
    $highestEduc = s(rowValue($row, ['HIGHEST EDUC. ATTAINMENT', 'highest_educ', 'Highest Educ Attainment'], '')) ?: null;
    $course = s(rowValue($row, ['Course', 'course'], '')) ?: null;

    // Normalize student type to enum values
    $studentTypeLower = strtolower($studentType);
    $studentTypeNorm = in_array($studentTypeLower, ['student', 'osy'], true) ? $studentTypeLower : 'student';

    // Insert SPES record
    $insSPES = $conn->prepare('
        INSERT INTO spes
            (benef_id, student_type, highest_educ, course, school)
        VALUES (?, ?, ?, ?, ?)
    ');
    $insSPES->bind_param('issss', $benefId, $studentTypeNorm, $highestEduc, $course, $school);
    $insSPES->execute();
    $spesId = (int)$insSPES->insert_id;

    if ($spesId > 0) {
        $state['insertedSPESIds'][] = $spesId;
    }

    // Handle employment data if company is provided
    $company = s(rowValue($row, ['Company', 'company'], ''));
    if (!empty($company)) {
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

        // Resolve employer
        $batchId = $ctx['batchId'] ?? null;
        $employerResult = resolveEmployer($conn, $company, $batchId, [
            'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
            'industry' => rowValue($row, ['Industry', 'industry'], ''),
            'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
        ]);
        $companyId = (int)($employerResult['id'] ?? 0);

        if (!empty($employerResult['created']) && $companyId > 0) {
            $state['createdEmployerIds'][] = $companyId;
            $state['warnings'][] = 'New company created: ' . ($employerResult['matched_name'] ?? $company);
        }

        // Calculate days between contract start and end
        $days = null;
        if ($startDate && $endDate) {
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
        $insEmp = $conn->prepare('
            INSERT INTO spes_employment
                (spes_id, company_id, store_assignment, start_of_contract, end_of_contract, days, category)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $insEmp->bind_param('iisssis', $spesId, $companyId, $storeAssignment, $startDate, $endDate, $days, $categoryNorm);
        $insEmp->execute();
        $employmentId = (int)$insEmp->insert_id;

        if ($employmentId > 0) {
            $state['insertedSPESEmploymentIds'][] = $employmentId;
        }
    }

    return 'saved';
}
