<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth-check.php';

$user = auth_require_subscription();
$meta = app_read_json_file(app_data_path('meta.json'));

$pageTitle = 'Prompt Library | PromptRN';
$metaDescription = 'Full member library of nurse-written condition prompts.';
$canonicalUrl = app_url('/members/library');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Full Prompt Library</h1>
    <p>Subscription access confirmed for <?= app_h((string) ($user['email'] ?? '')); ?>.</p>
    <ul>
        <?php foreach (($meta['conditions'] ?? []) as $condition): ?>
            <li><a href="/prompts/<?= app_h((string) ($condition['slug'] ?? '')); ?>"><?= app_h((string) ($condition['condition_name'] ?? '')); ?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
