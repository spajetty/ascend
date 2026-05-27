<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../api/db.php';
require_once __DIR__ . '/helpers/formatting_utils.php';

$input = json_decode(file_get_contents('php://input'), true);
$names = $input['companies'] ?? [];

if (!is_array($names) || empty($names)) {
    echo json_encode(['success' => true, 'results' => []]);
    exit;
}

// Load all companies for matching
$stmt = $conn->prepare("SELECT company_id, company_name FROM employers");
$stmt->execute();
$result = $stmt->get_result();

$allCompanies = [];
while ($row = $result->fetch_assoc()) {
    $companyName = trim((string)$row['company_name']);
    if ($companyName !== '') {
        $allCompanies[] = [
            'id' => (int)$row['company_id'],
            'name' => $companyName,
            'norm' => normalizeEmployerName($companyName)
        ];
    }
}
$stmt->close();

$results = [];

foreach ($names as $originalName) {
    $originalNameStr = trim((string)$originalName);
    if ($originalNameStr === '') continue;

    $inputNorm = normalizeEmployerName($originalNameStr);
    
    $exactMatch = null;
    $bestFuzzyMatch = null;
    $bestScore = 0.0;
    
    foreach ($allCompanies as $company) {
        if ($company['norm'] === $inputNorm) {
            $exactMatch = [
                'company_id' => $company['id'],
                'company_name' => $company['name']
            ];
            break; // Exact match found, no need to check others
        }
        
        // Fuzzy matching logic
        similar_text($inputNorm, $company['norm'], $percent);
        $percent = $percent / 100;
        
        // Also check substring
        if (strpos($company['norm'], $inputNorm) !== false || strpos($inputNorm, $company['norm']) !== false) {
            $percent = max($percent, 0.85);
        }
        
        if ($percent > $bestScore && $percent >= 0.75) { // 75% similarity threshold
            $bestScore = $percent;
            $bestFuzzyMatch = [
                'company_id' => $company['id'],
                'company_name' => $company['name'],
                'score' => $percent
            ];
        }
    }
    
    $results[] = [
        'original' => $originalNameStr,
        'exact_match' => $exactMatch,
        'fuzzy_match' => ($exactMatch === null) ? $bestFuzzyMatch : null
    ];
}

echo json_encode(['success' => true, 'results' => $results]);
