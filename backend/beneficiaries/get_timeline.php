<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../../api/db.php';

$benefId = (int)($_GET['id'] ?? 0);

if ($benefId <= 0) {
	echo json_encode(['success' => false, 'message' => 'Invalid beneficiary id']);
	exit;
}

function timelineStatusLabel(string $status): string {
	return match ($status) {
		'PENDING'   => 'Pending',
		'PROCESSING'=> 'Processing',
		'HIRED'     => 'Hired',
		'REJECTED'  => 'Rejected',
		'NO_FEEDBACK' => 'No feedback',
		default     => ucwords(strtolower(str_replace('_', ' ', $status))),
	};
}

function timelineSafeText(?string $value): string {
	return trim((string)$value);
}

try {
	$stmt = $conn->prepare('
		SELECT
			h.history_id,
			h.benef_id,
			h.classification,
			h.date_of_record,
			h.created_at,
			h.visit_number,
			h.company_id,
			h.position,
			h.referral_status,
			h.jobfairevent_id,
			e.company_name,
			jfe.venue,
			jfe.date_start,
			(
				SELECT COUNT(*)
				FROM beneficiary_activity_history h2
				WHERE h2.benef_id = h.benef_id
				  AND h2.classification = h.classification
				  AND (
					(h.classification = "JOB_FAIR_PARTICIPATION" AND h2.jobfairevent_id = h.jobfairevent_id)
					OR (h.classification <> "JOB_FAIR_PARTICIPATION" AND h2.history_id <= h.history_id)
				  )
			) AS participation_count
		FROM beneficiary_activity_history h
		LEFT JOIN employers e ON e.company_id = h.company_id
		LEFT JOIN job_fair_events jfe ON jfe.jobfairevent_id = h.jobfairevent_id
		WHERE h.benef_id = ?
		  AND h.classification IN ("PESO_VISIT", "REFERRAL", "JOB_FAIR_PARTICIPATION")
		ORDER BY h.date_of_record DESC, h.created_at DESC, h.history_id DESC
	');
	$stmt->bind_param('i', $benefId);
	$stmt->execute();
	$result = $stmt->get_result();

	$timeline = [];
	while ($row = $result->fetch_assoc()) {
		$classification = (string)($row['classification'] ?? '');
		$date = $row['date_of_record']
			? date('F j, Y', strtotime((string)$row['date_of_record']))
			: '—';

		$companyName = timelineSafeText($row['company_name'] ?? '');
		$position = timelineSafeText($row['position'] ?? '');
		// notes column removed from schema; do not attempt to read it

		if ($classification === 'PESO_VISIT') {
			$visitNumber = (int)($row['visit_number'] ?? 0);
			$title = $visitNumber > 0 ? $visitNumber . ordinalSuffix($visitNumber) . ' PESO Visit' : 'PESO Visit';
			$description = 'Recorded PESO visit.';
			$icon = '🚶';
			$color = 'blue';
		} elseif ($classification === 'REFERRAL') {
			$status = timelineStatusLabel((string)($row['referral_status'] ?? ''));
			$parts = [];
			if ($companyName !== '') {
				$parts[] = 'Referred to ' . $companyName;
			} else {
				$parts[] = 'Referral recorded';
			}
			if ($position !== '') {
				$parts[] = 'for ' . $position;
			}
			$description = implode(' ', $parts) . '. Status: ' . $status . '.';
			$title = 'Referral';
			$icon = '↗';
			$color = 'purple';
		} else {
			$appliedCount = (int)($row['participation_count'] ?? 0);
			$venue = timelineSafeText($row['venue'] ?? '');
			$eventDate = $row['date_start']
				? date('F j, Y', strtotime((string)$row['date_start']))
				: '';
			$eventBits = [];
			if ($venue !== '') {
				$eventBits[] = $venue;
			}
			if ($eventDate !== '') {
				$eventBits[] = $eventDate;
			}
			$eventText = $eventBits ? ' at ' . implode(' on ', $eventBits) : '';
			$companyText = $companyName !== ''
				? ' including ' . $companyName . ($position !== '' ? ' for ' . $position : '')
				: '';
			$description = 'Participated in a job fair' . $eventText . '. Applied to ' . $appliedCount . ' employer' . ($appliedCount === 1 ? '' : 's') . $companyText . '.';
			$title = 'Job Fair Participation';
			$icon = '📋';
			$color = 'green';
		}

		$timeline[] = [
			'id' => (int)$row['history_id'],
			'type' => $classification === 'PESO_VISIT' ? 'visit' : ($classification === 'REFERRAL' ? 'referral' : 'jobfair'),
			'classification' => $classification,
			'title' => $title,
			'description' => preg_replace('/\s+/', ' ', trim($description)),
			'date' => $date,
			'icon' => $icon,
			'color' => $color,
			'raw' => [
				'company_name' => $companyName,
				'position' => $position,
				'referral_status' => (string)($row['referral_status'] ?? ''),
				'visit_number' => (int)($row['visit_number'] ?? 0),
				'jobfairevent_id' => (int)($row['jobfairevent_id'] ?? 0),
			],
		];
	}
	$stmt->close();

	echo json_encode([
		'success' => true,
		'beneficiary' => ['benef_id' => $benefId],
		'timeline' => $timeline,
	]);
} catch (Throwable $e) {
	echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

function ordinalSuffix(int $n): string {
	$s = ['th', 'st', 'nd', 'rd'];
	$v = $n % 100;
	return $s[($v - 20) % 10] ?? $s[$v] ?? $s[0];
}
