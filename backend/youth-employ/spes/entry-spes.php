<?php
require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USER'] ?? 'root';
$pass = $_ENV['DB_PASS'] ?? '';
$db   = $_ENV['DB_NAME'] ?? null;

if (!$db) { echo json_encode(['success' => false, 'error' => 'Missing DB_NAME']); exit; }

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) { echo json_encode(['success' => false, 'error' => $conn->connect_error]); exit; }

header('Content-Type: application/json');

function json_error(string $msg, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}
function json_ok(mixed $data = null): void {
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// ─── GET: fetch unfilled months + companies with missing vac/ref data ─────────
// Also used to get companies per month for the bulk fill modal
// ?action=unfilled&year=2026        → returns months where any company has vac=0 AND ref=0
// ?action=companies&month=5&year=2026 → returns all companies for that month with current vac/ref values
// ?action=search_companies&q=query  → search employers by name for the Excel import company matching

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    // ── Unfilled months check ────────────────────────────────────────────────
    if ($action === 'unfilled') {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        $sql = "
            SELECT
                ib.month,
                CASE ib.month
                    WHEN 1 THEN 'January'   WHEN 2 THEN 'February' WHEN 3 THEN 'March'
                    WHEN 4 THEN 'April'     WHEN 5 THEN 'May'      WHEN 6 THEN 'June'
                    WHEN 7 THEN 'July'      WHEN 8 THEN 'August'   WHEN 9 THEN 'September'
                    WHEN 10 THEN 'October'  WHEN 11 THEN 'November' WHEN 12 THEN 'December'
                    ELSE ''
                END AS month_name,
                COUNT(DISTINCT se.company_id) AS total_companies,
                SUM(CASE
                    WHEN COALESCE(jv.vacancy_male,0) = 0 AND COALESCE(jv.vacancy_female,0) = 0
                    THEN 1 ELSE 0
                END) AS unfilled_vac
            FROM import_batches ib
            INNER JOIN spes s ON s.batch_id = ib.batch_id
            INNER JOIN spes_employment se ON se.spes_id = s.spes_id
            LEFT JOIN jobvacancies jv
                ON jv.company_id = se.company_id
               AND jv.month = ib.month
               AND jv.year  = ib.year
            WHERE ib.year = ?
            GROUP BY ib.month
            HAVING unfilled_vac > 0
            ORDER BY ib.month ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $year);
        $stmt->execute();
        $res = $stmt->get_result();
        $months = [];
        while ($row = $res->fetch_assoc()) $months[] = $row;
        $stmt->close();

        json_ok($months);
    }

    // ── Companies for a specific month ───────────────────────────────────────
    if ($action === 'companies') {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : 0;
        $year  = isset($_GET['year'])  ? (int)$_GET['year']  : (int)date('Y');
        if (!$month) json_error('Missing month');

        $sql = "
            SELECT
                e.company_id,
                e.company_name,
                COALESCE(jv.vacancy_male,  0) AS vac_m,
                COALESCE(jv.vacancy_female, 0) AS vac_f
            FROM spes_employment se
            INNER JOIN spes s ON s.spes_id = se.spes_id
            INNER JOIN import_batches ib ON ib.batch_id = s.batch_id
            INNER JOIN employers e ON e.company_id = se.company_id
            LEFT JOIN jobvacancies jv
                ON jv.company_id = se.company_id
               AND jv.month = ib.month
               AND jv.year  = ib.year
            WHERE ib.month = ? AND ib.year = ?
            GROUP BY e.company_id, e.company_name, jv.vacancy_male, jv.vacancy_female
            ORDER BY e.company_name ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $month, $year);
        $stmt->execute();
        $res = $stmt->get_result();
        $companies = [];
        while ($row = $res->fetch_assoc()) $companies[] = $row;
        $stmt->close();

        json_ok($companies);
    }

    // ── Company name search (for Excel import matching) ──────────────────────
    if ($action === 'search_companies') {
        $q = isset($_GET['q']) ? trim($_GET['q']) : '';
        if (strlen($q) < 2) json_ok([]);

        $like = "%$q%";
        $stmt = $conn->prepare("
            SELECT company_id, company_name
            FROM employers
            WHERE company_name LIKE ?
            ORDER BY company_name ASC
            LIMIT 10
        ");
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $res = $stmt->get_result();
        $results = [];
        while ($row = $res->fetch_assoc()) $results[] = $row;
        $stmt->close();
        json_ok($results);
    }

    json_error('Unknown action', 400);
}

// ─── POST: bulk save vacancies for a month ────────────────────────────────────
// Body: { month, year, entries: [{ company_id, vac_m, vac_f }] }

if ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);

    $month   = isset($body['month'])   ? (int)$body['month']   : 0;
    $year    = isset($body['year'])    ? (int)$body['year']     : 0;
    $entries = $body['entries'] ?? [];

    if (!$month || !$year)         json_error('Missing month or year');
    if (!is_array($entries) || !count($entries)) json_error('No entries provided');

    // Get the logged-in user id from session (auth-check.php sets $_SESSION['user_id'])
    $userId = $_SESSION['user_id'] ?? null;

    $conn->begin_transaction();
    try {
        $saved = 0;
        foreach ($entries as $entry) {
            $company_id = (int)($entry['company_id'] ?? 0);
            $vac_m      = max(0, (int)($entry['vac_m'] ?? 0));
            $vac_f      = max(0, (int)($entry['vac_f'] ?? 0));

            if (!$company_id) continue;

            // UPSERT: update if exists, insert if not
            $stmt = $conn->prepare("
                INSERT INTO jobvacancies (user_id, company_id, vacancy_male, vacancy_female, month, year)
                VALUES (?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    vacancy_male   = VALUES(vacancy_male),
                    vacancy_female = VALUES(vacancy_female),
                    user_id        = VALUES(user_id)
            ");
            $stmt->bind_param('iiiiii', $userId, $company_id, $vac_m, $vac_f, $month, $year);
            $stmt->execute();
            $saved++;
            $stmt->close();
        }
        $conn->commit();
        json_ok(['saved' => $saved]);
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Save failed: ' . $e->getMessage());
    }
}

json_error('Method not allowed', 405);