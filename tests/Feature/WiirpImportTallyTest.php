<?php

use GuzzleHttp\Client;

const BASE_URL = 'http://ascend.test/';

beforeEach(function () {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../api');
    $dotenv->load();
    $this->conn = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};charset=utf8mb4",
        $_ENV['DB_USER'],
        $_ENV['DB_PASS'] ?? '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
    $this->marker = 'PESTAUTO_' . uniqid();

    $hashed = password_hash('testpassword123', PASSWORD_DEFAULT);
    $this->testEmail = "testadmin_{$this->marker}@example.com";
    $stmt = $this->conn->prepare(
        "INSERT INTO users (fname, lname, email, password, role, is_verified, access) VALUES (?, ?, ?, ?, 'Admin', 1, 'Approved')"
    );
    $stmt->execute(['Test', 'Admin', $this->testEmail, $hashed]);
    $this->testUserId = (int) $this->conn->lastInsertId();

    $this->client = new Client([
        'cookies' => true,
        'base_uri' => BASE_URL,
        'http_errors' => false,
    ]);

    $loginResponse = $this->client->post('backend/auth/login_handler.php', [
        'form_params' => ['email' => $this->testEmail, 'password' => 'testpassword123'],
    ]);
    expect($loginResponse->getStatusCode())->toBe(200);

    $stmt = $this->conn->prepare("SELECT program_id FROM programs WHERE name LIKE '%Work Immersion%' LIMIT 1");
    $stmt->execute();
    $programRow = $stmt->fetch(PDO::FETCH_ASSOC);
    expect($programRow)->not->toBeNull('Could not find a WIIRP program row.');
    $this->wiirpProgramId = (int) $programRow['program_id'];
});

afterEach(function () {
    $this->conn->exec("
        DELETE w FROM wiirp w
        INNER JOIN beneficiaries b ON b.benef_id = w.benef_id
        WHERE b.last_name = '{$this->marker}'
    ");
    $this->conn->exec("
        DELETE bp FROM beneficiary_programs bp
        INNER JOIN beneficiaries b ON b.benef_id = bp.benef_id
        WHERE b.last_name = '{$this->marker}'
    ");
    $this->conn->exec("DELETE FROM beneficiaries WHERE last_name = '{$this->marker}'");
    $this->conn->exec("DELETE FROM import_batches WHERE file_name LIKE '{$this->marker}%'");
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
        'json' => ['program' => 'Work Immersion and Internship Referral Program', 'data' => []],
    ]);

    expect($response->getStatusCode())->toBeIn([302, 401, 403]);
});

it('tallies WIIRP categories correctly across dashboard and show-work-imm.php', function () {
    // 1. Snapshot dashboard totals for WIIRP before import
    $before = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    expect($before['success'])->toBeTrue();

    $beforeRow = collect($before['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->wiirpProgramId);
    $beforeRegistered = $beforeRow['total_registered'] ?? 0;

    // 2. We use a year far in the future (2099) to ensure isolation in show-work-imm.php grouping
    // First import: Category = inquiry. 
    // We send 1 Male Referred, 1 Female Interviewed.
    // BOTH will be tallied under `inq_m` / `inq_f` because of their wiirpCategory.
    // They will ALSO be tallied under `ref_m` and `int_f` because of their Classification.
    $importPayload1 = [
        'program' => 'Work Immersion and Internship Referral Program',
        'importMonth' => 'December',
        'importYear' => '2099',
        'fileName' => $this->marker . '_inq',
        'wiirpCategory' => 'inquiry',
        'data' => [
            [
                'First Name' => 'M1',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'Classification' => 'Referred',
                'Contact Number' => '09123456001',
                'Age' => '20',
            ],
            [
                'First Name' => 'F1',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'Classification' => 'Interviewed',
                'Contact Number' => '09123456002',
                'Age' => '21',
            ],
        ],
    ];

    // Second import: Category = private. 
    // We send 1 Female Not Proceeded, 1 Male Registered.
    // BOTH will be tallied under `priv_m` / `priv_f` because of their wiirpCategory.
    // Female will ALSO be tallied under `notpr_f` because of her Classification.
    $importPayload2 = [
        'program' => 'Work Immersion and Internship Referral Program',
        'importMonth' => 'December',
        'importYear' => '2099',
        'fileName' => $this->marker . '_priv',
        'wiirpCategory' => 'private',
        'data' => [
            [
                'First Name' => 'F2',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'Classification' => 'Not Proceeded',
                'Contact Number' => '09123456003',
                'Age' => '22',
            ],
            [
                'First Name' => 'M2',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'Classification' => 'Registered',
                'Contact Number' => '09123456004',
                'Age' => '23',
            ],
        ],
    ];

    // Third import: Category = peso-assigned.
    // We send 1 Male Registered.
    // Will be tallied under `peso_m`.
    $importPayload3 = [
        'program' => 'Work Immersion and Internship Referral Program',
        'importMonth' => 'December',
        'importYear' => '2099',
        'fileName' => $this->marker . '_peso',
        'wiirpCategory' => 'peso-assigned',
        'data' => [
            [
                'First Name' => 'M3',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'Classification' => 'Registered',
                'Contact Number' => '09123456005',
                'Age' => '24',
            ],
        ],
    ];

    $res1 = $this->client->post('backend/import/save_data.php', ['json' => $importPayload1]);
    $res2 = $this->client->post('backend/import/save_data.php', ['json' => $importPayload2]);
    $res3 = $this->client->post('backend/import/save_data.php', ['json' => $importPayload3]);

    $res1Json = json_decode($res1->getBody(), true);
    $res2Json = json_decode($res2->getBody(), true);
    $res3Json = json_decode($res3->getBody(), true);

    expect($res1Json['success'])->toBeTrue($res1Json['error'] ?? 'Import 1 failed');
    expect($res1Json['saved'])->toBe(2);
    expect($res2Json['success'])->toBeTrue($res2Json['error'] ?? 'Import 2 failed');
    expect($res2Json['saved'])->toBe(2);
    expect($res3Json['success'])->toBeTrue($res3Json['error'] ?? 'Import 3 failed');
    expect($res3Json['saved'])->toBe(1);

    // 3. Check Dashboard Total Registered increased by 2 (M2 and M3 were 'Registered')
    $after = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    $afterRow = collect($after['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->wiirpProgramId);

    expect($afterRow['total_registered'])->toBe($beforeRegistered + 2);

    // 4. Verify show-work-imm.php group tallies for 2099-12
    $wiirpResponse = json_decode(
        $this->client->get("backend/youth-employ/work-imm/show-work-imm.php?year=2099")->getBody(),
        true
    );
    expect($wiirpResponse['success'])->toBeTrue();

    $rows = $wiirpResponse['data']['rows'];
    $testGroup = collect($rows)->firstWhere('group_key', '2099-12');

    expect($testGroup)->not->toBeNull("Expected a new show-work-imm.php row for 2099-12");

    // Total participants (3 male, 2 female)
    expect((int) $testGroup['part_m'])->toBe(3);
    expect((int) $testGroup['part_f'])->toBe(2);

    // Category: inquiry (M1, F1)
    expect((int) $testGroup['inq_m'])->toBe(1);
    expect((int) $testGroup['inq_f'])->toBe(1);

    // Category: peso-assigned (M3)
    expect((int) $testGroup['peso_m'])->toBe(1);
    expect((int) $testGroup['peso_f'])->toBe(0);

    // Category: private (M2, F2)
    expect((int) $testGroup['priv_m'])->toBe(1);
    expect((int) $testGroup['priv_f'])->toBe(1);

    // Classification: referred (M1)
    expect((int) $testGroup['ref_m'])->toBe(1);
    expect((int) $testGroup['ref_f'])->toBe(0);

    // Classification: interviewed (F1)
    expect((int) $testGroup['int_m'])->toBe(0);
    expect((int) $testGroup['int_f'])->toBe(1);

    // Classification: not proceeded (F2)
    expect((int) $testGroup['notpr_m'])->toBe(0);
    expect((int) $testGroup['notpr_f'])->toBe(1);
});
