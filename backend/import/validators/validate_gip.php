<?php

function validateGip(mysqli $conn, array $rows, string $gipCategory = '', ?string $importMonth = null, ?string $importYear = null): array {
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

        // Base required fields for both
        $requiredFields = [
            'Last Name' => ['Last Name', 'LastName', 'lname'],
            'First Name' => ['First Name', 'FirstName', 'fname'],
            'Sex' => ['Sex', 'sex', 'Gender', 'gender'],
        ];

        if ($gipType === 'LGU') {
            $requiredFields['Start of Contract'] = ['Start of Contract', 'start_of_contract'];
            $requiredFields['End of Contract'] = ['End of Contract', 'end_of_contract'];
            $requiredFields['Proponent'] = ['Proponent', 'proponent'];
        } else if ($gipType === 'DOLE') {
            // Note: GSIS Beneficiary, Relationship, Contact No, and Education are required columns in the sheet (checked by frontend), 
            // but the actual cell values can be blank.
        }

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

        // parse fields for UI preview / saving
        $previewRow['student_type'] = strtolower(s(rowValue($row, ['Student Type', 'Student/OSY', 'student_type'], '')));
        $previewRow['highest_educ'] = s(rowValue($row, ['Highest Education Attained', 'Highest Educ. Attainment', 'Highest Education', 'highest_educ'], ''));
        
        $previewRow['school'] = s(rowValue($row, ['School Name', 'School', 'school'], ''));
        $previewRow['course'] = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
        $previewRow['office_assignment'] = s(rowValue($row, ['Office Assignment', 'office_assignment'], ''));
        $previewRow['status'] = strtolower(s(rowValue($row, ['Status', 'status'], '')));
        $previewRow['proponent'] = s(rowValue($row, ['Proponent', 'proponent'], ''));
        
        $parsedStart = parseDateNullable(rowValue($row, ['Start of Contract', 'start_of_contract'], ''));
        $parsedEnd = parseDateNullable(rowValue($row, ['End of Contract', 'end_of_contract'], ''));
        $previewRow['start_of_contract'] = $parsedStart;
        $previewRow['end_of_contract'] = $parsedEnd;
        $previewRow['Start of Contract'] = $parsedStart;
        $previewRow['End of Contract'] = $parsedEnd;
        $previewRow['days'] = parseIntNullable(rowValue($row, ['No. of Days', 'Days', 'days'], ''));

        $parsedBirthdate = parseDateNullable(rowValue($row, ['Birthdate', 'Birthday', 'DOB'], ''));
        if ($parsedBirthdate !== null) {
            $previewRow['_parsed_dob'] = $parsedBirthdate;
            $previewRow['Birthdate'] = $parsedBirthdate;
        }

        $previewRow['gsis_beneficiary'] = s(rowValue($row, ['GSIS Beneficiary', 'gsis_beneficiary'], ''));
        $previewRow['relationship'] = s(rowValue($row, ['Relationship', 'relationship'], ''));
        $previewRow['gsis_benef_contact_no'] = s(rowValue($row, ['GSIS Benef. Contact No.', 'GSIS Benef. Contact No', 'gsis_benef_contact_no'], ''));

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
            $isProgramDup = checkProgramDuplicate($conn, 'Government Internship Program', $dup['benef_id'], $importMonth, $importYear);
            $previewRow['_sys_is_existing'] = true;
            $previewRow['_sys_user_id'] = $dup['user_id'];
            $previewRow['_sys_benef_id'] = $dup['benef_id'];
            
            if ($isProgramDup) {
                $previewRow['status_message'] = 'Already Exists in Program';
                $previewRow['badge_status'] = 'duplicate';
                $previewRow['_sys_skip'] = true;
            } else {
                $previewRow['status_message'] = 'Existing Person, New Program';
                $previewRow['badge_status'] = 'new';
                $previewRow['_sys_skip'] = false;
            }
        } else {
            $previewRow['status_message'] = 'New Record';
            $previewRow['badge_status'] = 'new';
            $previewRow['_sys_skip'] = false;
        }

        $validatedData[] = $previewRow;
    }

    return $validatedData;
}
