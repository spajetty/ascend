<?php
// validate_beneficiaries.php

function validateBeneficiaries(mysqli $conn, array $rows, string $program): array {
    $validatedData = [];
    $seenExcelRows = [];

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;

        if (isWhipBeneficiariesProgram($program)) {
            foreach ($previewRow as $hk => $hv) {
                if (strcasecmp((string)$hk, 'Classification') === 0) {
                    unset($previewRow[$hk]);
                }
            }
            
            $companyName = trim((string)getRowVal($row, ['Company']));
            $projectName = trim((string)getRowVal($row, ['Name of Project']));

            if ($companyName === '' || $projectName === '') {
                $previewRow['status_message'] = 'Missing Company or Name of Project';
                $previewRow['badge_status'] = 'invalid';
                $previewRow['_sys_skip'] = true;
                $validatedData[] = $previewRow;
                continue;
            }

            $projectResult = findWhipBeneficiaryProjectId($conn, $row);
            $projectId = $projectResult['id'] ?? null;
            if ($projectId === null || $projectId <= 0) {
                $previewRow['status_message'] = $projectResult['error'] ?? 'Project not found';
                $previewRow['badge_status'] = 'invalid';
                $previewRow['_sys_skip'] = true;
                $validatedData[] = $previewRow;
                continue;
            }
            $previewRow['_sys_project_id'] = $projectId;
        } else {
            $previewRow['Classification'] = trim($row['Classification'] ?? '');
        }

        $fname = trim($row['First Name'] ?? $row['FirstName'] ?? '');
        $lname = trim($row['Last Name'] ?? $row['LastName'] ?? '');
        $contact = trim($row['Contact'] ?? '');
        $email = trim($row['Email'] ?? '');
        $dob = parseExcelDate($row['DOB'] ?? $row['Birthday'] ?? '');
        $age = trim($row['Age'] ?? '');

        $previewRow['fname'] = $fname;
        $previewRow['lname'] = $lname;
        $previewRow['sex'] = trim($row['Sex'] ?? $row['S'] ?? '');
        $previewRow['contact'] = $contact;
        $previewRow['_parsed_dob'] = $dob;

        if (empty($fname) || empty($lname)) {
            $previewRow['status_message'] = 'Missing Name';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        if (!empty($age) && !is_numeric($age)) {
            $previewRow['status_message'] = 'Invalid Age format';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $excelDupKey = buildExcelDuplicateKey($fname, $lname, $dob);
        if ($excelDupKey !== null) {
            if (isset($seenExcelRows[$excelDupKey])) {
                $previewRow['status_message'] = 'Duplicate in uploaded file';
                $previewRow['badge_status'] = 'duplicate';
                $previewRow['_sys_skip'] = true;
                $validatedData[] = $previewRow;
                continue;
            }
            $seenExcelRows[$excelDupKey] = true;
        }

        $dup = checkDuplicate($conn, $fname, $lname, $dob, $contact, $email);

        if ($dup['found']) {
            $previewRow['status_message'] = 'Already Exists';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_is_existing'] = true;
            $previewRow['_sys_user_id'] = $dup['user_id'];
            $previewRow['_sys_benef_id'] = $dup['benef_id'];
            $previewRow['_sys_skip'] = true;
        } else {
            $previewRow['status_message'] = 'New Record';
            $previewRow['badge_status'] = 'new';
            $previewRow['_sys_skip'] = false;
        }

        $validatedData[] = $previewRow;
    }
    
    return $validatedData;
}
