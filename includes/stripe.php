<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function stripe_secret_key(): string
{
    return app_env('STRIPE_SECRET_KEY', '') ?? '';
}

function stripe_webhook_secret(): string
{
    return app_env('STRIPE_WEBHOOK_SECRET', '') ?? '';
}

function stripe_price_id_for_plan(string $plan): string
{
    return match ($plan) {
            'pack' => (string)app_env('STRIPE_PRICE_PACK_SINGLE', ''),
            'prep_kit' => (string)app_env('STRIPE_PRICE_PREP_KIT', ''),
            'annual' => (string)app_env('STRIPE_PRICE_SUB_ANNUAL', ''),
            default => (string)app_env('STRIPE_PRICE_SUB_MONTHLY', ''),
        };
}

function stripe_sdk_bootstrap(): bool
{
    static $initialized = null;

    if ($initialized !== null) {
        return $initialized;
    }

    $autoload = APP_ROOT . '/vendor/autoload.php';
    if (!is_readable($autoload)) {
        $initialized = false;
        return false;
    }

    require_once $autoload;
    $initialized = class_exists('Stripe\\Stripe') && class_exists('Stripe\\Checkout\\Session');

    if ($initialized) {
        $curlClient = \Stripe\HttpClient\CurlClient::instance();
        $curlClient->setConnectTimeout(5);
        $curlClient->setTimeout(15);
        \Stripe\ApiRequestor::setHttpClient($curlClient);
        \Stripe\Stripe::setMaxNetworkRetries(2);
        \Stripe\Stripe::setApiKey(stripe_secret_key());
    }

    return $initialized;
}

function stripe_require_sdk(): void
{
    if (!stripe_sdk_bootstrap()) {
        throw new RuntimeException('Stripe SDK unavailable. Run composer install.');
    }
}

function stripe_signing_payload(string $payload, string $timestamp): string
{
    return $timestamp . '.' . $payload;
}

function stripe_verify_signature(string $payload, ?string $signatureHeader, string $secret): bool
{
    if ($signatureHeader === null || $signatureHeader === '' || $secret === '') {
        return false;
    }

    if (stripe_sdk_bootstrap()) {
        try {
            \Stripe\Webhook::constructEvent($payload, $signatureHeader, $secret);
            return true;
        }
        catch (Throwable $exception) {
            stripe_log('Stripe signature verification error: ' . $exception->getMessage());
            return false;
        }
    }

    $parts = [];
    foreach (explode(',', $signatureHeader) as $pair) {
        $entry = explode('=', trim($pair), 2);
        if (count($entry) === 2) {
            $parts[$entry[0]][] = $entry[1];
        }
    }

    $timestamp = $parts['t'][0] ?? null;
    $signatures = $parts['v1'] ?? [];

    if ($timestamp === null || $signatures === []) {
        return false;
    }

    $signedPayload = stripe_signing_payload($payload, $timestamp);
    $expected = hash_hmac('sha256', $signedPayload, $secret);

    foreach ($signatures as $signature) {
        if (hash_equals($expected, $signature)) {
            return true;
        }
    }

    return false;
}

function stripe_construct_webhook_event(string $payload, ?string $signatureHeader): array
{
    $secret = stripe_webhook_secret();

    if (!stripe_verify_signature($payload, $signatureHeader, $secret)) {
        throw new RuntimeException('Signature verification failed.');
    }

    if (stripe_sdk_bootstrap()) {
        $event = \Stripe\Webhook::constructEvent($payload, (string)$signatureHeader, $secret);
        return $event->toArray();
    }

    $decoded = json_decode($payload, true);
    if (!is_array($decoded)) {
        throw new RuntimeException('Malformed webhook payload.');
    }

    return $decoded;
}

function stripe_create_checkout_url(array $params): string
{
    stripe_require_sdk();

    $plan = strtolower((string)($params['plan'] ?? 'monthly'));
    $email = strtolower(trim((string)($params['email'] ?? '')));
    $userId = (string)($params['user_id'] ?? '');
    $slug = strtolower((string)($params['slug'] ?? ''));

    if (!in_array($plan, ['pack', 'monthly', 'annual', 'prep_kit'], true)) {
        throw new RuntimeException('Unsupported checkout plan.');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new RuntimeException('Invalid checkout email.');
    }

    if ($plan === 'pack' && !app_condition_exists($slug)) {
        throw new RuntimeException('Invalid condition pack.');
    }

    $priceId = stripe_price_id_for_plan($plan);
    if ($priceId === '') {
        throw new RuntimeException('Missing Stripe price ID for plan: ' . $plan);
    }

    $commonSession = [
        'customer_email' => $email,
        'client_reference_id' => $userId,
        'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
        'success_url' => app_url('/billing/success?session_id={CHECKOUT_SESSION_ID}'),
        'cancel_url' => app_url('/billing/checkout?cancelled=1&plan=' . urlencode($plan)),
    ];

    if ($plan === 'pack' || $plan === 'prep_kit') {
        $session = \Stripe\Checkout\Session::create(array_merge($commonSession, [
            'mode' => 'payment',
            'metadata' => [
                'condition_slug' => $slug,
                'plan' => $plan,
                'email' => $email,
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'condition_slug' => $slug,
                    'plan' => $plan,
                    'email' => $email,
                ],
            ],
        ]));
    }
    else {
        $session = \Stripe\Checkout\Session::create(array_merge($commonSession, [
            'mode' => 'subscription',
            'metadata' => [
                'plan' => $plan,
                'email' => $email,
            ],
            'subscription_data' => [
                'metadata' => [
                    'plan' => $plan,
                    'email' => $email,
                ],
            ],
        ]));
    }

    $url = (string)($session->url ?? '');
    if ($url === '') {
        throw new RuntimeException('Stripe did not return a checkout URL.');
    }

    return $url;
}

function stripe_fetch_customer_email(string $customerId): ?string
{
    if ($customerId === '') {
        return null;
    }

    if (!stripe_sdk_bootstrap()) {
        return null;
    }

    try {
        $customer = \Stripe\Customer::retrieve($customerId);
        $email = strtolower(trim((string)($customer->email ?? '')));
        return $email !== '' ? $email : null;
    }
    catch (Throwable $exception) {
        stripe_log('Failed to fetch customer email for ' . $customerId . ': ' . $exception->getMessage());
        return null;
    }
}

function stripe_checkout_session_details(string $sessionId): array
{
    if ($sessionId === '' || !stripe_sdk_bootstrap()) {
        return [];
    }

    try {
        $session = \Stripe\Checkout\Session::retrieve($sessionId);
        return $session->toArray();
    }
    catch (Throwable $exception) {
        stripe_log('Unable to load checkout session ' . $sessionId . ': ' . $exception->getMessage());
        return [];
    }
}

function stripe_log(string $message): void
{
    $customPath = app_env('WEBHOOK_LOG_PATH');
    if ($customPath !== null && trim($customPath) !== '') {
        $path = trim($customPath);
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }

        $line = '[' . gmdate('c') . '] ' . $message . PHP_EOL;
        @file_put_contents($path, $line, FILE_APPEND);
        return;
    }

    app_log($message, 'webhook.log');
}