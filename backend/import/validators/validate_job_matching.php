<?php

function validateJobMatchingFamily(mysqli $conn, array $rows, string $program, string $jobFairEvent = ''): array {
    $validatedData = validateBeneficiaries($conn, $rows, $program);

    if ($program === 'Job Fair' && $jobFairEvent !== '') {
        $jobFairEventId = (int)$jobFairEvent;

        // Fetch valid companies for this job fair event with their names
        $stmt = $conn->prepare("
            SELECT jp.company_id, e.company_name 
            FROM jobfair_participants jp
            LEFT JOIN employers e ON e.company_id = jp.company_id
            WHERE jp.jobfairevent_id = ?
        ");
        $stmt->bind_param("i", $jobFairEventId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $validCompanyIds = [];
        $validCompanyNames = []; // Store company names for similarity matching
        while ($row = $result->fetch_assoc()) {
            $companyId = (int)$row['company_id'];
            $validCompanyIds[] = $companyId;
            $companyName = trim((string)($row['company_name'] ?? ''));
            if ($companyName !== '') {
                $validCompanyNames[$companyId] = $companyName;
            }
        }
        $stmt->close();
        
        // Load the employers cache to match names to IDs
        $existingEmployers = loadNormalizedEmployers($conn);

        foreach ($validatedData as &$row) {
            unset(
                $row['suggested_company_name'],
                $row['suggested_company_id'],
                $row['suggested_company_similarity']
            );

            if (!empty($row['_sys_skip'])) {
                continue;
            }

            $companyName = trim((string)rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            if ($companyName === '') {
                $row['status_message'] = 'Missing Company';
                $row['badge_status'] = 'invalid';
                $row['_sys_skip'] = true;
                continue;
            }

            $normalized = normalizeEmployerName($companyName);
            if (!isset($existingEmployers[$normalized])) {
                // Company not found; try to find closest match among valid participants
                $suggestion = findClosestCompanyMatch($companyName, $validCompanyNames);
                if ($suggestion) {
                    $row['status_message'] = "Company '{$companyName}' not found. Did you mean '{$suggestion['company_name']}'?";
                    $row['suggested_company_name'] = $suggestion['company_name'];
                    $row['suggested_company_id'] = $suggestion['company_id'];
                } else {
                    $row['status_message'] = "Company '{$companyName}' is not a participant of this Job Fair Event.";
                }
                $row['badge_status'] = 'invalid';
                $row['_sys_skip'] = true;
                continue;
            }

            $companyId = (int)$existingEmployers[$normalized];
            if (!in_array($companyId, $validCompanyIds, true)) {
                // Company exists but is not a participant of this event; try to find closest participant
                $suggestion = findClosestCompanyMatch($companyName, $validCompanyNames);
                if ($suggestion) {
                    $row['status_message'] = "'{$companyName}' is not a participant. Did you mean '{$suggestion['company_name']}'?";
                    $row['suggested_company_name'] = $suggestion['company_name'];
                    $row['suggested_company_id'] = $suggestion['company_id'];
                } else {
                    $row['status_message'] = "Company '{$companyName}' is not a participant of this Job Fair Event.";
                }
                $row['badge_status'] = 'invalid';
                $row['_sys_skip'] = true;
            } else {
                $row['badge_status'] = $row['badge_status'] ?? 'new';
                $row['_sys_skip'] = false;
            }
        }
        unset($row);

        // For Job Fair, override beneficiary-level duplicate check with Job Fair-specific logic:
        // Only mark as duplicate if same benef + company + event + position exists
        foreach ($validatedData as &$row) {
            // Skip if already marked invalid or if beneficiary not resolved
            if (!empty($row['_sys_skip']) || !($row['_sys_benef_id'] ?? null)) {
                continue;
            }

            // Only check Job Fair duplicates if beneficiary is marked as existing from beneficiary validation
            if (!($row['_sys_is_existing'] ?? false)) {
                continue;
            }

            $benefId = (int)$row['_sys_benef_id'];
            $position = trim((string)rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';
            $companyName = trim((string)rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            $normalized = normalizeEmployerName($companyName);
            $companyId = (int)($existingEmployers[$normalized] ?? 0);

            if ($companyId > 0) {
                // Check if this exact combo (benef + company + event + position) already exists
                $checkStmt = $conn->prepare('
                    SELECT 1 FROM jobfair 
                    WHERE benef_id = ? AND company_id = ? AND jobfairevent_id = ? AND position = ?
                    LIMIT 1
                ');
                $checkStmt->bind_param('iiss', $benefId, $companyId, $jobFairEventId, $position);
                $checkStmt->execute();
                $existsResult = $checkStmt->get_result()->fetch_assoc();

                if ($existsResult) {
                    // Exact duplicate found: same person, company, event, and position
                    $row['status_message'] = 'Duplicate Job Fair record (same person, company, event, and position)';
                    $row['badge_status'] = 'duplicate';
                    $row['_sys_skip'] = true;
                } else {
                    // Different company, event, or position: allow it even if person exists
                    $row['status_message'] = 'New Record';
                    $row['badge_status'] = 'new';
                    $row['_sys_skip'] = false;
                    $row['_sys_is_existing'] = false;
                }
            }
        }
        unset($row);

        // Additionally, check for duplicates within the uploaded file for Job Fair
        // (same person + same company + same event + same position). This handles
        // new beneficiaries that were not present in the DB but were duplicated
        // across rows in the same upload.
        $inUploadKeys = [];
        foreach ($validatedData as &$row) {
            if (!empty($row['_sys_skip'])) {
                continue;
            }
            $benefId = (int)($row['_sys_benef_id'] ?? 0);
            if ($benefId <= 0) {
                // Use name|dob fallback for identity for new beneficiaries
                $identityKey = strtolower(trim((string)($row['fname'] ?? ''))) . '|' . strtolower(trim((string)($row['lname'] ?? ''))) . '|' . (string)($row['_parsed_dob'] ?? '');
            } else {
                $identityKey = 'id:' . $benefId;
            }

            $position = trim((string)rowValue($row, ['Position', 'Desired Position', 'desired_position'], '')) ?: 'N/A';
            $companyName = trim((string)rowValue($row, ['Company', 'CompanyName', 'Employer'], ''));
            $normalized = normalizeEmployerName($companyName);
            $companyId = (int)($existingEmployers[$normalized] ?? 0);
            $key = $identityKey . '|' . $companyId . '|' . $position . '|' . $jobFairEventId;

            if (isset($inUploadKeys[$key])) {
                $row['status_message'] = 'Duplicate Job Fair record (same person, company, event, and position)';
                $row['badge_status'] = 'duplicate';
                $row['_sys_skip'] = true;
            } else {
                $inUploadKeys[$key] = true;
            }
        }
        unset($row);
    }

    return $validatedData;
}

function findClosestCompanyMatch(string $inputName, array $validCompanyNames): ?array {
    if (empty($validCompanyNames)) {
        return null;
    }

    $inputNorm = normalizeEmployerName($inputName);
    $bestMatch = null;
    $bestScore = 0.0;
    $threshold = 0.6; // Require at least 60% similarity

    foreach ($validCompanyNames as $companyId => $validName) {
        $validNorm = normalizeEmployerName($validName);
        
        // Use similar_text for similarity scoring
        similar_text($inputNorm, $validNorm, $percent);
        $percent = $percent / 100;

        // Also check if one is a substring of the other
        if (strpos($validNorm, $inputNorm) !== false || strpos($inputNorm, $validNorm) !== false) {
            $percent = max($percent, 0.8);
        }

        if ($percent > $bestScore && $percent >= $threshold) {
            $bestScore = $percent;
            $bestMatch = [
                'company_id' => (int)$companyId,
                'company_name' => $validName,
                'score' => $percent,
            ];
        }
    }

    return $bestMatch;
}
