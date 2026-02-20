<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/users.php';

function auth_start_session_if_needed(): void
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

function auth_current_user(): ?array
{
    auth_start_session_if_needed();

    $email = $_SESSION['user_email'] ?? null;
    if (!is_string($email) || $email === '') {
        return null;
    }

    return user_find_by_email($email);
}

function auth_redirect_to_login(): void
{
    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $target = '/auth/login?redirect=' . urlencode($requestUri);
    header('Location: ' . $target);
    exit;
}

function auth_require_login(): array
{
    $user = auth_current_user();
    if ($user === null) {
        auth_redirect_to_login();
    }

    return $user;
}

function auth_require_subscription(): array
{
    $user = auth_require_login();
    if (!user_has_active_subscription($user)) {
        header('Location: /billing/checkout?plan=monthly');
        exit;
    }

    return $user;
}

function auth_user_can_access_condition(?array $user, string $conditionSlug): bool
{
    if ($user === null) {
        return false;
    }

    return user_has_condition_access($user, $conditionSlug);
}
