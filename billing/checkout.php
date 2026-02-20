<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth-check.php';
require_once __DIR__ . '/../includes/stripe.php';

$user = auth_require_login();

$plan = strtolower(trim((string) ($_GET['plan'] ?? 'monthly')));
$allowedPlans = ['pack', 'monthly', 'annual'];
if (!in_array($plan, $allowedPlans, true)) {
    $plan = 'monthly';
}

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if ($plan === 'pack' && !app_condition_exists($slug)) {
    $slug = '';
}

try {
    $checkoutUrl = stripe_create_checkout_url([
        'plan' => $plan,
        'slug' => $slug,
        'email' => $user['email'] ?? '',
        'user_id' => $user['id'] ?? '',
    ]);
} catch (Throwable $exception) {
    stripe_log('Checkout creation failed: ' . $exception->getMessage());
    http_response_code(500);

    $pageTitle = 'Checkout Error | PromptRN';
    $metaDescription = 'We could not start checkout. Please try again.';
    $canonicalUrl = app_url('/billing/checkout.php');
    $robots = 'noindex, nofollow';
    require __DIR__ . '/../includes/header.php';
    ?>
    <section>
        <h1>Unable to Start Checkout</h1>
        <p>There was a billing setup issue. Please try again in a moment.</p>
        <p><a class="button" href="/members/account.php">Back to account</a></p>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    exit;
}

header('Location: ' . $checkoutUrl);
exit;
