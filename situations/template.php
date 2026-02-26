<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$slug = strtolower(trim((string)($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$situation = app_read_json_file(app_data_path('situations/' . $slug . '.json'));

if ($slug === '' || $situation === []) {
    http_response_code(404);
    $pageTitle = 'Situation Not Found | PromptRN';
    $metaDescription = 'Requested situation content not found.';
    $canonicalUrl = app_url('/');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section class="max-w-[1100px] mx-auto px-6 py-12 md:py-16"><h1 class="font-serif text-3xl font-bold text-[var(--ink)] mb-4">Situation Not Found</h1><p class="text-[var(--ink-light)]">This situation hub is not available yet.</p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = (string)($situation['seo_title'] ?? ($situation['title'] ?? 'Situation') . ' | PromptRN');
$metaDescription = (string)($situation['seo_description'] ?? 'PromptRN situation hub.');
$canonicalUrl = app_url('/situations/' . $slug);
$robots = 'index, follow';

$meta = app_meta();
$conditionMeta = [];
foreach (($meta['conditions'] ?? []) as $metaCondition) {
    $metaSlug = (string)($metaCondition['slug'] ?? '');
    if ($metaSlug === '') {
        continue;
    }
    $conditionMeta[$metaSlug] = $metaCondition;
}

$relatedConditions = is_array($situation['related_conditions'] ?? null) ? $situation['related_conditions'] : [];
$title = (string)($situation['title'] ?? 'Situation');
$summary = (string)($situation['summary'] ?? '');

require __DIR__ . '/../includes/header.php';
?>
<div class="bg-[var(--warm-white)] border-b border-[var(--border)] pt-16 pb-20 px-6 relative overflow-hidden">
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/clean-gray-paper.png')] opacity-20 pointer-events-none">
    </div>
    <div class="max-w-[1100px] mx-auto relative z-10">
        <nav class="mb-8" aria-label="Breadcrumb">
            <ol class="flex items-center text-sm text-[var(--ink-muted)] font-medium">
                <li><a href="/" class="hover:text-[var(--ink)] transition-colors">Home</a></li>
                <li class="mx-2">/</li>
                <li><a href="/prompts" class="hover:text-[var(--ink)] transition-colors">Conditions</a></li>
                <li class="mx-2">/</li>
                <li class="text-[var(--ink-light)] truncate" aria-current="page">
                    <?= app_h($title); ?>
                </li>
            </ol>
        </nav>

        <div
            class="inline-flex flex-wrap items-center gap-2 bg-[var(--teal-light)] text-[var(--teal-dark)] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-6">
            <span class="material-symbols-outlined text-base">explore</span>
            Situation Hub
        </div>

        <h1
            class="text-4xl md:text-5xl lg:text-[3.5rem] font-serif font-bold leading-tight text-[var(--ink)] mb-6 tracking-tight max-w-4xl">
            <?= app_h($title); ?>
        </h1>

        <?php if ($summary !== ''): ?>
        <p
            class="text-xl md:text-2xl text-[var(--ink-light)] leading-relaxed max-w-3xl border-l-4 border-[var(--amber)] pl-6 py-2">
            <?= app_h($summary); ?>
        </p>
        <?php
endif; ?>
    </div>
</div>

<div class="max-w-[1100px] mx-auto px-6 py-16">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
        <div>
            <h2 class="font-serif text-2xl md:text-3xl font-bold text-[var(--ink)] mb-2">Conditions in this Collection
            </h2>
            <p class="text-[var(--ink-light)] text-base">Select a condition to access specific prompts related to
                <?= app_h(strtolower($title)); ?>.
            </p>
        </div>
        <div class="shrink-0">
            <span
                class="inline-block bg-[var(--cream)] border border-[var(--border)] text-[var(--ink-muted)] text-sm font-bold px-3 py-1.5 rounded-md">
                <?= app_h((string)count($relatedConditions)); ?> condition
                <?= count($relatedConditions) !== 1 ? 's' : ''; ?>
            </span>
        </div>
    </div>

    <?php if ($relatedConditions === []): ?>
    <div
        class="bg-white border-2 border-dashed border-[var(--border-strong)] rounded-2xl p-12 text-center text-[var(--ink-muted)]">
        <span class="material-symbols-outlined text-4xl mb-3 opacity-50">search_off</span>
        <h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-1">No conditions listed yet</h3>
        <p>We are still adding conditions to this situation hub. Check back later.</p>
    </div>
    <?php
else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($relatedConditions as $index => $conditionSlug): ?>
        <?php
        $conditionInfo = $conditionMeta[$conditionSlug] ?? null;
        $conditionName = $conditionInfo ? (string)($conditionInfo['condition_name'] ?? $conditionSlug) : app_h((string)$conditionSlug);
        $promptCount = $conditionInfo ? (int)($conditionInfo['prompt_count'] ?? 12) : 12;
        $lastUpdated = $conditionInfo ? (string)($conditionInfo['last_updated'] ?? '') : '';
?>
        <a href="/prompts/<?= app_h((string)$conditionSlug); ?>/<?= app_h($slug); ?>"
            class="group bg-white border border-[var(--border)] hover:border-[var(--amber)] rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 relative overflow-hidden flex flex-col h-full">
            <div
                class="absolute top-0 left-0 w-1 h-full bg-[var(--amber)] opacity-0 group-hover:opacity-100 transition-opacity">
            </div>

            <div class="flex-1">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 bg-[var(--cream)] rounded-full flex items-center justify-center text-[var(--amber)] group-hover:bg-[var(--amber-pale)] transition-colors">
                        <span class="material-symbols-outlined">folder_special</span>
                    </div>
                    <?php if ($lastUpdated): ?>
                    <span class="text-xs text-[var(--ink-muted)] bg-[var(--cream)] px-2 py-1 rounded">Updated
                        <?= app_h($lastUpdated); ?>
                    </span>
                    <?php
        endif; ?>
                </div>

                <h3
                    class="font-serif text-[19px] font-bold text-[var(--ink)] mb-3 leading-snug group-hover:text-[var(--amber)] transition-colors">
                    <?= app_h($conditionName); ?>
                </h3>
            </div>

            <div class="mt-4 pt-4 border-t border-[var(--border)] flex items-center justify-between">
                <span class="text-sm font-medium text-[var(--ink-light)] flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">list_alt</span>
                    <?= app_h((string)$promptCount); ?> prompts
                </span>
                <span
                    class="material-symbols-outlined text-[var(--ink-muted)] group-hover:text-[var(--amber)] group-hover:translate-x-1 transition-all">arrow_forward</span>
            </div>
        </a>
        <?php
    endforeach; ?>
    </div>
    <?php
endif; ?>

    <div
        class="mt-16 bg-[var(--cream)] border border-[var(--border-strong)] rounded-2xl p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 shadow-sm">
        <div
            class="w-20 h-20 rounded-full bg-[var(--amber-pale)] text-[var(--amber-dark)] flex items-center justify-center shrink-0 border-4 border-white shadow-md">
            <span class="material-symbols-outlined text-4xl">notification_important</span>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="font-serif text-2xl font-bold text-[var(--ink)] mb-2">Can't find your condition?</h3>
            <p class="text-[var(--ink-light)] text-[15px] leading-relaxed mb-0">
                Use our free <a href="/tools/prompt-generator"
                    class="text-[var(--amber-dark)] font-bold hover:underline">Prompt Generator</a> to create a custom
                prompt for your exact situation, or browse our full <a href="/prompts"
                    class="text-[var(--amber-dark)] font-bold hover:underline">Condition Library</a>.
            </p>
        </div>
        <div class="shrink-0 w-full md:w-auto">
            <a href="/tools/prompt-generator"
                class="btn-primary w-full md:w-auto bg-[var(--ink)] hover:bg-[var(--ink-light)] text-white focus:ring-[var(--ink)] border-0">Build
                Custom Prompt</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>