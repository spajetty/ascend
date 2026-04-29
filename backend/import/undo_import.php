<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$input = json_decode(file_get_contents('php://input'), true);
$token = trim((string)($input['undoToken'] ?? ''));

if ($token === '') {
    echo json_encode(['success' => false, 'error' => 'Missing undo token.']);
    exit;
}

$undoStore = $_SESSION['import_undo'] ?? null;
if (!is_array($undoStore) || !isset($undoStore[$token]) || !is_array($undoStore[$token])) {
    echo json_encode(['success' => false, 'error' => 'Undo request is no longer available.']);
    exit;
}

$payload = $undoStore[$token];
if ((int)($payload['expires_at'] ?? 0) < time()) {
    unset($_SESSION['import_undo'][$token]);
    echo json_encode(['success' => false, 'error' => 'Undo window expired.']);
    exit;
}

function deleteByIds(mysqli $conn, string $table, string $idColumn, array $ids): int {
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids), static fn($id) => $id > 0)));
    if (empty($ids)) return 0;

    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $types = str_repeat('i', count($ids));
    $sql = "DELETE FROM {$table} WHERE {$idColumn} IN ({$placeholders})";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$ids);
    $stmt->execute();
    return $stmt->affected_rows;
}

function findExistingColumn(mysqli $conn, string $table, array $candidates): ?string {
    foreach ($candidates as $column) {
        $stmt = $conn->prepare('
            SELECT 1
            FROM information_schema.columns
            WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?
            LIMIT 1
        ');
        $stmt->bind_param('ss', $table, $column);
        $stmt->execute();
        if ($stmt->get_result()->fetch_assoc()) {
            return $column;
        }
    }
    return null;
}

function tableExists(mysqli $conn, string $table): bool {
    $stmt = $conn->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = ? LIMIT 1');
    $stmt->bind_param('s', $table);
    $stmt->execute();
    return (bool)$stmt->get_result()->fetch_assoc();
}

$conn->begin_transaction();

try {
    $deletedFirstJobSeek = deleteByIds($conn, 'firstJobSeek', 'jobseek_id', (array)($payload['first_job_seek_ids'] ?? []));
    $deletedJobFair = deleteByIds($conn, 'jobFair', 'jobfair_id', (array)($payload['jobfair_ids'] ?? []));
    $deletedSPESEmployment = deleteByIds($conn, 'spes_employment', 'employment_id', (array)($payload['spes_employment_ids'] ?? []));

    $deletedSPES = deleteByIds($conn, 'spes', 'spes_id', (array)($payload['spes_ids'] ?? []));
    // Fallback for older payloads that may not include spes_ids but include beneficiary_ids.
    if ($deletedSPES === 0) {
        $benefIds = array_values(array_unique(array_filter(array_map('intval', (array)($payload['beneficiary_ids'] ?? [])), static fn($id) => $id > 0)));
        if (!empty($benefIds)) {
            $ph = implode(',', array_fill(0, count($benefIds), '?'));
            $types = str_repeat('i', count($benefIds));
            $selSpes = $conn->prepare("SELECT spes_id FROM spes WHERE benef_id IN ({$ph})");
            $selSpes->bind_param($types, ...$benefIds);
            $selSpes->execute();
            $resSpes = $selSpes->get_result();
            $spesIdsByBenef = [];
            while ($spesRow = $resSpes->fetch_assoc()) {
                $spesIdsByBenef[] = (int)$spesRow['spes_id'];
            }

            if (!empty($spesIdsByBenef)) {
                // Ensure child rows are removed first before deleting spes.
                $deletedSPESEmployment += deleteByIds($conn, 'spes_employment', 'spes_id', $spesIdsByBenef);
                $deletedSPES += deleteByIds($conn, 'spes', 'spes_id', $spesIdsByBenef);
            }
        }
    }

    $deletedSchools = deleteByIds($conn, 'schools', 'school_id', (array)($payload['school_ids'] ?? []));

    // Undo WIIRP imports (Work Immersion and Internship Referral Program)
    $deletedWiirp = 0;
    $wiirpIds = (array)($payload['wiirp_ids'] ?? []);
    if (!empty($wiirpIds)) {
        $wiirpTable = trim((string)($payload['wiirp_table'] ?? ''));
        if ($wiirpTable === '' || !tableExists($conn, $wiirpTable)) {
            foreach (['wiirp'] as $candidateTable) {
                if (tableExists($conn, $candidateTable)) {
                    $wiirpTable = $candidateTable;
                    break;
                }
            }
        }

        if ($wiirpTable !== '') {
            // First delete private details if present to avoid FK constraint errors
            $privateIds = (array)($payload['wiirp_private_ids'] ?? []);
            if (!empty($privateIds) && tableExists($conn, 'wiirp_private_details')) {
                deleteByIds($conn, 'wiirp_private_details', 'id', $privateIds);
            }

            $wiirpIdCol = findExistingColumn($conn, $wiirpTable, ['work_immersion_id', 'id']);
            if ($wiirpIdCol !== null) {
                $deletedWiirp = deleteByIds($conn, $wiirpTable, $wiirpIdCol, $wiirpIds);
            }
        }
    }

    // Undo WHIP beneficiary imports (Workers Hiring for Infrastructure Projects - Beneficiaries)
    $deletedWhip = 0;
    $whipIds = (array)($payload['whip_ids'] ?? []);
    if (!empty($whipIds)) {
        $whipTable = trim((string)($payload['whip_table'] ?? ''));
        if ($whipTable === '' || !tableExists($conn, $whipTable)) {
            foreach (['whip', 'whip_beneficiaries', 'whipBeneficiaries'] as $candidateTable) {
                if (tableExists($conn, $candidateTable)) {
                    $whipTable = $candidateTable;
                    break;
                }
            }
        }

        if ($whipTable !== '') {
            $whipIdCol = findExistingColumn($conn, $whipTable, ['whip_id', 'id']);
            if ($whipIdCol !== null) {
                $deletedWhip = deleteByIds($conn, $whipTable, $whipIdCol, $whipIds);
            }
        }
    }

    $deletedProjects = 0;
    $projectIds = (array)($payload['project_ids'] ?? []);
    if (!empty($projectIds)) {
        $projectTable = trim((string)($payload['project_table'] ?? ''));
        if ($projectTable === '' || !tableExists($conn, $projectTable)) {
            foreach (['projects', 'whip_projects', 'whipProject', 'whip_project', 'workers_hiring_projects', 'workers_infra_projects', 'infrastructure_projects'] as $candidateTable) {
                if (tableExists($conn, $candidateTable)) {
                    $projectTable = $candidateTable;
                    break;
                }
            }
        }

        if ($projectTable !== '') {
            $projectIdCol = findExistingColumn($conn, $projectTable, ['project_id', 'whip_project_id', 'id']);
            if ($projectIdCol !== null) {
                $deletedProjects = deleteByIds($conn, $projectTable, $projectIdCol, $projectIds);
            }
        }
    }

    $deletedJobMatch = 0;
    $jobMatchIdCol = findExistingColumn($conn, 'jobMatch', ['jobmatch_id']);
    if ($jobMatchIdCol !== null) {
        $deletedJobMatch = deleteByIds($conn, 'jobMatch', $jobMatchIdCol, (array)($payload['jobmatch_ids'] ?? []));
    }

    // Fallback: if id-column-based delete did not run, remove by inserted beneficiary ids.
    if ($deletedJobMatch === 0) {
        $benefIds = array_values(array_unique(array_filter(array_map('intval', (array)($payload['beneficiary_ids'] ?? [])), static fn($id) => $id > 0)));
        if (!empty($benefIds)) {
            $ph = implode(',', array_fill(0, count($benefIds), '?'));
            $types = str_repeat('i', count($benefIds));
            $sql = "DELETE FROM jobMatch WHERE benef_id IN ({$ph})";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$benefIds);
            $stmt->execute();
            $deletedJobMatch = $stmt->affected_rows;
        }
    }

    $deletedDocs = deleteByIds($conn, 'docs_benef', 'document_id', (array)($payload['docs_ids'] ?? []));
    $deletedBenef = deleteByIds($conn, 'beneficiaries', 'benef_id', (array)($payload['beneficiary_ids'] ?? []));

    // Delete accreditation records first, then employers (to avoid FK conflicts).
    $deletedAccreditations = deleteByIds($conn, 'employers_accreditations', 'accreditation_id', (array)($payload['accreditation_ids'] ?? []));

    $deletedEmployers = 0;
    $employerIds = array_values(array_unique(array_filter(array_map('intval', (array)($payload['employer_ids'] ?? [])), static fn($id) => $id > 0)));
    foreach ($employerIds as $employerId) {
        $stillUsed = false;
        foreach (['jobMatch', 'jobFair', 'firstJobSeek', 'spes_employment', 'projects', 'whip_projects', 'whipProject', 'whip_project', 'workers_hiring_projects', 'workers_infra_projects', 'infrastructure_projects'] as $refTable) {
            $companyIdCol = findExistingColumn($conn, $refTable, ['company_id']);
            if ($companyIdCol === null) {
                continue;
            }

            $check = $conn->prepare(sprintf('SELECT 1 FROM `%s` WHERE `%s` = ? LIMIT 1', $refTable, $companyIdCol));
            $check->bind_param('i', $employerId);
            $check->execute();
            if ((bool)$check->get_result()->fetch_assoc()) {
                $stillUsed = true;
                break;
            }
        }

        if (!$stillUsed) {
            $delEmployer = $conn->prepare('DELETE FROM employers WHERE company_id = ?');
            $delEmployer->bind_param('i', $employerId);
            $delEmployer->execute();
            $deletedEmployers += $delEmployer->affected_rows;
        }
    }

    $batchId = (int)($payload['batch_id'] ?? 0);
    $deletedBatch = 0;
    if ($batchId > 0) {
        $delBatch = $conn->prepare('DELETE FROM import_batches WHERE batch_id = ?');
        $delBatch->bind_param('i', $batchId);
        $delBatch->execute();
        $deletedBatch = $delBatch->affected_rows;
    }

    $conn->commit();

    unset($_SESSION['import_undo'][$token]);

    echo json_encode([
        'success' => true,
        'deleted' => [
            'first_job_seek' => $deletedFirstJobSeek,
            'jobfair' => $deletedJobFair,
            'spes_employment' => $deletedSPESEmployment,
            'spes' => $deletedSPES,
            'schools' => $deletedSchools,
            'jobmatch' => $deletedJobMatch,
            'wiirp' => $deletedWiirp,
            'whip' => $deletedWhip,
            'projects' => $deletedProjects,
            'docs' => $deletedDocs,
            'beneficiaries' => $deletedBenef,
            'employers' => $deletedEmployers,
            'batches' => $deletedBatch,
        ],
        'message' => 'Last import was successfully undone.',
    ]);
} catch (Throwable $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
