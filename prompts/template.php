<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/auth-check.php';

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$meta = app_meta();

if ($slug === '' || !app_condition_exists($slug)) {
    http_response_code(404);
    $pageTitle = 'Condition Not Found | PromptRN';
    $metaDescription = 'The requested condition page does not exist.';
    $canonicalUrl = app_url('/prompts');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Condition Not Found</h1><p>Try browsing all available condition pages.</p><p><a class="button" href="/prompts">Back to all conditions</a></p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
} else {
    $condition = app_read_json_file(app_condition_path($slug));
}

if ($condition === []) {
    http_response_code(404);
    $pageTitle = 'Condition Not Found | PromptRN';
    $metaDescription = 'The requested condition page does not exist.';
    $canonicalUrl = app_url('/prompts');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Condition Not Found</h1><p>Try browsing all available condition pages.</p><p><a class="button" href="/prompts">Back to all conditions</a></p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$missingSeoFields = seo_condition_missing_fields($condition);
$user = auth_current_user();
$hasFullAccess = auth_user_can_access_condition($user, $slug);

$pageTitle = (string) ($condition['seo']['page_title'] ?? (($condition['condition_name'] ?? 'Condition') . ' | PromptRN'));
$metaDescription = (string) ($condition['seo']['meta_description'] ?? 'Nurse-written prompts for patients.');
$canonicalUrl = app_url('/prompts/' . $slug);
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<article class="condition-page">
    <?php if ($missingSeoFields !== [] && app_is_development()): ?>
        <section class="alert">
            <strong>Missing SEO fields:</strong> <?= app_h(implode(', ', $missingSeoFields)); ?>
        </section>
    <?php endif; ?>

    <h1><?= app_h((string) ($condition['seo']['h1'] ?? ($condition['condition_name'] ?? 'Condition'))); ?></h1>
    <p class="last-updated"><strong>Last updated:</strong> <?= app_h((string) ($condition['last_updated'] ?? 'Unknown')); ?></p>
    <p><?= app_h((string) ($condition['clinical_context'] ?? 'Clinical context coming soon.')); ?></p>

    <?php if (($condition['patient_fears'] ?? []) !== []): ?>
        <section>
            <h2>Common Patient Concerns</h2>
            <ul>
                <?php foreach (($condition['patient_fears'] ?? []) as $fear): ?>
                    <li><?= app_h((string) $fear); ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <section>
        <h2>Free Prompts</h2>
        <?php foreach (($condition['free_prompts'] ?? []) as $prompt): ?>
            <div class="prompt-card">
                <h3><?= app_h((string) ($prompt['title'] ?? 'Untitled prompt')); ?></h3>
                <p><?= nl2br(app_h((string) ($prompt['prompt'] ?? ''))); ?></p>
                <p><strong>Why this works:</strong> <?= app_h((string) ($prompt['why_it_works'] ?? '')); ?></p>
            </div>
        <?php endforeach; ?>
    </section>

    <section>
        <h2>Full Prompt Pack</h2>
        <?php if ($hasFullAccess): ?>
            <?php foreach (($condition['paid_prompts'] ?? []) as $prompt): ?>
                <div class="prompt-card">
                    <h3><?= app_h((string) ($prompt['title'] ?? 'Untitled prompt')); ?></h3>
                    <p><?= nl2br(app_h((string) ($prompt['prompt'] ?? ''))); ?></p>
                    <p><strong>Why this works:</strong> <?= app_h((string) ($prompt['why_it_works'] ?? '')); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <ul>
                <?php foreach (($condition['paid_prompts'] ?? []) as $prompt): ?>
                    <li><?= app_h((string) ($prompt['title'] ?? 'Locked prompt')); ?> (locked)</li>
                <?php endforeach; ?>
            </ul>
            <p><a class="button" href="/billing/checkout.php?plan=pack&amp;slug=<?= app_h($slug); ?>">Unlock this pack</a></p>
            <p><a href="/billing/checkout.php?plan=monthly">Or subscribe for full library access</a></p>
        <?php endif; ?>
    </section>

    <section>
        <h2>FAQs</h2>
        <?php foreach (($condition['faqs'] ?? []) as $faq): ?>
            <details>
                <summary><?= app_h((string) ($faq['question'] ?? '')); ?></summary>
                <p><?= app_h((string) ($faq['answer'] ?? '')); ?></p>
            </details>
        <?php endforeach; ?>
    </section>

    <section>
        <h2>Related Conditions</h2>
        <ul>
            <?php foreach (($condition['related_conditions'] ?? []) as $relatedSlug): ?>
                <?php if (app_condition_exists((string) $relatedSlug)): ?>
                    <li><a href="/prompts/<?= app_h((string) $relatedSlug); ?>"><?= app_h(app_condition_name((string) $relatedSlug)); ?></a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </section>

    <section>
        <h2>About The Author</h2>
        <p><strong><?= app_h((string) ($condition['author']['name'] ?? 'PromptRN RN Team')); ?></strong> — <?= app_h((string) ($condition['author']['credentials'] ?? 'Registered Nurse')); ?></p>
        <p><?= app_h((string) ($condition['author']['experience'] ?? '')); ?></p>
    </section>
</article>
<?php seo_render_condition_schemas($condition, $meta, $canonicalUrl); ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
