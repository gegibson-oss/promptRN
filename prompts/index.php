<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$meta = app_meta();
$conditions = $meta['conditions'] ?? [];

$pageTitle = 'Browse Condition Prompt Packs | PromptRN';
$metaDescription = 'Find nurse-written AI prompt packs by condition, with free prompts and full paid packs.';
$canonicalUrl = app_url('/prompts');
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Browse Condition Prompt Packs</h1>
    <p>Start with free nurse-written prompts and unlock full packs when you need deeper support.</p>

    <?php if ($conditions === []): ?>
        <p>No conditions found in <code>/data/meta.json</code>.</p>
    <?php else: ?>
        <ul class="card-list">
            <?php foreach ($conditions as $condition): ?>
                <?php $slug = (string) ($condition['slug'] ?? ''); ?>
                <li>
                    <h2>
                        <a href="/prompts/<?= app_h($slug); ?>">
                            <?= app_h((string) ($condition['condition_name'] ?? $slug)); ?>
                        </a>
                    </h2>
                    <p>Last updated: <?= app_h((string) ($condition['last_updated'] ?? 'Unknown')); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
