<?php

function refreshCdspCache(mysqli $conn, int $year): array
{
    $years = [];

    $yearQuery = $conn->query("\n        SELECT DISTINCT YEAR(date_of_conduct) AS yr\n        FROM careerdev\n        WHERE date_of_conduct IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }

    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    $stmt = $conn->prepare("\n        SELECT\n            c.cdsp_id,\n            c.date_of_conduct,\n            c.grade_level,\n            c.participants_male,\n            c.participants_female,\n            c.approval_letter,\n            c.created_at,\n            s.school_id,\n            s.school_name,\n            s.congressional_district,\n            s.grades_offered\n        FROM careerdev c\n        LEFT JOIN schools s\n        ON c.school_id = s.school_id\n        WHERE YEAR(c.date_of_conduct) = ?\n        ORDER BY c.date_of_conduct ASC\n    ");

    $stmt->bind_param('i', $year);
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];
    $totals = [
        'sessions' => 0,
        'total_m' => 0,
        'total_f' => 0,
        'total' => 0,
    ];

    while ($row = $result->fetch_assoc()) {
        $row['participants_male'] = (int) $row['participants_male'];
        $row['participants_female'] = (int) $row['participants_female'];
        $row['total'] = $row['participants_male'] + $row['participants_female'];
        $rows[] = $row;
        $totals['sessions']++;
        $totals['total_m'] += $row['participants_male'];
        $totals['total_f'] += $row['participants_female'];
        $totals['total'] += $row['total'];
    }

    $cachePath = __DIR__ . '/../../cache/fetch-cdsp.json';
    $cacheRefreshCount = 0;
    if (file_exists($cachePath)) {
        $existingCache = json_decode((string) file_get_contents($cachePath), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($existingCache)) {
            $cacheRefreshCount = (int) ($existingCache['data']['cache_refresh_count'] ?? 0);
        }
    }

    $payload = [
        'rows' => $rows,
        'totals' => $totals,
        'years' => $years,
        'year' => $year,
        'cache_refresh_count' => $cacheRefreshCount + 1,
        'cache_refreshed_at' => date('Y-m-d H:i:s'),
    ];

    $response = [
        'success' => true,
        'data' => $payload,
    ];

    if (!is_dir(dirname($cachePath))) {
        mkdir(dirname($cachePath), 0755, true);
    }

    file_put_contents($cachePath, json_encode($response, JSON_PRETTY_PRINT));

    return $response;
}

function refreshLmiCache(mysqli $conn, int $year): array
{
    $years = [];

    $yearQuery = $conn->query("\n        SELECT DISTINCT YEAR(date_of_conduct) AS yr\n        FROM lmi\n        WHERE date_of_conduct IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }

    if (!in_array($year, $years, true)) {
        $years[] = $year;
        rsort($years);
    }

    $stmt = $conn->prepare("\n        SELECT\n            l.lmi_id,\n            l.date_of_conduct,\n            l.grade_level,\n            l.participants_male,\n            l.participants_female,\n            l.approval_letter,\n            l.created_at,\n            s.school_id,\n            s.school_name,\n            s.congressional_district,\n            s.grades_offered\n        FROM lmi l\n        LEFT JOIN schools s\n        ON l.school_id = s.school_id\n        WHERE YEAR(l.date_of_conduct) = ?\n        ORDER BY l.date_of_conduct ASC\n    ");

    $stmt->bind_param('i', $year);
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];
    $totals = [
        'sessions' => 0,
        'total_m' => 0,
        'total_f' => 0,
        'total' => 0,
    ];

    while ($row = $result->fetch_assoc()) {
        $row['participants_male'] = (int) $row['participants_male'];
        $row['participants_female'] = (int) $row['participants_female'];
        $row['total'] = $row['participants_male'] + $row['participants_female'];
        $rows[] = $row;
        $totals['sessions']++;
        $totals['total_m'] += $row['participants_male'];
        $totals['total_f'] += $row['participants_female'];
        $totals['total'] += $row['total'];
    }

    $cachePath = __DIR__ . '/../../cache/fetch-lmi.json';
    $cacheRefreshCount = 0;
    if (file_exists($cachePath)) {
        $existingCache = json_decode((string) file_get_contents($cachePath), true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($existingCache)) {
            $cacheRefreshCount = (int) ($existingCache['data']['cache_refresh_count'] ?? 0);
        }
    }

    $payload = [
        'rows' => $rows,
        'totals' => $totals,
        'years' => $years,
        'year' => $year,
        'cache_refresh_count' => $cacheRefreshCount + 1,
        'cache_refreshed_at' => date('Y-m-d H:i:s'),
    ];

    $response = [
        'success' => true,
        'data' => $payload,
    ];

    if (!is_dir(dirname($cachePath))) {
        mkdir(dirname($cachePath), 0755, true);
    }

    file_put_contents($cachePath, json_encode($response, JSON_PRETTY_PRINT));

    return $response;
}