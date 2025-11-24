<?php
// Simple CSRF helper functions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function generate_csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token, int $max_age = 3600): bool
{
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    // Use hash_equals to mitigate timing attacks
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        return false;
    }
    if (isset($_SESSION['csrf_token_time']) && (time() - $_SESSION['csrf_token_time']) > $max_age) {
        // Token expired
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
        return false;
    }
    return true;
}
