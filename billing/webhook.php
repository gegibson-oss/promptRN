<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/users.php';
require_once __DIR__ . '/../includes/stripe.php';

if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    http_response_code(405);
    echo 'Method Not Allowed';
    exit;
}

$payload = file_get_contents('php://input');
if ($payload === false || trim($payload) === '') {
    http_response_code(400);
    echo 'Invalid payload';
    exit;
}

try {
    $event = stripe_construct_webhook_event($payload, $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? null);
}
catch (Throwable $exception) {
    stripe_log('Webhook rejected: ' . $exception->getMessage());
    http_response_code(400);
    echo 'Signature verification failed';
    exit;
}

$eventId = (string)($event['id'] ?? '');
$eventType = (string)($event['type'] ?? 'unknown');
if ($eventId === '') {
    http_response_code(400);
    echo 'Missing event ID';
    exit;
}

if (users_has_processed_event($eventId)) {
    stripe_log('Webhook skipped (already processed): ' . $eventId);
    http_response_code(200);
    echo 'Already processed';
    exit;
}

$object = is_array($event['data']['object'] ?? null) ? $event['data']['object'] : [];
$email = strtolower((string)($object['customer_email'] ?? $object['receipt_email'] ?? $object['metadata']['email'] ?? ''));
$customerId = (string)($object['customer'] ?? '');

$user = null;
if ($email !== '') {
    $user = user_find_by_email($email);
}

if ($user === null && $customerId !== '') {
    $user = user_find_by_stripe_customer_id($customerId);

    if ($user === null) {
        $email = stripe_fetch_customer_email($customerId) ?? $email;
        if ($email !== '') {
            $user = user_find_by_email($email);
        }
    }
}

if ($user === null && $email !== '') {
    $user = [
        'id' => 'usr_' . bin2hex(random_bytes(6)),
        'email' => $email,
        'password_hash' => '',
        'created_at' => gmdate('c'),
        'subscription_status' => 'free',
        'subscription_plan' => null,
        'subscription_expires' => null,
        'stripe_customer_id' => $customerId,
        'stripe_subscription_id' => null,
        'purchased_packs' => [],
        'has_prep_kit' => false,
        'has_course' => false,
        'convertkit_subscriber_id' => null,
    ];
}

if ($user !== null) {
    switch ($eventType) {
        case 'payment_intent.succeeded':
            $plan = strtolower((string)($object['metadata']['plan'] ?? ''));
            if ($plan === 'prep_kit') {
                $user['has_prep_kit'] = true;
            }
            else {
                $slug = strtolower((string)($object['metadata']['condition_slug'] ?? ''));
                if (app_condition_exists($slug)) {
                    $packs = $user['purchased_packs'] ?? [];
                    if (!is_array($packs)) {
                        $packs = [];
                    }
                    if (!in_array($slug, $packs, true)) {
                        $packs[] = $slug;
                    }
                    $user['purchased_packs'] = $packs;
                }
            }
            break;

        case 'customer.subscription.created':
        case 'customer.subscription.updated':
            $user['subscription_status'] = 'active';
            $plan = strtolower((string)($object['metadata']['plan'] ?? 'monthly'));
            if (!in_array($plan, ['monthly', 'annual'], true)) {
                $plan = 'monthly';
            }

            $user['subscription_plan'] = $plan;
            $periodEnd = (int)($object['current_period_end'] ?? 0);
            $user['subscription_expires'] = $periodEnd > 0 ? gmdate('c', $periodEnd) : null;
            $user['stripe_customer_id'] = $customerId;
            $user['stripe_subscription_id'] = (string)($object['id'] ?? ($user['stripe_subscription_id'] ?? ''));
            break;

        case 'customer.subscription.deleted':
            $user['subscription_status'] = 'cancelled';
            break;

        case 'invoice.payment_failed':
            $user['subscription_status'] = 'past_due';
            break;
    }

    if (!user_upsert($user)) {
        stripe_log('Webhook write failed for event: ' . $eventId);
        http_response_code(500);
        echo 'Write failed';
        exit;
    }
}

if (!users_mark_event_processed($eventId)) {
    stripe_log('Could not mark event as processed: ' . $eventId);
    http_response_code(500);
    echo 'Event tracking failed';
    exit;
}

stripe_log('Webhook processed: ' . $eventId . ' (' . $eventType . ')');
http_response_code(200);
echo 'OK';
stripe_log('Webhook processed: ' . $eventId . ' (' . $eventType . ')');
http_response_code(200);
echo 'OK';