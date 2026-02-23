<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = app_meta();
$conditions = $meta['conditions'] ?? [];
$authorName = (string) ($meta['author_name'] ?? 'Sarah Mitchell RN');
$bodyClass = 'home-page';

$featuredCategories = [
    [
        'title' => 'Diabetes & Metabolism',
        'description' => 'Understand A1C, manage blood sugar, and navigate dietary changes with confidence.',
        'prompt_count' => 12,
        'icon' => 'D',
        'tone' => 'teal',
        'link' => '/prompts/type-2-diabetes-diagnosis',
    ],
    [
        'title' => 'Heart Health',
        'description' => 'Hypertension management, post-stroke care, and cholesterol education simplified.',
        'prompt_count' => 18,
        'icon' => 'H',
        'tone' => 'amber',
        'link' => '/prompts/hypertension-high-blood-pressure',
    ],
    [
        'title' => 'Lab Result Translation',
        'description' => 'Upload your results safely to get a plain-English explanation of your bloodwork.',
        'prompt_count' => 8,
        'icon' => 'L',
        'tone' => 'teal',
        'link' => '/tools/prompt-generator',
    ],
    [
        'title' => 'New Diagnosis Prep',
        'description' => 'What to ask, what to expect, and how to prepare for your first specialist visit.',
        'prompt_count' => 24,
        'icon' => 'N',
        'tone' => 'amber',
        'link' => '/prompts',
    ],
    [
        'title' => 'Mental Wellness',
        'description' => 'Prompts for journaling, understanding therapy types, and managing anxiety.',
        'prompt_count' => 15,
        'icon' => 'M',
        'tone' => 'teal',
        'link' => '/prompts/anxiety-disorder-newly-diagnosed',
    ],
    [
        'title' => 'Caregiving',
        'description' => 'Support for those caring for aging parents or chronically ill family members.',
        'prompt_count' => 10,
        'icon' => 'C',
        'tone' => 'amber',
        'link' => '/about',
    ],
];

$pageTitle = 'PromptRN | Nurse-Written AI Health Prompts';
$metaDescription = 'Understand your diagnosis with structured, nurse-written AI prompts for better questions and clearer care decisions.';
$canonicalUrl = app_url('/');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<article class="home-template">
    <section class="home-template-hero">
        <div class="home-template-container home-template-hero-inner">
            <p class="home-template-badge">Verified by Registered Nurses</p>
            <h1>
                Expert AI Prompts for your Health,
                <em>Written by Nurses.</em>
            </h1>
            <p class="home-template-hero-lead">
                Bridge the gap between your doctor&apos;s visit and your daily life with clinically-vetted prompts for ChatGPT.
            </p>
            <div class="home-template-hero-actions">
                <a class="button home-template-primary-button" href="/prompts">Browse 500+ Conditions</a>
                <a class="button secondary home-template-secondary-button" href="/tools/prompt-generator">See Free Tools</a>
            </div>
            <div class="home-template-hero-stats">
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value">10k+</p>
                    <p class="home-template-hero-stat-label">prompts copied</p>
                </div>
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value">4.9</p>
                    <p class="home-template-hero-stat-label">patient rating</p>
                </div>
                <div class="home-template-hero-stat">
                    <p class="home-template-hero-stat-value">100%</p>
                    <p class="home-template-hero-stat-label">verified RN authors</p>
                </div>
            </div>
        </div>
    </section>

    <section class="home-template-proof">
        <div class="home-template-container">
            <h2>Most patients leave their appointments with more questions than answers.</h2>
            <p class="home-template-proof-lead">Healthcare is complex. We simplify it so you can take control.</p>
            <div class="home-template-proof-grid">
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon" aria-hidden="true">+</span>
                    <h3>Clinically Vetted</h3>
                    <p>Every prompt is written and reviewed by registered nurses with clinical experience in that specific field.</p>
                </article>
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon home-template-proof-icon-amber" aria-hidden="true">A</span>
                    <h3>Plain English</h3>
                    <p>We translate complex medical jargon into clear, understandable language you can actually use.</p>
                </article>
                <article class="home-template-proof-card">
                    <span class="home-template-proof-icon" aria-hidden="true">P</span>
                    <h3>Patient Centered</h3>
                    <p>Designed for your perspective, addressing the fears, doubts, and daily challenges doctors often miss.</p>
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
                    <h3>Pick your situation</h3>
                    <p>Find the exact condition or challenge you are facing.</p>
                </li>
                <li>
                    <span class="home-template-step-number">2</span>
                    <h3>Copy a prompt</h3>
                    <p>One click to copy our nurse-engineered structure.</p>
                </li>
                <li>
                    <span class="home-template-step-number">3</span>
                    <h3>Get real answers</h3>
                    <p>Paste into ChatGPT for clear, actionable guidance.</p>
                </li>
            </ol>
        </div>
    </section>

    <section class="home-template-categories">
        <div class="home-template-container">
            <div class="home-template-categories-header">
                <div>
                    <p class="home-template-section-label home-template-section-label-muted">Library</p>
                    <h2>Featured Categories</h2>
                </div>
                <a href="/prompts">View all categories</a>
            </div>
            <div class="home-template-categories-grid">
                <?php foreach ($featuredCategories as $category): ?>
                    <a class="home-template-category-card" href="<?= app_h((string) $category['link']); ?>">
                        <div class="home-template-category-head">
                            <span class="home-template-category-icon home-template-category-icon-<?= app_h((string) $category['tone']); ?>" aria-hidden="true"><?= app_h((string) $category['icon']); ?></span>
                            <span class="home-template-category-pill"><?= app_h((string) $category['prompt_count']); ?> prompts</span>
                        </div>
                        <p class="home-template-category-title"><?= app_h((string) $category['title']); ?></p>
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
                <p>Get full access to every condition pack, nurse&apos;s notes, and priority updates for one simple price.</p>
                <a class="button home-template-primary-button" href="/billing/checkout?plan=monthly">Get Full Access - $17/month</a>
                <p class="home-template-cta-subtext">Cancel anytime.</p>
            </div>
        </div>
    </section>

    <footer class="home-template-footer">
        <div class="home-template-container home-template-footer-inner">
            <div>
                <a href="/" class="home-template-footer-logo">Prompt<span>RN</span></a>
                <p class="home-template-footer-copy">Empowering patients with nurse-authored AI prompts for clearer conversations.</p>
            </div>
            <div>
                <p class="home-template-footer-heading">Platform</p>
                <a href="/prompts">Browse Conditions</a>
                <a href="/tools/prompt-generator">Free Tools</a>
                <a href="/billing/checkout?plan=monthly">Pricing</a>
            </div>
            <div>
                <p class="home-template-footer-heading">Company</p>
                <a href="/about">About</a>
                <a href="/about">Careers</a>
                <a href="/about">Contact</a>
            </div>
            <div>
                <p class="home-template-footer-heading">Legal</p>
                <a href="/about">Privacy Policy</a>
                <a href="/about">Terms of Service</a>
                <a href="/about">Medical Disclaimer</a>
            </div>
        </div>
        <div class="home-template-container home-template-footer-bottom">
            <span>&copy; <?= app_h((string) date('Y')); ?> PromptRN. All rights reserved.</span>
            <span>PromptRN content is educational and does not replace medical care.</span>
        </div>
    </footer>
</article>
<?php require __DIR__ . '/includes/footer.php'; ?>
