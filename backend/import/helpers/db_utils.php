<?php

function tableExists(mysqli $conn, string $table): bool {
    $stmt = $conn->prepare('
        SELECT 1
        FROM information_schema.tables
        WHERE table_schema = DATABASE() AND table_name = ?
        LIMIT 1
    ');
    $stmt->bind_param('s', $table);
    $stmt->execute();
    return (bool)$stmt->get_result()->fetch_assoc();
}

function tableHasColumn(mysqli $conn, string $table, string $column): bool {
    static $cache = [];
    $key = strtolower($table . '|' . $column);
    if (array_key_exists($key, $cache)) {
        return $cache[$key];
    }

    $stmt = $conn->prepare('SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ? LIMIT 1');
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $exists = (bool)$stmt->get_result()->fetch_assoc();
    $cache[$key] = $exists;
    return $exists;
}

function firstExistingColumn(mysqli $conn, string $table, array $candidates): ?string {
    foreach ($candidates as $column) {
        if (tableHasColumn($conn, $table, $column)) {
            return $column;
        }
    }
    return null;
}
