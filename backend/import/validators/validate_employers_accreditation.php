<?php
// validate_employers_accreditation.php

function validateEmployersAccreditation(mysqli $conn, array $rows, string $importYear = ''): array {
    $validatedData = [];

    // ── 1. Load existing employers (name → company_id) ────────────────────────
    $existingEmployers = loadNormalizedEmployers($conn);

    // ── 2. Load existing accreditation records (company_id|month|year → true) ─
    $existingAccreditations = [];
    $res = $conn->query('SELECT company_id, month, year FROM employers_accreditations');
    if ($res) {
        while ($accRow = $res->fetch_assoc()) {
            $key = (int)$accRow['company_id'] . '|' . (int)$accRow['month'] . '|' . (int)$accRow['year'];
            $existingAccreditations[$key] = true;
        }
    }

    // ── 3. Determine the import year ──────────────────────────────────────────
    // Use the year selected by the user on the import form; fall back to current year.
    $yearInt = ($importYear !== '' && is_numeric($importYear)) ? (int)$importYear : (int)date('Y');

    // ── 4. Within-file duplicate tracker (normalised company|month) ───────────
    $seenInFile = [];

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id']     = null;
        $previewRow['_sys_benef_id']    = null;
        $previewRow['_sys_skip']        = false;

        // ── Company name ──────────────────────────────────────────────────────
        $companyName = trim((string)($row['COMPANY'] ?? $row['Company'] ?? $row['CompanyName'] ?? ''));
        if ($companyName === '') {
            $previewRow['status_message'] = 'Missing Company Name';
            $previewRow['badge_status']   = 'invalid';
            $previewRow['_sys_skip']      = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // ── Month ─────────────────────────────────────────────────────────────
        $monthRaw = trim((string)($row['MONTH'] ?? $row['Month'] ?? $row['month'] ?? ''));
        $monthInt = monthToInt($monthRaw);
        if ($monthInt === null) {
            $previewRow['status_message'] = 'Missing or invalid Month';
            $previewRow['badge_status']   = 'invalid';
            $previewRow['_sys_skip']      = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $normalized  = normalizeEmployerName($companyName);
        $companyId   = $existingEmployers[$normalized] ?? null;

        // ── Within-file duplicate check (same normalised company + month) ─────
        $fileKey = $normalized . '|' . $monthInt;
        if (isset($seenInFile[$fileKey])) {
            $previewRow['status_message']   = 'Duplicate in file — same company & month already appears above';
            $previewRow['badge_status']     = 'duplicate';
            $previewRow['_sys_employer_id'] = $companyId;
            $previewRow['_sys_is_existing'] = false;
            $previewRow['_sys_skip']        = true;
            $validatedData[] = $previewRow;
            continue;
        }
        $seenInFile[$fileKey] = true;

        // ── DB duplicate check (company_id + month + year in accreditations) ──
        if ($companyId !== null) {
            $dbKey = $companyId . '|' . $monthInt . '|' . $yearInt;
            if (isset($existingAccreditations[$dbKey])) {
                $previewRow['status_message']   = 'Already accredited for this month — will be skipped';
                $previewRow['badge_status']     = 'duplicate';
                $previewRow['_sys_employer_id'] = $companyId;
                $previewRow['_sys_is_existing'] = true;
                $previewRow['_sys_skip']        = true;   // same month/year → skip (not an update)
                $validatedData[] = $previewRow;
                continue;
            }
        }

        // ── New record ────────────────────────────────────────────────────────
        $previewRow['status_message']   = $companyId !== null
            ? 'Known company — new accreditation month'
            : 'New Company';
        $previewRow['badge_status']     = 'new';
        $previewRow['_sys_employer_id'] = $companyId;   // null if brand new company
        $previewRow['_sys_is_existing'] = $companyId !== null;
        $previewRow['_sys_skip']        = false;

        $validatedData[] = $previewRow;
    }

    return $validatedData;
}
