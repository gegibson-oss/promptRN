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
<section class="hero">
    <h1>Nurse-Written AI Health Prompts for Patients</h1>
    <p>Understand your diagnosis, prep better questions, and make confident care decisions without medical jargon.</p>
    <a class="button" href="/prompts">Browse Conditions</a>
</section>

<section>
    <h2>Available Conditions</h2>
    <?php if ($conditions === []): ?>
        <p>No condition content has been added yet.</p>
    <?php else: ?>
        <ul class="card-list">
            <?php foreach ($conditions as $condition): ?>
                <li>
                    <a href="/prompts/<?= app_h((string) ($condition['slug'] ?? '')); ?>">
                        <?= app_h((string) ($condition['condition_name'] ?? 'Untitled Condition')); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<section>
    <h2>Pricing</h2>
    <div class="card-list">
        <div>
            <h3>Single Condition Pack</h3>
            <p><strong>$9</strong> one-time</p>
            <p>Unlock all 12 prompts for one diagnosis.</p>
        </div>
        <div>
            <h3>Monthly Membership</h3>
            <p><strong>$17/month</strong></p>
            <p>Access every condition pack in the full library.</p>
        </div>
        <div>
            <h3>Annual Membership</h3>
            <p><strong>$99/year</strong></p>
            <p>Best value for long-term condition management.</p>
        </div>
    </div>
</section>

<section>
    <h2>Why Patients Trust PromptRN</h2>
    <p><strong>Sarah Mitchell RN</strong>, Registered Nurse, 12 years in endocrinology and primary care.</p>
    <p>PromptRN content is designed to help patients understand care plans, improve appointment quality, and ask better questions faster.</p>
    <p><a href="/about">Read full nurse bio</a></p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
