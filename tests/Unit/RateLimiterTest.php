<?php

require_once __DIR__ . '/../../api/RateLimiter.php';

function getTestDbConnection() {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../api');
    $dotenv->safeLoad();
    
    $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
    $user = $_ENV['DB_USER'] ?? 'root';
    $pass = $_ENV['DB_PASS'] ?? '';
    $db   = $_ENV['DB_NAME'] ?? 'ascend_db';

    $conn = new mysqli($host, $user, $pass, $db);
    return $conn;
}

beforeEach(function () {
    $this->conn = getTestDbConnection();
    // Ensure test records are clean before every test
    $stmt = $this->conn->prepare("DELETE FROM rate_limits WHERE identifier = 'test_ip_123'");
    $stmt->execute();
    $stmt->close();
});

test('rate limiter allows requests under limit', function () {
    // Simulate 4 requests
    for ($i = 0; $i < 4; $i++) {
        $allowed = RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
        expect($allowed)->toBeTrue();
    }
});

test('rate limiter blocks request when limit exceeded', function () {
    // Simulate 5 requests (hitting the limit)
    for ($i = 0; $i < 5; $i++) {
        RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
    }
    
    // The 6th request should be blocked
    $allowed = RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
    expect($allowed)->toBeFalse();
});

test('rate limiter can clear records', function () {
    // Hit limit
    for ($i = 0; $i < 5; $i++) {
        RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
    }
    
    // Blocked
    $allowed = RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
    expect($allowed)->toBeFalse();
    
    // Clear
    RateLimiter::clear($this->conn, 'test_action', 'test_ip_123');
    
    // Should be allowed again
    $allowed = RateLimiter::check($this->conn, 'test_action', 'test_ip_123', 5, 3600);
    expect($allowed)->toBeTrue();
});
