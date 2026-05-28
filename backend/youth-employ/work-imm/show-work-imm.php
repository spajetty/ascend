<?php
$require_auth = __DIR__ . '/../../../includes/auth-check.php';
require_once $require_auth;
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

// ─── GET ?year=2026 ──────────────────────────────────────────────────────────
// One row per month/year.
// Counts wiirp beneficiaries by classification x sex.
//
// classification values (on beneficiaries table):
//   Participants, Inquired, Referred, Interviewed,
//   PESO-Accepted, Privately-Accepted, Not Proceeded
//
// sex values: 'Male' / 'Female'
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET') {

    $year = isset($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

    // Available years that have wiirp data
    $res = $conn->query("
        SELECT DISTINCT ib.year
        FROM import_batches ib
        INNER JOIN wiirp w ON w.batch_id = ib.batch_id
        ORDER BY ib.year DESC
    ");

    $years = [];
    while ($row = $res->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }

    // Always include current year and requested year
    $currentYear = (int) date('Y');
    $years = array_unique(array_merge($years, [$currentYear, $year]));
    rsort($years);

    $sql = "
        SELECT
            ib.batch_id,
            ib.month,
            ib.year,
            CASE ib.month
                WHEN 1  THEN 'January'
                WHEN 2  THEN 'February'
                WHEN 3  THEN 'March'
                WHEN 4  THEN 'April'
                WHEN 5  THEN 'May'
                WHEN 6  THEN 'June'
                WHEN 7  THEN 'July'
                WHEN 8  THEN 'August'
                WHEN 9  THEN 'September'
                WHEN 10 THEN 'October'
                WHEN 11 THEN 'November'
                WHEN 12 THEN 'December'
                ELSE ''
            END AS month_name,
            w.type,
            LOWER(COALESCE(b.sex, '')) AS sex,
            LOWER(COALESCE(b.classification, '')) AS classification

        FROM import_batches ib

        INNER JOIN wiirp w
            ON w.batch_id = ib.batch_id

        INNER JOIN beneficiaries b
            ON b.benef_id = w.benef_id

        WHERE ib.year = ?

        ORDER BY ib.month ASC, ib.batch_id ASC
    ";

    $stmt = $conn->prepare($sql);
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param('i', $year);
    $stmt->execute();
    $result = $stmt->get_result();

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $groupKey = sprintf('%04d-%02d', (int)$row['year'], (int)$row['month']);

        if (!isset($rows[$groupKey])) {
            $rows[$groupKey] = [
                'group_key'   => $groupKey,
                'batch_ids'   => [],
                'batch_ids_csv' => '',
                'month'       => (int) $row['month'],
                'year'        => (int) $row['year'],
                'month_name'  => $row['month_name'],
                'period'      => $row['month_name'] . ' ' . $row['year'],
                'wiirp_id'    => $groupKey,
                'part_m'      => 0,
                'part_f'      => 0,
                'inq_m'       => 0,
                'inq_f'       => 0,
                'ref_m'       => 0,
                'ref_f'       => 0,
                'int_m'       => 0,
                'int_f'       => 0,
                'peso_m'      => 0,
                'peso_f'      => 0,
                'priv_m'      => 0,
                'priv_f'      => 0,
                'notpr_m'     => 0,
                'notpr_f'     => 0,
            ];
        }

        $group =& $rows[$groupKey];
        $group['batch_ids'][(int) $row['batch_id']] = (int) $row['batch_id'];

        if ($row['sex'] === 'male') {
            $group['part_m']++;
        } elseif ($row['sex'] === 'female') {
            $group['part_f']++;
        }

        if ($row['type'] === 'inquiry') {
            if ($row['sex'] === 'male') {
                $group['inq_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['inq_f']++;
            }
        }

        if ($row['type'] === 'peso-assigned') {
            if ($row['sex'] === 'male') {
                $group['peso_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['peso_f']++;
            }
        }

        if ($row['type'] === 'private') {
            if ($row['sex'] === 'male') {
                $group['priv_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['priv_f']++;
            }
        }

        if ($row['classification'] === 'referred') {
            if ($row['sex'] === 'male') {
                $group['ref_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['ref_f']++;
            }
        }

        if ($row['classification'] === 'interviewed') {
            if ($row['sex'] === 'male') {
                $group['int_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['int_f']++;
            }
        }

        if ($row['classification'] === 'not proceeded') {
            if ($row['sex'] === 'male') {
                $group['notpr_m']++;
            } elseif ($row['sex'] === 'female') {
                $group['notpr_f']++;
            }
        }
    }
    $stmt->close();

    $rows = array_values($rows);

    foreach ($rows as &$row) {
        $row['batch_ids'] = array_values($row['batch_ids']);
        $row['batch_ids_csv'] = implode(',', $row['batch_ids']);
        $row['part_total']  = (int) $row['part_m']  + (int) $row['part_f'];
        $row['inq_total']   = (int) $row['inq_m']   + (int) $row['inq_f'];
        $row['ref_total']   = (int) $row['ref_m']   + (int) $row['ref_f'];
        $row['int_total']   = (int) $row['int_m']   + (int) $row['int_f'];
        $row['peso_total']  = (int) $row['peso_m']  + (int) $row['peso_f'];
        $row['priv_total']  = (int) $row['priv_m']  + (int) $row['priv_f'];
        $row['notpr_total'] = (int) $row['notpr_m'] + (int) $row['notpr_f'];
    }
    unset($row);

    // Card totals (summed across all batches in the year)
    $totals = [
        'part_m'  => 0, 'part_f'  => 0, 'part_total'  => 0,
        'inq_m'   => 0, 'inq_f'   => 0, 'inq_total'   => 0,
        'ref_m'   => 0, 'ref_f'   => 0, 'ref_total'   => 0,
        'int_m'   => 0, 'int_f'   => 0, 'int_total'   => 0,
        'peso_m'  => 0, 'peso_f'  => 0, 'peso_total'  => 0,
        'priv_m'  => 0, 'priv_f'  => 0, 'priv_total'  => 0,
        'notpr_m' => 0, 'notpr_f' => 0, 'notpr_total' => 0,
    ];
    foreach ($rows as $r) {
        foreach (array_keys($totals) as $k) {
            $totals[$k] += (int)($r[$k] ?? 0);
        }
    }

    json_ok(['rows' => $rows, 'totals' => $totals, 'years' => $years]);
}

// ─── PUT — update batch month/year ───────────────────────────────────────────
// Body: { wiirp_id: <batch_id>, month: N, year: N }
if ($method === 'PUT') {

    $body     = json_decode(file_get_contents('php://input'), true);
    $batch_id = isset($body['wiirp_id']) ? (int) $body['wiirp_id'] : 0;
    if (!$batch_id) json_error('Missing wiirp_id');

    $allowed = ['month', 'year'];
    $sets    = [];
    $types   = '';
    $values  = [];

    foreach ($allowed as $col) {
        if (array_key_exists($col, $body)) {
            $sets[]   = "$col = ?";
            $types   .= 'i';
            $values[] = (int) $body[$col];
        }
    }

    if (empty($sets)) json_error('Nothing to update');

    $types   .= 'i';
    $values[] = $batch_id;

    $stmt = $conn->prepare(
        "UPDATE import_batches SET " . implode(', ', $sets) . " WHERE batch_id = ?"
    );
    if (!$stmt) json_error('Query prepare failed: ' . $conn->error);

    $stmt->bind_param($types, ...$values);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();

    json_ok(['updated' => $affected]);
}

// ─── DELETE ?id=<batch_id> ───────────────────────────────────────────────────
// Nulls wiirp.batch_id first, then deletes the batch.
if ($method === 'DELETE') {

    $batchIds = [];

    if (!empty($_GET['batch_ids'])) {
        $batchIds = array_values(array_filter(array_map('intval', explode(',', (string) $_GET['batch_ids']))));
    } elseif (!empty($_GET['id'])) {
        $batchIds = [(int) $_GET['id']];
    }

    if (!$batchIds) json_error('Missing batch_ids');

    $placeholders = implode(',', array_fill(0, count($batchIds), '?'));
    $types = str_repeat('i', count($batchIds));

    $conn->begin_transaction();
    try {
        $s1 = $conn->prepare("UPDATE wiirp SET batch_id = NULL WHERE batch_id IN ($placeholders)");
        $s1->bind_param($types, ...$batchIds);
        $s1->execute();
        $s1->close();

        $s2 = $conn->prepare("DELETE FROM import_batches WHERE batch_id IN ($placeholders)");
        $s2->bind_param($types, ...$batchIds);
        $s2->execute();
        $affected = $s2->affected_rows;
        $s2->close();

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Delete failed: ' . $e->getMessage());
    }

    json_ok(['deleted' => $affected]);
}

json_error('Method not allowed', 405);