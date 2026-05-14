<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

$benefId = (int)($_GET['id'] ?? 0);

if ($benefId <= 0) {
	echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
	exit;
}

function docsBenefLabel(string $field): string {
	return match ($field) {
		'proof_of_residency' => 'Proof of Residency',
		'latest_credential'   => 'Latest Credential',
		'letter_of_intent'    => 'Letter of Intent',
		'reco_letter'         => 'Recommendation Letter',
		'resume'              => 'Resume',
		'tor'                 => 'Transcript of Records',
		'brgy_clearance'      => 'Barangay Clearance',
		'nbi_clearance'       => 'NBI Clearance',
		'birth_cert'          => 'Birth Certificate',
		'tesda_cert'          => 'TESDA Certificate',
		default               => ucwords(str_replace(['_', '-'], ' ', $field)),
	};
}

function docsBenefPersonName(array $row): string {
	$parts = array_values(array_filter([
		trim((string)($row['last_name'] ?? '')),
		trim((string)($row['first_name'] ?? '')),
		trim((string)($row['middle_name'] ?? '')),
		trim((string)($row['suffix'] ?? '')),
	], fn($value) => $value !== ''));

	if (!$parts) {
		return 'Beneficiary';
	}

	$last = $parts[0];
	$first = $parts[1] ?? '';
	$middle = $parts[2] ?? '';
	$suffix = $parts[3] ?? '';

	$name = trim($last . ', ' . trim($first . ' ' . $middle));
	if ($suffix !== '') {
		$name .= ' ' . $suffix;
	}

	return preg_replace('/\s+/', ' ', trim($name));
}

try {
	$stmt = $conn->prepare('
		SELECT b.benef_id, b.first_name, b.middle_name, b.last_name, b.suffix,
			   d.proof_of_residency, d.latest_credential, d.letter_of_intent, d.reco_letter,
			   d.resume, d.tor, d.brgy_clearance, d.nbi_clearance, d.birth_cert, d.tesda_cert
		FROM beneficiaries b
		LEFT JOIN docs_benef d ON d.benef_id = b.benef_id
		WHERE b.benef_id = ?
		LIMIT 1
	');
	$stmt->bind_param('i', $benefId);
	$stmt->execute();
	$row = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$row) {
		echo json_encode(['success' => false, 'message' => 'Beneficiary not found']);
		exit;
	}

	$beneficiaryName = docsBenefPersonName($row);
	$documentFields = [
		'proof_of_residency',
		'latest_credential',
		'letter_of_intent',
		'reco_letter',
		'resume',
		'tor',
		'brgy_clearance',
		'nbi_clearance',
		'birth_cert',
		'tesda_cert',
	];

	$documents = [];
	foreach ($documentFields as $field) {
		$value = trim((string)($row[$field] ?? ''));
		if ($value === '') {
			continue;
		}

		$label = docsBenefLabel($field);
		$name = $beneficiaryName . ' - ' . $label;
		$documents[] = [
			'field' => $field,
			'label' => $label,
			'name'  => $name,
			'url'   => $value,
		];
	}

	echo json_encode([
		'success' => true,
		'beneficiary' => [
			'benef_id' => (int)$row['benef_id'],
			'name' => $beneficiaryName,
		],
		'documents' => $documents,
	]);
} catch (Throwable $e) {
	echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
