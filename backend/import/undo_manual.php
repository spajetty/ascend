<?php

header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/helpers/db_utils.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['state']) || !is_array($input['state'])) {
    echo json_encode(['success' => false, 'error' => 'Missing or invalid state object.']);
    exit;
}

$state = $input['state'];

try {
    $conn->begin_transaction();

    // Helper to delete an array of IDs from a table
    $deleteIds = function ($table, $idColumn, $ids) use ($conn) {
        if (empty($ids) || !is_array($ids)) return;
        $validIds = array_values(array_filter(array_map('intval', $ids), fn($id) => $id > 0));
        if (empty($validIds)) return;
        
        $placeholders = implode(',', array_fill(0, count($validIds), '?'));
        $types = str_repeat('i', count($validIds));
        
        $stmt = $conn->prepare("DELETE FROM {$table} WHERE {$idColumn} IN ({$placeholders})");
        if (!$stmt) {
            throw new RuntimeException("Failed to prepare delete statement for {$table}: " . $conn->error);
        }
        
        $bindArgs = [$types];
        foreach ($validIds as $key => $value) {
            $bindArgs[] = &$validIds[$key];
        }
        call_user_func_array([$stmt, 'bind_param'], $bindArgs);
        
        if (!$stmt->execute()) {
            throw new RuntimeException("Failed to execute delete for {$table}: " . $stmt->error);
        }
    };

    // Note: We deliberately skip 'createdEmployerIds' as per user request to keep new companies.

    // 1. Delete dependent program rows first
    if (!empty($state['insertedJobMatchIds'])) {
        $deleteIds('jobmatch', 'jobmatch_id', $state['insertedJobMatchIds']);
    }
    if (!empty($state['insertedJobFairIds'])) {
        $deleteIds('jobfair', 'jobfair_id', $state['insertedJobFairIds']);
    }
    if (!empty($state['insertedWhipIds'])) {
        $whipTable = !empty($state['insertedWhipTable']) ? (string)$state['insertedWhipTable'] : 'whip';
        $whipIdColumn = tableHasColumn($conn, $whipTable, 'whip_id') ? 'whip_id' : 'id';
        $deleteIds($whipTable, $whipIdColumn, $state['insertedWhipIds']);
    }
    if (!empty($state['insertedActivityHistoryIds'])) {
        $deleteIds('beneficiary_activity_history', 'history_id', $state['insertedActivityHistoryIds']);
    }
    if (!empty($state['insertedFirstJobSeekIds'])) {
        $deleteIds('firstjobseek', 'first_job_seek_id', $state['insertedFirstJobSeekIds']);
    }
    if (!empty($state['insertedSpesIds'])) {
        $deleteIds('spes', 'spes_id', $state['insertedSpesIds']);
    }
    if (!empty($state['insertedWiirpAssignIds'])) {
        $deleteIds('wiirp_assignment_details', 'id', $state['insertedWiirpAssignIds']);
    }
    if (!empty($state['insertedWiirpIds'])) {
        $deleteIds('wiirp', 'work_immersion_id', $state['insertedWiirpIds']);
    }
    if (!empty($state['insertedProjectIds'])) {
        $projectTable = !empty($state['insertedProjectTable']) ? (string)$state['insertedProjectTable'] : 'projects';
        $projectIdColumn = tableHasColumn($conn, $projectTable, 'project_id') ? 'project_id' : 'id';
        $deleteIds($projectTable, $projectIdColumn, $state['insertedProjectIds']);
    }
    if (!empty($state['insertedAccreditationIds'])) {
        $deleteIds('employers_accreditations', 'accreditation_id', $state['insertedAccreditationIds']);
    }
    
    // 2. Delete docs related to beneficiary
    if (!empty($state['insertedDocIds'])) {
        $deleteIds('docs_benef', 'document_id', $state['insertedDocIds']);
    }

    // 3. Delete the newly created beneficiary record (if any)
    if (!empty($state['insertedBenefIds'])) {
        $deleteIds('beneficiaries', 'benef_id', $state['insertedBenefIds']);
    }

    $conn->commit();
    echo json_encode(['success' => true]);

} catch (Throwable $e) {
    if (isset($conn) && $conn->ping()) {
        $conn->rollback();
    }
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
