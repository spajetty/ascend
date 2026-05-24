<?php

function readCacheRefreshCount(string $cachePath): int
{
    if (!file_exists($cachePath)) {
        return 0;
    }

    $existingCache = json_decode((string) file_get_contents($cachePath), true);
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($existingCache)) {
        return 0;
    }

    return (int) ($existingCache['data']['cache_refresh_count'] ?? 0);
}

function persistCacheResponse(string $cachePath, array $payload): array
{
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

function refreshCdspCache(mysqli $conn, int $year): array
{
    $years = [];

    $yearQuery = $conn->query("\n        SELECT DISTINCT YEAR(date_of_conduct) AS yr\n        FROM careerdev\n        WHERE date_of_conduct IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }
    $yearQuery->free();

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
    $result->free();
    $stmt->close();

    $cachePath = __DIR__ . '/../../cache/fetch-cdsp.json';
    $payload = [
        'rows' => $rows,
        'totals' => $totals,
        'years' => $years,
        'year' => $year,
        'cache_refresh_count' => readCacheRefreshCount($cachePath) + 1,
        'cache_refreshed_at' => date('Y-m-d H:i:s'),
    ];

    return persistCacheResponse($cachePath, $payload);
}

function refreshLmiCache(mysqli $conn, int $year): array
{
    $years = [];

    $yearQuery = $conn->query("\n        SELECT DISTINCT YEAR(date_of_conduct) AS yr\n        FROM lmi\n        WHERE date_of_conduct IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearQuery->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }
    $yearQuery->free();

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
    $result->free();
    $stmt->close();

    $cachePath = __DIR__ . '/../../cache/fetch-lmi.json';
    $payload = [
        'rows' => $rows,
        'totals' => $totals,
        'years' => $years,
        'year' => $year,
        'cache_refresh_count' => readCacheRefreshCount($cachePath) + 1,
        'cache_refreshed_at' => date('Y-m-d H:i:s'),
    ];

    return persistCacheResponse($cachePath, $payload);
}

function refreshEmployersCache(mysqli $conn, $yearFilter): array
{
    $yearFilter = (string) $yearFilter;
    $years = [];

    $yearRes = $conn->query("
        SELECT DISTINCT year
        FROM employers_accreditations
        ORDER BY year DESC
    ");

    while ($row = $yearRes->fetch_assoc()) {
        $years[] = (int) $row['year'];
    }
    $yearRes->free();

    $monthNames = [
        1 => 'January',  2 => 'February', 3 => 'March',
        4 => 'April',    5 => 'May',       6 => 'June',
        7 => 'July',     8 => 'August',    9 => 'September',
        10 => 'October', 11 => 'November', 12 => 'December',
    ];

    $rows = [];
    $newCount = 0;
    $renewCount = 0;
    $activeSet = [];
    $totalUnique = 0;

    if ($yearFilter === 'all' || $yearFilter === '') {

        $stmt = $conn->prepare("
            SELECT
                e.company_id, e.company_name, e.est_type, e.industry, e.city, e.created_at,
                ea.accreditation_id, ea.status AS accreditation, ea.month, ea.year
            FROM employers e
            LEFT JOIN (
                SELECT ea1.*
                FROM employers_accreditations ea1
                INNER JOIN (
                    SELECT company_id, MAX(accreditation_id) AS max_id
                    FROM employers_accreditations
                    GROUP BY company_id
                ) latest ON ea1.accreditation_id = latest.max_id
            ) ea ON e.company_id = ea.company_id
            ORDER BY e.company_name ASC
        ");
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $monthNumber = isset($row['month']) ? (int) $row['month'] : null;
            $row['month_number'] = $monthNumber;
            $row['month'] = $monthNumber ? ($monthNames[$monthNumber] ?? null) : null;
            $rows[] = $row;

            if ($row['accreditation'] === 'new')   $newCount++;
            if ($row['accreditation'] === 'renew')  $renewCount++;
            if (!empty($row['accreditation']))       $activeSet[$row['company_name']] = true;
        }
        $result->free();
        $stmt->close();

        // ✅ Run count query AFTER stmt is fully closed
        $totalRes = $conn->query("
            SELECT COUNT(DISTINCT company_name) AS cnt FROM employers
        ");
        $totalRow = $totalRes->fetch_assoc();
        $totalUnique = (int) ($totalRow['cnt'] ?? 0);
        $totalRes->free();

        $cacheYear = 'all';

    } else {
        $selectedYear = (int) $yearFilter;

        $stmt = $conn->prepare("
            SELECT
                e.company_id, e.company_name, e.est_type, e.industry, e.city, e.created_at,
                ea.accreditation_id, ea.status AS accreditation, ea.month, ea.year
            FROM employers e
            LEFT JOIN (
                SELECT ea1.*
                FROM employers_accreditations ea1
                INNER JOIN (
                    SELECT company_id, MAX(accreditation_id) AS max_id
                    FROM employers_accreditations
                    WHERE year = ?
                    GROUP BY company_id
                ) latest ON ea1.accreditation_id = latest.max_id
            ) ea ON e.company_id = ea.company_id
            WHERE ea.accreditation_id IS NOT NULL
            ORDER BY e.company_name ASC
        ");
        $stmt->bind_param('i', $selectedYear);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $monthNumber = isset($row['month']) ? (int) $row['month'] : null;
            $row['month_number'] = $monthNumber;
            $row['month'] = $monthNumber ? ($monthNames[$monthNumber] ?? null) : null;
            $rows[] = $row;

            if ($row['accreditation'] === 'new')   $newCount++;
            if ($row['accreditation'] === 'renew')  $renewCount++;
            if (!empty($row['accreditation']))       $activeSet[$row['company_name']] = true;
        }
        $result->free();
        $stmt->close();

        // ✅ Run count query AFTER stmt is fully closed
        $totalRes = $conn->prepare("
            SELECT COUNT(DISTINCT company_id) AS cnt
            FROM employers_accreditations
            WHERE year = ?
        ");
        $totalRes->bind_param('i', $selectedYear);
        $totalRes->execute();
        $totalResult = $totalRes->get_result();
        $totalRow = $totalResult->fetch_assoc();
        $totalResult->free();
        $totalUnique = (int) ($totalRow['cnt'] ?? 0);
        $totalRes->close();

        $cacheYear = $selectedYear;
    }

    $cachePath = __DIR__ . '/../../cache/fetch-employers.json';
    $payload = [
        'rows'   => $rows,
        'years'  => $years,
        'year'   => $cacheYear,
        'totals' => [
            'total'   => $totalUnique,
            'new'     => $newCount,
            'renewed' => $renewCount,
            'active'  => count($activeSet),
        ],
        'cache_refresh_count' => readCacheRefreshCount($cachePath) + 1,
        'cache_refreshed_at'  => date('Y-m-d H:i:s'),
    ];

    return persistCacheResponse($cachePath, $payload);
}

function refreshWhipCache(mysqli $conn, $yearFilter): array
{
    $yearFilter = (string) $yearFilter;
    $years = [];

    $yearRes = $conn->query("\n        SELECT DISTINCT YEAR(date_hired) AS yr\n        FROM whip\n        WHERE date_hired IS NOT NULL\n        ORDER BY yr DESC\n    ");

    while ($row = $yearRes->fetch_assoc()) {
        $years[] = (int) $row['yr'];
    }
    $yearRes->free();

    $sql = "\n        SELECT\n            w.whip_id,\n            w.benef_id,\n            w.project_id,\n            w.batch_id,\n            w.position,\n            w.date_hired,\n            w.created_at,\n\n            b.first_name,\n            b.middle_name,\n            b.last_name,\n            b.suffix,\n            b.sex,\n            b.city,\n            b.barangay,\n            b.district,\n            b.classification,\n\n            p.project_title,\n            p.nature_of_project,\n            p.duration,\n            p.budget,\n            p.fund_source,\n            p.persons_from_locality,\n            p.skills_required,\n            p.skills_deficiencies,\n            p.contractor,\n            p.is_legitimate_contractor,\n            p.filled,\n            p.unfilled\n\n        FROM whip w\n        LEFT JOIN beneficiaries b ON w.benef_id = b.benef_id\n        LEFT JOIN projects p ON w.project_id = p.project_id\n    ";

    $params = [];
    $types = '';

    if ($yearFilter !== 'all' && $yearFilter !== '') {
        $sql .= " WHERE YEAR(w.date_hired) = ? ";
        $params[] = (int) $yearFilter;
        $types .= 'i';
    }

    $sql .= " ORDER BY w.date_hired DESC, b.last_name ASC, b.first_name ASC";

    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();

    $result = $stmt->get_result();
    $rows = [];
    $maleCount = 0;
    $femaleCount = 0;
    $projectSet = [];

    while ($row = $result->fetch_assoc()) {
        $row['budget'] = $row['budget'] !== null ? (float) $row['budget'] : null;
        $row['persons_from_locality'] = (int) ($row['persons_from_locality'] ?? 0);
        $row['is_legitimate_contractor'] = (bool) ($row['is_legitimate_contractor'] ?? false);
        $row['filled'] = (int) ($row['filled'] ?? 0);
        $row['unfilled'] = (int) ($row['unfilled'] ?? 0);

        $sex = strtolower($row['sex'] ?? '');
        if ($sex === 'male') {
            $maleCount++;
        }
        if ($sex === 'female') {
            $femaleCount++;
        }

        if (!empty($row['project_id'])) {
            $projectSet[$row['project_id']] = true;
        }

        $rows[] = $row;
    }
    $result->free();
    $stmt->close();

    $cachePath = __DIR__ . '/../../cache/fetch-whip.json';
    $payload = [
        'rows' => $rows,
        'years' => $years,
        'year' => $yearFilter === 'all' || $yearFilter === '' ? 'all' : (int) $yearFilter,
        'default_year' => !empty($years) ? max($years) : (int) date('Y'),
        'totals' => [
            'total' => $maleCount + $femaleCount,
            'male' => $maleCount,
            'female' => $femaleCount,
            'projects' => count($projectSet),
        ],
        'cache_refresh_count' => readCacheRefreshCount($cachePath) + 1,
        'cache_refreshed_at' => date('Y-m-d H:i:s'),
    ];

    return persistCacheResponse($cachePath, $payload);
}