<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

// Attempt to load the JSON file
$filePath = __DIR__ . '/../data/blog/' . $slug . '.json';
$post = null;

if (file_exists($filePath)) {
    $json = file_get_contents($filePath);
    if ($json) {
        $post = json_decode($json, true);
    }
}

// 404 handling if post isn't found
if (!$post || empty($post['slug'])) {
    http_response_code(404);
    $pageTitle = 'Blog Post Not Found | PromptRN';
    require __DIR__ . '/../includes/header.php';
    ?>
    <section class="py-32 px-6 text-center bg-[var(--cream)] min-h-[60vh] flex flex-col justify-center items-center">
        <div class="w-20 h-20 bg-[var(--warm-white)] rounded-full flex items-center justify-center mb-6 border border-[var(--border)] shadow-sm">
            <span class="material-symbols-outlined text-[var(--ink-muted)] text-4xl">search_off</span>
        </div>
        <h1 class="font-serif text-4xl font-bold text-[var(--ink)] mb-4">Post Not Found</h1>
        <p class="text-[var(--ink-light)] mb-8 text-lg">We couldn't find the article you're looking for.</p>
        <a href="/blog" class="btn-primary">Return to Blog</a>
    </section>
    <?php
    require __DIR__ . '/../includes/footer.php';
    exit;
}

// Post found, setup SEO & Meta
$pageTitle = $post['seo']['page_title'] ?? ($post['title'] ?? 'PromptRN Blog');
$metaDescription = $post['seo']['meta_description'] ?? '';
$canonicalUrl = app_url('/blog/' . $post['slug']);
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>

<article class="bg-[var(--cream)] pb-24">
    <!-- Article Hero -->
    <header class="pt-16 lg:pt-24 pb-12 px-6 border-b border-[var(--border)] bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1/2 h-full bg-[var(--teal-light)] opacity-20 transform skew-x-12 translate-x-32 -z-10"></div>
        <div class="max-w-4xl mx-auto">
            
            <a href="/blog" class="inline-flex items-center text-[var(--ink-muted)] hover:text-[var(--teal)] text-sm font-bold uppercase tracking-wider mb-8 transition-colors">
                <span class="material-symbols-outlined text-base mr-1">arrow_back</span>
                Back to Blog
            </a>

            <?php if(!empty($post['category'])): ?>
                <div class="mb-6">
                    <span class="bg-[var(--teal-light)] text-[var(--teal-dark)] text-xs font-bold uppercase tracking-wider px-3 py-1.5 rounded-full">
                        <?= app_h((string)$post['category']) ?>
                    </span>
                </div>
            <?php endif; ?>

            <h1 class="text-4xl lg:text-5xl xl:text-6xl font-serif font-bold leading-[1.1] text-[var(--ink)] mb-6 tracking-tight">
                <?= app_h((string)($post['seo']['h1'] ?? ($post['title'] ?? ''))) ?>
            </h1>
            
            <p class="text-xl text-[var(--ink-light)] leading-relaxed mb-10 max-w-3xl">
                <?= app_h((string)($post['seo']['meta_description'] ?? '')) ?>
            </p>

            <div class="flex items-center gap-4 py-6 border-y border-[var(--border)]">
                <?php if(!empty($post['author']['image'])): ?>
                    <img src="<?= app_h((string)$post['author']['image']) ?>" alt="<?= app_h((string)($post['author']['name'] ?? '')) ?>" class="w-14 h-14 rounded-full object-cover bg-[var(--cream)] border border-[var(--border)]" />
                <?php else: ?>
                    <div class="w-14 h-14 rounded-full bg-[var(--amber-pale)] flex items-center justify-center text-[var(--amber-dark)] font-serif font-bold text-xl">
                        <?= substr(app_h((string)($post['author']['name'] ?? 'P')), 0, 1) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="text-base font-bold text-[var(--ink)]">
                        <?= app_h((string)($post['author']['name'] ?? 'PromptRN Team')) ?>
                        <?php if(!empty($post['author']['role'])): ?>
                            <span class="text-[var(--ink-muted)] font-normal ml-1">&middot; <?= app_h((string)$post['author']['role']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="text-sm text-[var(--ink-muted)] flex items-center gap-2 mt-1">
                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                        <?php 
                            $dateStr = $post['published_date'] ?? ''; 
                            if($dateStr) {
                                echo date('F j, Y', strtotime($dateStr));
                            }
                        ?>
                        <?php if(!empty($post['estimated_reading_time'])): ?>
                            <span class="mx-1">&middot;</span>
                            <span class="material-symbols-outlined text-[14px]">schedule</span>
                            <?= app_h((string)$post['estimated_reading_time']) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Featured Image -->
    <?php if(!empty($post['featured_image'])): ?>
    <div class="max-w-6xl mx-auto px-6 -mt-8 relative z-10 mb-16">
        <div class="rounded-2xl overflow-hidden shadow-xl border border-[var(--border)] bg-[var(--warm-white)] h-[400px] md:h-[500px] lg:h-[600px]">
            <img src="<?= app_h((string)$post['featured_image']) ?>" alt="<?= app_h((string)($post['title'] ?? '')) ?> Featured Image" class="w-full h-full object-cover" />
        </div>
    </div>
    <?php else: ?>
    <div class="h-16"></div>
    <?php endif; ?>

    <!-- Main Content Layout -->
    <div class="max-w-6xl mx-auto px-6 flex flex-col lg:flex-row gap-16">
        
        <!-- Left Sidebar: Sharing (Sticky) -->
        <aside class="hidden lg:block w-48 shrink-0 relative">
            <div class="sticky top-32 flex flex-col gap-4">
                <span class="text-xs font-bold uppercase tracking-widest text-[var(--ink-muted)] mb-2">Share Article</span>
                <!-- Mock Share Buttons -->
                <button class="w-10 h-10 rounded-full bg-white border border-[var(--border)] hover:border-[var(--teal)] text-[var(--ink)] hover:text-[var(--teal)] flex items-center justify-center transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                </button>
                <button class="w-10 h-10 rounded-full bg-white border border-[var(--border)] hover:border-[#0a66c2] text-[var(--ink)] hover:text-[#0a66c2] flex items-center justify-center transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path></svg>
                </button>
                <button class="w-10 h-10 rounded-full bg-white border border-[var(--border)] hover:border-[var(--amber)] text-[var(--ink)] hover:text-[var(--amber)] flex items-center justify-center transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">mail</span>
                </button>
            </div>
        </aside>

        <!-- Article Body -->
        <div class="w-full lg:max-w-2xl xl:max-w-3xl pb-16">
            <div class="prose prose-lg prose-teal max-w-none text-[var(--ink)] prose-headings:font-serif prose-headings:text-[var(--ink)] prose-a:text-[var(--teal-dark)] prose-a:font-semibold">
                
                <?php if (!empty($post['content_blocks'])): ?>
                    <?php foreach ($post['content_blocks'] as $block): ?>
                        
                        <?php if ($block['type'] === 'paragraph'): ?>
                            <p class="leading-relaxed mb-6 text-lg text-[var(--ink)] opacity-90"><?= nl2br(app_h((string)$block['content'])) ?></p>
                        
                        <?php elseif ($block['type'] === 'heading'): ?>
                            <?php $tag = in_array($block['level'], ['h2','h3','h4']) ? $block['level'] : 'h2'; ?>
                            <<?= $tag ?> class="font-bold mt-12 mb-6 tracking-tight <?= $tag==='h2' ? 'text-3xl' : 'text-2xl' ?> border-b border-[var(--border)] pb-2"><?= app_h((string)$block['content']) ?></<?= $tag ?>>
                        
                        <?php elseif ($block['type'] === 'list'): ?>
                            <ul class="list-disc pl-6 mb-8 space-y-3 text-lg text-[var(--ink)] opacity-90 marker:text-[var(--teal)]">
                                <?php foreach ($block['items'] as $item): ?>
                                    <li><?= app_h((string)$item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        
                        <?php elseif ($block['type'] === 'cta'): ?>
                            <div class="my-10 bg-[var(--teal-light)] border border-[var(--teal)]/30 p-8 rounded-2xl shadow-sm text-center relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-1 bg-[var(--teal)]"></div>
                                <span class="material-symbols-outlined text-[var(--teal-dark)] text-4xl mb-3 block">assignment</span>
                                <h3 class="font-serif text-2xl font-bold text-[var(--ink)] mb-4 leading-tight">Take Action</h3>
                                <p class="text-[var(--ink)] text-lg mb-6 max-w-md mx-auto"><?= app_h((string)$block['text']) ?></p>
                                <a href="<?= app_h((string)$block['link']) ?>" class="btn-primary inline-block">Learn More</a>
                            </div>
                        
                        <?php endif; ?>

                    <?php endforeach; ?>
                <?php endif; ?>

            </div>

             <!-- Article FAQs -->
             <?php if (!empty($post['faq'])): ?>
                <div class="mt-16 pt-12 border-t border-[var(--border)]">
                    <h2 class="font-serif text-3xl font-bold text-[var(--ink)] mb-8">Frequently Asked Questions</h2>
                    <div class="space-y-6">
                        <?php foreach ($post['faq'] as $faq): ?>
                            <div class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm">
                                <h3 class="font-bold text-xl text-[var(--ink)] mb-3 flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[var(--amber-dark)] shrink-0 mt-0.5">help</span>
                                    <span><?= app_h((string)$faq['question']) ?></span>
                                </h3>
                                <p class="text-[var(--ink-light)] leading-relaxed pl-9">
                                    <?= nl2br(app_h((string)$faq['answer'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>

     <!-- Footer Lead Capture / Newsletter -->
     <div class="max-w-4xl mx-auto px-6 mt-12 mb-20">
        <div class="bg-[var(--ink)] rounded-3xl p-10 md:p-14 text-center relative overflow-hidden shadow-2xl">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10">
                <span class="material-symbols-outlined text-[var(--amber)] text-4xl mb-4 block">mail</span>
                <h3 class="font-serif text-3xl font-bold text-white mb-4">Patient-Centric Health Insights</h3>
                <p class="text-white/80 text-lg mb-8 max-w-xl mx-auto">
                    Subscribe to get actionable guides and specific clinical prompts delivered to your inbox every week.
                </p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto" action="#" onsubmit="event.preventDefault(); alert('ConvertKit Integration: Subscribed from post!');">
                    <input type="email" placeholder="Your email address" class="flex-1 bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white placeholder:text-white/50 focus:outline-none focus:border-[var(--teal)] focus:ring-1 focus:ring-[var(--teal)] transition-all" required>
                    <button type="submit" class="bg-[var(--teal)] hover:bg-[var(--teal-dark)] text-white font-bold py-3 px-6 rounded-lg transition-colors whitespace-nowrap">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</article>

<?php require __DIR__ . '/../includes/footer.php'; ?>
