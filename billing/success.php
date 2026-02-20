<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/stripe.php';

$plan = (string) ($_GET['plan'] ?? 'monthly');
$slug = (string) ($_GET['slug'] ?? '');
$sessionId = (string) ($_GET['session_id'] ?? '');

if ($sessionId !== '') {
    $session = stripe_checkout_session_details($sessionId);
    if ($session !== []) {
        $plan = strtolower((string) ($session['metadata']['plan'] ?? $plan));
        $slug = strtolower((string) ($session['metadata']['condition_slug'] ?? $slug));
    }
}

$pageTitle = 'Payment Success | PromptRN';
$metaDescription = 'Your PromptRN purchase was successful.';
$canonicalUrl = app_url('/billing/success.php');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Payment Received</h1>
    <p>Your <?= app_h($plan); ?> purchase was processed.</p>
    <?php if ($slug !== ''): ?>
        <p><a class="button" href="/prompts/<?= app_h($slug); ?>">Open your condition pack</a></p>
    <?php else: ?>
        <p><a class="button" href="/members/library.php">Open your library</a></p>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
