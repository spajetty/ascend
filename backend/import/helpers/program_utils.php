<?php

function isWhipProjectsProgram(string $program): bool {
    return in_array($program, [
        'Workers Hiring for Infrastructure Projects - Projects',
        'Workers Hiring for Infrastructure Projects — Projects',
    ], true);
}

function isWhipBeneficiariesProgram(string $program): bool {
    return in_array($program, [
        'Workers Hiring for Infrastructure Projects - Beneficiaries',
        'Workers Hiring for Infrastructure Projects — Beneficiaries',
    ], true);
}

function isWiirpProgram(string $program): bool {
    return $program === 'Work Immersion and Internship Referral Program';
}

function isGipProgram(string $program): bool {
    return $program === 'Government Internship Program';
}


function whipProjectRowFields(array $row): array {
    return [
        'title'      => getRowVal($row, ['Project Title / Name of Implementing Partner', 'Project Title', 'Project Name', 'project_title']),
        'contractor' => getRowVal($row, ['Project Contractor', 'Company', 'contractor']),
        'duration'   => getRowVal($row, ['Duration', 'duration']),
        'budget'     => getRowVal($row, ['Budget', 'budget']),
        'fund'       => getRowVal($row, ['Fund Source', 'fund_source']),
        'nature'     => getRowVal($row, ['Nature of Project', 'nature_of_project']),
    ];
}

function collapseWhipProjectRows(array $rows): array {
    $collapsed = [];
    $currentIndex = -1;

    foreach ($rows as $row) {
        $title      = getRowVal($row, ['Project Title / Name of Implementing Partner']);
        $contractor = getRowVal($row, ['Project Contractor', 'Company']);
        $nature     = getRowVal($row, ['Nature of Project']);
        $duration   = getRowVal($row, ['Duration']);
        $budget     = getRowVal($row, ['Budget']);
        $fund       = getRowVal($row, ['Fund Source']);
        $hasAnchor  = ($title !== '' && $contractor !== '');

        $skills     = getRowVal($row, ['Skills Required for the Job']);
        $skillsDef  = getRowVal($row, ['Skills Deficiencies']);
        $isContinuation = !$hasAnchor && ($skills !== '' || $skillsDef !== '');

        if ($isContinuation && $currentIndex >= 0) {
            $existingSkills = (string)($collapsed[$currentIndex]['Skills Required for the Job'] ?? '');
            $existingDef = (string)($collapsed[$currentIndex]['Skills Deficiencies'] ?? '');

            $collapsed[$currentIndex]['Skills Required for the Job'] = joinRowValue($existingSkills, $skills);
            $collapsed[$currentIndex]['Skills Deficiencies'] = joinRowValue($existingDef, $skillsDef);
            continue;
        }

        $collapsed[] = $row;

        if ($hasAnchor) {
            $currentIndex = count($collapsed) - 1;
        }
    }

    return $collapsed;
}

function buildWhipProjectDuplicateKey(array $row): ?string {
    $f = whipProjectRowFields($row);
    if ($f['title'] === '' || $f['contractor'] === '') {
        return null;
    }

    return implode('|', [
        normalizeKeyText($f['title']),
        normalizeKeyText($f['contractor']),
        normalizeKeyText($f['duration']),
        normalizeMoneyValue($f['budget']),
        normalizeKeyText($f['fund']),
    ]);
}

function resolveWiirpTableSchema(mysqli $conn): array {
    static $schema = null;
    if ($schema !== null) return $schema;

    $tables = ['wiirp'];
    $table = null;
    foreach ($tables as $candidate) {
        if (tableExists($conn, $candidate)) {
            $table = $candidate;
            break;
        }
    }

    if ($table === null) {
        $schema = ['table' => null];
        return $schema;
    }

    $schema = [
        'table' => $table,
        'id_col' => firstExistingColumn($conn, $table, ['work_immersion_id', 'id']),
        'benef_id_col' => firstExistingColumn($conn, $table, ['benef_id']),
        'contract_period_col' => firstExistingColumn($conn, $table, ['contract_period']),
        'school_col' => firstExistingColumn($conn, $table, ['school']),
        'course_col' => firstExistingColumn($conn, $table, ['course']),
        'required_hours_col' => firstExistingColumn($conn, $table, ['required_hours']),
        'inquiry_type_col' => firstExistingColumn($conn, $table, ['inquiry_type']),
        'preferred_org_type_col' => firstExistingColumn($conn, $table, ['preferred_org_type']),
        'preferred_industry_col' => firstExistingColumn($conn, $table, ['preferred_industry']),
        'is_willing_outside_col' => firstExistingColumn($conn, $table, ['is_willing_outside']),
        'internship_sched_col' => firstExistingColumn($conn, $table, ['internship_sched']),
        'start_col' => firstExistingColumn($conn, $table, ['start']),
        'end_col' => firstExistingColumn($conn, $table, ['end', 'end_date', 'est_end', 'est_end_date', 'estimated_end']),
        'office_assignment_col' => firstExistingColumn($conn, $table, ['office_assignment', 'office_assignment_endorsement', 'office_assignment_endorsement_1']),
        'endorsement1_col' => firstExistingColumn($conn, $table, ['endorsement_1', 'endorsement1', 'endorsement_a']),
        'endorsement2_col' => firstExistingColumn($conn, $table, ['endorsement_2', 'endorsement2', 'endorsement_b']),
        'year_level_col' => firstExistingColumn($conn, $table, ['year_level']),
        'type_col' => firstExistingColumn($conn, $table, ['type']),
    ];

    return $schema;
}

function resolveWhipTableSchema(mysqli $conn): array {
    static $schema = null;
    if ($schema !== null) return $schema;

    $tables = ['whip', 'whip_beneficiaries', 'whipBeneficiaries'];
    $table = null;
    foreach ($tables as $candidate) {
        if (tableExists($conn, $candidate)) {
            $table = $candidate;
            break;
        }
    }

    if ($table === null) {
        $schema = ['table' => null];
        return $schema;
    }

    $schema = [
        'table' => $table,
        'id_col' => firstExistingColumn($conn, $table, ['whip_id', 'id']),
        'benef_id_col' => firstExistingColumn($conn, $table, ['benef_id']),
        'project_id_col' => firstExistingColumn($conn, $table, ['project_id']),
        'position_col' => firstExistingColumn($conn, $table, ['position']),
        'date_hired_col' => firstExistingColumn($conn, $table, ['date_hired', 'datehired']),
        'batch_id_col' => firstExistingColumn($conn, $table, ['batch_id']),
    ];

    return $schema;
}

function resolveWhipProjectsSchema(mysqli $conn): array {
    static $schema = null;
    if ($schema !== null) return $schema;

    $tables = [
        'projects',
        'whip_projects',
        'whipProject',
        'whip_project',
        'workers_hiring_projects',
        'workers_infra_projects',
        'infrastructure_projects',
    ];

    $table = null;
    foreach ($tables as $candidate) {
        if (tableExists($conn, $candidate)) {
            $table = $candidate;
            break;
        }
    }

    if ($table === null) {
        $schema = ['table' => null];
        return $schema;
    }

    $schema = [
        'table' => $table,
        'project_id_col' => firstExistingColumn($conn, $table, ['project_id', 'whip_project_id', 'id']),
        'title_col' => firstExistingColumn($conn, $table, ['project_title', 'project_name', 'name_of_project', 'title']),
        'nature_col' => firstExistingColumn($conn, $table, ['nature_of_project', 'project_nature', 'nature']),
        'duration_col' => firstExistingColumn($conn, $table, ['duration', 'project_duration']),
        'budget_col' => firstExistingColumn($conn, $table, ['budget', 'project_budget']),
        'fund_col' => firstExistingColumn($conn, $table, ['fund_source', 'fundsource', 'source_of_funds']),
        'jobs_generated_col' => firstExistingColumn($conn, $table, ['jobs_generated']),
        'persons_locality_col' => firstExistingColumn($conn, $table, ['persons_employed_locality', 'no_of_persons', 'persons_locality']),
        'skills_required_col' => firstExistingColumn($conn, $table, ['skills_required', 'skills_required_for_the_job']),
        'skills_def_col' => firstExistingColumn($conn, $table, ['skills_deficiencies', 'skills_deficiency']),
        'contractor_col' => firstExistingColumn($conn, $table, ['project_contractor', 'contractor', 'company_name']),
        'legit_col' => firstExistingColumn($conn, $table, ['legitimate_contractors', 'legitimate_contractor']),
        'filled_col' => firstExistingColumn($conn, $table, ['filled']),
        'unfilled_col' => firstExistingColumn($conn, $table, ['unfilled']),
        'company_id_col' => firstExistingColumn($conn, $table, ['company_id']),
        'program_id_col' => firstExistingColumn($conn, $table, ['program_id']),
        'batch_id_col' => firstExistingColumn($conn, $table, ['batch_id']),
    ];

    return $schema;
}

function getWhipProjectsShape(mysqli $conn): array {
    return resolveWhipProjectsSchema($conn);
}

function whipProjectExists(mysqli $conn, array $row): bool {
    $shape = getWhipProjectsShape($conn);
    $table = $shape['table'] ?? null;
    $titleCol = $shape['title_col'] ?? null;
    if (!$table || !$titleCol) return false;

    $f = whipProjectRowFields($row);
    if ($f['title'] === '') return false;

    $titleNorm = normalizeKeyText($f['title']);

    $sql = sprintf('SELECT * FROM `%s` WHERE `%s` LIKE ? LIMIT 200', $table, $titleCol);
    $stmt = $conn->prepare($sql);
    $firstWord = explode(' ', trim($f['title']))[0] ?? '';
    $likeParam = (strlen($firstWord) >= 3) ? '%' . $firstWord . '%' : '%';
    $stmt->bind_param('s', $likeParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $contractorNorm = normalizeKeyText($f['contractor']);
    $durationNorm = normalizeKeyText($f['duration']);
    $budgetNorm = normalizeMoneyValue($f['budget']);
    $fundNorm = normalizeKeyText($f['fund']);

    while ($db = $result->fetch_assoc()) {
        $dbTitleNorm = normalizeKeyText($db[$titleCol] ?? '');
        if ($dbTitleNorm !== $titleNorm) continue;

        $matches = true;
        if ($contractorNorm !== '') {
            $companyIdCol = $shape['company_id_col'] ?? null;
            $contractorCol = $shape['contractor_col'] ?? null;
            $dbContractor = normalizeKeyText($db[$contractorCol] ?? '');
            if ($contractorCol && $dbContractor !== $contractorNorm) {
                $matches = false;
            }
        }
        $durationCol = $shape['duration_col'] ?? null;
        if ($matches && $durationNorm !== '' && $durationCol) {
            if (normalizeKeyText($db[$durationCol] ?? '') !== $durationNorm) $matches = false;
        }
        $budgetCol = $shape['budget_col'] ?? null;
        if ($matches && $budgetNorm !== '' && $budgetCol) {
            if (normalizeMoneyValue($db[$budgetCol] ?? '') !== $budgetNorm) $matches = false;
        }
        $fundCol = $shape['fund_col'] ?? null;
        if ($matches && $fundNorm !== '' && $fundCol) {
            if (normalizeKeyText($db[$fundCol] ?? '') !== $fundNorm) $matches = false;
        }
        if ($matches) return true;
    }
    return false;
}

function whipProjectAlreadyExists(mysqli $conn, array $schema, array $payload, ?int $companyId): bool {
    // This basically relies on the same logic as above. Let's merge it:
    return whipProjectExists($conn, [
        'Project Title' => $payload['title'],
        'Company' => $payload['contractor'],
        'Duration' => $payload['duration'],
        'Budget' => $payload['budget_raw'],
        'Fund Source' => $payload['fund_source']
    ]);
}

function findWhipBeneficiaryProjectId(mysqli $conn, array $row): array {
    $shape = getWhipProjectsShape($conn);
    $table = $shape['table'] ?? null;
    $titleCol = $shape['title_col'] ?? null;
    if (!$table || !$titleCol) return ['id' => null, 'error' => 'Database configuration error: Projects table not found'];
    $projectIdCol = firstExistingColumn($conn, $table, ['project_id', 'whip_project_id', 'id']);
    if (!$projectIdCol) return ['id' => null, 'error' => 'Database configuration error: Project ID column not found'];

    $projectName = getRowVal($row, ['Name of Project', 'Project Title / Name of Implementing Partner']);
    $companyName = getRowVal($row, ['Company', 'Project Contractor']);
    if ($projectName === '') return ['id' => null, 'error' => 'Missing Project Name'];

    $projectNameNorm = normalizeKeyText($projectName);
    $sql = sprintf('SELECT * FROM `%s` WHERE `%s` LIKE ? LIMIT 300', $table, $titleCol);
    $stmt = $conn->prepare($sql);
    $firstWord = explode(' ', trim($projectName))[0] ?? '';
    $likeParam = (strlen($firstWord) >= 3) ? '%' . $firstWord . '%' : '%';
    $stmt->bind_param('s', $likeParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $companyNorm = normalizeEmployerName($companyName);
    $contractorCol = $shape['contractor_col'] ?? null;
    $matchedTitleMismatchedContractor = false;

    while ($db = $result->fetch_assoc()) {
        $dbTitleNorm = normalizeKeyText($db[$titleCol] ?? '');
        if ($dbTitleNorm !== $projectNameNorm) continue;

        if ($companyNorm !== '' && $contractorCol) {
            $dbContractor = normalizeEmployerName((string)($db[$contractorCol] ?? ''));
            if ($dbContractor !== $companyNorm) {
                $matchedTitleMismatchedContractor = true;
                continue;
            }
        }
        return ['id' => isset($db[$projectIdCol]) ? (int)$db[$projectIdCol] : null, 'error' => null];
    }
    if ($matchedTitleMismatchedContractor) {
        return ['id' => null, 'error' => 'Project title found, but Contractor/Company mismatch'];
    }
    return ['id' => null, 'error' => 'Project not found (make sure to add the project first)'];
}

function findProjectIdForWhipBeneficiary(mysqli $conn, array $row, array $projectSchema): ?int {
    return findWhipBeneficiaryProjectId($conn, $row)['id'] ?? null;
}

function resolveProgramId(mysqli $conn, string $programName): ?int {
    if ($programName === '') return null;
    $stmt = $conn->prepare('SELECT program_id FROM programs WHERE name = ? LIMIT 1');
    $stmt->bind_param('s', $programName);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    return $row ? (int)$row['program_id'] : null;
}

function resolveEmployer(mysqli $conn, string $name, ?int $batchId = null, array $meta = []): array {
    $name = trim($name);
    if ($name === '') return ['id' => null, 'created' => false];

    $normalized = normalizeEmployerName($name);

    $stmt = $conn->prepare('SELECT company_id, company_name FROM employers');
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $existingName = (string)($row['company_name'] ?? '');
        if ($existingName === '') continue;

        if (normalizeEmployerName($existingName) === $normalized) {
            return ['id' => (int)$row['company_id'], 'created' => false, 'matched_name' => $existingName];
        }
    }

    $columns = ['company_name'];
    $placeholders = ['?'];
    $bindTypes = 's';
    $bindValues = [$name];

    if (tableHasColumn($conn, 'employers', 'est_type')) {
        $columns[] = 'est_type';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['est_type'] ?? '') ?: null;
    }
    if (tableHasColumn($conn, 'employers', 'industry')) {
        $columns[] = 'industry';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['industry'] ?? '') ?: null;
    }
    if (tableHasColumn($conn, 'employers', 'city')) {
        $columns[] = 'city';
        $placeholders[] = '?';
        $bindTypes .= 's';
        $bindValues[] = s($meta['city'] ?? '') ?: null;
    }
    if ($batchId !== null && $batchId > 0 && tableHasColumn($conn, 'employers', 'batch_id')) {
        $columns[] = 'batch_id';
        $placeholders[] = '?';
        $bindTypes .= 'i';
        $bindValues[] = $batchId;
    }

    $sql = 'INSERT INTO employers (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $placeholders) . ')';
    $ins = $conn->prepare($sql);
    $ins->bind_param($bindTypes, ...$bindValues);
    $ins->execute();
    return ['id' => (int)$ins->insert_id, 'created' => true, 'matched_name' => $name];
}

function loadNormalizedEmployers(mysqli $conn): array {
    $map = [];
    $res = $conn->query('SELECT company_id, company_name FROM employers');
    if ($res) {
        while ($db = $res->fetch_assoc()) {
            $name = trim((string)($db['company_name'] ?? ''));
            if ($name === '') continue;
            $norm = normalizeEmployerName($name);
            if ($norm !== '') {
                $map[$norm] = (int)$db['company_id'];
            }
        }
    }
    return $map;
}

function ensureDocsBenef(mysqli $conn, int $benefId, array $row): ?int {
    $check = $conn->prepare('SELECT document_id FROM docs_benef WHERE benef_id = ? LIMIT 1');
    $check->bind_param('i', $benefId);
    $check->execute();
    if ($check->get_result()->fetch_assoc()) return null;

    $proof = s(getRowVal($row, ['Proof of Residency', 'proof_of_residency'], '')) ?: null;
    $latest = s(getRowVal($row, ['Latest Credentials', 'latest_credential'], '')) ?: null;
    $intent = s(getRowVal($row, ['Letter of Intent', 'letter_of_intent'], '')) ?: null;
    $reco = s(getRowVal($row, ['Reco Letter', 'reco_letter'], '')) ?: null;
    $resume = s(getRowVal($row, ['Resume', 'resume'], '')) ?: null;
    $tor = s(getRowVal($row, ['TOR', 'tor'], '')) ?: null;
    $brgy = s(getRowVal($row, ['Brgy Clearance', 'Barangay Clearance', 'brgy_clearance'], '')) ?: null;
    $nbi = s(getRowVal($row, ['NBI Clearance', 'nbi_clearance'], '')) ?: null;
    $birth = s(getRowVal($row, ['Birth Cert', 'B-Cert', 'birth_cert'], '')) ?: null;
    $tesda = s(getRowVal($row, ['TESDA Cert', 'tesda_cert'], '')) ?: null;

    $ins = $conn->prepare('
        INSERT INTO docs_benef
            (benef_id, proof_of_residency, latest_credential, letter_of_intent, reco_letter, resume, tor, brgy_clearance, nbi_clearance, birth_cert, tesda_cert)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    $ins->bind_param('issssssssss', $benefId, $proof, $latest, $intent, $reco, $resume, $tor, $brgy, $nbi, $birth, $tesda);
    $ins->execute();
    return (int)$ins->insert_id;
}

function whipProjectPayload(array $row): array {
    $f = whipProjectRowFields($row);
    return [
        'title' => $f['title'],
        'nature' => $f['nature'],
        'duration' => $f['duration'],
        'budget_raw' => $f['budget'],
        'fund_source' => $f['fund'],
        'jobs_generated' => s(getRowVal($row, ['Jobs Generated', 'jobs_generated'], '')),
        'persons_locality' => s(getRowVal($row, ['No. of Persons Employed from the Locality', 'No. of Persons', 'persons_employed_locality'], '')),
        'skills_required' => s(getRowVal($row, ['Skills Required for the Job', 'skills_required'], '')),
        'skills_deficiencies' => s(getRowVal($row, ['Skills Deficiencies', 'skills_deficiencies'], '')),
        'contractor' => $f['contractor'],
        'legitimate_contractors' => strtoupper(s(getRowVal($row, ['Legitimate Contractors (YES or NO)', 'Legitimate Contractors', 'legitimate_contractors'], ''))),
        'filled' => s(getRowVal($row, ['Filled', 'filled'], '')),
        'unfilled' => s(getRowVal($row, ['Unfilled', 'unfilled'], '')),
    ];
}

function createUndoToken(): string {
    return bin2hex(random_bytes(16));
}

function buildExcelDuplicateKey(string $fname, string $lname, ?string $dob): ?string {
    if (empty($fname) || empty($lname) || empty($dob)) return null;
    return strtolower(trim($fname)) . '|' . strtolower(trim($lname)) . '|' . $dob;
}

function findExistingWhipBeneficiary(mysqli $conn, array $row): array {
    $empty = ['found' => false, 'user_id' => null, 'benef_id' => null];
    $fname = trim($row['First Name'] ?? $row['FirstName'] ?? '');
    $lname = trim($row['Last Name'] ?? $row['LastName'] ?? '');
    if ($fname === '' || $lname === '') return $empty;

    $stmt = $conn->prepare('SELECT user_id, benef_id FROM beneficiaries WHERE fname = ? AND lname = ? LIMIT 1');
    $stmt->bind_param('ss', $fname, $lname);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res) {
        return ['found' => true, 'user_id' => $res['user_id'], 'benef_id' => $res['benef_id']];
    }
    return $empty;
}

function findBeneficiaryInDatabase(mysqli $conn, string $fname, string $lname, string $dobVal, string $email, string $contact): array {
    $empty = ['found' => false, 'user_id' => null, 'benef_id' => null];
    
    // Exact match
    $stmt = $conn->prepare('SELECT user_id, benef_id FROM beneficiaries WHERE fname = ? AND lname = ? LIMIT 1');
    $stmt->bind_param('ss', $fname, $lname);
    $stmt->execute();
    if ($res = $stmt->get_result()->fetch_assoc()) {
        return ['found' => true, 'user_id' => $res['user_id'], 'benef_id' => $res['benef_id']];
    }

    if ($email === '' && $dobVal === '' && $contact === '') {
        return $empty;
    }

    $stmt = $conn->prepare('
        SELECT user_id, benef_id, email, dob, contact
        FROM beneficiaries
        WHERE (email IS NOT NULL AND email <> "" AND email = ?)
           OR (dob IS NOT NULL AND dob = ?)
           OR (contact IS NOT NULL AND contact = ?)
        LIMIT 200
    ');
    $stmt->bind_param('sss', $email, $dobVal, $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $score = 0;
        if ($email !== '' && isset($row['email']) && trim((string)$row['email']) === $email) $score++;
        if ($dobVal !== '' && isset($row['dob']) && trim((string)$row['dob']) === $dobVal) $score++;
        if ($contact !== '' && isset($row['contact']) && trim((string)$row['contact']) === $contact) $score++;

        if ($score >= 2) {
            return ['found' => true, 'user_id' => null, 'benef_id' => $row['benef_id']];
        }
    }
    return $empty;
}

function getBeneficiaryNameColumns(mysqli $conn): array {
    static $cached = null;
    if ($cached !== null) {
        return $cached;
    }

    $firstCandidates = ['first_name', 'fname', 'firstName'];
    $lastCandidates = ['last_name', 'lname', 'lastName'];
    $foundFirst = null;
    $foundLast = null;

    $stmt = $conn->prepare('
        SELECT column_name
        FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = ?
    ');
    $table = 'beneficiaries';
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $columns = [];
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if (isset($row['column_name'])) {
            $columns[] = strtolower((string)$row['column_name']);
        }
    }

    foreach ($firstCandidates as $candidate) {
        if (in_array(strtolower($candidate), $columns, true)) {
            $foundFirst = $candidate;
            break;
        }
    }

    foreach ($lastCandidates as $candidate) {
        if (in_array(strtolower($candidate), $columns, true)) {
            $foundLast = $candidate;
            break;
        }
    }

    $cached = [$foundFirst, $foundLast];
    return $cached;
}

function checkDuplicate(mysqli $conn, string $fname, string $lname, ?string $dob, string $contact, string $email): array {
    $empty = ['found' => false, 'user_id' => null, 'benef_id' => null];

    $fnameVal = trim($fname);
    $lnameVal = trim($lname);
    $dobVal = trim((string)($dob ?? ''));
    $contact = trim($contact);
    $email = trim($email);

    [$firstCol, $lastCol] = getBeneficiaryNameColumns($conn);

    if ($firstCol && $lastCol && $fnameVal !== '' && $lnameVal !== '' && $dobVal !== '') {
        $sql = sprintf(
            'SELECT benef_id FROM beneficiaries WHERE LOWER(TRIM(%s)) = ? AND LOWER(TRIM(%s)) = ? AND dob = ? LIMIT 1',
            $firstCol,
            $lastCol
        );

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $fnameVal, $lnameVal, $dobVal);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row) {
            return ['found' => true, 'user_id' => null, 'benef_id' => $row['benef_id']];
        }
    }

    if ($email === '' && $contact === '' && $dobVal === '') {
        return $empty;
    }

    $stmt = $conn->prepare('
        SELECT benef_id, email, dob, contact
        FROM beneficiaries
        WHERE (email IS NOT NULL AND email <> "" AND email = ?)
           OR (dob IS NOT NULL AND dob = ?)
           OR (contact IS NOT NULL AND contact = ?)
        LIMIT 200
    ');

    $stmt->bind_param('sss', $email, $dobVal, $contact);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $score = 0;
        if ($email !== '' && isset($row['email']) && trim((string)$row['email']) === $email) $score++;
        if ($dobVal !== '' && isset($row['dob']) && trim((string)$row['dob']) === $dobVal) $score++;
        if ($contact !== '' && isset($row['contact']) && trim((string)$row['contact']) === $contact) $score++;

        if ($score >= 2) {
            return ['found' => true, 'user_id' => null, 'benef_id' => $row['benef_id']];
        }
    }

    return $empty;
}

