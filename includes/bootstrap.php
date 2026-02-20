<?php
declare(strict_types=1);

if (defined('APP_BOOTSTRAPPED')) {
    return;
}

define('APP_BOOTSTRAPPED', true);
define('APP_ROOT', dirname(__DIR__));
define('APP_DATA_DIR', APP_ROOT . '/data');
define('APP_PRIVATE_DIR', APP_ROOT . '/private');
define('APP_LOG_DIR', APP_ROOT . '/logs');

if (!is_dir(APP_LOG_DIR)) {
    @mkdir(APP_LOG_DIR, 0755, true);
}

function load_env_file(string $path): void
{
    if (!is_readable($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || str_starts_with($trimmed, '#')) {
            continue;
        }

        $pair = explode('=', $trimmed, 2);
        if (count($pair) !== 2) {
            continue;
        }

        [$key, $value] = $pair;
        $key = trim($key);
        $value = trim($value);
        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        }

        if ($key === '') {
            continue;
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }
}

load_env_file(APP_ROOT . '/.env');
load_env_file(APP_ROOT . '/.env.local');

function app_env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }

    return $value;
}

if (app_env('APP_ENV', 'development') === 'production') {
    $hasEnv = is_readable(APP_ROOT . '/.env') || is_readable(APP_ROOT . '/.env.local');
    if (!$hasEnv) {
        http_response_code(500);
        error_log('Missing .env/.env.local in production.');
        exit('Configuration error: .env file missing.');
    }
}

function app_env_required(string $key): string
{
    $value = app_env($key);
    if ($value === null || $value === '') {
        throw new RuntimeException('Missing required environment variable: ' . $key);
    }

    return $value;
}

function app_is_production(): bool
{
    return app_env('APP_ENV', 'development') === 'production';
}

function app_is_development(): bool
{
    return !app_is_production();
}

ini_set('log_errors', '1');
ini_set('error_log', APP_LOG_DIR . '/php-error.log');
ini_set('display_errors', app_is_production() ? '0' : '1');

function app_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function app_data_path(string $relativePath = ''): string
{
    if ($relativePath === '') {
        return APP_DATA_DIR;
    }

    return APP_DATA_DIR . '/' . ltrim($relativePath, '/');
}

function app_private_path(string $relativePath = ''): string
{
    if ($relativePath === '') {
        return APP_PRIVATE_DIR;
    }

    return APP_PRIVATE_DIR . '/' . ltrim($relativePath, '/');
}

function app_log(string $message, string $logFile = 'app.log'): void
{
    $line = '[' . gmdate('c') . '] ' . $message . PHP_EOL;
    @file_put_contents(APP_LOG_DIR . '/' . $logFile, $line, FILE_APPEND);
}

function app_read_json_file(string $path): array
{
    if (!is_readable($path)) {
        return [];
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [];
    }

    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        app_log('Invalid JSON read: ' . $path);
        return [];
    }

    return $decoded;
}

function app_write_json_file(string $path, array $payload): bool
{
    $dir = dirname($path);
    if (!is_dir($dir) && !@mkdir($dir, 0755, true)) {
        return false;
    }

    $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    if ($json === false) {
        return false;
    }

    $handle = fopen($path, 'c+');
    if ($handle === false) {
        return false;
    }

    $ok = false;
    if (flock($handle, LOCK_EX)) {
        ftruncate($handle, 0);
        rewind($handle);
        $written = fwrite($handle, $json . PHP_EOL);
        fflush($handle);
        flock($handle, LOCK_UN);
        $ok = $written !== false;
    }

    fclose($handle);
    return $ok;
}

function app_request_is_post(): bool
{
    return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function app_is_https(): bool
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        return true;
    }

    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';
    return strtolower($forwardedProto) === 'https';
}

function app_base_url(): string
{
    $configured = app_env('APP_URL');
    if ($configured !== null) {
        return rtrim($configured, '/');
    }

    $scheme = app_is_https() ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $scheme . '://' . $host;
}

function app_url(string $path = '/'): string
{
    return app_base_url() . '/' . ltrim($path, '/');
}

function app_validate_slug(string $slug): bool
{
    return (bool) preg_match('/^[a-z0-9-]+$/', $slug);
}

function app_meta(): array
{
    static $meta = null;

    if ($meta !== null) {
        return $meta;
    }

    $meta = app_read_json_file(app_data_path('meta.json'));
    return $meta;
}

function app_condition_slugs(): array
{
    $slugs = [];
    foreach ((app_meta()['conditions'] ?? []) as $condition) {
        $slug = (string) ($condition['slug'] ?? '');
        if (app_validate_slug($slug)) {
            $slugs[] = $slug;
        }
    }

    return array_values(array_unique($slugs));
}

function app_condition_exists(string $slug): bool
{
    if (!app_validate_slug($slug)) {
        return false;
    }

    return in_array($slug, app_condition_slugs(), true);
}

function app_condition_path(string $slug): string
{
    return app_data_path('conditions/' . $slug . '.json');
}

function app_condition_name(string $slug): string
{
    foreach ((app_meta()['conditions'] ?? []) as $condition) {
        if ((string) ($condition['slug'] ?? '') === $slug) {
            $name = (string) ($condition['condition_name'] ?? '');
            if ($name !== '') {
                return $name;
            }
            break;
        }
    }

    return ucwords(str_replace('-', ' ', $slug));
}
