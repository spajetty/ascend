<?php

function saveWhipProjectsRow(mysqli $conn, array $row, array $ctx, array &$state): string {
    $payload = whipProjectPayload($row);
    if ($payload['title'] === '' || $payload['contractor'] === '') {
        return 'skipped';
    }

    $schema = resolveWhipProjectsSchema($conn);
    $table = $schema['table'] ?? null;
    $titleCol = $schema['title_col'] ?? null;
    if (!$table || !$titleCol) {
        throw new RuntimeException('WHIP Projects table is not configured in the database.');
    }

    $batchId = $ctx['batchId'] ?? null;
    $programId = $ctx['programId'] ?? null;

    $employerResult = resolveEmployer($conn, $payload['contractor'], $batchId, [
        'est_type' => rowValue($row, ['Establishment Type', 'Est Type', 'est_type'], ''),
        'industry' => rowValue($row, ['Industry', 'industry'], ''),
        'city' => rowValue($row, ['City/Municipality', 'City', 'city'], ''),
    ]);
    $companyId = (int)($employerResult['id'] ?? 0);
    if (!empty($employerResult['created']) && $companyId > 0) {
        $state['createdEmployerIds'][] = $companyId;
        $state['warnings'][] = 'New company created: ' . ($employerResult['matched_name'] ?? $payload['contractor']);
    }

    if (whipProjectAlreadyExists($conn, $schema, $payload, $companyId > 0 ? $companyId : null)) {
        return 'skipped';
    }

    $columns = [];
    $placeholders = [];
    $types = '';
    $values = [];

    $addValue = static function (string $column, string $type, $value) use (&$columns, &$placeholders, &$types, &$values) {
        $columns[] = $column;
        $placeholders[] = '?';
        $types .= $type;
        $values[] = $value;
    };

    $addValue($titleCol, 's', $payload['title']);

    if (($col = $schema['nature_col'] ?? null) && $payload['nature'] !== '') {
        $addValue($col, 's', $payload['nature']);
    }
    if (($col = $schema['duration_col'] ?? null) && $payload['duration'] !== '') {
        $addValue($col, 's', $payload['duration']);
    }
    if (($col = $schema['budget_col'] ?? null)) {
        $budgetValue = parseMoneyNullable($payload['budget_raw']);
        if ($budgetValue !== null) {
            $addValue($col, 'd', $budgetValue);
        }
    }
    if (($col = $schema['fund_col'] ?? null) && $payload['fund_source'] !== '') {
        $addValue($col, 's', $payload['fund_source']);
    }
    if (($col = $schema['jobs_generated_col'] ?? null)) {
        $v = parseIntNullable($payload['jobs_generated']);
        if ($v !== null) {
            $addValue($col, 'i', $v);
        }
    }
    if (($col = $schema['persons_locality_col'] ?? null)) {
        $v = parseIntNullable($payload['persons_locality']);
        if ($v !== null) {
            $addValue($col, 'i', $v);
        }
    }
    if (($col = $schema['skills_required_col'] ?? null) && $payload['skills_required'] !== '') {
        $addValue($col, 's', $payload['skills_required']);
    }
    if (($col = $schema['skills_def_col'] ?? null) && $payload['skills_deficiencies'] !== '') {
        $addValue($col, 's', $payload['skills_deficiencies']);
    }

    if (($col = $schema['company_id_col'] ?? null) && $companyId > 0) {
        $addValue($col, 'i', $companyId);
    } elseif (($col = $schema['contractor_col'] ?? null) && $payload['contractor'] !== '') {
        $addValue($col, 's', $payload['contractor']);
    }

    if (($col = $schema['legit_col'] ?? null) && $payload['legitimate_contractors'] !== '') {
        $legit = in_array($payload['legitimate_contractors'], ['YES', 'NO'], true)
            ? $payload['legitimate_contractors']
            : null;
        if ($legit !== null) {
            $addValue($col, 's', $legit);
        }
    }
    if (($col = $schema['filled_col'] ?? null)) {
        $v = parseIntNullable($payload['filled']);
        if ($v !== null) {
            $addValue($col, 'i', $v);
        }
    }
    if (($col = $schema['unfilled_col'] ?? null)) {
        $v = parseIntNullable($payload['unfilled']);
        if ($v !== null) {
            $addValue($col, 'i', $v);
        }
    }
    if (($col = $schema['program_id_col'] ?? null) && $programId !== null) {
        $addValue($col, 'i', $programId);
    }
    if (($col = $schema['batch_id_col'] ?? null) && $batchId !== null) {
        $addValue($col, 'i', $batchId);
    }

    $quotedColumns = array_map(static fn($c) => '`' . $c . '`', $columns);
    $sql = sprintf(
        'INSERT INTO `%s` (%s) VALUES (%s)',
        $table,
        implode(', ', $quotedColumns),
        implode(', ', $placeholders)
    );
    $ins = $conn->prepare($sql);
    $ins->bind_param($types, ...$values);
    $ins->execute();

    $state['insertedProjectIds'][] = (int)$ins->insert_id;
    if (empty($state['insertedProjectTable'])) {
        $state['insertedProjectTable'] = $table;
    }

    return 'saved';
}
