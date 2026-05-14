<?php
// validate_schools.php

function validateSchools(mysqli $conn, array $rows): array {
    $validatedData = [];
    $seenExcelRows = [];

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;

        $schoolName = s(rowValue($row, ['School Name', 'SchoolName', 'school_name'], ''));
        $districtRaw = s(rowValue($row, ['Congressional District', 'CongressionalDistrict', 'district'], ''));
        $gradesOffered = s(rowValue($row, ['Grades Offered', 'GradesOffered', 'grades_offered'], ''));

        $previewRow['school_name'] = $schoolName;
        $previewRow['congressional_district'] = $districtRaw;
        $previewRow['grades_offered'] = $gradesOffered;

        if ($schoolName === '') {
            $previewRow['status_message'] = 'Missing School Name';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $district = parseIntNullable($districtRaw);
        if ($district === null || $district <= 0) {
            $previewRow['status_message'] = 'Invalid Congressional District';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        if ($gradesOffered === '') {
            $previewRow['status_message'] = 'Missing Grades Offered';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $dupKey = normalizeKeyText($schoolName) . '|' . $district . '|' . normalizeKeyText($gradesOffered);
        if (isset($seenExcelRows[$dupKey])) {
            $previewRow['status_message'] = 'Duplicate in uploaded file';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }
        $seenExcelRows[$dupKey] = true;

        $stmt = $conn->prepare('
            SELECT school_id
            FROM schools
            WHERE LOWER(school_name) = LOWER(?)
              AND congressional_district = ?
              AND LOWER(COALESCE(grades_offered, "")) = LOWER(?)
            LIMIT 1
        ');
        $stmt->bind_param('sis', $schoolName, $district, $gradesOffered);
        $stmt->execute();
        $existing = $stmt->get_result()->fetch_assoc();

        if ($existing) {
            $previewRow['status_message'] = 'Already Exists';
            $previewRow['badge_status'] = 'duplicate';
            $previewRow['_sys_school_id'] = (int)$existing['school_id'];
            $previewRow['_sys_skip'] = true;
        } else {
            $previewRow['status_message'] = 'New Record';
            $previewRow['badge_status'] = 'new';
            $previewRow['_sys_school_id'] = null;
            $previewRow['_sys_skip'] = false;
        }

        $validatedData[] = $previewRow;
    }

    return $validatedData;
}
