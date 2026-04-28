<?php
// validate_spes.php - SPES (Special Program for Employment of Students) Validator

function validateSPES(mysqli $conn, array $rows, string $program): array {
    $validatedData = [];
    $seenExcelRows = [];

    foreach ($rows as $row) {
        $previewRow = $row;
        $previewRow['_sys_is_existing'] = false;
        $previewRow['_sys_user_id'] = null;
        $previewRow['_sys_benef_id'] = null;
        $previewRow['_sys_skip'] = false;
        $previewRow['Classification'] = s(rowValue($row, ['Classification', 'classification'], ''));

        $fname = s(rowValue($row, ['First Name', 'FirstName', 'fname'], ''));
        $lname = s(rowValue($row, ['Last Name', 'LastName', 'lname'], ''));
        $contact = s(rowValue($row, ['Contact', 'contact'], ''));
        $email = s(rowValue($row, ['Email', 'email'], ''));
        $dob = parseExcelDate(rowValue($row, ['DOB', 'Birthday', 'dob'], ''));
        $age = s(rowValue($row, ['Age', 'age'], ''));

        $previewRow['fname'] = $fname;
        $previewRow['lname'] = $lname;
        $previewRow['sex'] = s(rowValue($row, ['Sex', 'sex', 'Gender', 'gender', 'S'], ''));
        $previewRow['contact'] = $contact;
        $previewRow['_parsed_dob'] = $dob;

        // Required fields
        if (empty($fname) || empty($lname)) {
            $previewRow['status_message'] = 'Missing Name';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // Validate age format if present
        if (!empty($age) && !is_numeric($age)) {
            $previewRow['status_message'] = 'Invalid Age format';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // SPES-specific fields
        $school = s(rowValue($row, ['School', 'school'], ''));
        $studentType = s(rowValue($row, ['Student/OSY', 'student_type'], ''));
        $company = s(rowValue($row, ['Company', 'company'], ''));

        if (empty($school)) {
            $previewRow['status_message'] = 'Missing School';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        if (empty($studentType)) {
            $previewRow['status_message'] = 'Missing Student/OSY classification';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // Validate student type
        $studentTypeLower = strtolower($studentType);
        if (!in_array($studentTypeLower, ['student', 'osy'], true)) {
            $previewRow['status_message'] = 'Student/OSY must be "Student" or "OSY"';
            $previewRow['badge_status'] = 'invalid';
            $previewRow['_sys_skip'] = true;
            $validatedData[] = $previewRow;
            continue;
        }

        // Employment fields - required for SPES employment tracking
        if (!empty($company)) {
            $storeAssignment = s(rowValue($row, ['Store Assignment', 'store_assignment'], ''));
            $startOfContract = parseExcelDate(rowValue($row, ['Start of Contract', 'start_of_contract'], ''));
            $endOfContract = parseExcelDate(rowValue($row, ['End of Contract', 'end_of_contract'], ''));

            if (empty($storeAssignment)) {
                $previewRow['status_message'] = 'Company provided but missing Store Assignment';
                $previewRow['badge_status'] = 'invalid';
                $previewRow['_sys_skip'] = true;
                $validatedData[] = $previewRow;
                continue;
            }

            $previewRow['_parsed_start_of_contract'] = $startOfContract;
            $previewRow['_parsed_end_of_contract'] = $endOfContract;
        }

        // Check for duplicates within Excel
        $excelDupKey = buildExcelDuplicateKey($fname, $lname, $dob);
        if ($excelDupKey !== null) {
            if (isset($seenExcelRows[$excelDupKey])) {
                $previewRow['status_message'] = 'Duplicate in uploaded file';
                $previewRow['badge_status'] = 'duplicate';
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
