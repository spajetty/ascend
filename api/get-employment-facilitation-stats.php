<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

try {
    $stats = [];

    // Total Users (count beneficiaries)
    $result = $conn->query("SELECT COUNT(*) as total FROM beneficiaries");
    $stats['totalUsers'] = $result->fetch_assoc()['total'] ?? 0;

    // Total Employers
    $result = $conn->query("SELECT COUNT(DISTINCT company_id) as total FROM employers");
    $stats['totalEmployers'] = $result->fetch_assoc()['total'] ?? 0;

    // Total Job Fair Vacancies (sum male + female vacancies)
    $result = $conn->query("SELECT SUM(vacancy_male + vacancy_female) as total FROM jobFair");
    $stats['totalJobFairVacancies'] = $result->fetch_assoc()['total'] ?? 0;

    // Total First Time Job Seekers
    $result = $conn->query("SELECT COUNT(*) as total FROM firstJobSeek");
    $stats['firstTimeJobSeekers'] = $result->fetch_assoc()['total'] ?? 0;

    echo json_encode(['success' => true, 'data' => $stats]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
