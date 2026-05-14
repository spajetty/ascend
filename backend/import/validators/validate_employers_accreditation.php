<?php
// validate_employers_accreditation.php

function validateEmployersAccreditation(mysqli $conn, array $rows): array {
    $validatedData = [];
    $existingEmployers = loadNormalizedEmployers($conn);

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;

        $companyName = trim((string)($row['COMPANY'] ?? $row['Company'] ?? $row['CompanyName'] ?? ''));

        if ($companyName === '') {
            $previewRow['status_message'] = 'Missing Company Name';
            $previewRow['badge_status']   = 'invalid';
            $previewRow['_sys_skip']      = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $normalized = normalizeEmployerName($companyName);
        $existingId = $existingEmployers[$normalized] ?? null;

        if ($existingId !== null) {
            $previewRow['status_message']    = 'Already Exists — will update accreditation';
            $previewRow['badge_status']      = 'duplicate';
            $previewRow['_sys_employer_id']  = $existingId;
            $previewRow['_sys_is_existing']  = true;
            $previewRow['_sys_skip']         = false; // update is still useful
        } else {
            $previewRow['status_message']    = 'New Employer';
            $previewRow['badge_status']      = 'new';
            $previewRow['_sys_employer_id']  = null;
            $previewRow['_sys_is_existing']  = false;
            $previewRow['_sys_skip']         = false;
        }

        $validatedData[] = $previewRow;
    }
    
    return $validatedData;
}
