<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/auth-check.php';

$slug = strtolower(trim((string)($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$situationSlug = strtolower(trim((string)($_GET['situation'] ?? '')));
$hasSituation = false;
$situation = [];
if ($situationSlug !== '') {
    if (!app_validate_slug($situationSlug) || !app_situation_exists($situationSlug)) {
        http_response_code(404);
        $slug = ''; // Force 404
    }
    else {
        $situation = app_read_json_file(app_data_path('situations/' . $situationSlug . '.json'));
        if ($situation !== []) {
            $hasSituation = true;
        }
        else {
            http_response_code(404);
            $slug = '';
        }
    }
}

$condition = app_read_json_file(app_condition_path($slug));

if ($slug === '' || $condition === []) {
    http_response_code(404);
    $pageTitle = 'Condition Not Found | PromptRN';
    $metaDescription = 'Requested condition prompts not found.';
    $canonicalUrl = app_url('/');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Condition Not Found</h1><p>We could not find the prompt pack you are looking for.</p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$meta = app_meta();

$missingSeoFields = seo_condition_missing_fields($condition);
$user = auth_current_user();
$hasFullAccess = auth_user_can_access_condition($user, $slug);
$freePrompts = is_array($condition['free_prompts'] ?? null) ? $condition['free_prompts'] : [];
$paidPrompts = is_array($condition['paid_prompts'] ?? null) ? $condition['paid_prompts'] : [];
$faqs = is_array($condition['faqs'] ?? null) ? $condition['faqs'] : [];
$relatedConditions = is_array($condition['related_conditions'] ?? null) ? $condition['related_conditions'] : [];
$relatedSituations = is_array($condition['related_situations'] ?? null) ? $condition['related_situations'] : [];

$conditionName = (string)($condition['condition_name'] ?? 'Condition');

// Dynamic Merging for Situations
$pageTitle = (string)($condition['seo']['page_title'] ?? $conditionName . ' Prompts | PromptRN');
$metaDescription = (string)($condition['seo']['meta_description'] ?? 'Nurse-written AI prompts for ' . $conditionName . '.');
$h1 = (string)($condition['seo']['h1'] ?? $conditionName . ' Prompts');
$canonicalUrl = app_url('/prompts/' . $slug);

if ($hasSituation) {
    $situationTitle = (string)($situation['title'] ?? 'Situation');
    $pageTitle = $situationTitle . ' - ' . $conditionName . ' Prompts | PromptRN';
    $metaDescription = 'Nurse-written AI prompts and guidance for ' . strtolower($situationTitle) . ' patients with ' . $conditionName . '.';
    $h1 = $conditionName . ' Prompts: ' . $situationTitle;
    $canonicalUrl = app_url('/prompts/' . $slug . '/' . $situationSlug);
}

// Meta robot rules
$robots = 'index, follow';

$authorName = (string)($condition['author']['name'] ?? 'PromptRN RN Team');
$authorCredentials = (string)($condition['author']['credentials'] ?? 'Registered Nurse');
$authorExperience = (string)($condition['author']['experience'] ?? '');
$authorWords = preg_split('/\s+/', trim($authorName)) ?: [];
$authorInitials = '';
foreach ($authorWords as $word) {
    if ($word === '' || !ctype_alpha($word[0])) {
        continue;
    }
    $authorInitials .= strtoupper($word[0]);
    if (strlen($authorInitials) >= 2) {
        break;
    }
}
if ($authorInitials === '') {
    $authorInitials = 'RN';
}

$totalPromptCount = count($freePrompts) + count($paidPrompts);
$packPriceUsd = (int)($condition['pack_price_usd'] ?? 9);
if ($packPriceUsd < 1) {
    $packPriceUsd = 9;
}
$packPrice = '$' . number_format($packPriceUsd);

$lastUpdatedRaw = (string)($condition['last_updated'] ?? '');
$lastUpdatedDisplay = $lastUpdatedRaw === '' ? 'Unknown' : $lastUpdatedRaw;
if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastUpdatedRaw) === 1) {
    $lastUpdatedDate = DateTimeImmutable::createFromFormat('Y-m-d', $lastUpdatedRaw);
    if ($lastUpdatedDate !== false) {
        $lastUpdatedDisplay = $lastUpdatedDate->format('M j, Y');
    }
}

$promptNumber = static function (array $prompt, int $fallback): string {
    $id = (string)($prompt['id'] ?? '');
    if (preg_match('/(\d+)/', $id, $matches) === 1) {
        return str_pad((string)((int)$matches[1]), 2, '0', STR_PAD_LEFT);
    }

    return str_pad((string)$fallback, 2, '0', STR_PAD_LEFT);
};

$slugToLabel = static function (string $value): string {
    return ucwords(str_replace('-', ' ', $value));
};

$conditionMeta = [];
foreach (($meta['conditions'] ?? []) as $metaCondition) {
    $metaSlug = (string)($metaCondition['slug'] ?? '');
    if ($metaSlug === '') {
        continue;
    }
    $conditionMeta[$metaSlug] = $metaCondition;
}

$faqItems = $faqs;
if ($faqItems === []) {
    $faqItems = [
        [
            'question' => 'Can AI help me understand this condition?',
            'answer' => 'Yes. These prompts are structured to help you ask specific questions in plain language so answers are easier to act on.',
        ],
    ];
}

require __DIR__ . '/../includes/header.php';
?>
<article class="condition-template">
    <?php if ($missingSeoFields !== [] && app_is_development()): ?>
    <section class="condition-template-alert alert">
        <strong>Missing SEO fields:</strong>
        <?= app_h(implode(', ', $missingSeoFields)); ?>
    </section>
    <?php
endif; ?>

    <nav class="condition-template-breadcrumb" aria-label="Breadcrumb">
        <div class="condition-template-container">
            <ol class="condition-template-breadcrumb-list"
                style="display:flex;flex-wrap:wrap;list-style:none;margin:0;padding:0;gap:0">
                <li><a href="/">Home</a></li>
                <li class="condition-template-breadcrumb-separator" aria-hidden="true">/</li>
                <li><a href="/prompts">Conditions</a></li>
                <li class="condition-template-breadcrumb-separator" aria-hidden="true">/</li>
                <?php if ($hasSituation): ?>
                <li><a href="/prompts/<?= app_h($slug); ?>">
                        <?= app_h($conditionName); ?>
                    </a></li>
                <li class="condition-template-breadcrumb-separator" aria-hidden="true">/</li>
                <li class="condition-template-breadcrumb-current" aria-current="page">
                    <?= app_h((string)($situation['title'] ?? 'Situation')); ?>
                </li>
                <?php
else: ?>
                <li class="condition-template-breadcrumb-current" aria-current="page">
                    <?= app_h($conditionName); ?>
                </li>
                <?php
endif; ?>
            </ol>
        </div>
    </nav>

    <section class="condition-template-hero">
        <div class="condition-template-container condition-template-hero-grid">
            <div class="condition-template-hero-copy">
                <p class="condition-template-hero-badge">
                    <span class="condition-template-hero-badge-icon" aria-hidden="true">
                        <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="1.4" stroke-linecap="round" focusable="false" aria-hidden="true">
                            <circle cx="8" cy="8" r="6.5"></circle>
                            <path d="M8 5v6M5 8h6"></path>
                        </svg>
                    </span>
                    <span>Nurse-Written AI Guidance</span>
                </p>
                <h1 class="condition-template-hero-title">
                    <?= app_h($h1); ?>
                </h1>
                <?php if ($hasSituation && isset($situation['summary'])): ?>
                <p class="condition-template-hero-subtitle">
                    <?= app_h((string)$situation['summary']); ?>
                </p>
                <?php
elseif (isset($condition['clinical_context'])): ?>
                <p class="condition-template-hero-subtitle">
                    <?= app_h((string)$condition['clinical_context']); ?>
                </p>
                <?php
endif; ?>
                <?php
$patientRating = (string)($condition['patient_rating'] ?? '4.9');
$patientCount = (string)($condition['patient_count'] ?? '214');
?>
                <div class="condition-template-hero-stats">
                    <div class="condition-template-hero-stat">
                        <p class="condition-template-hero-stat-value">
                            <?= app_h((string)$totalPromptCount); ?>
                        </p>
                        <p class="condition-template-hero-stat-label">Nurse-written prompts</p>
                    </div>
                    <div class="condition-template-hero-stat">
                        <p class="condition-template-hero-stat-value condition-template-hero-stat-rating">
                            <span>
                                <?= app_h($patientRating); ?>
                            </span>
                            <span class="condition-template-hero-stat-star" aria-hidden="true">
                                <svg width="20" height="19" viewBox="0 0 20 19" fill="currentColor" focusable="false"
                                    aria-hidden="true">
                                    <path
                                        d="M10 0.9l2.7 5.5 6.1 0.9-4.4 4.3 1 6-5.4-2.8-5.4 2.8 1-6-4.4-4.3 6.1-0.9L10 0.9z">
                                    </path>
                                </svg>
                            </span>
                        </p>
                        <p class="condition-template-hero-stat-label">From
                            <?= app_h($patientCount); ?> patients
                        </p>
                    </div>
                </div>
            </div>

            <aside class="condition-template-author-card">
                <div class="condition-template-author-header">
                    <span class="condition-template-author-avatar">
                        <?= app_h($authorInitials); ?>
                    </span>
                    <div>
                        <p class="condition-template-author-name">
                            <?= app_h($authorName); ?>
                        </p>
                        <p class="condition-template-author-role">
                            <?= app_h($authorCredentials); ?>
                        </p>
                    </div>
                </div>
                <?php
$authorQuote = trim((string)($condition['author']['quote'] ?? ''));
if ($authorQuote === '') {
    $authorQuote = 'I wrote these prompts because I watched thousands of patients leave their diagnosis appointment overwhelmed and without the information they needed.';
}
?>
                <p class="condition-template-author-bio">
                    "
                    <?= app_h($authorQuote); ?>"
                </p>
                <p class="condition-template-author-verified">
                    <span class="condition-template-author-verified-icon" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" focusable="false"
                            aria-hidden="true">
                            <circle cx="8" cy="8" r="6.5"></circle>
                            <path d="M4.5 8.1l2.2 2.2 4.8-4.8"></path>
                        </svg>
                    </span>
                    <span>RN License Verified</span>
                </p>
                <?php if (isset($condition['medical_review'])): ?>
                <div class="condition-template-medical-review">
                    <p class="condition-template-review-label">Medically Reviewed By:</p>
                    <p class="condition-template-reviewer">
                        <strong><?= app_h($condition['medical_review']['reviewer_name']); ?></strong>, 
                        <?= app_h($condition['medical_review']['reviewer_credentials']); ?>
                    </p>
                    <p class="condition-template-review-date">
                        Last Reviewed: <?php
    $reviewDateRaw = $condition['medical_review']['last_reviewed_date'];
    $reviewDateDisplay = $reviewDateRaw;
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $reviewDateRaw) === 1) {
        $reviewDate = DateTimeImmutable::createFromFormat('Y-m-d', $reviewDateRaw);
        if ($reviewDate !== false) {
            $reviewDateDisplay = $reviewDate->format('M j, Y');
        }
    }
    echo app_h($reviewDateDisplay);
?>
                    </p>
                </div>
                <?php
endif; ?>
            </aside>
        </div>
    </section>

    <section class="condition-template-main">
        <div class="condition-template-container condition-template-main-grid">
            <div class="condition-template-primary">
                <section class="condition-template-prompts">
                    <p class="condition-template-section-label">Start Here - Free Prompts</p>
                    <h2>
                        <?= app_h((string)count($freePrompts)); ?> Prompts to Try Right Now
                    </h2>
                    <?php foreach ($freePrompts as $index => $prompt): ?>
                    <?php
    $number = $promptNumber($prompt, $index + 1);
    $promptTitle = (string)($prompt['title'] ?? 'Untitled prompt');
    $promptText = (string)($prompt['prompt'] ?? '');
    $whyText = (string)($prompt['why_it_works'] ?? '');
?>
                    <article class="condition-template-prompt-card">
                        <p class="condition-template-prompt-number">Prompt
                            <?= app_h($number); ?> of
                            <?= app_h((string)$totalPromptCount); ?>
                        </p>
                        <h3>
                            <?= app_h($promptTitle); ?>
                        </h3>
                        <div class="condition-template-prompt-box">
                            <span class="condition-template-copy-chip" aria-hidden="true">Copy</span>
                            <blockquote>
                                <?= nl2br(app_h($promptText)); ?>
                            </blockquote>
                        </div>
                        <p class="condition-template-why-label">Why this works</p>
                        <p class="condition-template-why-text">
                            <?= app_h($whyText); ?>
                        </p>
                    </article>
                    <?php
endforeach; ?>
                </section>

                <section class="condition-template-locked">
                    <header class="condition-template-locked-header">
                        <p class="condition-template-section-label">Full Prompt Pack</p>
                        <h2>
                            <?php if ($hasFullAccess): ?>
                            Full Pack Unlocked
                            <?php
else: ?>
                            <?= app_h((string)count($paidPrompts)); ?> More Nurse-Written Prompts
                            <?php
endif; ?>
                        </h2>
                        <p>
                            <?php if ($hasFullAccess): ?>
                            You can now access every prompt in this pack.
                            <?php
else: ?>
                            Unlock this condition pack, or subscribe to access every condition.
                            <?php
endif; ?>
                        </p>
                    </header>

                    <?php if ($hasFullAccess): ?>
                    <div class="condition-template-prompt-stack">
                        <?php foreach ($paidPrompts as $paidIndex => $prompt): ?>
                        <?php
        $number = $promptNumber($prompt, count($freePrompts) + $paidIndex + 1);
        $promptTitle = (string)($prompt['title'] ?? 'Untitled prompt');
        $promptText = (string)($prompt['prompt'] ?? '');
        $whyText = (string)($prompt['why_it_works'] ?? '');
?>
                        <article class="condition-template-prompt-card">
                            <p class="condition-template-prompt-number">Prompt
                                <?= app_h($number); ?> of
                                <?= app_h((string)$totalPromptCount); ?>
                            </p>
                            <h3>
                                <?= app_h($promptTitle); ?>
                            </h3>
                            <div class="condition-template-prompt-box">
                                <span class="condition-template-copy-chip" aria-hidden="true">Copy</span>
                                <blockquote>
                                    <?= nl2br(app_h($promptText)); ?>
                                </blockquote>
                            </div>
                            <p class="condition-template-why-label">Why this works</p>
                            <p class="condition-template-why-text">
                                <?= app_h($whyText); ?>
                            </p>
                        </article>
                        <?php
    endforeach; ?>
                    </div>
                    <?php
else: ?>
                    <ol class="condition-template-locked-list">
                        <?php foreach ($paidPrompts as $paidIndex => $prompt): ?>
                        <?php
        $number = $promptNumber($prompt, count($freePrompts) + $paidIndex + 1);
        $promptTitle = (string)($prompt['title'] ?? 'Locked prompt');
?>
                        <li class="condition-template-locked-item">
                            <span class="condition-template-locked-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path
                                        d="M7 10V8a5 5 0 0 1 10 0v2h1a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h1Zm2 0h6V8a3 3 0 0 0-6 0v2Z">
                                    </path>
                                </svg>
                                <?= app_h($promptTitle); ?>
                            </span>
                            <span class="condition-template-locked-badge">Prompt
                                <?= app_h($number); ?>
                            </span>
                        </li>
                        <?php
    endforeach; ?>
                    </ol>
                    <div class="condition-template-unlock">
                        <p>Get this full pack instantly and keep it forever.</p>
                        <a class="button" href="/billing/checkout?plan=pack&amp;slug=<?= app_h($slug); ?>">Unlock All
                            <?= app_h((string)$totalPromptCount); ?> Prompts -
                            <?= app_h($packPrice); ?>
                        </a>
                        <p class="condition-template-unlock-subtext">
                            Or subscribe for full library access: <a href="/billing/checkout?plan=monthly">$17/month</a>
                        </p>
                    </div>
                    <?php
endif; ?>
                </section>

                <section class="condition-template-faq">
                    <p class="condition-template-section-label">Common Questions</p>
                    <h2>What Patients Ask Us</h2>
                    <div class="condition-template-faq-list">
                        <?php foreach ($faqItems as $faqIndex => $faq): ?>
                        <?php
    $faqQuestion = (string)($faq['question'] ?? '');
    $faqAnswer = (string)($faq['answer'] ?? '');
    if ($faqQuestion === '' || $faqAnswer === '') {
        continue;
    }
?>
                        <details class="condition-template-faq-item" <?= $faqIndex === 0 ? ' open' : ''; ?>>
                            <summary>
                                <?= app_h($faqQuestion); ?>
                            </summary>
                            <p>
                                <?= app_h($faqAnswer); ?>
                            </p>
                        </details>
                        <?php
endforeach; ?>
                    </div>
                </section>

                <section class="condition-template-disclaimer">
                    <p>
                        <strong>Medical Disclaimer:</strong>
                        Prompt content is educational and is not medical advice. Always verify important decisions with
                        your licensed clinician.
                    </p>
                    <?php if (isset($condition['sources']) && is_array($condition['sources']) && count($condition['sources']) > 0): ?>
                    <p class="condition-template-sources">
                        <strong>Sources:</strong>
                        <ul class="condition-template-sources-list">
                            <?php foreach ($condition['sources'] as $source): ?>
                            <li><?= app_h((string)$source); ?></li>
                            <?php
    endforeach; ?>
                        </ul>
                    </p>
                    <?php
endif; ?>
                </section>

                <?php if ($relatedConditions !== []): ?>
                <section class="condition-template-related">
                    <p class="condition-template-section-label">Related Conditions</p>
                    <h2>Other Patients Also Viewed</h2>
                    <div class="condition-template-related-grid">
                        <?php foreach ($relatedConditions as $relatedSlug): ?>
                        <?php
        $relatedSlug = (string)$relatedSlug;
        if (!app_condition_exists($relatedSlug)) {
            continue;
        }
        $relatedPromptCount = (int)(($conditionMeta[$relatedSlug]['prompt_count'] ?? 12));
?>
                        <a class="condition-template-related-card" href="/prompts/<?= app_h($relatedSlug); ?>">
                            <span>
                                <strong>
                                    <?= app_h(app_condition_name($relatedSlug)); ?>
                                </strong>
                                <small>
                                    <?= app_h((string)$relatedPromptCount); ?> prompts
                                </small>
                            </span>
                            <span aria-hidden="true">&rarr;</span>
                        </a>
                        <?php
    endforeach; ?>
                    </div>
                </section>
                <?php
endif; ?>
            </div>

            <aside class="condition-template-sidebar">
                <section class="condition-template-sidebar-cta">
                    <?php if ($hasFullAccess): ?>
                    <h3>Full Access Enabled</h3>
                    <p>You already have this full pack available in your account.</p>
                    <a class="button" href="/members/dashboard">Go to Dashboard</a>
                    <?php
else: ?>
                    <h3>Get Every Condition Pack</h3>
                    <p>Full library access, new nurse-written packs every week.</p>
                    <a class="button" href="/billing/checkout?plan=monthly">Start for $17/month</a>
                    <?php
endif; ?>
                </section>

                <section class="condition-template-sidebar-card">
                    <h3>What&apos;s Included</h3>
                    <ul>
                        <li>
                            <?= app_h((string)$totalPromptCount); ?> prompts per condition
                        </li>
                        <li>Clinical context for each pack</li>
                        <li>Doctor appointment prep prompts</li>
                        <li>Written by a registered nurse</li>
                        <li>Cancel anytime</li>
                    </ul>
                </section>

                <?php if ($relatedSituations !== []): ?>
                <section class="condition-template-sidebar-card">
                    <h3>Related Situations</h3>
                    <div class="condition-template-situation-links">
                        <?php foreach ($relatedSituations as $relatedSituation): ?>
                        <?php $relatedSituation = (string)$relatedSituation; ?>
                        <?php if (!app_validate_slug($relatedSituation)) {
            continue;
        }?>
                        <a href="/prompts/<?= app_h($slug); ?>/<?= app_h($relatedSituation); ?>">
                            <?= app_h($slugToLabel($relatedSituation)); ?>
                        </a>
                        <?php
    endforeach; ?>
                    </div>
                </section>
                <?php
endif; ?>
            </aside>
        </div>
    </section>
</article>
<?php seo_render_condition_schemas($condition, $meta, $canonicalUrl); ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>