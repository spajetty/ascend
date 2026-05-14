<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

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

    // ── Build WHERE clause ──────────────────────────────────────────────────
    $where  = [];
    $params = [];
    $types  = '';

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
        $where[]  = 'b.classification = ?';
        $params[] = $statusF;
        $types   .= 's';
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
            -- Latest emphistory record (status + date)
            (
                SELECT eh.classification
                FROM   emphistory eh
                WHERE  eh.benef_id = b.benef_id
                ORDER  BY eh.date_of_record DESC, eh.created_at DESC
                LIMIT  1
            ) AS latest_emp_status,
            (
                SELECT DATE_FORMAT(eh.date_of_record, '%b %d, %Y')
                FROM   emphistory eh
                WHERE  eh.benef_id = b.benef_id
                ORDER  BY eh.date_of_record DESC, eh.created_at DESC
                LIMIT  1
            ) AS last_visit,
            -- Visit count (number of emphistory records)
            (
                SELECT COUNT(*)
                FROM   emphistory eh
                WHERE  eh.benef_id = b.benef_id
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
            $diff        = (new DateTime())->diff(new DateTime($row['dob']));
            $row['age']  = $diff->y;
        }

        // Formatted dates
        $row['applied_formatted'] = $row['created_at']
            ? (new DateTime($row['created_at']))->format('F j, Y')
            : '—';

        $row['dob_formatted'] = $row['dob']
            ? (new DateTime($row['dob']))->format('F j, Y')
            : '—';

        // Ordinal visit label
        $vc = (int)($row['visit_count'] ?? 0);
        $row['visit_label'] = $vc > 0 ? ordinalLabel($vc) : '—';

        $data[] = $row;
    }
    $stmt->close();

    // ── Stats: count by classification ──────────────────────────────────────
    $statsSql  = "
        SELECT
            COUNT(*)                                                           AS total,
            SUM(LOWER(classification) LIKE '%hired%'
             OR LOWER(classification) LIKE '%placed%'
             OR LOWER(classification) LIKE '%hots%')                          AS hired,
            SUM(LOWER(classification) LIKE '%refer%')                         AS referred,
            SUM(LOWER(classification) LIKE '%register%'
             OR LOWER(classification) LIKE '%issued%'
             OR LOWER(classification) LIKE '%inquir%'
             OR classification IS NULL
             OR classification = '')                                           AS registered
        FROM beneficiaries
    ";
    $statsRow = $conn->query($statsSql)->fetch_assoc();

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