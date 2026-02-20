<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth-check.php';

$user = auth_require_login();

$pageTitle = 'Account Settings | PromptRN';
$metaDescription = 'Manage your PromptRN account and billing details.';
$canonicalUrl = app_url('/members/account');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Account Settings</h1>
    <p>Email: <?= app_h((string) ($user['email'] ?? '')); ?></p>
    <p>Plan: <?= app_h((string) ($user['subscription_plan'] ?? 'none')); ?></p>
    <p>Status: <?= app_h((string) ($user['subscription_status'] ?? 'free')); ?></p>
    <p><a class="button" href="/billing/checkout?plan=monthly">Upgrade to Monthly ($17)</a></p>
    <p><a class="button secondary" href="/billing/checkout?plan=annual">Upgrade to Annual ($99)</a></p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
