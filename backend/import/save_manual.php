<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Shared helpers
require_once __DIR__ . '/helpers/db_utils.php';
require_once __DIR__ . '/helpers/formatting_utils.php';
require_once __DIR__ . '/helpers/program_utils.php';
require_once __DIR__ . '/helpers/followup_utils.php';

// Row savers
require_once __DIR__ . '/savers/save_common_person.php';
require_once __DIR__ . '/savers/save_job_matching.php';
require_once __DIR__ . '/savers/save_spes.php';
require_once __DIR__ . '/savers/save_wiirp.php';
require_once __DIR__ . '/savers/save_gip.php';

try {
    $program = trim((string)($_POST['program'] ?? ''));
    if (!in_array($program, ['Job Matching and Referral', 'First Time Jobseeker', 'Job Fair', 'SPES', 'Government Internship Program', 'Work Immersion and Internship Referral Program'], true)) {
        throw new RuntimeException("Program '{$program}' is currently not supported for manual entry.");
    }

    $programId = resolveProgramId($conn, $program);

    // Construct synthetic $row from POST data
    $row = [
        // Beneficiary Info
        'First Name' => $_POST['first_name'] ?? '',
        'Middle Name' => $_POST['middle_name'] ?? '',
        'Last Name' => $_POST['last_name'] ?? '',
        'Suffix' => $_POST['suffix'] ?? '',
        'Sex' => $_POST['sex'] ?? '',
        'Civil Status' => $_POST['civil_status'] ?? '',
        'DOB' => $_POST['dob'] ?? '',
        'Contact' => $_POST['contact'] ?? '',
        'Email' => $_POST['email'] ?? '',
        'Classification' => $_POST['classification'] ?? '',
        'Status' => $_POST['spes_status'] ?? '',
        'House No.' => $_POST['house_no'] ?? '',
        'Barangay' => $_POST['barangay'] ?? '',
        'District' => $_POST['district'] ?? '',
        'City' => $_POST['city'] ?? '',
        
        // Employer Info
        'Company' => $_POST['company_name'] ?? '',
        'Position' => $_POST['position'] ?? '',
        'Occupational Permit' => $_POST['occ_permit'] ?? 0,
        'Health Card' => $_POST['health_card'] ?? 0,
        
        // Documents
        'Proof of Residency' => $_POST['proof_of_residency'] ?? '',
        'Latest Credentials' => $_POST['latest_credential'] ?? '',
        'Letter of Intent' => $_POST['letter_of_intent'] ?? '',
        'Reco Letter' => $_POST['reco_letter'] ?? '',
        'Resume' => $_POST['resume'] ?? '',
        'TOR' => $_POST['tor'] ?? '',
        'Brgy Clearance' => $_POST['brgy_clearance'] ?? '',
        'NBI Clearance' => $_POST['nbi_clearance'] ?? '',
        'Birth Cert' => $_POST['birth_cert'] ?? '',
        'TESDA Cert' => $_POST['tesda_cert'] ?? '',

        // SPES Fields
        'school' => $_POST['spes_school'] ?? '',
        'student_type' => $_POST['student_type'] ?? '',
        'highest_educ' => $_POST['highest_educ'] ?? '',
        'course' => !empty($_POST['course']) ? $_POST['course'] : ($_POST['int_course'] ?? ''),
        'store_assignment' => $_POST['store_assignment'] ?? '',
        '_spes_category' => $_POST['spes_category'] ?? '',
        'start_of_contract' => $_POST['start_of_contract'] ?? '',
        'end_of_contract' => $_POST['end_of_contract'] ?? '',
        'days' => $_POST['days'] ?? '',
        
        // WIIRP Fields
        'school' => $program === 'Government Internship Program' ? ($_POST['gip_school'] ?? '') : ($program === 'Work Immersion and Internship Referral Program' ? ($_POST['int_school'] ?? '') : ($_POST['spes_school'] ?? '')),
        'course' => $program === 'Government Internship Program' ? ($_POST['gip_course'] ?? '') : ($program === 'Work Immersion and Internship Referral Program' ? ($_POST['int_course'] ?? '') : ($_POST['course'] ?? '')),
        'highest_educ' => $program === 'Government Internship Program' ? ($_POST['gip_highest_educ'] ?? '') : ($_POST['highest_educ'] ?? ''),
        'year_level' => $_POST['year_level'] ?? '',
        'contract_period' => $_POST['contract_period'] ?? '',
        'required_hours' => $_POST['required_hours'] ?? '',
        'inquiry_type' => $_POST['inquiry_type'] ?? 'inquiry',
        'preferred_org_type' => $_POST['preferred_org_type'] ?? '',
        'preferred_industry' => (($_POST['preferred_industry'] ?? '') === 'Other' && !empty($_POST['preferred_industry_other'])) ? $_POST['preferred_industry_other'] : ($_POST['preferred_industry'] ?? ''),
        'is_willing_outside' => $_POST['is_willing_outside'] ?? '',
        'internship_sched' => (($_POST['internship_sched'] ?? '') === 'Other' && !empty($_POST['internship_sched_other'])) ? $_POST['internship_sched_other'] : ($_POST['internship_sched'] ?? ''),
        'start' => $_POST['int_start'] ?? '',
        '_parsed_start_date' => !empty($_POST['gip_start_of_contract']) ? $_POST['gip_start_of_contract'] : ($_POST['assign_start'] ?? ''), // Assignment start
        '_parsed_end_date' => !empty($_POST['gip_end_of_contract']) ? $_POST['gip_end_of_contract'] : ($_POST['assign_end'] ?? ''),     // Assignment end
        'office_assignment' => !empty($_POST['gip_office_assignment']) ? $_POST['gip_office_assignment'] : ($_POST['office_assignment'] ?? ''),
        'endorsement_1' => $_POST['endorsement_1'] ?? '',
        'endorsement_2' => $_POST['endorsement_2'] ?? '',
        
        // GIP Specific Fields mapped to common names or unique names for saveGipRow
        // GIP Specific Fields mapped to common names or unique names for saveGipRow
        'student_type' => $program === 'Government Internship Program' ? ($_POST['gip_student_type'] ?? '') : ($_POST['student_type'] ?? ''),
        'start_of_contract' => $program === 'Government Internship Program' ? ($_POST['gip_start_of_contract'] ?? '') : ($_POST['start_of_contract'] ?? ''),
        'end_of_contract' => $program === 'Government Internship Program' ? ($_POST['gip_end_of_contract'] ?? '') : ($_POST['end_of_contract'] ?? ''),
        'days' => $program === 'Government Internship Program' ? ($_POST['gip_days'] ?? '') : ($_POST['days'] ?? ''),
    ];

    $duplicate = checkDuplicate(
        $conn,
        (string)$row['First Name'],
        (string)$row['Last Name'],
        $row['DOB'] !== '' ? (string)$row['DOB'] : null,
        (string)$row['Contact'],
        (string)$row['Email']
    );

    if (!empty($duplicate['found'])) {
        $existingBenefId = (int)$duplicate['benef_id'];
        
        $progCheckStmt = $conn->prepare('SELECT id FROM beneficiary_programs WHERE benef_id = ? AND program_id = ?');
        $progCheckStmt->bind_param('ii', $existingBenefId, $programId);
        $progCheckStmt->execute();
        $isAlreadyEnrolled = $progCheckStmt->get_result()->num_rows > 0;
        $progCheckStmt->close();

        if ($isAlreadyEnrolled) {
            throw new RuntimeException('Duplicate beneficiary already exists in the database and is already enrolled in this program.');
        } else {
            // Re-use the existing beneficiary ID and just link the new program
            $row['_sys_benef_id'] = $existingBenefId;
        }
    }

    $conn->begin_transaction();

    // Resolve Batch ID
    $batchId = null;
    $batchPeriod = trim((string)($_POST['batch_period'] ?? $_POST['spes_batch'] ?? $_POST['int_batch'] ?? $_POST['whip_batch'] ?? $_POST['school_batch'] ?? ''));

    if ($program === 'Job Fair' && empty($batchPeriod)) {
        $eventIdsRaw = $_POST['jobfairevent_ids'] ?? [];
        if (!is_array($eventIdsRaw)) $eventIdsRaw = [$eventIdsRaw];
        $firstEventId = (int)($eventIdsRaw[0] ?? 0);
        if ($firstEventId) {
            $evtStmt = $conn->prepare('SELECT date_start FROM job_fair_events WHERE jobfairevent_id = ?');
            $evtStmt->bind_param('i', $firstEventId);
            $evtStmt->execute();
            $evtRes = $evtStmt->get_result()->fetch_assoc();
            if ($evtRes && !empty($evtRes['date_start'])) {
                $batchPeriod = date('Y-m', strtotime($evtRes['date_start']));
            }
        }
    }

    if ($batchPeriod !== '') {
        $parts = explode('-', $batchPeriod);
        if (count($parts) === 2) {
            $yearInt = (int)$parts[0];
            $monthInt = (int)$parts[1];
            
            // Check if batch exists
            $stmt = $conn->prepare('SELECT batch_id FROM import_batches WHERE month = ? AND year = ? AND file_name = "Manual Entry" LIMIT 1');
            $stmt->bind_param('ii', $monthInt, $yearInt);
            $stmt->execute();
            $res = $stmt->get_result()->fetch_assoc();
            
            if ($res) {
                $batchId = (int)$res['batch_id'];
            } else {
                // Create new batch
                $uploadedBy = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
                $fileName = 'Manual Entry';
                $insBatch = $conn->prepare('INSERT INTO import_batches (file_name, month, year, uploaded_by) VALUES (?, ?, ?, ?)');
                $insBatch->bind_param('siii', $fileName, $monthInt, $yearInt, $uploadedBy);
                $insBatch->execute();
                $batchId = (int)$insBatch->insert_id;
            }
        }
    }

    $classification = strtolower(trim((string)($_POST['classification'] ?? '')));
    $gipCategory = '';
    if (strpos($classification, 'dole-accepted') !== false) {
        $gipCategory = 'DOLE';
    } elseif (strpos($classification, 'peso-accepted') !== false) {
        $gipCategory = 'LGU';
    }

    $programStatus = 'Registered';
    if ($program === 'SPES') {
        $programStatus = !empty($_POST['spes_status']) ? trim($_POST['spes_status']) : 'Registered';
    } elseif (in_array($program, ['Job Matching and Referral', 'First Time Jobseeker', 'Job Fair'])) {
        $programStatus = !empty($_POST['classification']) ? trim($_POST['classification']) : 'Registered';
    } elseif (strpos($program, 'Workers Hiring') !== false) {
        $programStatus = 'Placed';
    }

    $ctx = [
        'program' => $program,
        'programId' => $programId,
        'batchId' => $batchId,
        'wiirpCategory' => trim((string)($_POST['inquiry_type'] ?? 'inquiry')),
        'gipCategory' => $gipCategory,
        'programStatus' => $programStatus,
        'is_manual' => true
    ];


    $state = [
        'insertedBenefIds' => [],
        'jobFairBeneficiaryMap' => [],
        'insertedDocIds' => [],
        'insertedJobMatchIds' => [],
        'insertedJobFairIds' => [],
        'createdEmployerIds' => [],
        'insertedBeneficiaryProgramIds' => [],
        'warnings' => [],
    ];

    $benefId = ensurePersonBeneficiaryAndDocs($conn, $row, $ctx, $state);
    if (!$benefId) {
        throw new RuntimeException("Failed to save beneficiary.");
    }

    if ($program === 'Job Fair') {
        if (!tableExists($conn, 'jobfair')) {
            throw new RuntimeException('jobfair table does not exist.');
        }

        $position = s((string)($_POST['position'] ?? '')) ?: 'N/A';

        $eventIdsRaw = $_POST['jobfairevent_ids'] ?? [];
        if (!is_array($eventIdsRaw)) {
            $eventIdsRaw = [$eventIdsRaw];
        }

        $eventIds = array_values(array_unique(array_filter(array_map(static function ($v) {
            return is_numeric($v) ? (int)$v : 0;
        }, $eventIdsRaw))));

        if (empty($eventIds)) {
            throw new RuntimeException('Please select at least one Job Fair event.');
        }

        $companyMapRaw = $_POST['jf_company_ids'] ?? [];
        if (!is_array($companyMapRaw)) {
            $companyMapRaw = [];
        }

        $insertedRows = 0;
        $ins = $conn->prepare('INSERT INTO jobfair (benef_id, company_id, position, batch_id, jobfairevent_id) VALUES (?, ?, ?, ?, ?)');

        foreach ($eventIds as $eventId) {
            $eventKey = (string)$eventId;
            $companyIdsRaw = $companyMapRaw[$eventKey] ?? [];
            if (!is_array($companyIdsRaw)) {
                $companyIdsRaw = [$companyIdsRaw];
            }

            $companyIds = array_values(array_unique(array_filter(array_map(static function ($v) {
                return is_numeric($v) ? (int)$v : 0;
            }, $companyIdsRaw))));

            foreach ($companyIds as $companyId) {
                $ins->bind_param('iisii', $benefId, $companyId, $position, $batchId, $eventId);
                $ins->execute();
                $insertedRows++;
                $state['insertedJobFairIds'][] = (int)$ins->insert_id;
            }
        }

        if ($insertedRows === 0) {
            throw new RuntimeException('Please select at least one participating company for each chosen event.');
        }
    } elseif ($program === 'SPES') {
        $result = saveSPESRow($conn, $row, $benefId, $ctx, $state);
        if ($result !== 'saved') {
            throw new RuntimeException("Failed to save SPES record.");
        }
    } elseif ($program === 'Government Internship Program') {
        $row['_sys_benef_id'] = $benefId;
        $result = saveGipRow($conn, $row, $ctx, $state);
        if ($result !== 'saved') {
            throw new RuntimeException("Failed to save GIP record.");
        }
    } elseif ($program === 'Work Immersion and Internship Referral Program') {
        $row['_sys_benef_id'] = $benefId;
        $result = saveWiirpRow($conn, $row, $ctx, $state);
        if ($result !== 'saved') {
            throw new RuntimeException("Failed to save WIIRP record.");
        }
    } else {
        $result = saveJobMatchingFamilyRow($conn, $row, $benefId, $ctx, $state);
        if ($result !== 'saved') {
            throw new RuntimeException("Failed to save job match record.");
        }
    }

    $conn->commit();

    echo json_encode([
        'success' => true,
        'beneficiary_id' => $benefId,
        'state' => $state,
        'warnings' => array_values(array_unique($state['warnings'])),
    ]);

} catch (Throwable $e) {
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
