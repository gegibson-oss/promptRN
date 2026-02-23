<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$bodyClass = 'home-page';
$pageTitle = 'Expert AI Prompts for your Health | PromptRN';
$metaDescription = 'Bridge the gap between your doctor\'s visit and your daily life with clinically-vetted AI prompts written by nurses.';
$canonicalUrl = app_url('/');
$robots = 'index, follow';
$extraHeadTags = <<<'HTML'
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,300;400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
HTML;

$featuredCategories = [
    [
        'title' => 'Diabetes & Metabolism',
        'description' => 'Understand A1C, manage blood sugar, and navigate dietary changes with confidence.',
        'prompt_count' => '12 Prompts',
        'icon' => 'blood_pressure',
        'tone' => 'teal',
        'link' => '/prompts/type-2-diabetes-diagnosis',
    ],
    [
        'title' => 'Heart Health',
        'description' => 'Hypertension management, post-stroke care, and cholesterol education simplified.',
        'prompt_count' => '18 Prompts',
        'icon' => 'cardiology',
        'tone' => 'amber',
        'link' => '/prompts/hypertension-high-blood-pressure',
    ],
    [
        'title' => 'Lab Result Translation',
        'description' => 'Upload your results (safely) or paste values to get a plain-English explanation of your bloodwork.',
        'prompt_count' => '8 Prompts',
        'icon' => 'science',
        'tone' => 'teal',
        'link' => '/tools/prompt-generator',
    ],
    [
        'title' => 'New Diagnosis Prep',
        'description' => 'What to ask, what to expect, and how to prepare for your first specialist appointment.',
        'prompt_count' => '24 Prompts',
        'icon' => 'diagnosis',
        'tone' => 'amber',
        'link' => '/prompts',
    ],
    [
        'title' => 'Mental Wellness',
        'description' => 'Prompts for journaling, understanding therapy types, and managing anxiety.',
        'prompt_count' => '15 Prompts',
        'icon' => 'psychology',
        'tone' => 'teal',
        'link' => '/prompts/anxiety-disorder-newly-diagnosed',
    ],
    [
        'title' => 'Caregiving',
        'description' => 'Support for those caring for aging parents or chronically ill family members.',
        'prompt_count' => '10 Prompts',
        'icon' => 'elderly',
        'tone' => 'amber',
        'link' => '/about',
    ],
];

require __DIR__ . '/includes/header.php';
?>
<article class="home-template">
    <header class="home-template-header">
        <div class="home-template-container home-template-nav-inner">
            <a class="home-template-logo" href="/">Prompt<span>RN</span></a>
            <nav class="home-template-nav-links" aria-label="Main">
                <a href="/prompts">Browse Conditions</a>
                <a href="/tools/prompt-generator">Free Tools</a>
                <a href="/about">About</a>
                <a class="home-template-nav-cta" href="/auth/register">Get Full Access</a>
            </nav>
        </div>
    </header>

    <main class="home-template-main" role="main">
        <section class="home-template-hero" aria-labelledby="home-template-hero-title">
            <div class="home-template-hero-texture" aria-hidden="true"></div>
            <div class="home-template-container home-template-hero-inner">
                <p class="home-template-badge">
                    <span class="material-symbols-outlined" aria-hidden="true">verified</span>
                    Verified by Registered Nurses
                </p>
                <h1 id="home-template-hero-title">
                    Expert AI Prompts for your Health,
                    <em>Written by Nurses.</em>
                </h1>
                <p class="home-template-hero-lead">
                    Bridge the gap between your doctor's visit and your daily life with clinically-vetted prompts for ChatGPT.
                </p>
                <div class="home-template-hero-actions">
                    <a class="home-template-btn home-template-btn-primary" href="/prompts">Browse 500+ Conditions</a>
                    <a class="home-template-btn home-template-btn-secondary" href="/tools/prompt-generator">See Free Tools</a>
                </div>
                <div class="home-template-hero-stats">
                    <div class="home-template-hero-stat">
                        <p class="home-template-hero-stat-value">10k+</p>
                        <p class="home-template-hero-stat-label">Prompts Copied</p>
                    </div>
                    <div class="home-template-hero-stat">
                        <p class="home-template-hero-stat-value home-template-hero-stat-rating">
                            <span>4.9</span>
                            <span class="material-symbols-outlined home-template-star" aria-hidden="true">star</span>
                        </p>
                        <p class="home-template-hero-stat-label">Patient Rating</p>
                    </div>
                    <div class="home-template-hero-stat">
                        <p class="home-template-hero-stat-value">100%</p>
                        <p class="home-template-hero-stat-label">Verified RN Authors</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="home-template-proof">
            <div class="home-template-container">
                <div class="home-template-proof-copy">
                    <h2>Most patients leave their appointments with more questions than answers.</h2>
                    <p>Healthcare is complex. We simplify it so you can take control.</p>
                </div>
                <div class="home-template-proof-grid">
                    <article class="home-template-proof-card">
                        <span class="home-template-proof-icon home-template-proof-icon-teal material-symbols-outlined" aria-hidden="true">medical_services</span>
                        <h3>Clinically Vetted</h3>
                        <p>Every prompt is written and reviewed by registered nurses with clinical experience in that specific field.</p>
                    </article>
                    <article class="home-template-proof-card">
                        <span class="home-template-proof-icon home-template-proof-icon-amber material-symbols-outlined" aria-hidden="true">translate</span>
                        <h3>Plain English</h3>
                        <p>We translate complex medical jargon into clear, understandable language you can actually use.</p>
                    </article>
                    <article class="home-template-proof-card">
                        <span class="home-template-proof-icon home-template-proof-icon-teal material-symbols-outlined" aria-hidden="true">accessibility_new</span>
                        <h3>Patient Centered</h3>
                        <p>Designed for your perspective, addressing the fears, doubts, and daily challenges doctors often miss.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="home-template-steps">
            <div class="home-template-container">
                <p class="home-template-section-kicker">Simple Process</p>
                <h2>How It Works</h2>
                <ol class="home-template-steps-list">
                    <li>
                        <span class="home-template-step-number">1</span>
                        <h3>Pick your situation</h3>
                        <p>Find the exact condition or challenge you are facing.</p>
                    </li>
                    <li>
                        <span class="home-template-step-number">2</span>
                        <h3>Copy the prompt</h3>
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

        <section class="home-template-categories" id="categories">
            <div class="home-template-container">
                <div class="home-template-categories-header">
                    <div>
                        <p class="home-template-section-label">Library</p>
                        <h2>Featured Categories</h2>
                    </div>
                    <a href="/prompts">
                        View all categories
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>
                <div class="home-template-categories-grid">
                    <?php foreach ($featuredCategories as $category): ?>
                        <a class="home-template-category-card" href="<?= app_h((string) $category['link']); ?>">
                            <div class="home-template-category-head">
                                <span class="home-template-category-icon home-template-category-icon-<?= app_h((string) $category['tone']); ?>">
                                    <span class="material-symbols-outlined" aria-hidden="true"><?= app_h((string) $category['icon']); ?></span>
                                </span>
                                <span class="home-template-category-pill"><?= app_h((string) $category['prompt_count']); ?></span>
                            </div>
                            <h3><?= app_h((string) $category['title']); ?></h3>
                            <p><?= app_h((string) $category['description']); ?></p>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <section class="home-template-cta-shell">
            <div class="home-template-container">
                <div class="home-template-cta">
                    <div class="home-template-cta-overlay" aria-hidden="true"></div>
                    <div class="home-template-cta-content">
                        <h2>Unlock better health conversations.</h2>
                        <p>Get full access to every condition pack, nurse's notes, and priority updates for one simple price.</p>
                        <div class="home-template-cta-actions">
                            <a class="home-template-btn home-template-btn-primary" href="/billing/checkout?plan=monthly">Get Full Access - $17/month</a>
                            <span>Cancel anytime</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="home-template-footer">
        <div class="home-template-container">
            <div class="home-template-footer-grid">
                <div>
                    <p class="home-template-footer-logo">Prompt<span>RN</span></p>
                    <p class="home-template-footer-copy">Empowering patients with nurse-authored AI tools to bridge the gap in healthcare communication.</p>
                    <div class="home-template-footer-icons">
                        <span class="material-symbols-outlined" aria-hidden="true">mail</span>
                        <span class="material-symbols-outlined" aria-hidden="true">forum</span>
                    </div>
                </div>
                <div>
                    <h4>Platform</h4>
                    <a href="/prompts">Browse Conditions</a>
                    <a href="/tools/prompt-generator">Free Tools</a>
                    <a href="/billing/checkout?plan=monthly">Pricing</a>
                </div>
                <div>
                    <h4>Company</h4>
                    <a href="/about">About Us</a>
                    <a href="/about">Careers</a>
                    <a href="/about">Contact</a>
                </div>
                <div>
                    <h4>Legal</h4>
                    <a href="/about">Privacy Policy</a>
                    <a href="/about">Terms of Service</a>
                    <a href="/about">Medical Disclaimer</a>
                </div>
            </div>
            <div class="home-template-footer-bottom">
                <div>&copy; <?= app_h((string) date('Y')); ?> PromptRN - Written by nurses, for patients</div>
                <div>PromptRN does not provide medical advice. Always consult a professional.</div>
            </div>
        </div>
    </footer>
</article>
<?php require __DIR__ . '/includes/footer.php'; ?>
