<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = app_meta();
$conditions = is_array($meta['conditions'] ?? null) ? $meta['conditions'] : [];
$conditionCount = count($conditions);
$totalPrompts = array_reduce(
    $conditions,
    static fn (int $carry, array $condition): int => $carry + (int) ($condition['prompt_count'] ?? 0),
    0
);

$featuredConditions = array_slice($conditions, 0, 6);

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

$summaryForSituation = static function (string $situation): string {
    return match ($situation) {
        'newly-diagnosed' => 'What to ask first, what to track, and how to prepare for follow-up visits.',
        'understanding-lab-results' => 'Decode bloodwork, trend changes, and prioritize questions for your clinician.',
        'talking-to-your-doctor' => 'Build a focused appointment agenda with better decision-making prompts.',
        'managing-chronic-illness' => 'Create practical routines and avoid common adherence pitfalls.',
        'medication-questions' => 'Clarify side effects, expected timelines, and escalation triggers.',
        default => 'Nurse-written prompts for understanding your condition and next steps.',
    };
};

$formatSituation = static function (string $value): string {
    if ($value === '') {
        return 'Condition Support';
    }
    return ucwords(str_replace('-', ' ', $value));
};

$pageTitle = 'Expert AI Prompts for your Health | PromptRN';
$metaDescription = 'Bridge the gap between your doctor visit and daily life with clinically vetted AI prompts written by nurses.';
$canonicalUrl = app_url('/');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<section class="mock-hero">
    <div class="mock-container center">
        <div class="hero-pill">
            <span class="material-symbols-outlined" aria-hidden="true">verified</span>
            Verified by Registered Nurses
        </div>
        <h1>Expert AI Prompts for your Health, <em>Written by Nurses.</em></h1>
        <p class="hero-copy">Bridge the gap between your doctor visit and your daily life with clinically-vetted prompts for ChatGPT.</p>
        <div class="hero-cta-row">
            <a class="btn-primary" href="/prompts">Browse <?= app_h((string) $conditionCount); ?> Conditions</a>
            <a class="btn-secondary" href="/tools/prompt-generator">See Free Tools</a>
        </div>
        <div class="hero-stat-row">
            <div class="hero-stat">
                <strong><?= app_h((string) max($totalPrompts, 1)); ?>+</strong>
                <span>Prompts Live</span>
            </div>
            <div class="hero-stat">
                <strong>4.9</strong>
                <span>Patient Rating</span>
            </div>
            <div class="hero-stat">
                <strong>100%</strong>
                <span>Verified RN Authors</span>
            </div>
        </div>
    </div>
</section>

<section class="mock-value-prop">
    <div class="mock-container center narrow">
        <h2>Most patients leave their appointments with more questions than answers.</h2>
        <p>Healthcare is complex. We simplify it so you can take control.</p>
    </div>
    <div class="mock-container">
        <div class="value-grid">
            <article class="value-card">
                <div class="value-icon teal"><span class="material-symbols-outlined" aria-hidden="true">medical_services</span></div>
                <h3>Clinically Vetted</h3>
                <p>Every prompt is written and reviewed by registered nurses with clinical experience.</p>
            </article>
            <article class="value-card">
                <div class="value-icon amber"><span class="material-symbols-outlined" aria-hidden="true">translate</span></div>
                <h3>Plain English</h3>
                <p>We translate medical language into clear guidance you can actually use.</p>
            </article>
            <article class="value-card">
                <div class="value-icon teal"><span class="material-symbols-outlined" aria-hidden="true">accessibility_new</span></div>
                <h3>Patient Centered</h3>
                <p>Built around daily challenges and the exact questions doctors do not always have time to cover.</p>
            </article>
        </div>
    </div>
</section>

<section class="mock-how-it-works">
    <div class="mock-container">
        <div class="section-heading center">
            <div class="section-kicker">Simple Process</div>
            <h2>How It Works</h2>
        </div>
        <div class="steps-grid">
            <article class="step-card">
                <div class="step-circle">1</div>
                <h3>Pick your situation</h3>
                <p>Find the exact condition or challenge you are facing.</p>
            </article>
            <article class="step-card">
                <div class="step-circle">2</div>
                <h3>Copy the prompt</h3>
                <p>One click to copy our nurse-engineered structure.</p>
            </article>
            <article class="step-card">
                <div class="step-circle">3</div>
                <h3>Get real answers</h3>
                <p>Paste in ChatGPT for clear, actionable guidance.</p>
            </article>
        </div>
    </div>
</section>

<section class="mock-featured" id="categories">
    <div class="mock-container">
        <div class="section-row">
            <div>
                <div class="section-kicker muted">Library</div>
                <h2>Featured Categories</h2>
            </div>
            <a class="section-link" href="/prompts">View all categories <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </div>
        <?php if ($featuredConditions === []): ?>
            <p>No published conditions yet.</p>
        <?php else: ?>
            <div class="featured-grid">
                <?php foreach ($featuredConditions as $condition): ?>
                    <?php
                    $slug = (string) ($condition['slug'] ?? '');
                    $name = (string) ($condition['condition_name'] ?? 'Condition');
                    $situation = (string) ($condition['situation'] ?? '');
                    $count = (int) ($condition['prompt_count'] ?? 12);
                    ?>
                    <a class="featured-card" href="/prompts/<?= app_h($slug); ?>">
                        <div class="featured-top">
                            <div class="featured-icon <?= ($count % 2 === 0) ? 'teal' : 'amber'; ?>">
                                <span class="material-symbols-outlined" aria-hidden="true"><?= app_h($iconForSituation($situation)); ?></span>
                            </div>
                            <span class="featured-count"><?= app_h((string) $count); ?> Prompts</span>
                        </div>
                        <h3><?= app_h($name); ?></h3>
                        <p><?= app_h($summaryForSituation($situation)); ?></p>
                        <span class="featured-tag"><?= app_h($formatSituation($situation)); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="mock-cta">
    <div class="mock-container">
        <div class="cta-panel">
            <h2>Unlock better health conversations.</h2>
            <p>Get full access to every condition pack, nurse notes, and priority updates.</p>
            <div class="cta-actions">
                <a class="btn-primary" href="/billing/checkout?plan=monthly">Get Full Access - $17/month</a>
                <span>Cancel anytime</span>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
