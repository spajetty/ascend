<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

header('Content-Type: application/json');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../api/');
$dotenv->load();

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if ($conn->connect_error) {
    echo json_encode([
        'success' => false,
        'error'   => $conn->connect_error
    ]);
    exit;
}

set_error_handler(function ($errno, $errstr) {
    if (!headers_sent()) {
        header('Content-Type: application/json');
    }
    echo json_encode([
        'success' => false,
        'error'   => "PHP Error ($errno): $errstr"
    ]);
    exit;
});

// ─────────────────────────────────────────
// HELPERS
// ─────────────────────────────────────────

function json_error(string $msg, int $code = 400): void
{
    http_response_code($code);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

function json_ok($data = null): void
{
    echo json_encode(['success' => true, 'data' => $data]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

try {

    // ─────────────────────────────────────────
    // GET
    // ─────────────────────────────────────────
    if ($method === 'GET') {

        $yearFilter = $_GET['year'] ?? 'all';

        // GET AVAILABLE YEARS
        $years = [];
        $yearRes = $conn->query("
            SELECT DISTINCT YEAR(date_hired) AS yr
            FROM whip
            WHERE date_hired IS NOT NULL
            ORDER BY yr DESC
        ");

        while ($row = $yearRes->fetch_assoc()) {
            $years[] = (int) $row['yr'];
        }

        // ─────────────────────────────────────────
        // BUILD QUERY (ALL YEARS FIX HERE)
        // ─────────────────────────────────────────

        $sql = "
            SELECT
                w.whip_id,
                w.benef_id,
                w.project_id,
                w.batch_id,
                w.position,
                w.date_hired,
                w.created_at,

                b.first_name,
                b.middle_name,
                b.last_name,
                b.suffix,
                b.sex,
                b.city,
                b.barangay,
                b.district,
                b.classification,

                p.project_title,
                p.nature_of_project,
                p.duration,
                p.budget,
                p.fund_source,
                p.persons_from_locality,
                p.skills_required,
                p.skills_deficiencies,
                p.contractor,
                p.is_legitimate_contractor,
                p.filled,
                p.unfilled

            FROM whip w
            LEFT JOIN beneficiaries b ON w.benef_id = b.benef_id
            LEFT JOIN projects p ON w.project_id = p.project_id
        ";

        $params = [];
        $types  = "";

        if ($yearFilter !== 'all' && !empty($yearFilter)) {
            $sql .= " WHERE YEAR(w.date_hired) = ? ";
            $params[] = (int)$yearFilter;
            $types .= "i";
        }

        $sql .= " ORDER BY w.date_hired DESC, b.last_name ASC, b.first_name ASC";

        $stmt = $conn->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $rows        = [];
        $maleCount   = 0;
        $femaleCount = 0;
        $projectSet  = [];

        while ($row = $result->fetch_assoc()) {

            $row['budget'] = $row['budget'] !== null ? (float)$row['budget'] : null;
            $row['persons_from_locality'] = (int)($row['persons_from_locality'] ?? 0);
            $row['is_legitimate_contractor'] = (bool)($row['is_legitimate_contractor'] ?? false);
            $row['filled'] = (int)($row['filled'] ?? 0);
            $row['unfilled'] = (int)($row['unfilled'] ?? 0);

            $sex = strtolower($row['sex'] ?? '');
            if ($sex === 'male') $maleCount++;
            if ($sex === 'female') $femaleCount++;

            if (!empty($row['project_id'])) {
                $projectSet[$row['project_id']] = true;
            }

            $rows[] = $row;
        }

        $defaultYear = !empty($years)
            ? max($years)
            : (int) date('Y');

        json_ok([
            'rows' => $rows,
            'years' => $years,
            'default_year' => $defaultYear,
            'totals' => [
                'total' => $maleCount + $femaleCount,
                'male' => $maleCount,
                'female' => $femaleCount,
                'projects' => count($projectSet),
            ]
        ]);
    }

    // ─────────────────────────────────────────
    // PUT
    // ─────────────────────────────────────────
    if ($method === 'PUT') {

        $body = json_decode(file_get_contents('php://input'), true);

        $whip_id = (int)($body['whip_id'] ?? 0);
        if (!$whip_id) json_error('Missing whip_id');

        $position   = trim($body['position'] ?? '');
        $date_hired = trim($body['date_hired'] ?? '');

        if ($date_hired && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_hired)) {
            json_error('Invalid date format');
        }

        $stmt = $conn->prepare("
            UPDATE whip
            SET position = ?, date_hired = ?
            WHERE whip_id = ?
        ");

        $stmt->bind_param("ssi", $position, $date_hired, $whip_id);
        $stmt->execute();

        json_ok(['updated' => $stmt->affected_rows]);
    }

    // ─────────────────────────────────────────
    // DELETE
    // ─────────────────────────────────────────
    if ($method === 'DELETE') {

        $whip_id = (int)($_GET['id'] ?? 0);
        if (!$whip_id) json_error('Missing id');

        $stmt = $conn->prepare("
            DELETE FROM whip WHERE whip_id = ?
        ");

        $stmt->bind_param("i", $whip_id);
        $stmt->execute();

        json_ok(['deleted' => $stmt->affected_rows]);
    }

    json_error('Method not allowed', 405);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}