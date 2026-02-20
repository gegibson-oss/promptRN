<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Forgot Password | PromptRN';
$metaDescription = 'Password reset is intentionally deferred for MVP.';
$canonicalUrl = app_url('/auth/forgot-password');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Forgot Password</h1>
    <p>Password reset flow is intentionally deferred in MVP scope. Contact support for manual help.</p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
