<?php

function ensurePersonBeneficiaryAndDocs(mysqli $conn, array $row, array $ctx, array &$state): ?int {
    $existingBenefId = $row['_sys_benef_id'] ?? null;
    $benefId = $existingBenefId ? (int)$existingBenefId : null;
    $program = (string)($ctx['program'] ?? '');

    $buildJobFairIdentityKey = static function (array $inputRow): string {
        $email = strtolower(trim((string)rowValue($inputRow, ['Email', 'email', 'Email address', 'Email Address'], '')));
        if ($email !== '') {
            return 'email:' . $email;
        }

        $contact = preg_replace('/\D+/', '', trim((string)rowValue($inputRow, ['Contact', 'contact', 'Contact Number'], '')));
        if ($contact !== '') {
            return 'contact:' . $contact;
        }

        $fname = strtolower(trim((string)rowValue($inputRow, ['First Name', 'FirstName', 'first_name', 'Firstname'], '')));
        $lname = strtolower(trim((string)rowValue($inputRow, ['Last Name', 'LastName', 'last_name', 'Lastname', 'Surname', 'surname'], '')));
        $dob = '';
        if (!empty($inputRow['_parsed_dob'])) {
            $dob = (string)$inputRow['_parsed_dob'];
        } else {
            $dob = trim((string)rowValue($inputRow, ['DOB', 'Birthday'], ''));
        }

        return 'name:' . $fname . '|' . $lname . '|' . strtolower($dob);
    };

    if ($program === 'Job Fair') {
        $jobFairKey = $buildJobFairIdentityKey($row);
        if ($jobFairKey !== '' && isset($state['jobFairBeneficiaryMap'][$jobFairKey])) {
            return (int)$state['jobFairBeneficiaryMap'][$jobFairKey];
        }
    }

    if (!$benefId) {
        $firstName  = s(rowValue($row, ['First Name', 'FirstName', 'first_name', 'Firstname'], '')) ?: null;
        $middleName = s(rowValue($row, ['Middle Name', 'MiddleName', 'middle_name', 'Middlename'], '')) ?: null;
        $lastName   = s(rowValue($row, ['Last Name', 'LastName', 'last_name', 'Lastname', 'Surname', 'surname'], '')) ?: null;
        $suffix     = s(rowValue($row, ['Suffix', 'suffix', 'Extension Name', 'extension_name'], '')) ?: null;
        $sex = s(rowValue($row, ['Sex', 'sex'], ''));
        $civil = s(rowValue($row, ['Civil Status', 'CivilStatus', 'civil_status'], ''));
        // If _parsed_dob exists (from validation), use it; otherwise parse raw DOB
        $dob = null;
        if (!empty($row['_parsed_dob'])) {
            $dob = (string)$row['_parsed_dob'];
        } else {
            $dobRaw = rowValue($row, ['Birthdate', 'DOB', 'Birthday'], '');
            $dob = parseDateNullable($dobRaw);
        }
        $rawContact = s(rowValue($row, ['Contact', 'contact', 'Contact Number'], ''));
        $contact = substr(trim(explode('/', $rawContact)[0]), 0, 15);
        $email = s(rowValue($row, ['Email', 'email', 'Email address', 'Email Address'], '')) ?: null;
        $classification = isWhipBeneficiariesProgram((string)($ctx['program'] ?? ''))
            ? 'Placed'
            : (s(rowValue($row, ['Classification', 'classification'], '')) ?: null);
        if ($classification !== null && $classification !== '') {
            $classification = titleCase($classification);
        }
        $spesStatus = s(rowValue($row, ['Status', 'status', 'spes_status'], '')) ?: null;
        if ($spesStatus !== null && $spesStatus !== '') {
            $spesStatus = titleCase($spesStatus);
        }
        $houseNo = s(rowValue($row, ['House No.', 'house_no', 'House/Block No./Street'], '')) ?: null;
        $barangay = s(rowValue($row, ['Barangay', 'barangay'], '')) ?: null;
        $district = s(rowValue($row, ['District', 'district'], '')) ?: null;
        $city = s(rowValue($row, ['City', 'city'], '')) ?: null;

        // Safety-net duplicate check: even if the validator missed it,
        // try to find this person in the database before creating a new record.
        $dupCheck = checkDuplicate(
            $conn,
            $firstName ?? '',
            $lastName ?? '',
            $dob,
            $contact,
            $email ?? ''
        );
        if ($dupCheck['found'] && !empty($dupCheck['benef_id'])) {
            $benefId = (int)$dupCheck['benef_id'];
        }

        if (!$benefId) {
            $programId = $ctx['programId'] ?? null;
            $insBenef = $conn->prepare('
                INSERT INTO beneficiaries
                    (first_name, middle_name, last_name, suffix,
                     sex, civil_status, dob, contact, email, program_id, classification,
                     house_no, barangay, district, city, spes_status)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ');
            $insBenef->bind_param('sssssssssissssss',
                $firstName, $middleName, $lastName, $suffix,
                $sex, $civil, $dob, $contact, $email, $programId, $classification,
                $houseNo, $barangay, $district, $city, $spesStatus
            );
            $insBenef->execute();

            $benefId = (int)$insBenef->insert_id;
            if ($benefId > 0) {
                $state['insertedBenefIds'][] = $benefId;
                if ($program === 'Job Fair') {
                    $jobFairKey = $buildJobFairIdentityKey($row);
                    if ($jobFairKey !== '') {
                        $state['jobFairBeneficiaryMap'][$jobFairKey] = $benefId;
                    }
                }
            }
        }
    }

    if ($benefId > 0 && $program === 'Job Fair') {
        $jobFairKey = $buildJobFairIdentityKey($row);
        if ($jobFairKey !== '') {
            $state['jobFairBeneficiaryMap'][$jobFairKey] = $benefId;
        }
    }

    if (!$benefId) {
        return null;
    }

    if ($benefId > 0 && !empty($ctx['programId'])) {
        $pId = (int)$ctx['programId'];
        $bId = !empty($ctx['batchId']) ? (int)$ctx['batchId'] : null;
        
        $rowStatus = titleCase(s(rowValue($row, ['Status', 'status', 'Program Status', 'program_status', 'Classification', 'classification'], '')));
        if ($rowStatus !== '') {
            $status = $rowStatus;
        } else {
            $status = !empty($ctx['programStatus']) ? $ctx['programStatus'] : 'Registered';
        }
        
        $checkSql = 'SELECT id FROM beneficiary_programs WHERE benef_id = ? AND program_id = ?' . ($bId ? ' AND batch_id = ?' : ' AND batch_id IS NULL');
        $checkStmt = $conn->prepare($checkSql);
        if ($bId) {
            $checkStmt->bind_param('iii', $benefId, $pId, $bId);
        } else {
            $checkStmt->bind_param('ii', $benefId, $pId);
        }
        $checkStmt->execute();
        $res = $checkStmt->get_result();
        
        if ($res->num_rows === 0) {
            $insSql = 'INSERT INTO beneficiary_programs (benef_id, program_id, batch_id, status) VALUES (?, ?, ?, ?)';
            $insBp = $conn->prepare($insSql);
            $insBp->bind_param('iiis', $benefId, $pId, $bId, $status);
            if ($insBp->execute()) {
                $state['insertedBeneficiaryProgramIds'][] = $insBp->insert_id;
            }
        } else {
            // Update status if it exists and a specific status was given
            $bpId = $res->fetch_assoc()['id'];
            if (!empty($ctx['programStatus'])) {
                $updSql = 'UPDATE beneficiary_programs SET status = ? WHERE id = ?';
                $updBp = $conn->prepare($updSql);
                $updBp->bind_param('si', $status, $bpId);
                $updBp->execute();
            }
        }
    }

    if (tableExists($conn, 'docs_benef')) {
        $insertedDocId = ensureDocsBenef($conn, $benefId, $row);
        if ($insertedDocId) {
            $state['insertedDocIds'][] = $insertedDocId;
        }
    }

    return $benefId;
}