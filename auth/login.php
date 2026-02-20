<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/users.php';
require_once __DIR__ . '/../includes/csrf.php';

csrf_start_session_if_needed();

function login_attempts_path(): string
{
    return app_private_path('login-attempts.json');
}

function login_attempts_read(): array
{
    $data = app_read_json_file(login_attempts_path());
    return is_array($data) ? $data : [];
}

function login_attempts_write(array $data): void
{
    app_write_json_file(login_attempts_path(), $data);
}

function login_attempts_key(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
}

function login_rate_limited(string $key): bool
{
    $attempts = login_attempts_read();
    $windowStart = time() - (15 * 60);
    $recent = array_filter($attempts[$key] ?? [], static fn ($ts) => (int) $ts >= $windowStart);
    return count($recent) >= 10;
}

function login_record_attempt(string $key): void
{
    $attempts = login_attempts_read();
    if (!isset($attempts[$key]) || !is_array($attempts[$key])) {
        $attempts[$key] = [];
    }

    $attempts[$key][] = time();
    $windowStart = time() - (15 * 60);
    $attempts[$key] = array_values(array_filter($attempts[$key], static fn ($ts) => (int) $ts >= $windowStart));

    login_attempts_write($attempts);
}

$error = null;
$email = strtolower(trim((string) ($_POST['email'] ?? '')));
$redirect = (string) ($_GET['redirect'] ?? '/members/dashboard.php');

if (app_request_is_post()) {
    $ipKey = login_attempts_key();
    $redirect = (string) ($_POST['redirect'] ?? '/members/dashboard.php');

    if (!csrf_validate($_POST['csrf_token'] ?? null)) {
        $error = 'Security token invalid. Refresh and try again.';
    } elseif (login_rate_limited($ipKey)) {
        $error = 'Too many login attempts. Try again in 15 minutes.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email and password are required.';
        login_record_attempt($ipKey);
    } else {
        $user = user_find_by_email($email);
        $password = (string) ($_POST['password'] ?? '');

        if ($user === null || !password_verify($password, (string) ($user['password_hash'] ?? ''))) {
            $error = 'Invalid email or password.';
            login_record_attempt($ipKey);
        } else {
            session_regenerate_id(true);
            $_SESSION['user_email'] = $email;

            if ($redirect === '' || str_starts_with($redirect, 'http')) {
                $redirect = '/members/dashboard.php';
            }

            header('Location: ' . $redirect);
            exit;
        }
    }
}

$pageTitle = 'Login | PromptRN';
$metaDescription = 'Sign in to your PromptRN account.';
$canonicalUrl = app_url('/auth/login.php');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Sign In</h1>
    <?php if ($error !== null): ?>
        <p class="alert"><?= app_h($error); ?></p>
    <?php endif; ?>

    <form method="post" action="/auth/login.php">
        <input type="hidden" name="csrf_token" value="<?= app_h(csrf_token()); ?>">
        <input type="hidden" name="redirect" value="<?= app_h($redirect); ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required value="<?= app_h($email); ?>">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button type="submit">Sign In</button>
    </form>

    <p><a href="/auth/forgot-password.php">Forgot your password?</a></p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
