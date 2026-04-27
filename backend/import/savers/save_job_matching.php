<?php

function saveJobMatchingFamilyRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    $program = (string)($ctx['program'] ?? '');
    $batchId = $ctx['batchId'] ?? null;

    $companyName = s(rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
    $employerResult = resolveEmployer($conn, $companyName, $batchId, [
        'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
        'industry' => rowValue($row, ['Industry', 'industry'], ''),
        'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
    ]);
    $companyId = (int)($employerResult['id'] ?? 0);

    if (!empty($employerResult['created']) && $companyId > 0) {
        $state['createdEmployerIds'][] = $companyId;
        $state['warnings'][] = 'New company created: ' . ($employerResult['matched_name'] ?? $companyName);
    }

    if (!$companyId) {
        return 'skipped';
    }

    if ($program === 'Job Matching and Referral') {
        $position = s(rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';

        $hasBatchField = false;
        if (tableExists($conn, 'jobMatch')) {
            $fieldCheck = $conn->prepare('
                SELECT 1
                FROM information_schema.columns
                WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?
                LIMIT 1
            ');
            $tbl = 'jobMatch';
            $col = 'batch_id';
            $fieldCheck->bind_param('ss', $tbl, $col);
            $fieldCheck->execute();
            $hasBatchField = (bool)$fieldCheck->get_result()->fetch_assoc();
        }

        if ($hasBatchField) {
            $ins = $conn->prepare('INSERT INTO jobMatch (benef_id, company_id, batch_id, position) VALUES (?, ?, ?, ?)');
            $ins->bind_param('iiis', $benefId, $companyId, $batchId, $position);
        } else {
            $ins = $conn->prepare('INSERT INTO jobMatch (benef_id, company_id, position) VALUES (?, ?, ?)');
            $ins->bind_param('iis', $benefId, $companyId, $position);
        }
        $ins->execute();
        $state['insertedJobMatchIds'][] = (int)$ins->insert_id;
        return 'saved';
    }

    if ($program === 'Job Fair' && tableExists($conn, 'jobFair')) {
        $position = s(rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';
        $ins = $conn->prepare('INSERT INTO jobFair (benef_id, company_id, position) VALUES (?, ?, ?)');
        $ins->bind_param('iis', $benefId, $companyId, $position);
        $ins->execute();
        $state['insertedJobFairIds'][] = (int)$ins->insert_id;
        return 'saved';
    }

    if ($program === 'First Time Jobseeker' && tableExists($conn, 'firstJobSeek')) {
        $occPermit = toBoolInt(rowValue($row, ['Occupational Permit', 'occ_permit', 'Occ Permit'], 0));
        $healthCard = toBoolInt(rowValue($row, ['Health Card', 'health_card'], 0));

        if (tableHasColumn($conn, 'firstJobSeek', 'company_id')) {
            $ins = $conn->prepare('INSERT INTO firstJobSeek (benef_id, company_id, occ_permit, health_card) VALUES (?, ?, ?, ?)');
            $ins->bind_param('iiii', $benefId, $companyId, $occPermit, $healthCard);
        } else {
            $ins = $conn->prepare('INSERT INTO firstJobSeek (benef_id, occ_permit, health_card) VALUES (?, ?, ?)');
            $ins->bind_param('iii', $benefId, $occPermit, $healthCard);
        }
        $ins->execute();
        $state['insertedFirstJobSeekIds'][] = (int)$ins->insert_id;
        return 'saved';
    }

    return 'saved';
}
