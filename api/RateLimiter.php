<?php

class RateLimiter {
    /**
     * Checks if a request is within the rate limit.
     * Automatically cleans up expired records.
     * 
     * @param \mysqli $conn The database connection
     * @param string $action The action identifier (e.g., 'login', 'import')
     * @param string $identifier The user identifier (e.g., IP address, user_id, email)
     * @param int $maxAttempts The maximum number of allowed attempts in the window
     * @param int $windowInSeconds The time window in seconds
     * @return bool True if allowed, false if limit exceeded
     */
    public static function check(\mysqli $conn, string $action, string $identifier, int $maxAttempts, int $windowInSeconds): bool {
        // 1. Delete old records for this action & identifier
        $cutoff = gmdate("Y-m-d H:i:s", time() - $windowInSeconds);
        $stmt = $conn->prepare("DELETE FROM rate_limits WHERE action = ? AND identifier = ? AND created_at < ?");
        $stmt->bind_param("sss", $action, $identifier, $cutoff);
        $stmt->execute();
        $stmt->close();

        // 2. Count recent records
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM rate_limits WHERE action = ? AND identifier = ?");
        $stmt->bind_param("ss", $action, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['count'] >= $maxAttempts) {
            return false; // Limit exceeded
        }

        // 3. Record the new attempt
        $stmt = $conn->prepare("INSERT INTO rate_limits (action, identifier, created_at) VALUES (?, ?, UTC_TIMESTAMP())");
        $stmt->bind_param("ss", $action, $identifier);
        $stmt->execute();
        $stmt->close();

        return true;
    }

    /**
     * Clears rate limit records for a specific action and identifier
     * (e.g. clear failed login attempts upon successful login)
     */
    public static function clear(\mysqli $conn, string $action, string $identifier): void {
        $stmt = $conn->prepare("DELETE FROM rate_limits WHERE action = ? AND identifier = ?");
        $stmt->bind_param("ss", $action, $identifier);
        $stmt->execute();
        $stmt->close();
    }
}
?>
