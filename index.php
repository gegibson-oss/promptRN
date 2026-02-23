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
<section class="relative bg-[var(--warm-white)] border-b border-[var(--border)] pt-20 pb-24 px-6 overflow-hidden">
<div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/clean-gray-paper.png')] opacity-20 pointer-events-none"></div>
<div class="max-w-4xl mx-auto text-center relative z-10">
<div class="inline-flex items-center gap-2 bg-[var(--teal-light)] text-[var(--teal-dark)] px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider mb-8">
<span class="material-symbols-outlined text-base">verified</span>
                Verified by Registered Nurses
            </div>
<h1 class="text-5xl md:text-6xl lg:text-[4rem] font-serif font-bold leading-[1.1] text-[var(--ink)] mb-8 tracking-tight">
                Expert AI Prompts for your Health, <span class="italic text-[var(--amber)]">Written by Nurses.</span>
</h1>
<p class="text-xl md:text-2xl text-[var(--ink-light)] leading-relaxed max-w-2xl mx-auto mb-12">
                Bridge the gap between your doctor’s visit and your daily life with clinically-vetted prompts for ChatGPT.
            </p>
<div class="flex flex-col sm:flex-row items-center justify-center gap-4">
<a class="btn-primary w-full sm:w-auto" href="#categories">Browse 500+ Conditions</a>
<a class="btn-secondary w-full sm:w-auto" href="/tools/prompt-generator">See Free Tools</a>
</div>
<div class="mt-16 flex flex-wrap justify-center gap-8 md:gap-16 border-t border-[var(--border)] pt-8 max-w-3xl mx-auto">
<div class="flex flex-col items-center">
<span class="font-serif font-bold text-3xl text-[var(--ink)]">10k+</span>
<span class="text-sm text-[var(--ink-muted)] font-medium mt-1">Prompts Copied</span>
</div>
<div class="flex flex-col items-center">
<div class="flex items-center gap-1">
<span class="font-serif font-bold text-3xl text-[var(--ink)]">4.9</span>
<span class="material-symbols-outlined text-[var(--amber)] text-2xl fill-current">star</span>
</div>
<span class="text-sm text-[var(--ink-muted)] font-medium mt-1">Patient Rating</span>
</div>
<div class="flex flex-col items-center">
<span class="font-serif font-bold text-3xl text-[var(--ink)]">100%</span>
<span class="text-sm text-[var(--ink-muted)] font-medium mt-1">Verified RN Authors</span>
</div>
</div>
</div>
</section>
<section class="py-20 px-6 bg-[var(--cream)]">
<div class="max-w-7xl mx-auto">
<div class="text-center max-w-3xl mx-auto mb-16">
<h2 class="font-serif text-3xl md:text-4xl font-bold text-[var(--ink)] mb-6">
                    Most patients leave their appointments with more questions than answers.
                </h2>
<p class="text-lg text-[var(--ink-light)]">
                    Healthcare is complex. We simplify it so you can take control.
                </p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<div class="bg-[var(--warm-white)] p-8 rounded-2xl border border-[var(--border)] shadow-sm">
<div class="w-12 h-12 bg-[var(--teal-light)] rounded-full flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[var(--teal-dark)] text-2xl">medical_services</span>
</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-3">Clinically Vetted</h3>
<p class="text-[var(--ink-light)] leading-relaxed">
                        Every prompt is written and reviewed by registered nurses with clinical experience in that specific field.
                    </p>
</div>
<div class="bg-[var(--warm-white)] p-8 rounded-2xl border border-[var(--border)] shadow-sm">
<div class="w-12 h-12 bg-[var(--amber-pale)] rounded-full flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[var(--amber-dark)] text-2xl">translate</span>
</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-3">Plain English</h3>
<p class="text-[var(--ink-light)] leading-relaxed">
                        We translate complex medical jargon into clear, understandable language you can actually use.
                    </p>
</div>
<div class="bg-[var(--warm-white)] p-8 rounded-2xl border border-[var(--border)] shadow-sm">
<div class="w-12 h-12 bg-[var(--teal-light)] rounded-full flex items-center justify-center mb-6">
<span class="material-symbols-outlined text-[var(--teal-dark)] text-2xl">accessibility_new</span>
</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-3">Patient Centered</h3>
<p class="text-[var(--ink-light)] leading-relaxed">
                        Designed for your perspective, addressing the fears, doubts, and daily challenges doctors often miss.
                    </p>
</div>
</div>
</div>
</section>
<section class="py-20 px-6 bg-[var(--warm-white)] border-y border-[var(--border)]">
<div class="max-w-7xl mx-auto">
<div class="text-center mb-16">
<div class="text-[var(--amber)] text-xs font-bold uppercase tracking-widest mb-3">Simple Process</div>
<h2 class="font-serif text-3xl md:text-4xl font-bold text-[var(--ink)]">How It Works</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12 relative">
<div class="hidden md:block absolute top-12 left-[16%] right-[16%] h-0.5 bg-[var(--border)] -z-10"></div>
<div class="text-center relative">
<div class="w-24 h-24 bg-[var(--cream)] border-2 border-[var(--border)] rounded-full flex items-center justify-center text-3xl font-serif font-bold text-[var(--amber)] mx-auto mb-6 shadow-sm z-10">1</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-2">Pick your situation</h3>
<p class="text-[var(--ink-muted)]">Find the exact condition or challenge you are facing.</p>
</div>
<div class="text-center relative">
<div class="w-24 h-24 bg-[var(--cream)] border-2 border-[var(--border)] rounded-full flex items-center justify-center text-3xl font-serif font-bold text-[var(--amber)] mx-auto mb-6 shadow-sm z-10">2</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-2">Copy the prompt</h3>
<p class="text-[var(--ink-muted)]">One click to copy our nurse-engineered structure.</p>
</div>
<div class="text-center relative">
<div class="w-24 h-24 bg-[var(--cream)] border-2 border-[var(--border)] rounded-full flex items-center justify-center text-3xl font-serif font-bold text-[var(--amber)] mx-auto mb-6 shadow-sm z-10">3</div>
<h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-2">Get real answers</h3>
<p class="text-[var(--ink-muted)]">Paste into ChatGPT for clear, actionable guidance.</p>
</div>
</div>
</div>
</section>
<section class="py-24 px-6 bg-[var(--cream)]" id="categories">
<div class="max-w-7xl mx-auto">
<div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
<div>
<div class="text-[var(--ink-muted)] text-xs font-bold uppercase tracking-widest mb-3">Library</div>
<h2 class="font-serif text-3xl md:text-4xl font-bold text-[var(--ink)]">Featured Categories</h2>
</div>
<a class="text-[var(--amber-dark)] font-bold hover:underline flex items-center gap-1 group" href="/prompts">
                    View all categories 
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
<?php foreach ($featuredCategories as $category): ?>
    <?php $t = $category['tone']; ?>
    <a class="category-card bg-[var(--warm-white)] border border-[var(--border)] rounded-xl p-6 transition-all duration-300 group" href="<?= app_h((string) $category['link']); ?>">
        <div class="flex items-start justify-between mb-4">
            <div class="bg-[var(--<?= $t === 'amber' ? 'amber-pale' : 'teal-light'; ?>)] p-3 rounded-lg text-[var(--<?= $t === 'amber' ? 'amber-dark' : 'teal-dark'; ?>)]">
                <span class="material-symbols-outlined text-2xl"><?= app_h((string) $category['icon']); ?></span>
            </div>
            <span class="text-xs font-bold bg-[var(--cream)] text-[var(--ink-muted)] px-2 py-1 rounded border border-[var(--border)]"><?= app_h((string) $category['prompt_count']); ?></span>
        </div>
        <h3 class="font-serif text-xl font-bold text-[var(--ink)] mb-2 group-hover:text-[var(--amber)] transition-colors"><?= app_h((string) $category['title']); ?></h3>
        <p class="text-sm text-[var(--ink-light)] line-clamp-2"><?= app_h((string) $category['description']); ?></p>
    </a>
<?php endforeach; ?>
</div>
</div>
</section>
<section class="py-12 px-6 bg-[var(--cream)]">
<div class="max-w-7xl mx-auto">
<div class="bg-gradient-to-br from-[var(--ink)] to-[#2D2520] rounded-2xl p-8 md:p-16 text-center shadow-xl relative overflow-hidden">
<div class="absolute top-0 left-0 w-full h-full opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
<div class="relative z-10">
<h2 class="font-serif text-3xl md:text-5xl font-bold text-white mb-6">Unlock better health conversations.</h2>
<p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                        Get full access to every condition pack, nurse's notes, and priority updates for one simple price.
                    </p>
<div class="flex flex-col sm:flex-row items-center justify-center gap-4">
<a class="btn-primary border-white/10 shadow-lg" href="/auth/register">Get Full Access — $17/month</a>
<span class="text-white/40 text-sm font-medium">Cancel anytime</span>
</div>
</div>
</div>
</div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
