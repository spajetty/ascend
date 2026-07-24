<?php

use GuzzleHttp\Client;

/**
 * Integration test: verifies that importing SPES beneficiaries via
 * save_data.php correctly tallies into:
 *   1. The dashboard (fetch-details.php) — beneficiaries_by_program totals
 *   2. The SPES program view (show-spes.php) — per-employer breakdown
 *
 * This directly tests the Registered / Referred / Placed classification
 *
 */

const BASE_URL = 'http://ascend.test/';

beforeEach(function () {
    // Connects to the same local DB your app itself uses (via .env) —
    // no test-DB isolation, since this is local-only dev with no shared
    // dependents. Cleanup below via afterEach() keeps this safe.
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../api');
    $dotenv->load();
    $this->conn = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'] ?? '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $this->marker = 'PESTAUTO_' . uniqid(); // unique per run — used as the company name
    // so show-spes.php always creates a brand-new
    // group row instead of merging into real data

    // 1. Create a temporary admin and log in via Guzzle, storing the session cookie
    // Note: users.access must be 'Approved' or login_handler.php may reject the login
    // even with correct credentials, depending on how it checks account status.
    $hashed = password_hash('testpassword123', PASSWORD_DEFAULT);
    $this->testEmail = "testadmin_{$this->marker}@example.com";
    $stmt = $this->conn->prepare(
        "INSERT INTO users (fname, lname, email, password, role, is_verified, access) VALUES (?, ?, ?, ?, 'Admin', 1, 'Approved')"
    );
    $stmt->execute(['Test', 'Admin', $this->testEmail, $hashed]);
    $this->testUserId = (int) $this->conn->lastInsertId(); // maps to users.user_id

    $this->client = new Client([
        'cookies' => true,
        'base_uri' => BASE_URL,
        'http_errors' => false,
    ]);

    $loginResponse = $this->client->post('backend/auth/login_handler.php', [
        'form_params' => ['email' => $this->testEmail, 'password' => 'testpassword123'],
    ]);
    expect($loginResponse->getStatusCode())->toBe(200);

    // 2. Look up SPES program_id for dashboard row matching
    $stmt = $this->conn->prepare("SELECT program_id FROM programs WHERE name LIKE '%SPES%' LIMIT 1");
    $stmt->execute();
    $programRow = $stmt->fetch(PDO::FETCH_ASSOC);
    expect($programRow)->not->toBeNull('Could not find a SPES program row — check the `programs` table.');
    $this->spesProgramId = (int) $programRow['program_id'];
});

afterEach(function () {
    // Clean up everything tied to this test run's unique marker.
    // last_name (not a "notes" field — SPES import schema has no Notes
    // column) is used as the marker since it's always populated and
    // directly maps to beneficiaries.last_name via the DEFAULT headers.
    // Order matters: children before parents, to respect FKs.
    $this->conn->exec("
        DELETE se FROM spes_employment se
        INNER JOIN employers e ON e.company_id = se.company_id
        WHERE e.company_name = '{$this->marker}'
    ");
    $this->conn->exec("
        DELETE s FROM spes s
        INNER JOIN beneficiaries b ON b.benef_id = s.benef_id
        WHERE b.last_name = '{$this->marker}'
    ");
    $this->conn->exec("
        DELETE bp FROM beneficiary_programs bp
        INNER JOIN beneficiaries b ON b.benef_id = bp.benef_id
        WHERE b.last_name = '{$this->marker}'
    ");
    $this->conn->exec("DELETE FROM beneficiaries WHERE last_name = '{$this->marker}'");
    $this->conn->exec("DELETE FROM employers WHERE company_name = '{$this->marker}'");
    $this->conn->exec("DELETE FROM import_batches WHERE file_name = '{$this->marker}'");
    $this->conn->exec("DELETE FROM users WHERE user_id = {$this->testUserId}");
});

it('rejects unauthenticated import attempts', function () {
    $unauthClient = new Client([
        'cookies' => false,
        'base_uri' => BASE_URL,
        'http_errors' => false,
        'allow_redirects' => false,
    ]);

    $response = $unauthClient->post('backend/import/save_data.php', [
        'json' => ['program' => 'SPES', 'data' => []],
    ]);

    expect($response->getStatusCode())->toBeIn([302, 401, 403]);
});

it('tallies SPES Registered, Referred, and Placed correctly across dashboard and show-spes.php', function () {
    // ── 1. Snapshot dashboard totals for SPES before import ────────────────
    $before = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    expect($before['success'])->toBeTrue();

    $beforeRow = collect($before['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->spesProgramId);
    $beforeRegistered = $beforeRow['total_registered'] ?? 0;
    $beforeHired = $beforeRow['total_hired'] ?? 0; // "Placed" rolls into total_hired at dashboard level

    // ── 2. Import 3 rows: one Registered, one Referred, one Placed ─────────
    // All three use the same unique "company" so show-spes.php groups them
    // into a single, brand-new row we can assert on precisely.
    // Field names below match programHeaders['SPES'] exactly — using the
    // wrong header names causes rowValue() to silently fall back to
    // defaults rather than error, which would make this test pass falsely.
    // Last Name = $this->marker doubles as the cleanup identifier (there is
    // no "Notes" column in the SPES import schema).
    $importPayload = [
        'program' => 'SPES',
        'importMonth' => 'July',
        'importYear' => '2026',
        'fileName' => $this->marker,
        'spesCategory' => 'private',

        'data' => [
            [
                'First Name' => 'T1',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'Classification' => 'Registered',
                'Status' => 'New',
                'Student/OSY' => 'Student',
                'Company' => $this->marker,
                'Contact' => '09123456001',
                'Birthday' => '2005-01-01',
            ],
            [
                'First Name' => 'T2',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'Classification' => 'Referred',
                'Status' => 'New',
                'Student/OSY' => 'Student',
                'Company' => $this->marker,
                'Contact' => '09123456002',
                'Birthday' => '2004-02-02',
            ],
            [
                'First Name' => 'T3',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'Classification' => 'Placed',
                'Status' => 'New',
                'Student/OSY' => 'Student',
                'Company' => $this->marker,
                'Contact' => '09123456003',
                'Birthday' => '2003-03-03',
            ],
        ],
    ];

    $importResponse = $this->client->post('backend/import/save_data.php', ['json' => $importPayload]);
    $importResult = json_decode($importResponse->getBody(), true);

    expect($importResult['success'])->toBeTrue($importResult['error'] ?? 'Import failed unexpectedly');
    expect($importResult['saved'])->toBe(3);
    expect($importResult['skipped'])->toBe(0);

    // ── 3. Re-check dashboard: registered +1, hired (=placed) +1 ───────────
    $after = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    $afterRow = collect($after['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->spesProgramId);

    expect($afterRow['total_registered'])->toBe($beforeRegistered + 1);
    expect($afterRow['total_hired'])->toBe($beforeHired + 1); // only the "Placed" row counts as hired

    // ── 4. Verify show-spes.php: exactly one new group, correct breakdown ──
    $spesResponse = json_decode(
        $this->client->get("backend/youth-employ/spes/show-spes.php?year=2026&search={$this->marker}")->getBody(),
        true
    );
    expect($spesResponse['success'])->toBeTrue();

    $rows = $spesResponse['data']['rows'];
    $testGroup = collect($rows)->firstWhere('employer', $this->marker);

    expect($testGroup)->not->toBeNull("Expected a new show-spes.php row grouped under '{$this->marker}'");

    // Registered: 1 male (T1)
    expect((int) $testGroup['reg_m'])->toBe(1);
    expect((int) $testGroup['reg_f'])->toBe(0);

    // Referred: 1 female (T2)
    expect((int) $testGroup['ref_m'])->toBe(0);
    expect((int) $testGroup['ref_f'])->toBe(1);

    // Placed: 1 female (T3) — this is the specific bug we fixed
    expect((int) $testGroup['placed_m'])->toBe(0);
    expect((int) $testGroup['placed_f'])->toBe(1);

    // Sanity check
    $registeredTotal = (int) $testGroup['reg_m'] + (int) $testGroup['reg_f'];
    $referredTotal = (int) $testGroup['ref_m'] + (int) $testGroup['ref_f'];
    $placedTotal = (int) $testGroup['placed_m'] + (int) $testGroup['placed_f'];

    expect([$registeredTotal, $referredTotal, $placedTotal])
        ->toBe([1, 1, 1]);

    // ── 5. Verify exactly one beneficiary_programs row per person ──────────
    // This directly guards against the duplicate-row bug from the
    // UPDATE-affected_rows=0 MySQL trap we found earlier.
    $stmt = $this->conn->prepare("
        SELECT bp.benef_id, COUNT(*) as row_count
        FROM beneficiary_programs bp
        INNER JOIN beneficiaries b ON b.benef_id = bp.benef_id
        WHERE b.last_name = ?
        GROUP BY bp.benef_id
    ");
    $stmt->execute([$this->marker]);
    $counts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    expect($counts)->toHaveCount(3); // one group per beneficiary
    foreach ($counts as $c) {
        expect((int) $c['row_count'])->toBe(1, "benef_id {$c['benef_id']} has duplicate beneficiary_programs rows");
    }
});

it('enforces the import rate limit after repeated calls', function () {
    // ⚠️ This test will consume real rate-limit budget for this user.
    // Consider adding a RateLimiter bypass for test accounts (env-gated)
    // so this doesn't interfere with other tests run in the same hour.
    $payload = [
        'program' => 'SPES',
        'importMonth' => 'July',
        'importYear' => '2026',
        'fileName' => $this->marker . '_ratelimit',
        'data' => [],
    ];

    $lastResponse = null;
    for ($i = 0; $i < 16; $i++) {
        $lastResponse = $this->client->post('backend/import/save_data.php', ['json' => $payload]);
    }

    $result = json_decode($lastResponse->getBody(), true);
    expect($result['success'])->toBeFalse();
    expect($result['error'])->toContain('Rate limit exceeded');
})->skip('Enable only when running in isolation — consumes the shared hourly import quota');