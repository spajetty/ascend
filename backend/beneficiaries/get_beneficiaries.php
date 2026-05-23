<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/../../backend/import/helpers/program_utils.php';
require_once __DIR__ . '/../../backend/import/helpers/followup_utils.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$response = ['success' => false, 'data' => [], 'stats' => [], 'total' => 0];

try {
    // ── Pagination / filter params ──────────────────────────────────────────
    $page      = max(1, (int)($_GET['page']    ?? 1));
    $limit     = min(200, max(1, (int)($_GET['limit']   ?? 200)));
    $search    = trim($_GET['search']   ?? '');
    $sectionF  = trim($_GET['section']  ?? '');
    $programF  = trim($_GET['program']  ?? '');
    $statusF   = trim($_GET['status']   ?? '');
    $offset    = ($page - 1) * $limit;

    $pendingFollowup = isset($_SESSION['user_id'])
        ? getLatestPendingImportFollowupForUser($conn, (int)$_SESSION['user_id'])
        : null;
    $pendingBatchId = $pendingFollowup ? (int)($pendingFollowup['batch_id'] ?? 0) : 0;

    // ── Build WHERE clause ──────────────────────────────────────────────────
    $where  = [];
    $params = [];
    $types  = '';

    $appendWhere = function(string $clause, array $clauseParams = [], string $clauseTypes = '') use (&$where, &$params, &$types): void {
        $where[] = $clause;
        if ($clauseParams) {
            $params = array_merge($params, $clauseParams);
            $types .= $clauseTypes;
        }
    };

    if ($search !== '') {
        $like = '%' . $search . '%';
        $likeWild = '%' . preg_replace('/\s+/', '%', $search) . '%';
        $where[]  = '(b.first_name LIKE ? OR b.last_name LIKE ? OR b.middle_name LIKE ? OR b.contact LIKE ? OR b.email LIKE ? OR s.name LIKE ? OR p.name LIKE ? OR CONCAT_WS(\' \', b.first_name, b.middle_name, b.last_name) LIKE ? OR CONCAT_WS(\' \', b.first_name, b.last_name) LIKE ?)';
        $params   = array_merge($params, [$like, $like, $like, $like, $like, $like, $like, $likeWild, $likeWild]);
        $types   .= 'sssssssss';
    }
    if ($sectionF !== '') {
        $where[]  = 's.name = ?';
        $params[] = $sectionF;
        $types   .= 's';
    }
    if ($programF !== '') {
        $where[]  = 'p.name = ?';
        $params[] = $programF;
        $types   .= 's';
    }
    if ($statusF !== '') {
        $appendWhere('b.classification = ?', [$statusF], 's');
    }

    if ($pendingBatchId > 0) {
        if (tableExists($conn, 'jobMatch') && tableHasColumn($conn, 'jobMatch', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM jobMatch jm WHERE jm.benef_id = b.benef_id AND jm.batch_id = ?)', [$pendingBatchId], 'i');
        }
        if ((tableExists($conn, 'jobfair') || tableExists($conn, 'jobFair'))) {
            $jobFairTable = tableExists($conn, 'jobfair') ? 'jobfair' : 'jobFair';
            if (tableHasColumn($conn, $jobFairTable, 'batch_id')) {
                $appendWhere('NOT EXISTS (SELECT 1 FROM `' . $jobFairTable . '` jf WHERE jf.benef_id = b.benef_id AND jf.batch_id = ?)', [$pendingBatchId], 'i');
            }
        }

        if (tableExists($conn, 'firstJobSeek') && tableHasColumn($conn, 'firstJobSeek', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM firstJobSeek fjs WHERE fjs.benef_id = b.benef_id AND fjs.batch_id = ?)', [$pendingBatchId], 'i');
        }

        if (tableExists($conn, 'spes') && tableHasColumn($conn, 'spes', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM spes s2 WHERE s2.benef_id = b.benef_id AND s2.batch_id = ?)', [$pendingBatchId], 'i');
        }

        if (tableExists($conn, 'wiirp') && tableHasColumn($conn, 'wiirp', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM wiirp w WHERE w.benef_id = b.benef_id AND w.batch_id = ?)', [$pendingBatchId], 'i');
        }

        if (tableExists($conn, 'gip') && tableHasColumn($conn, 'gip', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM gip g WHERE g.benef_id = b.benef_id AND g.batch_id = ?)', [$pendingBatchId], 'i');
        }

        $whipSchema = resolveWhipTableSchema($conn);
        $whipTable = $whipSchema['table'] ?? null;
        $whipBenefCol = $whipSchema['benef_id_col'] ?? null;
        $whipBatchCol = $whipSchema['batch_id_col'] ?? null;
        if ($whipTable && $whipBenefCol && $whipBatchCol) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM `' . $whipTable . '` wh WHERE wh.`' . $whipBenefCol . '` = b.benef_id AND wh.`' . $whipBatchCol . '` = ?)', [$pendingBatchId], 'i');
        }
    }

    $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    // ── Count total matching rows ────────────────────────────────────────────
    $countSql  = "
        SELECT COUNT(*) AS total
        FROM beneficiaries b
        LEFT JOIN programs p ON p.program_id = b.program_id
        LEFT JOIN sections s ON s.section_id = p.section_id
        $whereSql
    ";
    $countStmt = $conn->prepare($countSql);
    if ($params) {
        $countStmt->bind_param($types, ...$params);
    }
    $countStmt->execute();
    $totalRows = (int)$countStmt->get_result()->fetch_assoc()['total'];
    $countStmt->close();

    // ── Main query ───────────────────────────────────────────────────────────
    $sql = "
        SELECT
            b.benef_id,
            b.first_name,
            b.middle_name,
            b.last_name,
            b.suffix,
            b.sex,
            b.civil_status,
            b.dob,
            b.contact,
            b.email,
            b.classification,
            b.house_no,
            b.barangay,
            b.district,
            b.city,
            b.created_at,
            b.is_4ps,
            b.is_pwd,
            b.is_ofw_dependent,
            b.ps4_id_no,
            p.name      AS program_name,
            p.program_id,
            s.name      AS section_name,
            s.section_id,
            (
                SELECT bav.date_of_record
                FROM beneficiary_activity_history bav
                WHERE bav.benef_id = b.benef_id
                  AND bav.classification = 'PESO_VISIT'
                ORDER BY bav.date_of_record DESC, bav.created_at DESC, bav.history_id DESC
                LIMIT 1
            ) AS last_visit,
            (
                SELECT COUNT(*)
                FROM beneficiary_activity_history bav
                WHERE bav.benef_id = b.benef_id
                  AND bav.classification = 'PESO_VISIT'
            ) AS visit_count
        FROM beneficiaries b
        LEFT JOIN programs p ON p.program_id = b.program_id
        LEFT JOIN sections s ON s.section_id = p.section_id
        $whereSql
        ORDER BY b.benef_id DESC
        LIMIT ? OFFSET ?
    ";

    $queryParams = array_merge($params, [$limit, $offset]);
    $queryTypes  = $types . 'ii';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($queryTypes, ...$queryParams);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Build address from parts
        $addressParts = array_filter([
            $row['house_no'],
            $row['barangay'],
            $row['district'],
            $row['city'],
        ]);
        $row['address'] = $addressParts ? implode(', ', $addressParts) : '—';

        // Calculate age from DOB
        $row['age'] = null;
        if ($row['dob']) {
            $row['age'] = (new DateTime())->diff(new DateTime($row['dob']))->y;
        }

        $row['dob_formatted'] = $row['dob']
            ? (new DateTime($row['dob']))->format('F j, Y')
            : '—';

        $row['last_visit'] = $row['last_visit']
            ? (new DateTime($row['last_visit']))->format('F j, Y')
            : '—';

        $vc = (int)($row['visit_count'] ?? 0);
        $row['visit_label'] = $vc > 0 ? ordinalLabel($vc) : '—';

        $data[] = $row;
    }
    $stmt->close();

    // ── Stats: count by classification ──────────────────────────────────────
    $statsSql  = "
        SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN LOWER(COALESCE(classification, '')) LIKE '%hired%' OR LOWER(COALESCE(classification, '')) LIKE '%placed%' OR LOWER(COALESCE(classification, '')) LIKE '%hots%' THEN 1 ELSE 0 END) AS hired,
            SUM(CASE WHEN LOWER(COALESCE(classification, '')) LIKE '%refer%' THEN 1 ELSE 0 END) AS referred,
            SUM(CASE WHEN LOWER(COALESCE(classification, '')) LIKE '%register%' THEN 1 ELSE 0 END) AS registered
        FROM beneficiaries b
        LEFT JOIN programs p ON p.program_id = b.program_id
        LEFT JOIN sections s ON s.section_id = p.section_id
        $whereSql
    ";
    $statsStmt = $conn->prepare($statsSql);
    if ($params) {
        $statsStmt->bind_param($types, ...$params);
    }
    $statsStmt->execute();
    $statsRow = $statsStmt->get_result()->fetch_assoc();
    $statsStmt->close();

    $response['success'] = true;
    $response['data']    = $data;
    $response['total']   = $totalRows;
    $response['stats']   = [
        'total'      => (int)$statsRow['total'],
        'hired'      => (int)$statsRow['hired'],
        'referred'   => (int)$statsRow['referred'],
        'registered' => (int)$statsRow['registered'],
    ];

} catch (Throwable $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

// ── Helper ───────────────────────────────────────────────────────────────────
function ordinalLabel(int $n): string {
    $s = ['th','st','nd','rd'];
    $v = $n % 100;
    return $n . ($s[($v - 20) % 10] ?? $s[$v] ?? $s[0]);
}
