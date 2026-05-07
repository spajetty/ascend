<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['data']) || !isset($input['program'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid data format']);
    exit;
}

$program = trim((string)$input['program']);
$rows = $input['data'];
$wiirpCategory = trim((string)($input['wiirpCategory'] ?? ''));
$gipCategory = trim((string)($input['gipCategory'] ?? ''));

// Require shared helper functions
require_once __DIR__ . '/helpers/db_utils.php';
require_once __DIR__ . '/helpers/formatting_utils.php';
require_once __DIR__ . '/helpers/program_utils.php';

// Require validators
require_once __DIR__ . '/validators/validate_employers_accreditation.php';
require_once __DIR__ . '/validators/validate_whip_projects.php';
require_once __DIR__ . '/validators/validate_whip_beneficiaries.php';
require_once __DIR__ . '/validators/validate_wiirp.php';
require_once __DIR__ . '/validators/validate_gip.php';
require_once __DIR__ . '/validators/validate_job_matching.php';
require_once __DIR__ . '/validators/validate_spes.php';
require_once __DIR__ . '/validators/validate_schools.php';
require_once __DIR__ . '/validators/validate_beneficiaries.php';

$validatedData = [];

if ($program === 'Employers Accreditation') {
    $validatedData = validateEmployersAccreditation($conn, $rows);
} elseif (isWhipProjectsProgram($program)) {
    $validatedData = validateWhipProjects($conn, $rows);
} elseif (isWhipBeneficiariesProgram($program)) {
    $validatedData = validateWhipBeneficiaries($conn, $rows);
} elseif (isWiirpProgram($program)) {
    $validatedData = validateWiirp($conn, $rows, $wiirpCategory);
} elseif (isGipProgram($program)) {
    $validatedData = validateGip($conn, $rows, $gipCategory);
} elseif (in_array($program, ['Job Matching and Referral', 'Job Fair', 'First Time Jobseeker'], true)) {
    $validatedData = validateJobMatchingFamily($conn, $rows, $program);
} elseif ($program === 'SPES') {
    $validatedData = validateSPES($conn, $rows, $program);
} elseif ($program === 'Schools') {
    $validatedData = validateSchools($conn, $rows);
} else {
    // Falls back to generic beneficiary/program processing (CDSP, LMI, etc.)
    $validatedData = validateBeneficiaries($conn, $rows, $program);
}

function collectUnknownEmployers(mysqli $conn, array $rows, string $program): array {
    $fieldGroups = [];

    if ($program === 'Employers Accreditation') {
        $fieldGroups = [
            ['COMPANY', 'Company', 'CompanyName'],
        ];
    } elseif ($program === 'SPES') {
        $fieldGroups = [
            ['Company', 'company'],
        ];
    } elseif (in_array($program, ['Job Matching and Referral', 'Job Fair', 'First Time Jobseeker'], true)) {
        $fieldGroups = [
            ['Company', 'CompanyName', 'Employer'],
        ];
    } elseif (isWhipProjectsProgram($program)) {
        $fieldGroups = [
            ['Project Contractor', 'Company', 'contractor'],
        ];
    }

    if ($fieldGroups === []) {
        return [];
    }

    $existingEmployers = loadNormalizedEmployers($conn);
    $unknownEmployers = [];

    foreach ($rows as $row) {
        $employerName = '';

        foreach ($fieldGroups as $keys) {
            $candidate = trim((string)rowValue($row, $keys, ''));
            if ($candidate !== '') {
                $employerName = $candidate;
                break;
            }
        }

        if ($employerName === '') {
            continue;
        }

        $normalized = normalizeEmployerName($employerName);
        if ($normalized === '' || isset($existingEmployers[$normalized])) {
            continue;
        }

        if (!isset($unknownEmployers[$normalized])) {
            $unknownEmployers[$normalized] = $employerName;
        }
    }

    return array_values($unknownEmployers);
}

$newCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'new'));
$invalidCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'invalid'));
$duplicateCount = count(array_filter($validatedData, fn($r) => ($r['badge_status'] ?? '') === 'duplicate'));

echo json_encode([
    'success' => true,
    'data' => $validatedData,
    'unknownEmployers' => collectUnknownEmployers($conn, $validatedData, $program),
    'summary' => [
        'total' => count($validatedData),
        'new' => $newCount,
        'invalid' => $invalidCount,
        'duplicate' => $duplicateCount,
        'skipped' => $invalidCount + $duplicateCount,
    ],
]);
