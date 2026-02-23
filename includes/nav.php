<?php
declare(strict_types=1);

$isLoggedIn = isset($_SESSION['user_email']);
?>
<header class="sticky top-0 z-50 bg-[var(--warm-white)] border-b border-[var(--border)]">
    <div class="max-w-7xl mx-auto px-6 h-[72px] flex items-center justify-between">
        <a class="font-serif font-bold text-2xl text-[var(--ink)] tracking-tight hover:opacity-80 transition-opacity"
            href="/">
            Prompt<span class="text-[var(--amber)]">RN</span>
        </a>
        <nav class="hidden md:flex items-center gap-8">
            <a class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors"
                href="/prompts">Browse Conditions</a>
            <a class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors"
                href="/tools/prompt-generator">Free Tools</a>
            <a class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors"
                href="/about">About</a>
            <?php if ($isLoggedIn): ?>
            <a href="/members/dashboard"
                class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors">Dashboard</a>
            <a href="/auth/logout"
                class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors">Logout</a>
            <?php
else: ?>
            <a href="/auth/login"
                class="text-sm font-medium text-[var(--ink-light)] hover:text-[var(--amber)] transition-colors">Login</a>
            <a class="bg-[var(--ink)] text-white text-sm font-bold px-5 py-2.5 rounded-lg hover:bg-[var(--amber)] transition-colors duration-200"
                href="/billing/checkout?plan=monthly">
                Get Full Access
            </a>
            <?php
endif; ?>
        </nav>
        <button class="md:hidden p-2 text-[var(--ink-light)]">
            <span class="material-symbols-outlined text-3xl">menu</span>
        </button>
    </div>
</header>