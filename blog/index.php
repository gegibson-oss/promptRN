<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'Expert Health Insights | PromptRN Blog';
$metaDescription = 'Long-form health literacy content, written by nurses. Bridge the gap between your diagnosis and daily life.';
$canonicalUrl = app_url('/blog');
$robots = 'index, follow';

// Load all blog posts from JSON
$posts = [];
$blogDir = __DIR__ . '/../data/blog/';
if (is_dir($blogDir)) {
    $files = glob($blogDir . '*.json');
    if ($files) {
        foreach ($files as $file) {
            $json = file_get_contents($file);
            if ($json) {
                $data = json_decode($json, true);
                if ($data && isset($data['slug'])) {
                    $posts[] = $data;
                }
            }
        }
    }
}

// Sort posts by date descending
usort($posts, function ($a, $b) {
    return strcmp($b['published_date'] ?? '', $a['published_date'] ?? '');
});

// Pick the first post as the featured hero (if any exist)
$featuredPost = !empty($posts) ? array_shift($posts) : null;

require __DIR__ . '/../includes/header.php';
?>

<section class="py-20 px-6 bg-[var(--cream)] border-b border-[var(--border)] overflow-hidden">
    <div class="max-w-4xl mx-auto text-center relative z-10 mb-16">
        <h1 class="text-5xl md:text-6xl font-serif font-bold leading-[1.1] text-[var(--ink)] mb-6 tracking-tight">
            The <span class="italic text-[var(--teal)]">PromptRN</span> Blog
        </h1>
        <p class="text-xl md:text-2xl text-[var(--ink-light)] leading-relaxed max-w-2xl mx-auto">
            Deep-dive clinical insights, actionable patient strategies, and the latest from our nursing team.
        </p>
    </div>

    <?php if ($featuredPost): ?>
    <div class="max-w-7xl mx-auto mb-24">
        <a href="/blog/<?= app_h($featuredPost['slug'])?>"
            class="group block relative rounded-3xl overflow-hidden shadow-2xl border border-[var(--border)] hover:border-[var(--teal)] transition-colors duration-500">
            <div class="flex flex-col lg:flex-row">
                <!-- Featured Image -->
                <div class="lg:w-3/5 h-[400px] lg:h-auto relative overflow-hidden bg-[var(--teal-light)]">
                    <?php if (!empty($featuredPost['featured_image'])): ?>
                    <img src="<?= app_h($featuredPost['featured_image'])?>"
                        alt="<?= app_h($featuredPost['seo']['h1'] ?? $featuredPost['title'])?>"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out" />
                    <?php
    else: ?>
                    <div class="w-full h-full flex items-center justify-center text-[var(--teal-dark)]/50">
                        <span class="material-symbols-outlined text-6xl">article</span>
                    </div>
                    <?php
    endif; ?>

                    <!-- Category Badge -->
                    <?php if (!empty($featuredPost['category'])): ?>
                    <div class="absolute top-6 left-6 z-10">
                        <span
                            class="bg-[var(--cream)] text-[var(--ink)] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                            <?= app_h($featuredPost['category'])?>
                        </span>
                    </div>
                    <?php
    endif; ?>
                </div>

                <!-- Featured Content -->
                <div class="lg:w-2/5 p-8 lg:p-12 bg-white flex flex-col justify-center relative z-10">
                    <div
                        class="text-[var(--teal-dark)] text-sm font-bold uppercase tracking-widest mb-4 flex items-center gap-2">
                        <span>Featured Post</span>
                        <span class="w-8 h-[1px] bg-[var(--teal-dark)] opacity-50"></span>
                    </div>
                    <h2
                        class="font-serif text-3xl lg:text-4xl font-bold text-[var(--ink)] mb-4 leading-tight group-hover:text-[var(--teal)] transition-colors">
                        <?= app_h($featuredPost['seo']['h1'] ?? ($featuredPost['title'] ?? ''))?>
                    </h2>
                    <p class="text-lg text-[var(--ink-light)] mb-8 line-clamp-3">
                        <?= app_h($featuredPost['seo']['meta_description'] ?? '')?>
                    </p>

                    <div class="mt-auto flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <?php if (!empty($featuredPost['author']['image'])): ?>
                            <img src="<?= app_h($featuredPost['author']['image'])?>"
                                alt="<?= app_h($featuredPost['author']['name'] ?? '')?>"
                                class="w-10 h-10 rounded-full object-cover bg-[var(--cream)] border border-[var(--border)]" />
                            <?php
    else: ?>
                            <div
                                class="w-10 h-10 rounded-full bg-[var(--amber-pale)] flex items-center justify-center text-[var(--amber-dark)] font-serif font-bold">
                                <?= substr(app_h($featuredPost['author']['name'] ?? 'P'), 0, 1)?>
                            </div>
                            <?php
    endif; ?>
                            <div>
                                <div class="text-sm font-bold text-[var(--ink)]">
                                    <?= app_h($featuredPost['author']['name'] ?? 'PromptRN Team')?>
                                </div>
                                <div class="text-xs text-[var(--ink-muted)]">
                                    <?php
    $dateStr = $featuredPost['published_date'] ?? '';
    if ($dateStr) {
        echo date('M j, Y', strtotime($dateStr));
    }
?>
                                    <?php if (!empty($featuredPost['estimated_reading_time'])): ?>
                                    &middot;
                                    <?= app_h($featuredPost['estimated_reading_time'])?>
                                    <?php
    endif; ?>
                                </div>
                            </div>
                        </div>
                        <div
                            class="w-10 h-10 rounded-full bg-[var(--warm-white)] flex items-center justify-center text-[var(--ink)] group-hover:bg-[var(--teal)] group-hover:text-white transition-colors duration-300">
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <?php
endif; ?>
</section>

<?php if (empty($posts) && !$featuredPost): ?>
<section class="py-20 px-6 text-center">
    <div class="max-w-2xl mx-auto">
        <div
            class="w-20 h-20 bg-[var(--cream)] rounded-full flex items-center justify-center mx-auto mb-6 border border-[var(--border)]">
            <span class="material-symbols-outlined text-[var(--ink-muted)] text-3xl">edit_document</span>
        </div>
        <h2 class="font-serif text-2xl font-bold text-[var(--ink)] mb-4">No published articles yet.</h2>
        <p class="text-[var(--ink-light)]">We're currently busy crafting high-quality, clinical pillar content. Check
            back soon for deep dives into navigating your healthcare.</p>
    </div>
</section>
<?php
endif; ?>

<?php if (!empty($posts)): ?>
<section class="py-20 px-6 bg-[var(--warm-white)]">
    <div class="max-w-7xl mx-auto">
        <h2
            class="font-serif text-3xl font-bold text-[var(--ink)] mb-12 border-b border-[var(--border)] pb-4 flex items-center justify-between">
            <span>Latest Articles</span>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($posts as $post): ?>
            <a href="/blog/<?= app_h($post['slug'])?>"
                class="group bg-white border border-[var(--border)] hover:border-[var(--teal-light)] rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full">

                <div class="h-48 relative overflow-hidden bg-[var(--amber-pale)] shrink-0">
                    <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?= app_h($post['featured_image'])?>"
                        alt="<?= app_h($post['seo']['h1'] ?? $post['title'])?>"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out" />
                    <?php
        else: ?>
                    <div class="w-full h-full flex items-center justify-center text-[var(--amber-dark)]/50">
                        <span class="material-symbols-outlined text-4xl">medical_information</span>
                    </div>
                    <?php
        endif; ?>
                </div>

                <div class="p-8 flex flex-col grow">
                    <?php if (!empty($post['category'])): ?>
                    <div class="text-[var(--teal)] text-xs font-bold uppercase tracking-wider mb-3">
                        <?= app_h($post['category'])?>
                    </div>
                    <?php
        endif; ?>

                    <h3
                        class="font-serif text-xl font-bold text-[var(--ink)] mb-3 group-hover:text-[var(--teal-dark)] transition-colors leading-snug">
                        <?= app_h($post['seo']['h1'] ?? ($post['title'] ?? ''))?>
                    </h3>

                    <p class="text-[var(--ink-muted)] text-sm mb-6 line-clamp-3">
                        <?= app_h($post['seo']['meta_description'] ?? '')?>
                    </p>

                    <div class="mt-auto pt-6 border-t border-[var(--border)] flex items-center justify-between">
                        <div class="text-xs text-[var(--ink-muted)] flex items-center gap-2">
                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                            <?php
        $dateStr = $post['published_date'] ?? '';
        if ($dateStr) {
            echo date('M j, Y', strtotime($dateStr));
        }
?>
                        </div>
                        <?php if (!empty($post['estimated_reading_time'])): ?>
                        <div class="text-xs text-[var(--ink-muted)] font-medium bg-[var(--cream)] px-2 py-1 rounded">
                            <?= app_h($post['estimated_reading_time'])?>
                        </div>
                        <?php
        endif; ?>
                    </div>
                </div>
            </a>
            <?php
    endforeach; ?>
        </div>
    </div>
</section>
<?php
endif; ?>

<!-- Inline Lead Capture -->
<section class="py-24 px-6 bg-[var(--ink)] -mx-6 mb-[-6rem]">
    <div class="max-w-3xl mx-auto text-center">
        <span class="material-symbols-outlined text-[var(--amber)] text-4xl mb-6 block">mail</span>
        <h2 class="font-serif text-3xl md:text-4xl font-bold text-white mb-4">Never Walk into the Clinic Unprepared</h2>
        <p class="text-white/80 text-lg mb-10 max-w-xl mx-auto">
            Join 5,000+ patients receiving our nurse-curated weekly insights on navigating complex diagnoses and
            communicating effectively with specialists.
        </p>

        <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" action="#"
            onsubmit="event.preventDefault(); alert('ConvertKit Integration: Subscribed!');">
            <input type="email" placeholder="Enter your email address"
                class="flex-1 bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder:text-white/50 focus:outline-none focus:border-[var(--teal)] focus:ring-1 focus:ring-[var(--teal)] transition-all"
                required>
            <button type="submit"
                class="bg-[var(--teal)] hover:bg-[var(--teal-dark)] text-white font-bold py-3 px-6 rounded-lg transition-colors whitespace-nowrap">
                Subscribe Free
            </button>
        </form>
        <p class="text-white/40 text-xs mt-4">We respect your privacy. Unsubscribe anytime.</p>
    </div>
</section>

<?php require __DIR__ . '/../includes/footer.php'; ?>