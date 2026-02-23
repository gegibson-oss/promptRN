<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = app_meta();
$conditions = $meta['conditions'] ?? [];
$conditionCount = count($conditions);
$authorName = (string) ($meta['author_name'] ?? 'PromptRN RN Team');

$conditionsBySituation = [];
foreach ($conditions as $conditionMeta) {
    $situationSlug = strtolower(trim((string) ($conditionMeta['situation'] ?? '')));
    if (!app_validate_slug($situationSlug)) {
        continue;
    }
    $conditionsBySituation[$situationSlug] = ($conditionsBySituation[$situationSlug] ?? 0) + 1;
}

$featuredCategoryMap = [
    'newly-diagnosed' => [
        'title' => 'Newly Diagnosed',
        'description' => 'First-step prompts for understanding your diagnosis and what to ask next.',
        'icon' => '+',
    ],
    'medication-questions' => [
        'title' => 'Medication Questions',
        'description' => 'Prompt packs for side effects, dosing confusion, and refill planning.',
        'icon' => 'Rx',
    ],
    'understanding-lab-results' => [
        'title' => 'Understanding Labs',
        'description' => 'Translate lab numbers into plain language with practical follow-up prompts.',
        'icon' => 'Lab',
    ],
    'talking-to-your-doctor' => [
        'title' => 'Doctor Conversations',
        'description' => 'Prepare clearer questions before appointments and specialist visits.',
        'icon' => 'Q',
    ],
    'managing-chronic-illness' => [
        'title' => 'Chronic Care',
        'description' => 'Use prompts to build daily routines, symptom tracking, and care momentum.',
        'icon' => 'C',
    ],
    'preparing-for-surgery' => [
        'title' => 'Preparing for Surgery',
        'description' => 'Get questions for pre-op planning, recovery, and red-flag symptoms.',
        'icon' => 'S',
    ],
];

$featuredCategories = [];
foreach ($featuredCategoryMap as $slug => $category) {
    $situationPath = app_data_path('situations/' . $slug . '.json');
    $featuredCategories[] = [
        'slug' => $slug,
        'title' => (string) $category['title'],
        'description' => (string) $category['description'],
        'icon' => (string) $category['icon'],
        'count' => (int) ($conditionsBySituation[$slug] ?? 0),
        'link' => is_readable($situationPath) ? '/situations/' . $slug : '/prompts',
    ];
}

$pageTitle = 'PromptRN | Nurse-Written AI Health Prompts';
$metaDescription = 'Understand your diagnosis with structured, nurse-written AI prompts for better questions and clearer care decisions.';
$canonicalUrl = app_url('/');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<article class="home-template">
    <section class="home-template-hero">
        <div class="home-template-container home-template-hero-inner">
            <p class="home-template-badge">Nurse-written prompt packs</p>
            <h1>
                Expert AI Prompts for your Health,
                <em>Written by Nurses.</em>
            </h1>
            <p class="home-template-hero-lead">
                Enjoy 3 free prompts on every condition page, then unlock full nurse-written prompt packs to ask better questions and leave appointments with a clearer plan.
            </p>
            <div class="home-template-hero-actions">
                <a class="button home-template-primary-button" href="/prompts">Browse <?= app_h((string) $conditionCount); ?>+ Packs</a>
                <a class="button secondary home-template-secondary-button" href="/billing/checkout?plan=monthly">Start for $17/month</a>
            </div>
            <div class="home-template-hero-stats">
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value"><?= app_h((string) $conditionCount); ?>+</p>
                    <p class="home-template-hero-stat-label">condition packs</p>
                </div>
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value">4.9</p>
                    <p class="home-template-hero-stat-label">avg rating</p>
                </div>
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value">89%</p>
                    <p class="home-template-hero-stat-label">felt more prepared</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-template-proof">
        <div class="home-template-container">
            <h2>Most patients leave their appointments with more questions than answers.</h2>
            <p class="home-template-proof-lead">PromptRN gives you a practical, nurse-guided way to use AI for diagnosis clarity, medication questions, and next-step planning.</p>
            <div class="home-template-proof-grid">
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon" aria-hidden="true">+</span>
                    <h3>Nurse Vetted</h3>
                    <p>Every prompt is reviewed for patient safety, clarity, and clinical relevance.</p>
                </article>
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon" aria-hidden="true">Q</span>
                    <h3>Plain English</h3>
                    <p>Cut through medical jargon with structured prompts designed for real conversations.</p>
                </article>
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon" aria-hidden="true">!</span>
                    <h3>Faster Learning</h3>
                    <p>Move from confusion to confidence before your next appointment.</p>
                </article>
            </div>
        </div>
    </section>

    <section class="home-template-steps">
        <div class="home-template-container">
            <p class="home-template-section-label">Simple process</p>
            <h2>How It Works</h2>
            <ol class="home-template-steps-list">
                <li>
                    <span class="home-template-step-number">1</span>
                    <h3>Pick your condition</h3>
                    <p>Start with a diagnosis page and skim the free prompts.</p>
                </li>
                <li>
                    <span class="home-template-step-number">2</span>
                    <h3>Copy a prompt</h3>
                    <p>Use one prompt at a time in your AI tool of choice.</p>
                </li>
                <li>
                    <span class="home-template-step-number">3</span>
                    <h3>Ask and act</h3>
                    <p>Bring better questions into visits and make clearer care decisions.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="home-template-categories">
        <div class="home-template-container">
            <div class="home-template-categories-header">
                <h2>Featured Categories</h2>
                <a href="/prompts">View all conditions</a>
            </div>
            <div class="home-template-categories-grid">
                <?php foreach ($featuredCategories as $category): ?>
                    <a class="home-template-category-card" href="<?= app_h((string) $category['link']); ?>">
                        <span class="home-template-category-icon" aria-hidden="true"><?= app_h((string) $category['icon']); ?></span>
                        <p class="home-template-category-title"><?= app_h((string) $category['title']); ?></p>
                        <p class="home-template-category-meta">
                            <?php if ((int) $category['count'] > 0): ?>
                                <?= app_h((string) $category['count']); ?> condition<?= (int) $category['count'] === 1 ? '' : 's'; ?>
                            <?php else: ?>
                                New packs coming
                            <?php endif; ?>
                        </p>
                        <p class="home-template-category-description"><?= app_h((string) $category['description']); ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="home-template-cta-shell">
        <div class="home-template-container">
            <div class="home-template-cta">
                <h2>Unlock better health conversations.</h2>
                <p>Get access to every condition pack, new nurse-written releases, and ongoing updates for one monthly plan.</p>
                <a class="button home-template-primary-button" href="/billing/checkout?plan=monthly">Get Full Access - $17/month</a>
                <p class="home-template-cta-subtext">Written by <?= app_h($authorName); ?> and built for patients who need clear, practical support.</p>
            </div>
        </div>
    </section>
</article>
<?php require __DIR__ . '/includes/footer.php'; ?>
