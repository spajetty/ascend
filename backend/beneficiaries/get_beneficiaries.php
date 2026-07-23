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
    $categoryF = trim($_GET['category'] ?? '');
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
        $where[]  = '(b.first_name LIKE ? OR b.last_name LIKE ? OR b.middle_name LIKE ? OR b.contact LIKE ? OR b.email LIKE ? OR CONCAT_WS(\' \', b.first_name, b.middle_name, b.last_name) LIKE ? OR CONCAT_WS(\' \', b.first_name, b.last_name) LIKE ? OR EXISTS (SELECT 1 FROM beneficiary_programs bp JOIN programs p ON p.program_id = bp.program_id JOIN sections s ON s.section_id = p.section_id WHERE bp.benef_id = b.benef_id AND (s.name LIKE ? OR p.name LIKE ?)))';
        $params   = array_merge($params, [$like, $like, $like, $like, $like, $likeWild, $likeWild, $like, $like]);
        $types   .= 'sssssssss';
    }
    if ($sectionF !== '') {
        $where[]  = 'EXISTS (SELECT 1 FROM beneficiary_programs bp JOIN programs p ON p.program_id = bp.program_id JOIN sections s ON s.section_id = p.section_id WHERE bp.benef_id = b.benef_id AND s.name = ?)';
        $params[] = $sectionF;
        $types   .= 's';
    }
    if ($programF !== '' || $statusF !== '') {
        $conds = [];
        $p = [];
        $t = '';
        if ($programF !== '') {
            $conds[] = 'p.name = ?';
            $p[] = $programF;
            $t .= 's';
        }
        if ($statusF !== '') {
            $conds[] = 'LOWER(bp.status) = LOWER(?)';
            $p[] = $statusF;
            $t .= 's';
        }
        $condStr = implode(' AND ', $conds);
        $where[] = 'EXISTS (SELECT 1 FROM beneficiary_programs bp JOIN programs p ON p.program_id = bp.program_id WHERE bp.benef_id = b.benef_id AND ' . $condStr . ')';
        foreach ($p as $val) {
            $params[] = $val;
        }
        $types .= $t;
    }

    if ($categoryF !== '' && $programF !== '') {
        if ($programF === 'SPES') {
            $appendWhere('EXISTS (SELECT 1 FROM spes_employment se JOIN spes sp ON sp.spes_id = se.spes_id WHERE sp.benef_id = b.benef_id AND se.category = ?)', [$categoryF], 's');
        } elseif ($programF === 'Government Internship Program') {
            $appendWhere('EXISTS (SELECT 1 FROM gip g WHERE g.benef_id = b.benef_id AND g.type = ?)', [$categoryF], 's');
        } elseif ($programF === 'Work Immersion and Internship Referral Program') {
            $appendWhere('EXISTS (SELECT 1 FROM wiirp w WHERE w.benef_id = b.benef_id AND w.type = ?)', [$categoryF], 's');
        }
    }

    if ($pendingBatchId > 0) {
        if (tableExists($conn, 'jobmatch') && tableHasColumn($conn, 'jobmatch', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM jobmatch jm WHERE jm.benef_id = b.benef_id AND jm.batch_id = ?)', [$pendingBatchId], 'i');
        }
        if (tableExists($conn, 'jobfair')) {
            if (tableHasColumn($conn, 'jobfair', 'batch_id')) {
                $appendWhere('NOT EXISTS (SELECT 1 FROM jobfair jf WHERE jf.benef_id = b.benef_id AND jf.batch_id = ?)', [$pendingBatchId], 'i');
            }
        }

        if (tableExists($conn, 'firstjobseek') && tableHasColumn($conn, 'firstjobseek', 'batch_id')) {
            $appendWhere('NOT EXISTS (SELECT 1 FROM firstjobseek fjs WHERE fjs.benef_id = b.benef_id AND fjs.batch_id = ?)', [$pendingBatchId], 'i');
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
            b.spes_status,
            b.notes,
            b.created_at,
            b.is_4ps,
            b.is_pwd,
            b.is_ofw_dependent,
            b.ps4_id_no,
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

    $benefIds = array_column($data, 'benef_id');
    if (!empty($benefIds)) {
        $ph = implode(',', array_fill(0, count($benefIds), '?'));
        $progTypes = str_repeat('i', count($benefIds));
        $progSql = "
            SELECT bp.benef_id, bp.status, p.name AS program_name, p.program_id, s.name AS section_name, s.section_id
            FROM beneficiary_programs bp
            JOIN programs p ON p.program_id = bp.program_id
            JOIN sections s ON s.section_id = p.section_id
            WHERE bp.benef_id IN ($ph)
        ";
        $progStmt = $conn->prepare($progSql);
        $progStmt->bind_param($progTypes, ...$benefIds);
        $progStmt->execute();
        $progRes = $progStmt->get_result();
        
        $programsByBenef = [];
        while ($pr = $progRes->fetch_assoc()) {
            $programsByBenef[$pr['benef_id']][] = $pr;
        }
        $progStmt->close();
        
        foreach ($data as &$row) {
            $bid = $row['benef_id'];
            $row['enrollments'] = [];
            $progs = $programsByBenef[$bid] ?? [];
            
            // To ensure uniqueness of enrollments if they are in multiple batches of the same program
            $seenPrograms = [];
            
            foreach ($progs as $pr) {
                if (isset($seenPrograms[$pr['program_id']])) continue;
                $seenPrograms[$pr['program_id']] = true;
                
                $status = !empty($pr['status']) ? trim($pr['status']) : 'Registered';
                
                $row['enrollments'][] = [
                    'program_name' => $pr['program_name'],
                    'program_id' => $pr['program_id'],
                    'section_name' => $pr['section_name'],
                    'section_id' => $pr['section_id'],
                    'status' => $status
                ];
            }
            
            // For backwards compatibility in the UI before it's fully updated:
            if (!empty($row['enrollments'])) {
                $row['program_name'] = $row['enrollments'][0]['program_name'];
                $row['section_name'] = $row['enrollments'][0]['section_name'];
            } else {
                $row['program_name'] = '—';
                $row['section_name'] = '—';
            }
        }
        unset($row);
    }

    $progFilterCond = "";
    if ($programF !== '') {
        $progFilterCond = " AND p.name = '" . $conn->real_escape_string($programF) . "'";
    }

    // ── Stats: count by classification ──────────────────────────────────────
    $statsSql  = "
        SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN max_status = 3 THEN 1 ELSE 0 END) AS hired,
            SUM(CASE WHEN max_status = 2 THEN 1 ELSE 0 END) AS referred,
            SUM(CASE WHEN max_status = 1 THEN 1 ELSE 0 END) AS registered
        FROM (
            SELECT b.benef_id,
                (
                    SELECT MAX(
                        CASE 
                            WHEN LOWER(bp.status) LIKE '%hired%' OR LOWER(bp.status) LIKE '%placed%' OR LOWER(bp.status) LIKE '%hots%' THEN 3
                            WHEN LOWER(bp.status) LIKE '%refer%' THEN 2
                            WHEN LOWER(bp.status) = 'registered' THEN 1
                            ELSE 0
                        END
                    )
                    FROM beneficiary_programs bp
                    JOIN programs p ON p.program_id = bp.program_id
                    WHERE bp.benef_id = b.benef_id $progFilterCond
                ) AS max_status
            FROM beneficiaries b
            $whereSql
        ) AS status_table
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
