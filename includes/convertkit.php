<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function convertkit_api_key(): string
{
    return app_env('CONVERTKIT_API_KEY', '') ?? '';
}

function convertkit_form_id(): string
{
    return app_env('CONVERTKIT_FORM_ID', '') ?? '';
}

function convertkit_subscribe_email(string $email): bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $apiKey = convertkit_api_key();
    $formId = convertkit_form_id();
    if ($apiKey === '' || $formId === '') {
        return false;
    }

    $url = 'https://api.convertkit.com/v3/forms/' . rawurlencode($formId) . '/subscribe';
    $payload = http_build_query([
        'api_key' => $apiKey,
        'email' => $email,
    ]);

    $ch = curl_init($url);
    if ($ch === false) {
        return false;
    }

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    $response = curl_exec($ch);
    $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false || $httpCode < 200 || $httpCode >= 300) {
        app_log('ConvertKit subscribe failed for ' . $email . ' code=' . $httpCode . ' error=' . $error, 'app.log');
        return false;
    }

    return true;
}
