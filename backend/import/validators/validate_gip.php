<?php

function validateGip(mysqli $conn, array $rows, string $gipCategory = ''): array {
    $validatedData = [];
    $seenExcelRows = [];
    $gipType = strtoupper(trim((string)$gipCategory));

    // Process rows as normal
    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;
        $previewRow['type'] = $gipType;

        $fname = s(rowValue($row, ['First Name', 'FirstName', 'fname'], ''));
        $lname = s(rowValue($row, ['Last Name', 'LastName', 'lname'], ''));
        $contact = s(rowValue($row, ['Contact Number', 'Contact', 'contact'], ''));
        $email = s(rowValue($row, ['Email address', 'Email', 'email'], ''));
        $age = s(rowValue($row, ['Age', 'age'], ''));

        $previewRow['fname'] = $fname;
        $previewRow['lname'] = $lname;
        $previewRow['sex'] = s(rowValue($row, ['Sex', 'sex', 'Gender', 'gender', 'S'], ''));
        $previewRow['contact'] = $contact;

        if ($fname === '' || $lname === '') {
            $previewRow['status_message'] = 'Missing Name';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        if ($age !== '' && !is_numeric($age)) {
            $previewRow['status_message'] = 'Invalid Age format';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $requiredFields = [
            'School Name' => ['School Name', 'School', 'school'],
            'Year Level' => ['Year Level', 'year_level'],
            'Course/Degree/Strand' => ['Course/Degree/Strand', 'Course', 'course'],
            'Required Work Immersion / Internship Hours' => ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours'],
            'Preferred Host Organization Type' => ['Preferred Host Organization Type', 'preferred_org_type'],
            'Preferred Industry / Field of Internship' => ['Preferred Industry / Field of Internship', 'preferred_industry'],
            'Are you willing to be assigned outside your preferred field if not available?' => ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'],
            'Internship Schedule / Availability' => ['Internship Schedule / Availability', 'internship_sched'],
            'Internship Availability Date (Start of Internship)' => ['Internship Availability Date (Start of Internship)', 'Starting Date', 'start'],
        ];

        $missing = [];
        foreach ($requiredFields as $label => $keys) {
            if (s(rowValue($row, $keys, '')) === '') {
                $missing[] = $label;
            }
        }

        if ($missing !== []) {
            $previewRow['status_message'] = 'Missing required GIP field(s): ' . implode(', ', $missing);
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        $startDate = parseExcelDate(rowValue($row, ['Internship Availability Date (Start of Internship)', 'Starting Date', 'start'], ''));
        $previewRow['_parsed_start_date'] = $startDate;
        if ($startDate !== null && $startDate !== '') {
            $previewRow['Internship Availability Date (Start of Internship)'] = date('d/m/Y', strtotime($startDate));
            $previewRow['Starting Date'] = date('d/m/Y', strtotime($startDate));
        }

        $excelDupKey = buildExcelDuplicateKey($fname, $lname, null);
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

        $dup = checkDuplicate($conn, $fname, $lname, null, $contact, $email);

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
