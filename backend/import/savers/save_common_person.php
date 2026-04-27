<?php

function ensurePersonBeneficiaryAndDocs(mysqli $conn, array $row, array $ctx, array &$state): ?int {
    $existingBenefId = $row['_sys_benef_id'] ?? null;
    $benefId = $existingBenefId ? (int)$existingBenefId : null;

    if (!$benefId) {
        $sex = s(rowValue($row, ['Sex', 'sex'], ''));
        $civil = s(rowValue($row, ['Civil Status', 'CivilStatus', 'civil_status'], ''));
        // If _parsed_dob exists (from validation), use it; otherwise parse raw DOB
        $dob = null;
        if (!empty($row['_parsed_dob'])) {
            $dob = (string)$row['_parsed_dob'];
        } else {
            $dobRaw = rowValue($row, ['DOB', 'Birthday'], '');
            $dob = parseDateNullable($dobRaw);
        }
        $contact = s(rowValue($row, ['Contact', 'contact'], ''));
        $email = s(rowValue($row, ['Email', 'email'], '')) ?: null;
        $classification = isWhipBeneficiariesProgram((string)($ctx['program'] ?? ''))
            ? null
            : (s(rowValue($row, ['Classification', 'classification'], '')) ?: null);
        $houseNo = s(rowValue($row, ['House No.', 'house_no'], '')) ?: null;
        $barangay = s(rowValue($row, ['Barangay', 'barangay'], '')) ?: null;
        $district = s(rowValue($row, ['District', 'district'], '')) ?: null;
        $city = s(rowValue($row, ['City', 'city'], '')) ?: null;

        $programId = $ctx['programId'] ?? null;
        $insBenef = $conn->prepare('
            INSERT INTO beneficiaries
                (sex, civil_status, dob, contact, email, program_id, classification, house_no, barangay, district, city)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');
        $insBenef->bind_param('sssssisssss', $sex, $civil, $dob, $contact, $email, $programId, $classification, $houseNo, $barangay, $district, $city);
        $insBenef->execute();

        $benefId = (int)$insBenef->insert_id;
        if ($benefId > 0) {
            $state['insertedBenefIds'][] = $benefId;
        }
    }

    if (!$benefId) {
        return null;
    }

    if (tableExists($conn, 'docs_benef')) {
        $insertedDocId = ensureDocsBenef($conn, $benefId, $row);
        if ($insertedDocId) {
            $state['insertedDocIds'][] = $insertedDocId;
        }
    }

    return $benefId;
}
