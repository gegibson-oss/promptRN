<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function csrf_start_session_if_needed(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => app_is_https(),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);

    session_start();
}

function csrf_token(): string
{
    csrf_start_session_if_needed();

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return (string) $_SESSION['csrf_token'];
}

function csrf_validate(?string $token): bool
{
    csrf_start_session_if_needed();

    if ($token === null || !isset($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals((string) $_SESSION['csrf_token'], $token);
}
