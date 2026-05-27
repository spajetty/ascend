<?php
require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? null;

if (!$db) {
    echo json_encode(['success' => false, 'error' => 'Missing DB_NAME in .env']);
    exit;
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed: ' . $conn->connect_error]);
    exit;
}

set_error_handler(function (int $errno, string $errstr) {
    if (!headers_sent()) header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => "PHP Error ($errno): $errstr"]);
    exit;
});

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

function json_error(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function json_ok(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

// ─── GET ?year=2026&search=company ───────────────────────────────────────────
if ($method === 'GET') {

    $year   = isset($_GET['year'])   ? (int) $_GET['year']   : (int) date('Y');
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Available years
    $res = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN spes s ON s.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");
    $years = [];
    while ($row = $res->fetch_assoc()) $years[] = (int) $row['year'];
    $currentYear = (int) date('Y');
    $years = array_unique(array_merge($years, [$currentYear, $year]));
    rsort($years);

    // ── Main SPES rows ───────────────────────────────────────────────────────
    //
    // GROUP BY employer (company) + month so each row = one employer per month,
    // matching the screenshot table format.
    //
    // Registered  = beneficiaries with classification = 'Registered' for this employer/month
    // Referred    = beneficiaries with classification = 'Referred'
    // Placed      = beneficiaries who have a spes_employment record (have a contract)
    // Job Vac     = from jobvacancies table for that company/month/year
    // SPES Baby   = beneficiaries who appear in a PREVIOUS batch of spes (repeat availees)
    // 4Ps / PWD   = from beneficiaries flags, counted among Placed
    // ─────────────────────────────────────────────────────────────────────────

    $sql = "
        SELECT
            se.company_id,
            ib.month,
            ib.year,
            CASE ib.month
                WHEN 1  THEN 'January'   WHEN 2  THEN 'February'  WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'     WHEN 5  THEN 'May'        WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'      WHEN 8  THEN 'August'     WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'   WHEN 11 THEN 'November'   WHEN 12 THEN 'December'
                ELSE ''
            END                                                         AS month_reported,

            e.company_name                                              AS employer,
            MIN(se.start_of_contract)                                   AS start_of_contract,
            MAX(se.end_of_contract)                                     AS end_of_contract,
            MAX(se.days)                                                AS days,

            -- Registered: everyone in this batch for this company (total applicants/registrants)
            SUM(CASE WHEN b.sex = 'Male'   THEN 1 ELSE 0 END) AS reg_m,
            SUM(CASE WHEN b.sex = 'Female' THEN 1 ELSE 0 END) AS reg_f,

            -- Referred: SPES beneficiaries with a REFERRAL activity record
            --           for this company in the same batch month/year
            COALESCE((
                SELECT COUNT(*)
                FROM beneficiary_activity_history bah
                INNER JOIN spes s2  ON s2.benef_id  = bah.benef_id
                INNER JOIN import_batches ib2 ON ib2.batch_id = s2.batch_id
                INNER JOIN beneficiaries b2   ON b2.benef_id  = bah.benef_id
                WHERE bah.classification = 'REFERRAL'
                  AND bah.company_id = se.company_id
                  AND ib2.month = ib.month
                  AND ib2.year  = ib.year
                  AND b2.sex    = 'Male'
            ), 0) AS ref_m,
            COALESCE((
                SELECT COUNT(*)
                FROM beneficiary_activity_history bah
                INNER JOIN spes s2  ON s2.benef_id  = bah.benef_id
                INNER JOIN import_batches ib2 ON ib2.batch_id = s2.batch_id
                INNER JOIN beneficiaries b2   ON b2.benef_id  = bah.benef_id
                WHERE bah.classification = 'REFERRAL'
                  AND bah.company_id = se.company_id
                  AND ib2.month = ib.month
                  AND ib2.year  = ib.year
                  AND b2.sex    = 'Female'
            ), 0) AS ref_f,

            -- Placed: has a spes_employment record for this company (contract exists)
            SUM(CASE WHEN b.sex = 'Male'   THEN 1 ELSE 0 END) AS placed_m,
            SUM(CASE WHEN b.sex = 'Female' THEN 1 ELSE 0 END) AS placed_f,

            -- Job Vacancies: from jobvacancies table for this company + month + year
            COALESCE((
                SELECT SUM(jv.vacancy_male)
                FROM jobvacancies jv
                WHERE jv.company_id = se.company_id
                  AND jv.month = ib.month
                  AND jv.year  = ib.year
            ), 0) AS vac_m,
            COALESCE((
                SELECT SUM(jv.vacancy_female)
                FROM jobvacancies jv
                WHERE jv.company_id = se.company_id
                  AND jv.month = ib.month
                  AND jv.year  = ib.year
            ), 0) AS vac_f,

            -- SPES Baby: placed beneficiaries who have appeared in a PRIOR spes batch
            SUM(CASE WHEN b.sex = 'Male'   AND EXISTS (
                SELECT 1 FROM spes s2
                INNER JOIN import_batches ib2 ON ib2.batch_id = s2.batch_id
                WHERE s2.benef_id = s.benef_id
                  AND s2.spes_id  <> s.spes_id
                  AND (ib2.year < ib.year OR (ib2.year = ib.year AND ib2.month < ib.month))
            ) THEN 1 ELSE 0 END) AS spes_baby_m,
            SUM(CASE WHEN b.sex = 'Female' AND EXISTS (
                SELECT 1 FROM spes s2
                INNER JOIN import_batches ib2 ON ib2.batch_id = s2.batch_id
                WHERE s2.benef_id = s.benef_id
                  AND s2.spes_id  <> s.spes_id
                  AND (ib2.year < ib.year OR (ib2.year = ib.year AND ib2.month < ib.month))
            ) THEN 1 ELSE 0 END) AS spes_baby_f,

            -- 4Ps: among placed beneficiaries
            SUM(CASE WHEN b.is_4ps = 1 AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS fourps_m,
            SUM(CASE WHEN b.is_4ps = 1 AND b.sex = 'Female' THEN 1 ELSE 0 END) AS fourps_f,

            -- PWD: among placed beneficiaries
            SUM(CASE WHEN b.is_pwd = 1 AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS pwd_m,
            SUM(CASE WHEN b.is_pwd = 1 AND b.sex = 'Female' THEN 1 ELSE 0 END) AS pwd_f,

            -- Use company_id + month as the row identifier for edit/delete
            CONCAT(se.company_id, '_', ib.month, '_', ib.year)         AS spes_id

        FROM spes_employment se

        INNER JOIN spes s
            ON s.spes_id = se.spes_id

        INNER JOIN import_batches ib
            ON ib.batch_id = s.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = s.benef_id

        INNER JOIN employers e
            ON e.company_id = se.company_id

        WHERE ib.year = ?
    ";

    $params = [$year];
    $types  = 'i';

    if ($search !== '') {
        $sql   .= " AND e.company_name LIKE ?";
        $types   .= 's';
        $params[] = "%$search%";
    }

    $sql .= "
        GROUP BY se.company_id, e.company_name, ib.month, ib.year
        ORDER BY ib.month ASC, e.company_name ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $row['vac_total'] = (int)$row['vac_m'] + (int)$row['vac_f'];
        $rows[] = $row;
    }
    $stmt->close();

    // ── Monthly LGU / Private placed summary ────────────────────────────────
    // Groups by month only (not batch_id) to avoid duplicate month rows.
    // category comes from spes_employment.category ('lgu' or 'private').
    $sumSql = "
        SELECT
            ib.month,
            CASE ib.month
                WHEN 1  THEN 'January'   WHEN 2  THEN 'February'  WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'     WHEN 5  THEN 'May'        WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'      WHEN 8  THEN 'August'     WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'   WHEN 11 THEN 'November'   WHEN 12 THEN 'December'
                ELSE ''
            END AS month_reported,
            SUM(CASE WHEN se.category = 'lgu'     AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS lgu_m,
            SUM(CASE WHEN se.category = 'lgu'     AND b.sex = 'Female' THEN 1 ELSE 0 END) AS lgu_f,
            SUM(CASE WHEN se.category = 'private' AND b.sex = 'Male'   THEN 1 ELSE 0 END) AS priv_m,
            SUM(CASE WHEN se.category = 'private' AND b.sex = 'Female' THEN 1 ELSE 0 END) AS priv_f
        FROM import_batches ib
        INNER JOIN spes s             ON s.batch_id   = ib.batch_id
        INNER JOIN beneficiaries b    ON b.benef_id   = s.benef_id
        INNER JOIN spes_employment se ON se.spes_id   = s.spes_id
        WHERE ib.year = ?
        GROUP BY ib.month
        ORDER BY ib.month ASC
    ";
    $stmt2 = $conn->prepare($sumSql);
    if (!$stmt2) json_error('Summary query prepare failed: ' . $conn->error);
    $stmt2->bind_param('i', $year);
    $stmt2->execute();
    $sumResult = $stmt2->get_result();
    $summary = [];
    while ($row = $sumResult->fetch_assoc()) $summary[] = $row;
    $stmt2->close();

    // ── Card totals ──────────────────────────────────────────────────────────
    $totals = [
        'registered' => 0, 'referred' => 0, 'placed'   => 0,
        'vacancies'  => 0, 'spes_baby' => 0, 'fourps'  => 0, 'pwd' => 0,
    ];
    foreach ($rows as $r) {
        $totals['registered'] += (int)$r['reg_m']       + (int)$r['reg_f'];
        $totals['referred']   += (int)$r['ref_m']       + (int)$r['ref_f'];
        $totals['placed']     += (int)$r['placed_m']    + (int)$r['placed_f'];
        $totals['vacancies']  += (int)$r['vac_total'];
        $totals['spes_baby']  += (int)$r['spes_baby_m'] + (int)$r['spes_baby_f'];
        $totals['fourps']     += (int)$r['fourps_m']    + (int)$r['fourps_f'];
        $totals['pwd']        += (int)$r['pwd_m']       + (int)$r['pwd_f'];
    }

    json_ok(['rows' => $rows, 'summary' => $summary, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — edit row (month/year only, since employer rows are derived) ────────
if ($method === 'PUT') {

    $body = json_decode(file_get_contents('php://input'), true);

    // spes_id is now "company_id_month_year" composite key
    $raw = $body['spes_id'] ?? '';
    if (!$raw) json_error('Missing spes_id');

    $parts = explode('_', $raw);
    if (count($parts) !== 3) json_error('Invalid spes_id format');
    [$company_id, $old_month, $old_year] = [(int)$parts[0], (int)$parts[1], (int)$parts[2]];

    $new_month = isset($body['month']) ? (int)$body['month'] : $old_month;
    $new_year  = isset($body['year'])  ? (int)$body['year']  : $old_year;

    if ($new_month < 1 || $new_month > 12) json_error('Invalid month');
    if ($new_year  < 2000)                  json_error('Invalid year');

    // Update the import_batch month/year for all batches linked to this company + old month/year
    $stmt = $conn->prepare("
        UPDATE import_batches ib
        INNER JOIN spes s ON s.batch_id = ib.batch_id
        INNER JOIN spes_employment se ON se.spes_id = s.spes_id
        SET ib.month = ?, ib.year = ?
        WHERE se.company_id = ?
          AND ib.month = ?
          AND ib.year  = ?
    ");
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);
    $stmt->bind_param('iiiii', $new_month, $new_year, $company_id, $old_month, $old_year);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?company_id=X&month=Y&year=Z ─────────────────────────────────────
if ($method === 'DELETE') {

    // Accept either composite id string or separate params
    $idStr = isset($_GET['id']) ? trim($_GET['id']) : '';
    if ($idStr && strpos($idStr, '_') !== false) {
        $parts      = explode('_', $idStr);
        $company_id = (int)($parts[0] ?? 0);
        $month      = (int)($parts[1] ?? 0);
        $year       = (int)($parts[2] ?? 0);
    } else {
        $company_id = isset($_GET['company_id']) ? (int)$_GET['company_id'] : 0;
        $month      = isset($_GET['month'])      ? (int)$_GET['month']      : 0;
        $year       = isset($_GET['year'])       ? (int)$_GET['year']       : 0;
    }

    if (!$company_id || !$month || !$year) json_error('Missing or invalid id');

    $conn->begin_transaction();
    try {
        // Find all batch_ids that have spes records placed at this company for this month/year
        $find = $conn->prepare("
            SELECT DISTINCT ib.batch_id
            FROM import_batches ib
            INNER JOIN spes s ON s.batch_id = ib.batch_id
            INNER JOIN spes_employment se ON se.spes_id = s.spes_id
            WHERE se.company_id = ? AND ib.month = ? AND ib.year = ?
        ");
        $find->bind_param('iii', $company_id, $month, $year);
        $find->execute();
        $res      = $find->get_result();
        $batchIds = [];
        while ($r = $res->fetch_assoc()) $batchIds[] = (int)$r['batch_id'];
        $find->close();

        if (empty($batchIds)) json_error('No matching records found', 404);

        $affected = 0;
        foreach ($batchIds as $bid) {
            $s1 = $conn->prepare("UPDATE spes SET batch_id = NULL WHERE batch_id = ?");
            $s1->bind_param('i', $bid);
            $s1->execute();
            $s1->close();

            $s2 = $conn->prepare("DELETE FROM import_batches WHERE batch_id = ?");
            $s2->bind_param('i', $bid);
            $s2->execute();
            $affected += $s2->affected_rows;
            $s2->close();
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Delete failed: ' . $e->getMessage());
    }

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);