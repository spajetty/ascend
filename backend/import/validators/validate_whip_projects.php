<?php
// validate_whip_projects.php

function validateWhipProjects(mysqli $conn, array $rows): array {
    $validatedData = [];
    $seenExcelRows = [];
    $rows = collapseWhipProjectRows($rows);

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;

        $fields = whipProjectRowFields($row);

        $previewRow['fname'] = $fields['title'] !== '' ? $fields['title'] : '(missing project title)';
        $previewRow['lname'] = $fields['contractor'];
        $previewRow['sex'] = $fields['nature'];
        $previewRow['contact'] = $fields['budget'];

        if ($fields['title'] === '' || $fields['contractor'] === '') {
            $previewRow['status_message'] = 'Missing Project Title or Project Contractor';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $excelDupKey = buildWhipProjectDuplicateKey($row);
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

        if (whipProjectExists($conn, $row)) {
            $previewRow['status_message'] = 'Already Exists';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_is_existing'] = true;
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
