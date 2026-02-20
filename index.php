<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$meta = app_meta();
$conditions = $meta['conditions'] ?? [];

$pageTitle = 'PromptRN | Nurse-Written AI Health Prompts';
$metaDescription = 'Understand your diagnosis with structured, nurse-written AI prompts for better questions and clearer care decisions.';
$canonicalUrl = app_url('/');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<section class="home-hero">
    <span class="hero-badge">Nurse-Written Prompt Packs</span>
    <h1>AI Prompts for Patients Who Just Need Things Explained Clearly</h1>
    <p class="hero-lead">PromptRN gives you structured, nurse-written prompts so AI can help you understand your diagnosis, prep better questions, and leave appointments with less confusion.</p>
    <div class="hero-actions">
        <a class="button" href="/prompts">Browse Conditions</a>
        <a class="button secondary" href="#pricing">View Pricing</a>
    </div>
</section>

<section>
    <div class="section-title-row">
        <h2>Available Conditions</h2>
        <span class="meta-pill"><?= app_h((string) count($conditions)); ?> live packs</span>
    </div>
    <?php if ($conditions === []): ?>
        <p>No condition content has been added yet.</p>
    <?php else: ?>
        <ul class="condition-grid">
            <?php foreach ($conditions as $condition): ?>
                <li>
                    <h3><a href="/prompts/<?= app_h((string) ($condition['slug'] ?? '')); ?>"><?= app_h((string) ($condition['condition_name'] ?? 'Untitled Condition')); ?></a></h3>
                    <p class="card-meta"><?= app_h((string) ($condition['prompt_count'] ?? 12)); ?> prompts</p>
                    <p class="card-meta">Last updated: <?= app_h((string) ($condition['last_updated'] ?? 'Unknown')); ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section id="pricing">
    <h2>Pricing</h2>
    <div class="pricing-grid">
        <article class="pricing-card">
            <h3>Single Condition Pack</h3>
            <p><strong>$9</strong> one-time</p>
            <p>Unlock all 12 prompts for one diagnosis.</p>
            <p><a class="button" href="/auth/register">Start with a Pack</a></p>
        </article>
        <article class="pricing-card">
            <h3>Monthly Membership</h3>
            <p><strong>$17/month</strong></p>
            <p>Access every condition pack in the full library.</p>
            <p><a class="button" href="/auth/register">Get Monthly Access</a></p>
        </article>
        <article class="pricing-card">
            <h3>Annual Membership</h3>
            <p><strong>$99/year</strong></p>
            <p>Best value for long-term condition management.</p>
            <p><a class="button" href="/auth/register">Choose Annual</a></p>
        </article>
    </div>
</section>

<section>
    <h2>Why Patients Trust PromptRN</h2>
    <div class="feature-grid">
        <article class="feature-card">
            <h3>Clinically Grounded</h3>
            <p>Each prompt is written from real RN patient-education experience, not generic AI prompt lists.</p>
        </article>
        <article class="feature-card">
            <h3>Built for Appointments</h3>
            <p>Prompts focus on helping you ask better questions and understand your next care steps.</p>
        </article>
        <article class="feature-card">
            <h3>Clear Safety Framing</h3>
            <p>PromptRN is educational support only. It never replaces care from your licensed clinician.</p>
        </article>
    </div>
    <p class="muted"><strong>Sarah Mitchell RN</strong> has 12 years in endocrinology and primary care. <a href="/about">Read the full nurse bio.</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
