<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/users.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/convertkit.php';

csrf_start_session_if_needed();

$error = null;
$email = '';

if (app_request_is_post()) {
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_validate($_POST['csrf_token'] ?? null)) {
        $error = 'Security token invalid. Please refresh and try again.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif (user_find_by_email($email) !== null) {
        $error = 'An account with that email already exists.';
    } else {
        $user = [
            'id' => 'usr_' . bin2hex(random_bytes(6)),
            'email' => $email,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'created_at' => gmdate('c'),
            'subscription_status' => 'free',
            'subscription_plan' => null,
            'subscription_expires' => null,
            'stripe_customer_id' => null,
            'stripe_subscription_id' => null,
            'purchased_packs' => [],
            'convertkit_subscriber_id' => null,
        ];

        if (user_upsert($user)) {
            convertkit_subscribe_email($email);
            session_regenerate_id(true);
            $_SESSION['user_email'] = $email;
            header('Location: /members/dashboard');
            exit;
        }

        $error = 'Could not create account. Try again.';
    }
}

$pageTitle = 'Create Account | PromptRN';
$metaDescription = 'Register for PromptRN to unlock nurse-written AI prompt packs.';
$canonicalUrl = app_url('/auth/register');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Create Account</h1>
    <?php if ($error !== null): ?>
        <p class="alert"><?= app_h($error); ?></p>
    <?php endif; ?>
    <form method="post" action="/auth/register">
        <input type="hidden" name="csrf_token" value="<?= app_h(csrf_token()); ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required value="<?= app_h($email); ?>">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required minlength="8">

        <button type="submit">Create Account</button>
    </form>
    <p>Already have an account? <a href="/auth/login">Sign in</a>.</p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
