<?php

function saveSchoolsRow(mysqli $conn, array $row, array $ctx, array &$state): string {
    $schoolName = s(rowValue($row, ['School Name', 'SchoolName', 'school_name'], ''));
    $districtRaw = s(rowValue($row, ['Congressional District', 'CongressionalDistrict', 'district'], ''));
    $gradesOffered = s(rowValue($row, ['Grades Offered', 'GradesOffered', 'grades_offered'], '')) ?: null;

    if ($schoolName === '') {
        return 'skipped';
    }

    $district = parseIntNullable($districtRaw);
    if ($district === null || $district <= 0) {
        return 'skipped';
    }

    $existingId = isset($row['_sys_school_id']) && (int)$row['_sys_school_id'] > 0
        ? (int)$row['_sys_school_id']
        : null;

    if ($existingId) {
        $upd = $conn->prepare('UPDATE schools SET school_name = ?, congressional_district = ?, grades_offered = ? WHERE school_id = ?');
        $upd->bind_param('sisi', $schoolName, $district, $gradesOffered, $existingId);
        $upd->execute();
        return 'saved';
    }

    $chk = $conn->prepare('
        SELECT school_id
        FROM schools
        WHERE LOWER(school_name) = LOWER(?)
          AND congressional_district = ?
          AND LOWER(COALESCE(grades_offered, "")) = LOWER(COALESCE(?, ""))
        LIMIT 1
    ');
    $chk->bind_param('sis', $schoolName, $district, $gradesOffered);
    $chk->execute();
    if ($chk->get_result()->fetch_assoc()) {
        return 'skipped';
    }

    $ins = $conn->prepare('INSERT INTO schools (school_name, congressional_district, grades_offered) VALUES (?, ?, ?)');
    $ins->bind_param('sis', $schoolName, $district, $gradesOffered);
    $ins->execute();

    $schoolId = (int)$ins->insert_id;
    if ($schoolId > 0) {
        $state['insertedSchoolIds'][] = $schoolId;
    }

    return 'saved';
}
