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

    $stmt = $this->conn->prepare("SELECT program_id FROM programs WHERE name = 'Government Internship Program' LIMIT 1");
    $stmt->execute();
    $programRow = $stmt->fetch(PDO::FETCH_ASSOC);
    expect($programRow)->not->toBeFalse('Could not find a GIP program row.');
    $this->gipProgramId = (int) $programRow['program_id'];
});

afterEach(function () {
    $this->conn->exec("
        DELETE g FROM gip g
        INNER JOIN beneficiaries b ON b.benef_id = g.benef_id
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
        'json' => ['program' => 'Government Internship Program', 'data' => []],
    ]);

    expect($response->getStatusCode())->toBeIn([302, 401, 403]);
});

it('tallies GIP categories correctly across dashboard and show-gip.php', function () {
    // 1. Snapshot dashboard totals for GIP before import
    $before = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    expect($before['success'])->toBeTrue();

    $beforeRow = collect($before['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->gipProgramId);
    $beforeRegistered = $beforeRow['total_registered'] ?? 0;

    // 2. We use a year far in the future (2099) to ensure isolation in show-gip.php grouping
    // First import for 'LGU' category (1 Male, 1 Female)
    $importPayload1 = [
        'program' => 'Government Internship Program - LGU', // Use the specific LGU program variation for headers validation mapping if needed, though save_data accepts it.
        'importMonth' => 'December',
        'importYear' => '2099',
        'fileName' => $this->marker . '_lgu',
        'gipCategory' => 'LGU',
        'data' => [
            [
                'First Name' => 'M1',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'School' => 'LGU School',
                'Office Assignment' => 'Mayor Office',
                'Contact Number' => '09123456001',
                'Age' => '20',
            ],
            [
                'First Name' => 'F1',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'School' => 'LGU School 2',
                'Office Assignment' => 'HR Office',
                'Contact Number' => '09123456002',
                'Age' => '21',
            ],
        ],
    ];

    // Second import for 'DOLE' category (1 Male, 1 Female)
    // Note: DOLE headers don't strictly have School or Office Assignment, but we'll include minimal.
    $importPayload2 = [
        'program' => 'Government Internship Program - DOLE',
        'importMonth' => 'December',
        'importYear' => '2099',
        'fileName' => $this->marker . '_dole',
        'gipCategory' => 'DOLE',
        'data' => [
            [
                'First Name' => 'M2',
                'Last Name' => $this->marker,
                'Sex' => 'Male',
                'Contact Number' => '09123456003',
                'Age' => '22',
            ],
            [
                'First Name' => 'F2',
                'Last Name' => $this->marker,
                'Sex' => 'Female',
                'Contact Number' => '09123456004',
                'Age' => '23',
            ],
        ],
    ];

    $res1 = $this->client->post('backend/import/save_data.php', ['json' => $importPayload1]);
    $res2 = $this->client->post('backend/import/save_data.php', ['json' => $importPayload2]);
    
    $res1Json = json_decode($res1->getBody(), true);
    $res2Json = json_decode($res2->getBody(), true);

    expect($res1Json['success'])->toBeTrue($res1Json['error'] ?? 'Import 1 failed');
    expect($res1Json['saved'])->toBe(2);
    expect($res2Json['success'])->toBeTrue($res2Json['error'] ?? 'Import 2 failed');
    expect($res2Json['saved'])->toBe(2);

    // 3. Check Dashboard Total Registered increased by 4 (all default to Registered if status empty)
    $after = json_decode(
        $this->client->get('backend/dashboard/fetch-details.php')->getBody(),
        true
    );
    $afterRow = collect($after['data']['beneficiaries_by_program'])
        ->firstWhere('program_id', $this->gipProgramId);
    
    expect($afterRow['total_registered'])->toBe($beforeRegistered + 4);

    // 4. Verify show-gip.php group tallies for 2099-12
    $gipResponse = json_decode(
        $this->client->get("backend/youth-employ/gip/show-gip.php?year=2099")->getBody(),
        true
    );
    expect($gipResponse['success'])->toBeTrue();

    $totals = $gipResponse['data']['totals'];

    // We imported 4 records: 2 LGU, 2 DOLE
    // LGU: 1M, 1F
    // DOLE: 1M, 1F
    
    // Note: this assumes we are the only test data in 2099, so totals should EXACTLY equal 4.
    expect((int)$totals['participants']['m'] + (int)$totals['participants']['f'])->toBe(4);
    
    // For exact match, we can find the rows returned and manually sum them, 
    // or just rely on the totals block if we assume isolation by year.
    // Let's filter the actual rows to be safe.
    $rows = $gipResponse['data']['rows'];
    $lguMale = 0; $lguFemale = 0;
    $doleMale = 0; $doleFemale = 0;

    foreach ($rows as $row) {
        if ($row['year'] === 2099 && $row['month_num'] === 12) {
            if ($row['gip_type'] === 'lgu') {
                $lguMale += (int)$row['part_m'];
                $lguFemale += (int)$row['part_f'];
            } elseif ($row['gip_type'] === 'dole') {
                $doleMale += (int)$row['part_m'];
                $doleFemale += (int)$row['part_f'];
            }
        }
    }

    expect($lguMale)->toBe(1);
    expect($lguFemale)->toBe(1);
    expect($doleMale)->toBe(1);
    expect($doleFemale)->toBe(1);
});
