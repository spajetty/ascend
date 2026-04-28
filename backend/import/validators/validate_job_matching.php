<?php

function validateJobMatchingFamily(mysqli $conn, array $rows, string $program): array {
    return validateBeneficiaries($conn, $rows, $program);
}
