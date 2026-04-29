<?php

function saveWiirpRow(mysqli $conn, array $row, array $ctx, array &$state): string {
	$table = 'wiirp';
	if (!tableExists($conn, $table)) {
		throw new RuntimeException('WIIRP table is not configured in the database.');
	}

	$sanitizedRow = $row;
	foreach (['DOB', 'Birthday', 'dob', 'birthday'] as $birthdayKey) {
		unset($sanitizedRow[$birthdayKey]);
	}
	$sanitizedRow['_parsed_dob'] = null;

	$benefId = isset($sanitizedRow['_sys_benef_id']) ? (int)$sanitizedRow['_sys_benef_id'] : 0;
	if ($benefId <= 0) {
		$benefId = (int)(ensurePersonBeneficiaryAndDocs($conn, $sanitizedRow, $ctx, $state) ?? 0);
	}

	if ($benefId <= 0) {
		return 'skipped';
	}

	$shape = resolveWhipTableSchema($conn);

	$contractPeriod = trim((string)($ctx['importMonthRaw'] ?? '') . ' ' . (string)($ctx['importYearRaw'] ?? ''));
	$school = s(rowValue($row, ['School Name', 'School', 'school'], ''));
	$course = s(rowValue($row, ['Course/Degree/Strand', 'Course', 'course'], ''));
	$requiredHours = parseIntNullable(rowValue($row, ['Required Work Immersion / Internship Hours', 'Required Hours', 'required_hours', '# of hours', 'Number of hours'], ''));
	$inquiryType = s(rowValue($row, ['Inquiry Via', 'Inquiry Type', 'inquiry_type'], ''));
	$preferredOrgType = s(rowValue($row, ['Preferred Host Organization Type', 'preferred_org_type'], ''));
	$preferredIndustry = s(rowValue($row, ['Preferred Industry / Field of Internship', 'preferred_industry'], ''));
	$isWillingOutside = toBoolInt(rowValue($row, ['Are you willing to be assigned outside your preferred field if not available?', 'is_willing_outside'], ''));
	$internshipSched = s(rowValue($row, ['Internship Schedule / Availability', 'internship_sched'], ''));
	$startDate = !empty($row['_parsed_start_date']) ? (string)$row['_parsed_start_date'] : parseDateNullable(rowValue($row, ['Internship Availability Date (Start of Internship)', 'Starting Date', 'start'], ''));
	$endDate = !empty($row['_parsed_end_date']) ? (string)$row['_parsed_end_date'] : parseDateNullable(rowValue($row, ['Est. End', 'Estimated End', 'Est End', 'End Date', 'End'], ''));
	$yearLevel = s(rowValue($row, ['Year Level', 'year_level'], ''));
	$wiirpType = s($ctx['wiirpCategory'] ?? '');
	$officeAssignment = s(rowValue($row, ['Office Assign', 'Office Assignment', 'Office Assginment', 'office_assignment'], ''));
	$endorsement1 = s(rowValue($row, ['Endorsement 1', 'Endorsement_1', 'endorsement_1', 'endorsement 1'], ''));
	$endorsement2 = s(rowValue($row, ['Endorsement 2', 'Endorsement_2', 'endorsement_2'], ''));

	$dupSqlParts = ['`benef_id` = ?'];
	$dupTypes = 'i';
	$dupValues = [$benefId];
	// Use detected schema column names if available
	$contractPeriodCol = $shape['contract_period_col'] ?? 'contract_period';
	$typeCol = $shape['type_col'] ?? 'type';
	if ($contractPeriod !== '' && $contractPeriodCol !== null) {
		$dupSqlParts[] = sprintf('`%s` = ?', $contractPeriodCol);
		$dupTypes .= 's';
		$dupValues[] = $contractPeriod;
	}
	if ($wiirpType !== '' && $typeCol !== null) {
		$dupSqlParts[] = sprintf('`%s` = ?', $typeCol);
		$dupTypes .= 's';
		$dupValues[] = $wiirpType;
	}

	$dupStmt = $conn->prepare(sprintf('SELECT 1 FROM `%s` WHERE %s LIMIT 1', $table, implode(' AND ', $dupSqlParts)));
	$dupStmt->bind_param($dupTypes, ...$dupValues);
	$dupStmt->execute();
	if ($dupStmt->get_result()->fetch_assoc()) {
		return 'skipped';
	}

	$columns = ['benef_id'];
	$placeholders = ['?'];
	$types = 'i';
	$values = [$benefId];

	// Helper to add a column if the schema has it
	$addCol = function($colName, $val, $typeChar) use (&$columns, &$placeholders, &$types, &$values) {
		$columns[] = $colName;
		$placeholders[] = '?';
		$types .= $typeChar;
		$values[] = $val;
	};

	if (!empty($contractPeriod) && ($shape['contract_period_col'] ?? null)) {
		$addCol($shape['contract_period_col'], $contractPeriod, 's');
	} elseif (!empty($contractPeriod)) {
		$addCol('contract_period', $contractPeriod, 's');
	}

	if ($school !== '') {
		$col = $shape['school_col'] ?? 'school';
		$addCol($col, $school, 's');
	}
	if ($course !== '') {
		$col = $shape['course_col'] ?? 'course';
		$addCol($col, $course, 's');
	}
	if ($requiredHours !== null) {
		$col = $shape['required_hours_col'] ?? 'required_hours';
		$addCol($col, $requiredHours, 'i');
	}
	if ($inquiryType !== '') {
		$col = $shape['inquiry_type_col'] ?? 'inquiry_type';
		$addCol($col, $inquiryType, 's');
	}
	if ($preferredOrgType !== '') {
		$col = $shape['preferred_org_type_col'] ?? 'preferred_org_type';
		$addCol($col, $preferredOrgType, 's');
	}
	if ($preferredIndustry !== '') {
		$col = $shape['preferred_industry_col'] ?? 'preferred_industry';
		$addCol($col, $preferredIndustry, 's');
	}
	$col = $shape['is_willing_outside_col'] ?? 'is_willing_outside';
	$addCol($col, $isWillingOutside, 'i');
	if ($internshipSched !== '') {
		$col = $shape['internship_sched_col'] ?? 'internship_sched';
		$addCol($col, $internshipSched, 's');
	}
	if ($startDate !== null && $startDate !== '') {
		$col = $shape['start_col'] ?? 'start';
		$addCol($col, $startDate, 's');
	}
	if ($endDate !== null && $endDate !== '') {
		$col = $shape['end_col'] ?? null;
		if ($col) $addCol($col, $endDate, 's');
	}
	if ($yearLevel !== '') {
		$col = $shape['year_level_col'] ?? 'year_level';
		$addCol($col, $yearLevel, 's');
	}
	if ($wiirpType !== '') {
		$col = $shape['type_col'] ?? 'type';
		$addCol($col, $wiirpType, 's');
	}

	// Private-specific fields: office assignment and endorsements
	if ($officeAssignment !== '') {
		$col = $shape['office_assignment_col'] ?? null;
		if ($col) $addCol($col, $officeAssignment, 's');
	}
	if ($endorsement1 !== '') {
		$col = $shape['endorsement1_col'] ?? null;
		if ($col) $addCol($col, $endorsement1, 's');
	}
	if ($endorsement2 !== '') {
		$col = $shape['endorsement2_col'] ?? null;
		if ($col) $addCol($col, $endorsement2, 's');
	}

	$quoted = array_map(static fn($column) => '`' . $column . '`', $columns);
	$insSql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $table, implode(', ', $quoted), implode(', ', $placeholders));
	$stmt = $conn->prepare($insSql);
	$stmt->bind_param($types, ...$values);
	$stmt->execute();

	$insertedId = (int)$stmt->insert_id;
	$state['insertedWhipIds'][] = $insertedId;
	if (empty($state['insertedWhipTable'])) {
		$state['insertedWhipTable'] = $table;
	}

	// If this is a private WIIRP, also insert into wiirp_private_details (if the table exists)
	if (strtolower($wiirpType) === 'private') {
		$privateTable = 'wiirp_private_details';
		if (tableExists($conn, $privateTable)) {
			$pdCols = [];
			$pdPlaceholders = [];
			$pdTypes = '';
			$pdValues = [];

			// work_immersion_id
			$pdCols[] = 'work_immersion_id'; $pdPlaceholders[] = '?'; $pdTypes .= 'i'; $pdValues[] = $insertedId;
			// office_assignment
			if ($officeAssignment !== '') { $pdCols[] = 'office_assignment'; $pdPlaceholders[] = '?'; $pdTypes .= 's'; $pdValues[] = $officeAssignment; }
			// endorsement_1
			if ($endorsement1 !== '') { $pdCols[] = 'endorsement_1'; $pdPlaceholders[] = '?'; $pdTypes .= 's'; $pdValues[] = $endorsement1; }
			// endorsement_2
			if ($endorsement2 !== '') { $pdCols[] = 'endorsement_2'; $pdPlaceholders[] = '?'; $pdTypes .= 's'; $pdValues[] = $endorsement2; }
			// start_date
			if (!empty($startDate)) { $pdCols[] = 'start_date'; $pdPlaceholders[] = '?'; $pdTypes .= 's'; $pdValues[] = $startDate; }
			// end_date
			if (!empty($endDate)) { $pdCols[] = 'end_date'; $pdPlaceholders[] = '?'; $pdTypes .= 's'; $pdValues[] = $endDate; }
			// required_hours
			if ($requiredHours !== null) { $pdCols[] = 'required_hours'; $pdPlaceholders[] = '?'; $pdTypes .= 'i'; $pdValues[] = $requiredHours; }

			if (!empty($pdCols)) {
				$sql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $privateTable, implode(', ', array_map(fn($c) => '`' . $c . '`', $pdCols)), implode(', ', $pdPlaceholders));
				$ins = $conn->prepare($sql);
				$ins->bind_param($pdTypes, ...$pdValues);
				$ins->execute();
				$state['insertedWiirpPrivateIds'][] = (int)$ins->insert_id;
			}
		}
	}

	return 'saved';
}
