<?php

function validateWhipBeneficiaries(mysqli $conn, array $rows): array {
    return validateBeneficiaries($conn, $rows, 'Workers Hiring for Infrastructure Projects - Beneficiaries');
}
