<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/auth-check.php';

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$meta = app_meta();

if ($slug === '' || !app_condition_exists($slug)) {
    http_response_code(404);
    $pageTitle = 'Condition Not Found | PromptRN';
    $metaDescription = 'The requested condition page does not exist.';
    $canonicalUrl = app_url('/prompts');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Condition Not Found</h1><p>Try browsing all available condition pages.</p><p><a class="btn-primary" href="/prompts">Back to all conditions</a></p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$condition = app_read_json_file(app_condition_path($slug));
if ($condition === []) {
    http_response_code(404);
    $pageTitle = 'Condition Not Found | PromptRN';
    $metaDescription = 'The requested condition page does not exist.';
    $canonicalUrl = app_url('/prompts');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Condition Not Found</h1><p>Try browsing all available condition pages.</p><p><a class="btn-primary" href="/prompts">Back to all conditions</a></p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$missingSeoFields = seo_condition_missing_fields($condition);
$user = auth_current_user();
$hasFullAccess = auth_user_can_access_condition($user, $slug);
$freePrompts = is_array($condition['free_prompts'] ?? null) ? $condition['free_prompts'] : [];
$paidPrompts = is_array($condition['paid_prompts'] ?? null) ? $condition['paid_prompts'] : [];
$faqs = is_array($condition['faqs'] ?? null) ? $condition['faqs'] : [];
$relatedConditions = is_array($condition['related_conditions'] ?? null) ? $condition['related_conditions'] : [];
$relatedSituations = is_array($condition['related_situations'] ?? null) ? $condition['related_situations'] : [];
$patientFears = is_array($condition['patient_fears'] ?? null) ? $condition['patient_fears'] : [];

$conditionName = (string) ($condition['condition_name'] ?? 'Condition');
$situation = (string) ($condition['situation'] ?? '');
$clinicalContext = (string) ($condition['clinical_context'] ?? 'Clinical context coming soon.');
$authorName = (string) ($condition['author']['name'] ?? 'PromptRN RN Team');
$authorCredentials = (string) ($condition['author']['credentials'] ?? 'Registered Nurse');
$authorExperience = (string) ($condition['author']['experience'] ?? '');

$totalPrompts = count($freePrompts) + count($paidPrompts);

$formatSituation = static function (string $value): string {
    if ($value === '') {
        return 'Condition Pack';
    }
    return ucwords(str_replace('-', ' ', $value));
};

$initials = static function (string $name): string {
    $parts = preg_split('/\s+/', trim($name));
    if (!is_array($parts) || $parts === []) {
        return 'RN';
    }
    $letters = '';
    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        $letters .= strtoupper(substr($part, 0, 1));
        if (strlen($letters) >= 2) {
            break;
        }
    }
    return $letters !== '' ? $letters : 'RN';
};

$lastUpdatedRaw = (string) ($condition['last_updated'] ?? '');
$lastUpdated = $lastUpdatedRaw;
$timestamp = strtotime($lastUpdatedRaw);
if ($timestamp !== false) {
    $lastUpdated = date('M j, Y', $timestamp);
}

$pageTitle = (string) ($condition['seo']['page_title'] ?? ($conditionName . ' | PromptRN'));
$metaDescription = (string) ($condition['seo']['meta_description'] ?? 'Nurse-written prompts for patients.');
$canonicalUrl = app_url('/prompts/' . $slug);
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section class="condition-top-shell">
    <div class="mock-container">
        <nav class="crumb-row" aria-label="Breadcrumb">
            <a href="/">Home</a>
            <span>/</span>
            <a href="/prompts">Conditions</a>
            <span>/</span>
            <span><?= app_h($conditionName); ?></span>
        </nav>

        <?php if ($missingSeoFields !== [] && app_is_development()): ?>
            <div class="alert">
                <strong>Missing SEO fields:</strong> <?= app_h(implode(', ', $missingSeoFields)); ?>
            </div>
        <?php endif; ?>

        <div class="condition-hero-outer">
            <div class="condition-hero-main-col">
                <div class="hero-pill">
                    <span class="material-symbols-outlined" aria-hidden="true">medical_services</span>
                    <?= app_h($formatSituation($situation)); ?>
                </div>
                <h1>AI Prompts to Help You Understand Your <em><?= app_h($conditionName); ?></em> Diagnosis</h1>
                <p class="hero-copy"><?= app_h($clinicalContext); ?></p>
                <div class="hero-stat-row left">
                    <div class="hero-stat">
                        <strong><?= app_h((string) $totalPrompts); ?></strong>
                        <span>Nurse-written prompts</span>
                    </div>
                    <div class="hero-stat">
                        <strong><?= app_h((string) count($freePrompts)); ?></strong>
                        <span>Free to try now</span>
                    </div>
                    <div class="hero-stat">
                        <strong>4.9</strong>
                        <span>From patients</span>
                    </div>
                </div>
                <p class="updated-note">Updated <?= app_h($lastUpdated); ?></p>
            </div>

            <aside class="rn-card">
                <div class="rn-head">
                    <span class="rn-initials"><?= app_h($initials($authorName)); ?></span>
                    <div>
                        <h3><?= app_h($authorName); ?></h3>
                        <p><?= app_h($authorCredentials); ?></p>
                    </div>
                </div>
                <p class="rn-quote">"I wrote these prompts because patients leave diagnosis visits overwhelmed and without the information they need."</p>
                <?php if ($authorExperience !== ''): ?>
                    <p class="rn-exp"><?= app_h($authorExperience); ?></p>
                <?php endif; ?>
                <p class="rn-verified">
                    <span class="material-symbols-outlined" aria-hidden="true">verified</span>
                    RN License Verified
                </p>
            </aside>
        </div>
    </div>
</section>

<section class="condition-main-shell">
    <div class="mock-container condition-main-grid">
        <div class="condition-main-col">
            <?php if ($patientFears !== []): ?>
                <article class="nurse-note">
                    <div class="nurse-note-kicker"><span class="material-symbols-outlined" aria-hidden="true">stethoscope</span> A Nurse's Note</div>
                    <p>The most important thing to know: this diagnosis is manageable when you get clear explanations and practical next steps. These prompts are built to give you those explanations in plain language and at your own pace.</p>
                    <ul>
                        <?php foreach (array_slice($patientFears, 0, 4) as $fear): ?>
                            <li><?= app_h((string) $fear); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </article>
            <?php endif; ?>

            <article class="prompt-block">
                <div class="section-kicker">Start Here - Free Prompts</div>
                <h2><?= app_h((string) count($freePrompts)); ?> Prompts to Try Right Now</h2>

                <?php foreach ($freePrompts as $index => $prompt): ?>
                    <?php
                    $promptTitle = (string) ($prompt['title'] ?? 'Untitled prompt');
                    $promptBody = (string) ($prompt['prompt'] ?? '');
                    $why = (string) ($prompt['why_it_works'] ?? '');
                    $position = $index + 1;
                    ?>
                    <div class="prompt-item" data-prompt-card>
                        <div class="prompt-item-index">Prompt <?= app_h(str_pad((string) $position, 2, '0', STR_PAD_LEFT)); ?> of <?= app_h((string) $totalPrompts); ?></div>
                        <h3><?= app_h($promptTitle); ?></h3>
                        <div class="prompt-text" data-prompt-wrap>
                            <button class="prompt-copy" type="button" data-copy-prompt>Copy</button>
                            <p data-prompt-body><?= nl2br(app_h($promptBody)); ?></p>
                        </div>
                        <?php if ($why !== ''): ?>
                            <div class="why-wrap">
                                <h4>Why this works</h4>
                                <p><?= app_h($why); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </article>

            <article class="prompt-block locked">
                <div class="section-kicker">Full Prompt Pack</div>
                <h2><?= app_h((string) count($paidPrompts)); ?> More Nurse-Written Prompts</h2>

                <?php if ($hasFullAccess): ?>
                    <?php foreach ($paidPrompts as $index => $prompt): ?>
                        <?php
                        $promptTitle = (string) ($prompt['title'] ?? 'Untitled prompt');
                        $promptBody = (string) ($prompt['prompt'] ?? '');
                        $why = (string) ($prompt['why_it_works'] ?? '');
                        $position = count($freePrompts) + $index + 1;
                        ?>
                        <div class="prompt-item" data-prompt-card>
                            <div class="prompt-item-index">Prompt <?= app_h(str_pad((string) $position, 2, '0', STR_PAD_LEFT)); ?> of <?= app_h((string) $totalPrompts); ?></div>
                            <h3><?= app_h($promptTitle); ?></h3>
                            <div class="prompt-text" data-prompt-wrap>
                                <button class="prompt-copy" type="button" data-copy-prompt>Copy</button>
                                <p data-prompt-body><?= nl2br(app_h($promptBody)); ?></p>
                            </div>
                            <?php if ($why !== ''): ?>
                                <div class="why-wrap">
                                    <h4>Why this works</h4>
                                    <p><?= app_h($why); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="locked-list-box">
                        <?php foreach ($paidPrompts as $index => $prompt): ?>
                            <div class="locked-row">
                                <p><span class="material-symbols-outlined" aria-hidden="true">lock</span><?= app_h((string) ($prompt['title'] ?? 'Locked prompt')); ?></p>
                                <span>Prompt <?= app_h(str_pad((string) (count($freePrompts) + $index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                            </div>
                        <?php endforeach; ?>
                        <div class="locked-overlay-box">
                            <p>Get this specific pack instantly, or unlock every condition on the site.</p>
                            <a class="btn-primary" href="/billing/checkout?plan=pack&amp;slug=<?= app_h($slug); ?>">Unlock All <?= app_h((string) $totalPrompts); ?> Prompts - $9</a>
                            <small>Or get everything for $17/month</small>
                        </div>
                    </div>
                <?php endif; ?>
            </article>

            <?php if ($faqs !== []): ?>
                <article class="faq-block">
                    <div class="section-kicker">Common Questions</div>
                    <h2>What Patients Ask Us</h2>
                    <div class="faq-list">
                        <?php foreach ($faqs as $index => $faq): ?>
                            <?php
                            $question = (string) ($faq['question'] ?? '');
                            $answer = (string) ($faq['answer'] ?? '');
                            ?>
                            <div class="faq-item<?= $index === 0 ? ' is-open' : ''; ?>" data-faq-item>
                                <button type="button" data-faq-question>
                                    <span><?= app_h($question); ?></span>
                                    <span class="material-symbols-outlined" aria-hidden="true">expand_more</span>
                                </button>
                                <div class="faq-answer">
                                    <p><?= app_h($answer); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endif; ?>

            <article class="disclaimer-box">
                <p><strong>Medical Disclaimer:</strong> The prompts on this page are educational tools designed to help you have better conversations with AI and healthcare professionals. They do not replace medical advice from licensed clinicians.</p>
            </article>
        </div>

        <aside class="condition-side-col">
            <article class="side-cta">
                <h3><?= $hasFullAccess ? 'You have this pack unlocked' : 'Get Every Condition Pack'; ?></h3>
                <?php if ($hasFullAccess): ?>
                    <p>You already have access to this full pack and can copy any prompt.</p>
                    <a class="btn-primary" href="/members/library">Go to Library</a>
                <?php else: ?>
                    <p><?= app_h((string) count($paidPrompts)); ?> more prompts in this pack plus full library access options.</p>
                    <a class="btn-primary" href="/billing/checkout?plan=monthly">Start for $17/month</a>
                <?php endif; ?>
            </article>

            <article class="side-card">
                <h3>What's Included</h3>
                <ul>
                    <li><span class="material-symbols-outlined" aria-hidden="true">check_circle</span><?= app_h((string) $totalPrompts); ?> prompts per condition</li>
                    <li><span class="material-symbols-outlined" aria-hidden="true">check_circle</span>Clinical context for each prompt</li>
                    <li><span class="material-symbols-outlined" aria-hidden="true">check_circle</span>Doctor appointment prep guides</li>
                    <li><span class="material-symbols-outlined" aria-hidden="true">check_circle</span>Written by a registered nurse</li>
                    <li><span class="material-symbols-outlined" aria-hidden="true">check_circle</span>Cancel anytime</li>
                </ul>
            </article>

            <?php if ($relatedSituations !== []): ?>
                <article class="side-card">
                    <h3>Related Situations</h3>
                    <div class="side-links">
                        <?php foreach ($relatedSituations as $relatedSituation): ?>
                            <a href="/prompts">
                                <?= app_h($formatSituation((string) $relatedSituation)); ?>
                                <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endif; ?>

            <?php if ($relatedConditions !== []): ?>
                <div class="mini-related-grid">
                    <?php foreach (array_slice($relatedConditions, 0, 2) as $relatedSlug): ?>
                        <?php
                        $candidate = (string) $relatedSlug;
                        if (!app_condition_exists($candidate)) {
                            continue;
                        }
                        ?>
                        <a href="/prompts/<?= app_h($candidate); ?>" class="mini-related-card">
                            <div><?= app_h(app_condition_name($candidate)); ?></div>
                            <small>View prompts</small>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </aside>
    </div>
</section>

<?php seo_render_condition_schemas($condition, $meta, $canonicalUrl); ?>
<?php require __DIR__ . '/../includes/footer.php'; ?>
