<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$meta = app_meta();
$conditions = is_array($meta['conditions'] ?? null) ? $meta['conditions'] : [];

$iconForSituation = static function (string $situation): string {
    return match ($situation) {
        'newly-diagnosed' => 'diagnosis',
        'understanding-lab-results' => 'science',
        'talking-to-your-doctor' => 'stethoscope',
        'managing-chronic-illness' => 'monitor_heart',
        'medication-questions' => 'pill',
        default => 'medical_services',
    };
};

$formatSituation = static function (string $value): string {
    if ($value === '') {
        return 'Condition Support';
    }
    return ucwords(str_replace('-', ' ', $value));
};

$formatDate = static function (string $value): string {
    if ($value === '') {
        return 'Recently updated';
    }
    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return $value;
    }
    return date('M j, Y', $timestamp);
};

$pageTitle = 'Browse Condition Prompt Packs | PromptRN';
$metaDescription = 'Find nurse-written AI prompt packs by condition, with free prompts and full paid packs.';
$canonicalUrl = app_url('/prompts');
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section class="library-hero">
    <div class="mock-container center">
        <div class="hero-pill">
            <span class="material-symbols-outlined" aria-hidden="true">library_books</span>
            Condition Library
        </div>
        <h1>Browse Nurse-Written Condition Prompt Packs</h1>
        <p class="hero-copy">Each page includes free prompts to try now and a complete condition pack for deeper support.</p>
        <div class="hero-stat-row compact">
            <div class="hero-stat">
                <strong><?= app_h((string) count($conditions)); ?></strong>
                <span>Conditions</span>
            </div>
            <div class="hero-stat">
                <strong>3 Free</strong>
                <span>Prompts per page</span>
            </div>
            <div class="hero-stat">
                <strong>RN</strong>
                <span>Reviewed structure</span>
            </div>
        </div>
    </div>
</section>

<section class="library-grid-shell">
    <div class="mock-container">
        <?php if ($conditions === []): ?>
            <p>No condition content has been added yet.</p>
        <?php else: ?>
            <div class="featured-grid">
                <?php foreach ($conditions as $condition): ?>
                    <?php
                    $slug = (string) ($condition['slug'] ?? '');
                    $name = (string) ($condition['condition_name'] ?? $slug);
                    $situation = (string) ($condition['situation'] ?? '');
                    $promptCount = (int) ($condition['prompt_count'] ?? 12);
                    $updatedAt = $formatDate((string) ($condition['last_updated'] ?? ''));
                    ?>
                    <a class="featured-card" href="/prompts/<?= app_h($slug); ?>">
                        <div class="featured-top">
                            <div class="featured-icon <?= ($promptCount % 2 === 0) ? 'teal' : 'amber'; ?>">
                                <span class="material-symbols-outlined" aria-hidden="true"><?= app_h($iconForSituation($situation)); ?></span>
                            </div>
                            <span class="featured-count"><?= app_h((string) $promptCount); ?> Prompts</span>
                        </div>
                        <h3><?= app_h($name); ?></h3>
                        <p><?= app_h($formatSituation($situation)); ?></p>
                        <span class="featured-tag">Updated <?= app_h($updatedAt); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
