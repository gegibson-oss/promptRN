<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function users_store_path(): string
{
    $customPath = app_env('USERS_FILE_PATH');
    if ($customPath !== null && trim($customPath) !== '') {
        return trim($customPath);
    }

    return app_private_path('users.json');
}

function users_event_store_path(): string
{
    return app_private_path('processed-events.json');
}

function users_read_store(): array
{
    $data = app_read_json_file(users_store_path());
    if (!isset($data['users']) || !is_array($data['users'])) {
        $data['users'] = [];
    }

    return $data;
}

function users_write_store(array $store): bool
{
    if (!isset($store['users']) || !is_array($store['users'])) {
        $store['users'] = [];
    }

    return app_write_json_file(users_store_path(), $store);
}

function user_find_by_email(string $email): ?array
{
    $store = users_read_store();

    foreach ($store['users'] as $user) {
        if (($user['email'] ?? '') === strtolower($email)) {
            return $user;
        }
    }

    return null;
}

function user_find_by_stripe_customer_id(string $customerId): ?array
{
    $store = users_read_store();

    foreach ($store['users'] as $user) {
        if (($user['stripe_customer_id'] ?? '') === $customerId) {
            return $user;
        }
    }

    return null;
}

function user_upsert(array $user): bool
{
    $store = users_read_store();
    $email = strtolower($user['email'] ?? '');
    if ($email === '') {
        return false;
    }

    $user['email'] = $email;
    $updated = false;

    foreach ($store['users'] as $index => $existing) {
        if (($existing['email'] ?? '') === $email) {
            $store['users'][$index] = array_merge($existing, $user);
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        $store['users'][] = $user;
    }

    return users_write_store($store);
}

function user_has_active_subscription(array $user): bool
{
    return ($user['subscription_status'] ?? 'free') === 'active';
}

function user_has_condition_access(array $user, string $conditionSlug): bool
{
    if (user_has_active_subscription($user)) {
        return true;
    }

    $purchasedPacks = $user['purchased_packs'] ?? [];
    if (!is_array($purchasedPacks)) {
        return false;
    }

    return in_array($conditionSlug, $purchasedPacks, true);
}

function users_read_processed_events(): array
{
    $data = app_read_json_file(users_event_store_path());
    if (!isset($data['event_ids']) || !is_array($data['event_ids'])) {
        $data['event_ids'] = [];
    }

    return $data;
}

function users_mark_event_processed(string $eventId): bool
{
    $events = users_read_processed_events();
    if (!in_array($eventId, $events['event_ids'], true)) {
        $events['event_ids'][] = $eventId;
    }

    return app_write_json_file(users_event_store_path(), $events);
}

function users_has_processed_event(string $eventId): bool
{
    $events = users_read_processed_events();
    return in_array($eventId, $events['event_ids'], true);
}
