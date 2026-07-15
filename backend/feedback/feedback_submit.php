<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';

$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$details = isset($_POST['details']) ? trim($_POST['details']) : '';
$related_page = isset($_POST['related_page']) ? trim($_POST['related_page']) : '';

if (empty($type) || empty($subject) || empty($details)) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields.']);
    exit;
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("INSERT INTO `feedback` (`type`, `subject`, `details`, `related_page`) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("ssss", $type, $subject, $details, $related_page);
    if (!$stmt->execute()) {
        throw new Exception("Execute failed: " . $stmt->error);
    }
    
    $feedback_id = $stmt->insert_id;
    $stmt->close();
    
    // Process images
    if (!empty($_FILES['images'])) {
        $uploadDir = __DIR__ . '/../uploads/feedback/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $files = $_FILES['images'];
        $numFiles = count($files['name']);
        
        for ($i = 0; $i < $numFiles; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $name = basename($files['name'][$i]);
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                // Basic validation
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $newName = uniqid('fb_') . '_' . time() . '.' . $ext;
                    $destPath = $uploadDir . $newName;
                    
                    if (move_uploaded_file($tmpName, $destPath)) {
                        // Insert into DB
                        $relPath = 'uploads/feedback/' . $newName;
                        $imgStmt = $conn->prepare("INSERT INTO `feedback_images` (`feedback_id`, `image_path`) VALUES (?, ?)");
                        if ($imgStmt) {
                            $imgStmt->bind_param("is", $feedback_id, $relPath);
                            $imgStmt->execute();
                            $imgStmt->close();
                        }
                    }
                }
            }
        }
    }
    
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
