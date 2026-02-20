<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth-check.php';

$user = auth_require_login();

$pageTitle = 'Member Dashboard | PromptRN';
$metaDescription = 'Your PromptRN account dashboard.';
$canonicalUrl = app_url('/members/dashboard.php');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Member Dashboard</h1>
    <p>Signed in as <strong><?= app_h((string) ($user['email'] ?? '')); ?></strong>.</p>
    <p>Subscription status: <strong><?= app_h((string) ($user['subscription_status'] ?? 'free')); ?></strong></p>
    <p><a class="button" href="/members/library.php">Go to Library</a></p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
