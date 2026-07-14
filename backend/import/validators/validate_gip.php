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
            // GIP-required program fields
            'Student Type' => ['Student Type', 'student_type'],
            'School' => ['School Name', 'School', 'school'],
            'Course' => ['Course/Degree/Strand', 'Course', 'course'],
            'Highest Education Attained' => ['Highest Education Attained', 'Highest Education', 'highest_educ'],
            'Office Assignment' => ['Office Assignment', 'office_assignment'],
            'Start of Contract' => ['Start of Contract', 'start_of_contract'],
            'End of Contract' => ['End of Contract', 'end_of_contract'],
            'No. of Days' => ['No. of Days', 'Days', 'days'],
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
        $previewRow['student_type'] = strtolower(s(rowValue($row, ['Student Type', 'student_type'], '')));
        $previewRow['school'] = s(rowValue($row, ['School Name', 'School', 'school'], ''));
        $previewRow['course'] = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
        $previewRow['highest_educ'] = s(rowValue($row, ['Highest Education Attained', 'Highest Education', 'highest_educ'], ''));
        $previewRow['office_assignment'] = s(rowValue($row, ['Office Assignment', 'office_assignment'], ''));
        $parsedStart = parseDateNullable(rowValue($row, ['Start of Contract', 'start_of_contract'], ''));
        $parsedEnd = parseDateNullable(rowValue($row, ['End of Contract', 'end_of_contract'], ''));
        
        $previewRow['start_of_contract'] = $parsedStart;
        $previewRow['end_of_contract'] = $parsedEnd;
        $previewRow['Start of Contract'] = $parsedStart;
        $previewRow['End of Contract'] = $parsedEnd;
        $previewRow['days'] = parseIntNullable(rowValue($row, ['No. of Days', 'Days', 'days'], ''));

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
