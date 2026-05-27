<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

// Expected POST: jobseek_id, benef_id, occ_permit (0|1), health_card (0|1)
$jobseek_id = isset($_POST['jobseek_id']) ? (int)$_POST['jobseek_id'] : 0;
$benef_id = isset($_POST['benef_id']) ? (int)$_POST['benef_id'] : 0;
$occ_permit = isset($_POST['occ_permit']) ? (int)$_POST['occ_permit'] : 0;
$health_card = isset($_POST['health_card']) ? (int)$_POST['health_card'] : 0;

error_log("UPDATE ISSUANCE: jobseek_id=$jobseek_id, benef_id=$benef_id, occ_permit=$occ_permit, health_card=$health_card");

if ($benef_id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid beneficiary id']);
    exit;
}

try {
    $existingId = 0;

    if ($jobseek_id > 0) {
        $check = $conn->prepare('SELECT jobseek_id FROM firstjobseek WHERE jobseek_id = ? AND benef_id = ?');
        if (!$check) {
            throw new Exception('Prepare check failed: ' . $conn->error);
        }

        $check->bind_param('ii', $jobseek_id, $benef_id);
        if (!$check->execute()) {
            throw new Exception('Execute check failed: ' . $check->error);
        }

        $exists = $check->get_result()->fetch_assoc();
        $check->close();

        if ($exists) {
            $existingId = (int)$exists['jobseek_id'];
        }
    } else {
        $check = $conn->prepare('SELECT jobseek_id FROM firstjobseek WHERE benef_id = ? LIMIT 1');
        if (!$check) {
            throw new Exception('Prepare check failed: ' . $conn->error);
        }

        $check->bind_param('i', $benef_id);
        if (!$check->execute()) {
            throw new Exception('Execute check failed: ' . $check->error);
        }

        $exists = $check->get_result()->fetch_assoc();
        $check->close();

        if ($exists) {
            $existingId = (int)$exists['jobseek_id'];
        }
    }

    if ($existingId > 0) {
        $sql = "UPDATE firstjobseek 
                SET occ_permit = ?, health_card = ? 
                WHERE jobseek_id = ? AND benef_id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare update failed: ' . $conn->error);
        }

        $stmt->bind_param('iiii', $occ_permit, $health_card, $existingId, $benef_id);

        if (!$stmt->execute()) {
            throw new Exception('Execute update failed: ' . $stmt->error);
        }

        error_log("UPDATE ISSUANCE: affected_rows=" . $stmt->affected_rows);
        $stmt->close();

        echo json_encode(['success' => true, 'message' => 'Issuance status updated successfully', 'jobseek_id' => $existingId]);
        
    } else {
        $stmt = $conn->prepare('INSERT INTO firstjobseek (benef_id, occ_permit, health_card) VALUES (?, ?, ?)');
        if (!$stmt) {
            throw new Exception('Prepare insert failed: ' . $conn->error);
        }

        $stmt->bind_param('iii', $benef_id, $occ_permit, $health_card);
        if (!$stmt->execute()) {
            throw new Exception('Execute insert failed: ' . $stmt->error);
        }

        $newId = (int)$conn->insert_id;
        error_log("UPDATE ISSUANCE: inserted jobseek_id=$newId");
        $stmt->close();

        echo json_encode(['success' => true, 'message' => 'Issuance record created successfully', 'jobseek_id' => $newId]);
    }

} catch (Exception $e) {
    error_log("UPDATE ISSUANCE ERROR: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
