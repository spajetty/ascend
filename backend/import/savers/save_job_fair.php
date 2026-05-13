<?php

function saveJobFairRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    $ctx['program'] = 'Job Fair';
    return saveJobMatchingFamilyRow($conn, $row, $benefId, $ctx, $state);
}