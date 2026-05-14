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

        // Required GIP fields (not all headers are required to have values)
        $requiredFields = [
            // Basic info
            'Last Name' => ['Last Name', 'LastName', 'lname'],
            'First Name' => ['First Name', 'FirstName', 'fname'],
            'Sex' => ['Sex', 'sex', 'Gender', 'gender'],
            'Contact Number' => ['Contact Number', 'Contact', 'contact'],

            // GIP-required program fields
            'School Name' => ['School Name', 'School', 'school'],
            'College/SHS' => ['College/SHS', 'College or SHS', 'college_or_shs'],
            'Office Assignment' => ['Office Assignment', 'office_assignment'],
            'Course/Degree/Strand' => ['Course/Degree/Strand', 'Course', 'course'],
            'Required Hours' => ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours'],
            'Preferred Host Organization Type' => ['Preferred Host Organization Type', 'preferred_org_type'],
            'Preferred Industry / Field of Internship' => ['Preferred Industry / Field of Internship', 'preferred_industry'],
            'Are you willing to be assigned outside your preferred field if not available?' => ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'],
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

        // parse optional fields
        $previewRow['school'] = s(rowValue($row, ['School Name', 'School', 'school'], ''));
        $previewRow['course'] = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
        $previewRow['required_hours'] = parseIntNullable(rowValue($row, ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours'], ''));
        $previewRow['preferred_org_type'] = s(rowValue($row, ['Preferred Host Organization Type', 'preferred_org_type'], ''));
        $previewRow['preferred_industry'] = s(rowValue($row, ['Preferred Industry / Field of Internship', 'preferred_industry'], ''));
        $previewRow['is_willing_outside'] = toBoolInt(rowValue($row, ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'], ''));
        $previewRow['office_assignment'] = s(rowValue($row, ['Office Assignment', 'office_assignment'], ''));
        $previewRow['college_or_shs'] = s(rowValue($row, ['College/SHS', 'College or SHS', 'college_or_shs', 'college_or_shs'], ''));

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
