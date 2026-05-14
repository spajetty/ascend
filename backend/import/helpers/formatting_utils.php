<?php

function s($val): string {
    return trim((string)($val ?? ''));
}

function rowValue(array $row, array $keys, $default = '') {
    foreach ($row as $k => $v) {
        foreach ($keys as $expected) {
            if (strcasecmp(trim((string)$k), trim((string)$expected)) === 0) {
                if ($v !== null && trim((string)$v) !== '') {
                    return trim((string)$v);
                }
            }
        }
    }
    return $default;
}

// Keep getRowVal as an alias to rowValue to not break existing usage if missed
function getRowVal(array $row, array $keys, $default = '') {
    return rowValue($row, $keys, $default);
}

function monthToInt($val): ?int {
    $raw = trim((string)($val ?? ''));
    if ($raw === '') return null;

    if (ctype_digit($raw)) {
        $n = (int)$raw;
        return ($n >= 1 && $n <= 12) ? $n : null;
    }

    $ts = strtotime('1 ' . $raw);
    return $ts ? (int)date('n', $ts) : null;
}

function toBoolInt($val): int {
    if (is_bool($val)) return $val ? 1 : 0;
    $raw = strtolower(trim((string)($val ?? '')));
    if ($raw === '') return 0;
    return in_array($raw, ['1', 'true', 'yes', 'y', 'checked', 'x'], true) ? 1 : 0;
}

function normalizeKeyText($value): string {
    $text = strtolower(trim((string)($value ?? '')));
    if ($text === '') return '';
    $text = preg_replace('/[\s\t\r\n]+/', ' ', $text);
    $text = preg_replace('/[^a-z0-9 ]+/', ' ', $text);
    return trim(preg_replace('/\s+/', ' ', $text));
}

function normalizeMoneyValue($value): string {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return '';
    $clean = preg_replace('/[^0-9.\-]/', '', $raw);
    if ($clean !== '' && is_numeric($clean)) {
        return number_format((float)$clean, 2, '.', '');
    }
    return normalizeKeyText($raw);
}

function parseMoneyNullable($value): ?float {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return null;
    $clean = preg_replace('/[^0-9.\-]/', '', $raw);
    return ($clean !== '' && is_numeric($clean)) ? (float)$clean : null;
}

function parseIntNullable($value): ?int {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return null;
    return is_numeric($raw) ? (int)$raw : null;
}

function parseDateNullable($value): ?string {
    $raw = trim((string)($value ?? ''));
    if ($raw === '') return null;

    // Normalize Unicode whitespace (NBSP, narrow NBSP, zero-width, BOM) to regular spaces
    // and collapse repeated whitespace so strtotime can parse reliably.
    $raw = preg_replace('/[\x{00A0}\x{202F}\x{200B}\x{FEFF}]+/u', ' ', $raw);
    $raw = preg_replace('/\s+/u', ' ', $raw);
    $raw = trim($raw);

    // Handle Excel numeric dates
    if (is_numeric($raw) && (int)$raw > 1000) {
        $unix = ((int)$raw - 25569) * 86400;
        return date('Y-m-d', $unix);
    }

    // Handle common date formats explicitly
    $patterns = [
        // MM/DD/YYYY or M/D/YYYY
        '/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/' => fn($m) => $m[3] . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT),
        // DD/MM/YYYY or D/M/YYYY (less common in US, but try after MM/DD fails)
        '/^(\d{1,2})-(\d{1,2})-(\d{4})$/' => fn($m) => $m[3] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[1], 2, '0', STR_PAD_LEFT),
        // YYYY-MM-DD (already correct format)
        '/^(\d{4})-(\d{1,2})-(\d{1,2})$/' => fn($m) => $m[1] . '-' . str_pad($m[2], 2, '0', STR_PAD_LEFT) . '-' . str_pad($m[3], 2, '0', STR_PAD_LEFT),
    ];

    foreach ($patterns as $pattern => $formatter) {
        if (preg_match($pattern, $raw, $matches)) {
            $formatted = $formatter($matches);
            // Validate that the date is actually valid
            if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $formatted, $parts)) {
                $y = (int)$parts[1];
                $m = (int)$parts[2];
                $d = (int)$parts[3];
                if (checkdate($m, $d, $y)) {
                    return $formatted;
                }
            }
        }
    }

    // Fallback to strtotime for other formats
    $ts = strtotime($raw);
    return $ts ? date('Y-m-d', $ts) : null;
}

function parseExcelDate($value): ?string {
    return parseDateNullable($value);
}

function normalizeEmployerName(string $name): string {
    $name = strtolower(trim($name));
    $name = preg_replace('/[\.,\-\(\)\[\]\{\}]+/', ' ', $name);
    $name = trim(preg_replace('/\s+/', ' ', $name));

    $suffixes = [
        ' corporation', ' corp', ' incorporated', ' inc',
        ' company', ' co', ' limited', ' ltd', ' llc', ' l.l.c',
    ];

    $changed = true;
    while ($changed) {
        $changed = false;
        foreach ($suffixes as $suffix) {
            if ($suffix !== '' && substr($name, -strlen($suffix)) === $suffix) {
                $name = trim(substr($name, 0, -strlen($suffix)));
                $changed = true;
            }
        }
        $name = preg_replace('/\s+/', ' ', trim($name));
    }

    return trim($name);
}

function joinRowValue(string $existing, string $addition): string {
    $existing = trim($existing);
    $addition = trim($addition);
    if ($addition === '') return $existing;
    if ($existing === '') return $addition;

    $parts = array_map('trim', explode('|', $existing));
    foreach ($parts as $p) {
        if (strcasecmp($p, $addition) === 0) {
            return $existing;
        }
    }

    return $existing . ' | ' . $addition;
}
