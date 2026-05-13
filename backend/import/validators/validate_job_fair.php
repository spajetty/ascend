<?php

function validateJobFair(mysqli $conn, array $rows, string $jobFairEvent = ''): array {
    return validateJobMatchingFamily($conn, $rows, 'Job Fair', $jobFairEvent);
}