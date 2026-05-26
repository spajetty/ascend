<?php

require_once __DIR__ . '/../../../includes/auth-check.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

// Caching removed: fetch WHIP data directly from DB

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

// Helper: fetch whip data directly (no cache persistence)
function getWhipData(mysqli $conn, $yearFilter): array
{
    $yearFilter = (string) $yearFilter;
    $years = [];

    $yearRes = $conn->query("\n        SELECT DISTINCT YEAR(date_hired) AS yr\n        FROM whip\n        WHERE date_hired IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearRes->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }
    $yearRes->free();

    $sql = "\n        SELECT\n            w.whip_id,\n            w.benef_id,\n            w.project_id,\n            w.batch_id,\n            w.position,\n            w.date_hired,\n            w.created_at,\n\n            b.first_name,\n            b.middle_name,\n            b.last_name,\n            b.suffix,\n            b.sex,\n            b.city,\n            b.barangay,\n            b.district,\n            b.classification,\n\n            p.project_title,\n            p.nature_of_project,\n            p.duration,\n            p.budget,\n            p.fund_source,\n            p.persons_from_locality,\n            p.skills_required,\n            p.skills_deficiencies,\n            p.contractor,\n            p.is_legitimate_contractor,\n            p.filled,\n            p.unfilled\n\n        FROM whip w\n        LEFT JOIN beneficiaries b ON w.benef_id = b.benef_id\n        LEFT JOIN projects p ON w.project_id = p.project_id\n    ";

    $params = [];
    $types = '';

    if ($yearFilter !== 'all' && $yearFilter !== '') {
        $sql .= " WHERE YEAR(w.date_hired) = ? ";
        $params[] = (int) $yearFilter;
        $types .= 'i';
    }

    $sql .= " ORDER BY w.date_hired DESC, b.last_name ASC, b.first_name ASC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];
    $maleCount = 0;
    $femaleCount = 0;
    $projectSet = [];

    while ($row = $result->fetch_assoc()) {
        $row['budget'] = $row['budget'] !== null ? (float) $row['budget'] : null;
        $row['persons_from_locality'] = (int) ($row['persons_from_locality'] ?? 0);
        $row['is_legitimate_contractor'] = (bool) ($row['is_legitimate_contractor'] ?? false);
        $row['filled'] = (int) ($row['filled'] ?? 0);
        $row['unfilled'] = (int) ($row['unfilled'] ?? 0);

        $sex = strtolower($row['sex'] ?? '');
        if ($sex === 'male') {
            $maleCount++;
        }
        if ($sex === 'female') {
            $femaleCount++;
        }

        if (!empty($row['project_id'])) {
            $projectSet[$row['project_id']] = true;
        }

        $rows[] = $row;
    }
    $result->free();
    $stmt->close();

    $payload = [
        'rows' => $rows,
        'years' => $years,
        'year' => $yearFilter === 'all' || $yearFilter === '' ? 'all' : (int) $yearFilter,
        'default_year' => !empty($years) ? max($years) : (int) date('Y'),
        'totals' => [
            'total' => $maleCount + $femaleCount,
            'male' => $maleCount,
            'female' => $femaleCount,
            'projects' => count($projectSet),
        ],
    ];

    return $payload;
}

$method = $_SERVER['REQUEST_METHOD'];

try {
        if ($method === 'GET') {
        $yearFilter = $_GET['year'] ?? 'all';
        echo json_encode(['success' => true, 'data' => getWhipData($conn, $yearFilter)], JSON_PRETTY_PRINT);
        exit;
    }

    if ($method === 'PUT') {
        $body = json_decode(file_get_contents('php://input'), true);

        $whip_id = (int) ($body['whip_id'] ?? 0);
        if (!$whip_id) {
            json_error('Missing whip_id');
        }

        $yearStmt = $conn->prepare("\n            SELECT YEAR(date_hired) AS yr\n            FROM whip\n            WHERE whip_id = ?\n            LIMIT 1\n        ");
        $yearStmt->bind_param('i', $whip_id);
        $yearStmt->execute();
        $yearResult = $yearStmt->get_result();
        $yearRow = $yearResult->fetch_assoc();
        $yearResult->free();
        $yearStmt->close();
        $cacheYear = isset($yearRow['yr']) ? (int) $yearRow['yr'] : (int) date('Y');

        $position   = trim($body['position'] ?? '');
        $date_hired = trim($body['date_hired'] ?? '');

        if ($date_hired && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_hired)) {
            json_error('Invalid date format');
        }

        $stmt = $conn->prepare("\n            UPDATE whip\n            SET position = ?, date_hired = ?\n            WHERE whip_id = ?\n        ");

        $stmt->bind_param('ssi', $position, $date_hired, $whip_id);
        $stmt->execute();

        if ($date_hired) {
            $cacheYear = (int) date('Y', strtotime($date_hired));
        }

        $stmt->close();

        // caching removed: no cache refresh

        json_ok(['updated' => $stmt->affected_rows]);
    }

    if ($method === 'DELETE') {
        $whip_id = (int) ($_GET['id'] ?? 0);
        if (!$whip_id) {
            json_error('Missing id');
        }

        $yearStmt = $conn->prepare("\n            SELECT YEAR(date_hired) AS yr\n            FROM whip\n            WHERE whip_id = ?\n            LIMIT 1\n        ");
        $yearStmt->bind_param('i', $whip_id);
        $yearStmt->execute();
        $yearResult = $yearStmt->get_result();
        $yearRow = $yearResult->fetch_assoc();
        $yearResult->free();
        $yearStmt->close();
        $cacheYear = isset($yearRow['yr']) ? (int) $yearRow['yr'] : (int) date('Y');

        $stmt = $conn->prepare("\n            DELETE FROM whip WHERE whip_id = ?\n        ");

        $stmt->bind_param('i', $whip_id);
        $stmt->execute();

        $stmt->close();

        refreshWhipCacheFresh($cacheYear);

        json_ok(['deleted' => $stmt->affected_rows]);
    }

    json_error('Method not allowed', 405);

} catch (Exception $e) {

    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}