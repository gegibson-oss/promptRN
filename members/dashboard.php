<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/auth-check.php';

$user = auth_require_login();

$pageTitle = 'Member Dashboard | PromptRN';
$metaDescription = 'Your PromptRN account dashboard.';
$canonicalUrl = app_url('/members/dashboard');
$robots = 'noindex, nofollow';

require __DIR__ . '/../includes/header.php';

// Determine subscription status flags
$subStatus = $user['subscription_status'] ?? 'free';
$isSubscribed = in_array($subStatus, ['active', 'trialing'], true);
$purchasedPacks = $user['purchased_packs'] ?? [];
$hasPurchased = !empty($purchasedPacks);

// Progress calculation for UI rendering
$stepsTotal = 3;
$stepsCompleted = 1; // Base: Account created
if ($hasPurchased || $isSubscribed) {
    $stepsCompleted++;
}
$progressPercent = round(($stepsCompleted / $stepsTotal) * 100);
?>
<div class="max-w-[1100px] mx-auto px-6 py-12 md:py-16">
    <!-- Header Row -->
    <div class="flex flex-col md:flex-row md:items-start justify-between mb-10 gap-6">
        <div>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-[var(--ink)] tracking-tight leading-tight mb-2">
                Member Dashboard</h1>
            <p class="text-[var(--ink-light)] text-base">Manage your account, library access, and preferences.</p>
        </div>

        <!-- Progress Widget -->
        <div class="bg-white rounded-xl border border-[var(--border)] shadow-sm px-6 py-4 flex items-center shrink-0">
            <div class="relative w-14 h-14 flex items-center justify-center mr-5">
                <svg class="transform -rotate-90 w-14 h-14">
                    <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="4" fill="transparent"
                        class="text-[var(--border)]" />
                    <!-- stroke-dasharray roughly 2*PI*radius. 2 * 3.14159 * 24 = 150.8 -->
                    <circle cx="28" cy="28" r="24" stroke="currentColor" stroke-width="4" fill="transparent"
                        stroke-dasharray="150.8"
                        stroke-dashoffset="<?=(string)(150.8 - (150.8 * ($progressPercent / 100)))?>"
                        class="text-[var(--amber)] transition-all duration-1000 ease-out" />
                </svg>
                <span class="absolute text-xs font-bold text-[var(--amber-dark)]">
                    <?=(string)$progressPercent?>%
                </span>
            </div>
            <div>
                <p class="font-bold text-base text-[var(--ink)] leading-tight mb-1">
                    <?=(string)$stepsCompleted?> of
                    <?=(string)$stepsTotal?>
                </p>
                <p class="text-sm text-[var(--ink-muted)] leading-tight">Steps complete</p>
            </div>
        </div>
    </div>

    <!-- Two Columns Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_minmax(380px,460px)] gap-8">

        <!-- Left Column: Setup/Status Cards -->
        <div class="space-y-5">

            <!-- Card 1: Account (Completed) -->
            <div
                class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm relative opacity-90 transition-all hover:opacity-100 hover:border-[var(--amber)]">
                <div
                    class="absolute top-6 right-6 flex items-center gap-2 text-[var(--teal-dark)] text-sm font-bold uppercase tracking-wider bg-[var(--teal-light)] px-3 py-1 rounded">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--teal-dark)]"></span>
                    Connected
                </div>
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--teal-light)] text-[var(--teal-dark)] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-xl">check</span>
                    </div>
                    <div class="pr-28">
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Account Created</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed mb-5">Signed in as <strong>
                                <?= app_h((string)($user['email'] ?? '')); ?>
                            </strong>.</p>
                        <button
                            class="bg-[var(--warm-white)] hover:bg-[var(--cream)] text-[var(--ink)] border border-[var(--border-strong)] px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                            Update Password
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card 2: Library/Packs (Active or Completed depending on purchases) -->
            <?php if ($hasPurchased || $isSubscribed): ?>
            <div
                class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm relative opacity-90 transition-all hover:opacity-100 hover:border-[var(--amber)]">
                <div
                    class="absolute top-6 right-6 flex items-center gap-2 text-[var(--teal-dark)] text-sm font-bold uppercase tracking-wider bg-[var(--teal-light)] px-3 py-1 rounded">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--teal-dark)]"></span>
                    Active
                </div>
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--teal-light)] text-[var(--teal-dark)] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-xl">auto_awesome</span>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Prompt Library</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed mb-5">You have access to <strong>
                                <?= $isSubscribed ? 'All' : count($purchasedPacks)?>
                            </strong> condition pack(s).</p>
                        <a href="/members/library"
                            class="inline-block bg-[var(--amber)] hover:bg-[var(--amber-dark)] text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                            Go to Library
                        </a>
                    </div>
                </div>
            </div>
            <?php
else: ?>
            <div class="bg-white border-2 border-[var(--amber)] rounded-xl p-6 shadow-sm transition-all">
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--amber)] text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-sm mt-0.5">
                        2
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Find Your Condition</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed mb-5">Browse the library to find
                            nurse-authored prompts for your specific diagnosis.</p>
                        <a href="/prompts"
                            class="inline-block bg-[var(--amber)] hover:bg-[var(--amber-dark)] text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                            Browse Library
                        </a>
                    </div>
                </div>
            </div>
            <?php
endif; ?>

            <!-- Card 3: Subscription (Optional or Active) -->
            <div
                class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm relative opacity-90 transition-all hover:opacity-100 hover:border-[var(--amber)]">
                <?php if ($isSubscribed): ?>
                <div
                    class="absolute top-6 right-6 flex items-center gap-2 text-[var(--teal-dark)] text-sm font-bold uppercase tracking-wider bg-[var(--teal-light)] px-3 py-1 rounded">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--teal-dark)]"></span>
                    Subscribed
                </div>
                <?php
else: ?>
                <div
                    class="absolute top-6 right-6 px-3 py-1 bg-[var(--cream)] border border-[var(--border)] text-[var(--ink-muted)] text-xs font-bold uppercase tracking-wider rounded">
                    Optional
                </div>
                <?php
endif; ?>
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--cream)] border border-[var(--border-strong)] text-[var(--ink-light)] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-xl">star</span>
                    </div>
                    <div class="pr-24">
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Full Access Subscription</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed mb-5">Get unlimited access to all
                            expert-written condition packs and updates.</p>

                        <?php if ($isSubscribed): ?>
                        <a href="/members/account"
                            class="inline-block bg-[var(--warm-white)] hover:bg-[var(--cream)] text-[var(--ink)] border border-[var(--border-strong)] px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                            Manage Subscription
                        </a>
                        <?php
else: ?>
                        <div class="flex flex-wrap gap-3">
                            <a href="/billing/checkout?plan=monthly"
                                class="inline-block bg-[var(--warm-white)] hover:bg-[var(--cream)] text-[var(--ink)] border border-[var(--border-strong)] px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                                Monthly ($17/mo)
                            </a>
                            <a href="/billing/checkout?plan=annual"
                                class="inline-block bg-[var(--ink)] hover:bg-[var(--ink-light)] text-white px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--ink)] focus:ring-offset-2 relative">
                                Annual ($99/yr)
                                <span
                                    class="absolute -top-2.5 -right-2.5 bg-[var(--teal)] text-white text-[10px] uppercase tracking-wider font-bold px-1.5 py-0.5 rounded shadow-sm">Save
                                    50%</span>
                            </a>
                        </div>
                        <?php
endif; ?>
                    </div>
                </div>
            </div>

            <!-- Help link block -->
            <div
                class="bg-[var(--amber-pale)] border border-[var(--amber-light)] rounded-xl p-5 flex items-center gap-4 mt-8">
                <div
                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[var(--amber)] shrink-0 shadow-sm border border-[var(--amber-light)]">
                    <span class="material-symbols-outlined text-xl">help</span>
                </div>
                <p class="text-sm text-[var(--ink-dark)]">Need help? Check out our <a href="/tools/prompt-generator"
                        class="text-[var(--amber-dark)] font-bold hover:underline">free tools</a> or <a href="/about"
                        class="text-[var(--amber-dark)] font-bold hover:underline">contact support</a>.</p>
            </div>

        </div>

        <!-- Right Column: Prep Kit Upsell/Access -->
        <div
            class="bg-gradient-to-br from-[#1E293B] to-[var(--ink)] border border-[var(--border)] rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col h-fit relative">
            <div
                class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none">
            </div>

            <div class="px-8 py-6 border-b border-white/10 relative z-10 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-[19px] font-bold text-white mb-1">Doctor Appointment Prep Kit</h2>
                    <p class="text-[13px] text-white/70">Premium toolset for better clinical visits.</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center text-white shrink-0">
                    <span class="material-symbols-outlined">medical_information</span>
                </div>
            </div>

            <div class="p-8 flex-1 space-y-6 relative z-10 bg-white/5">
                <?php if (!empty($user['has_prep_kit'])): ?>
                <!-- State 2: Purchased Access -->
                <div class="bg-white/10 rounded-lg p-5 border border-white/20">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-[var(--teal)] text-2xl">check_circle</span>
                        <span class="text-white font-bold tracking-wide">Access Unlocked</span>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed mb-6">You have full access to our premium prep kit.
                        Download your printable agendas or use the interactive symptom tracker below.</p>

                    <div class="space-y-3">
                        <a href="#"
                            class="flex items-center justify-between bg-white text-[var(--ink)] hover:bg-[var(--cream)] px-4 py-3 rounded-lg font-bold text-sm transition-colors shadow-sm"
                            onclick="event.preventDefault(); alert('Downloading PDF...');">
                            <span class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-[18px]">picture_as_pdf</span> Printable
                                Agendas</span>
                            <span class="material-symbols-outlined text-[18px]">download</span>
                        </a>
                        <a href="#"
                            class="flex items-center justify-between bg-[var(--teal)] text-white hover:bg-[var(--teal-dark)] px-4 py-3 rounded-lg font-bold text-sm transition-colors shadow-sm"
                            onclick="event.preventDefault(); alert('Opening module...');">
                            <span class="flex items-center gap-2"><span
                                    class="material-symbols-outlined text-[18px]">fact_check</span> Interactive
                                Tracker</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <?php
else: ?>
                <!-- State 1: Upsell -->
                <p class="text-white/90 text-[15px] leading-relaxed">Most patients leave their appointments wishing they
                    had asked different questions. Don't let your next 15-minute appointment go to waste.</p>

                <ul class="space-y-3 mt-4">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[var(--amber)] text-xl shrink-0 mt-0.5">check</span>
                        <span class="text-white/80 text-sm">Downloadable symptom trackers and medication logs</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[var(--amber)] text-xl shrink-0 mt-0.5">check</span>
                        <span class="text-white/80 text-sm">Printable "Red Flag" question frameworks</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-[var(--amber)] text-xl shrink-0 mt-0.5">check</span>
                        <span class="text-white/80 text-sm">Negotiating effectively with insurance/specialists</span>
                    </li>
                </ul>

                <div class="pt-6 border-t border-white/10 mt-6">
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-3xl font-serif font-bold text-white">$29</span>
                        <span class="text-white/60 text-sm pb-1 mr-1">one-time</span>
                        <span
                            class="bg-[var(--amber)] text-[var(--ink)] text-[10px] uppercase font-bold px-2 py-0.5 rounded tracking-wider shadow-sm mb-1.5">Best
                            Value</span>
                    </div>
                    <a href="/billing/checkout?plan=prep_kit"
                        class="block w-full text-center bg-[var(--amber)] hover:bg-[var(--amber-dark)] text-[var(--ink)] px-6 py-3 rounded-lg font-bold transition-colors shadow-lg">
                        Upgrade & Unlock Kit
                    </a>
                </div>
                <?php
endif; ?>
            </div>
        </div>

    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>