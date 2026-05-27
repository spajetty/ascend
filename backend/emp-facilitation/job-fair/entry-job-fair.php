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

// ─── GET ─────────────────────────────────────────────────────────────────────
// ?action=unfilled&year=2026
//     → Returns job_fair_events that have participants but any company
//       still has vacancy_male = 0 AND vacancy_female = 0 in jobvacancies
//       (or has no row at all) for that event.
//
// ?action=companies&event_id=1
//     → Returns all participant companies for the given event, with their
//       current vacancy_male / vacancy_female values (0 if not yet entered).
//
// ?action=search_companies&q=query
//     → Full-text search on employers for the Excel import company matching.
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'GET') {
    $action = $_GET['action'] ?? '';

    // ── Unfilled events check ────────────────────────────────────────────────
    // An event is "unfilled" when at least one of its participants has
    // vacancy_male = 0 AND vacancy_female = 0 (or no row in jobvacancies at all).
    if ($action === 'unfilled') {
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        // Restrict to events that fall within the requested year
        $sql = "
            SELECT
                jfe.jobfairevent_id,
                jfe.job_fair_type,
                jfe.date_start,
                jfe.date_end,
                jfe.venue,
                COUNT(DISTINCT jp.company_id)                      AS total_companies,
                SUM(
                    CASE
                        WHEN COALESCE(jv.vacancy_male,  0) = 0
                         AND COALESCE(jv.vacancy_female, 0) = 0
                        THEN 1 ELSE 0
                    END
                )                                                   AS unfilled_vac
            FROM job_fair_events jfe
            INNER JOIN jobfair_participants jp
                ON jp.jobfairevent_id = jfe.jobfairevent_id
            LEFT JOIN jobvacancies jv
                ON  jv.jobfairevent_id = jfe.jobfairevent_id
                AND jv.company_id      = jp.company_id
            WHERE YEAR(jfe.date_start) = ?
            GROUP BY
                jfe.jobfairevent_id,
                jfe.job_fair_type,
                jfe.date_start,
                jfe.date_end,
                jfe.venue
            HAVING unfilled_vac > 0
            ORDER BY jfe.date_start ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $year);
        $stmt->execute();
        $res    = $stmt->get_result();
        $events = [];
        while ($row = $res->fetch_assoc()) $events[] = $row;
        $stmt->close();

        json_ok($events);
    }

    // ── Companies for a specific event ───────────────────────────────────────
    // Returns every participant company plus their current vacancy counts
    // (0 / 0 if not yet entered).
    if ($action === 'companies') {
        $event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
        if (!$event_id) json_error('Missing event_id');

        $sql = "
            SELECT
                e.company_id,
                e.company_name,
                COALESCE(jv.vacancy_male,   0) AS vac_m,
                COALESCE(jv.vacancy_female,  0) AS vac_f
            FROM jobfair_participants jp
            INNER JOIN employers e
                ON e.company_id = jp.company_id
            LEFT JOIN jobvacancies jv
                ON  jv.jobfairevent_id = jp.jobfairevent_id
                AND jv.company_id      = jp.company_id
            WHERE jp.jobfairevent_id = ?
            GROUP BY e.company_id, e.company_name, jv.vacancy_male, jv.vacancy_female
            ORDER BY e.company_name ASC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $event_id);
        $stmt->execute();
        $res       = $stmt->get_result();
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
        $res     = $stmt->get_result();
        $results = [];
        while ($row = $res->fetch_assoc()) $results[] = $row;
        $stmt->close();
        json_ok($results);
    }

    json_error('Unknown action', 400);
}

// ─── POST: bulk save vacancies for a job-fair event ───────────────────────────
// Body: { event_id: int, entries: [{ company_id, vac_m, vac_f }] }
//
// Strategy: the jobvacancies table has no UNIQUE constraint on
// (jobfairevent_id, company_id), so we use a manual check-and-upsert:
//   - If a row already exists for this event+company → UPDATE it.
//   - Otherwise → INSERT a new row.
// This avoids duplicate rows while keeping the schema untouched.
// ─────────────────────────────────────────────────────────────────────────────
if ($method === 'POST') {
    $body     = json_decode(file_get_contents('php://input'), true);
    $event_id = isset($body['event_id']) ? (int)$body['event_id'] : 0;
    $entries  = $body['entries'] ?? [];

    if (!$event_id)                                    json_error('Missing event_id');
    if (!is_array($entries) || !count($entries))       json_error('No entries provided');

    // Verify the event exists
    $chk = $conn->prepare("SELECT jobfairevent_id FROM job_fair_events WHERE jobfairevent_id = ?");
    $chk->bind_param('i', $event_id);
    $chk->execute();
    if (!$chk->get_result()->fetch_assoc()) json_error('Event not found', 404);
    $chk->close();

    $userId = $_SESSION['user_id'] ?? null;

    $conn->begin_transaction();
    try {
        $saved = 0;
        foreach ($entries as $entry) {
            $company_id = (int)($entry['company_id'] ?? 0);
            $vac_m      = max(0, (int)($entry['vac_m'] ?? 0));
            $vac_f      = max(0, (int)($entry['vac_f'] ?? 0));

            if (!$company_id) continue;

            // Check if a row already exists for this event + company
            $find = $conn->prepare("
                SELECT vacancy_id
                FROM jobvacancies
                WHERE jobfairevent_id = ? AND company_id = ?
                LIMIT 1
            ");
            $find->bind_param('ii', $event_id, $company_id);
            $find->execute();
            $existing = $find->get_result()->fetch_assoc();
            $find->close();

            if ($existing) {
                // UPDATE existing row
                $upd = $conn->prepare("
                    UPDATE jobvacancies
                    SET vacancy_male = ?, vacancy_female = ?, user_id = ?
                    WHERE vacancy_id = ?
                ");
                $upd->bind_param('iiii', $vac_m, $vac_f, $userId, $existing['vacancy_id']);
                $upd->execute();
                $upd->close();
            } else {
                // INSERT new row
                $ins = $conn->prepare("
                    INSERT INTO jobvacancies
                        (user_id, company_id, vacancy_male, vacancy_female, jobfairevent_id)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $ins->bind_param('iiiii', $userId, $company_id, $vac_m, $vac_f, $event_id);
                $ins->execute();
                $ins->close();
            }
            $saved++;
        }
        $conn->commit();
        json_ok(['saved' => $saved]);
    } catch (Exception $e) {
        $conn->rollback();
        json_error('Save failed: ' . $e->getMessage());
    }
}

json_error('Method not allowed', 405);