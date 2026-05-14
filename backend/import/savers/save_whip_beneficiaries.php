<?php

function saveWhipBeneficiariesRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    $projectSchema = resolveWhipProjectsSchema($conn);
    $projectId = isset($row['_sys_project_id']) ? (int)$row['_sys_project_id'] : 0;
    if ($projectId <= 0) {
        $projectId = (int)(findProjectIdForWhipBeneficiary($conn, $row, $projectSchema) ?? 0);
    }
    if ($projectId <= 0) {
        return 'skipped';
    }

    $whipSchema = resolveWhipTableSchema($conn);
    $whipTable = $whipSchema['table'] ?? null;
    $whipBenefCol = $whipSchema['benef_id_col'] ?? null;
    $whipProjectCol = $whipSchema['project_id_col'] ?? null;
    if (!$whipTable || !$whipBenefCol || !$whipProjectCol) {
        throw new RuntimeException('WHIP table is not configured in the database.');
    }

    $dupSql = sprintf('SELECT 1 FROM `%s` WHERE `%s` = ? AND `%s` = ? LIMIT 1', $whipTable, $whipBenefCol, $whipProjectCol);
    $dupStmt = $conn->prepare($dupSql);
    $dupStmt->bind_param('ii', $benefId, $projectId);
    $dupStmt->execute();
    if ($dupStmt->get_result()->fetch_assoc()) {
        return 'skipped';
    }

    $batchId = $ctx['batchId'] ?? null;
    $columns = [$whipBenefCol, $whipProjectCol];
    $placeholders = ['?', '?'];
    $types = 'ii';
    $values = [$benefId, $projectId];

    $positionCol = $whipSchema['position_col'] ?? null;
    $positionVal = s(rowValue($row, ['Position', 'position'], ''));
    if ($positionCol && $positionVal !== '') {
        $columns[] = $positionCol;
        $placeholders[] = '?';
        $types .= 's';
        $values[] = $positionVal;
    }

    $dateHiredCol = $whipSchema['date_hired_col'] ?? null;
    $dateHiredVal = parseDateNullable(rowValue($row, ['Date Hired', 'date_hired', 'date hired', 'DateHired'], ''));
    if ($dateHiredCol && $dateHiredVal !== null) {
        $columns[] = $dateHiredCol;
        $placeholders[] = '?';
        $types .= 's';
        $values[] = $dateHiredVal;
    }

    $whipBatchCol = $whipSchema['batch_id_col'] ?? null;
    if ($whipBatchCol && $batchId !== null) {
        $columns[] = $whipBatchCol;
        $placeholders[] = '?';
        $types .= 'i';
        $values[] = $batchId;
    }

    $quoted = array_map(static fn($c) => '`' . $c . '`', $columns);
    $insWhipSql = sprintf('INSERT INTO `%s` (%s) VALUES (%s)', $whipTable, implode(', ', $quoted), implode(', ', $placeholders));
    $insWhip = $conn->prepare($insWhipSql);
    $insWhip->bind_param($types, ...$values);
    $insWhip->execute();

    $state['insertedWhipIds'][] = (int)$insWhip->insert_id;
    if (empty($state['insertedWhipTable'])) {
        $state['insertedWhipTable'] = $whipTable;
    }

    return 'saved';
}
