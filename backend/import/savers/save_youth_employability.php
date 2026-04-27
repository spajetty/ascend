<?php

function saveYouthEmployabilityRow(mysqli $conn, array $row, int $benefId, array $ctx, array &$state): string {
    // Current youth/career programs only require beneficiary + docs persistence.
    return 'saved';
}
