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
<section class="prompts-hero">
    <span class="hero-badge">Condition Library</span>
    <h1>Browse Nurse-Written Condition Prompt Packs</h1>
    <p class="hero-lead">Each condition page includes 3 free prompts plus a full locked prompt pack for deeper support.
    </p>
</section>

<section>
    <div class="section-title-row">
        <h2>All Conditions</h2>
        <span class="meta-pill">
            <?= app_h((string)count($conditions)); ?> conditions
        </span>
    </div>
    <?php if ($conditions === []): ?>
    <p>No conditions found in <code>/data/meta.json</code>.</p>
    <?php
else: ?>
    <ul class="condition-grid">
        <?php foreach ($conditions as $condition): ?>
        <?php $slug = (string)($condition['slug'] ?? ''); ?>
        <li>
            <h3>
                <a href="/prompts/<?= app_h($slug); ?>">
                    <?= app_h((string)($condition['condition_name'] ?? $slug)); ?>
                </a>
            </h3>
            <p class="card-meta">
                <?= app_h((string)($condition['prompt_count'] ?? 12)); ?> base prompts
            </p>

            <?php $situations = app_situation_slugs(); ?>
            <?php if (!empty($situations)): ?>
            <div class="situation-links" style="margin-top: 1rem; display: flex; flex-direction: column; gap: 0.25rem;">
                <p
                    style="font-size: 0.75rem; font-weight: bold; color: var(--ink-muted); text-transform: uppercase; margin-bottom: 0.25rem;">
                    Specific Situations</p>
                <?php foreach ($situations as $sitSlug): ?>
                <a href="/prompts/<?= app_h($slug); ?>/<?= app_h($sitSlug); ?>"
                    style="font-size: 0.875rem; color: var(--amber-dark); hover:underline;">
                    &rarr;
                    <?= app_h(ucwords(str_replace('-', ' ', $sitSlug))); ?>
                </a>
                <?php
            endforeach; ?>
            </div>
            <?php
        endif; ?>
        </li>
        <?php
    endforeach; ?>
    </ul>
    <?php
endif; ?>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>