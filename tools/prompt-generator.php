<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/csrf.php';

$generatedPrompt = null;
$condition = '';
$goal = '';
$error = null;

csrf_start_session_if_needed();

if (app_request_is_post()) {
    $condition = trim((string) ($_POST['condition'] ?? ''));
    $goal = trim((string) ($_POST['goal'] ?? ''));

    if (!csrf_validate($_POST['csrf_token'] ?? null)) {
        $error = 'Security token invalid. Please refresh and try again.';
    } elseif ($condition !== '' && $goal !== '') {
        $generatedPrompt = "I was recently diagnosed with {$condition}. Help me understand {$goal} in simple language, list the most important questions to ask my doctor next, and highlight urgent warning signs that require medical attention.";
    } else {
        $error = 'Both fields are required.';
    }
}

$pageTitle = 'Free Health Prompt Generator | PromptRN';
$metaDescription = 'Generate a starter health prompt to use with ChatGPT or Claude.';
$canonicalUrl = app_url('/tools/prompt-generator');
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Free Prompt Generator</h1>
    <p>Generate a starter prompt in seconds, then upgrade to nurse-written condition packs.</p>
    <?php if ($error !== null): ?>
        <p class="alert"><?= app_h($error); ?></p>
    <?php endif; ?>

    <form method="post" action="/tools/prompt-generator">
        <input type="hidden" name="csrf_token" value="<?= app_h(csrf_token()); ?>">

        <label for="condition">Condition</label>
        <input id="condition" name="condition" type="text" required value="<?= app_h($condition); ?>">

        <label for="goal">What do you want help with?</label>
        <input id="goal" name="goal" type="text" required value="<?= app_h($goal); ?>">

        <button type="submit">Generate Prompt</button>
    </form>

    <?php if ($generatedPrompt !== null): ?>
        <h2>Your Prompt</h2>
        <pre><?= app_h($generatedPrompt); ?></pre>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
