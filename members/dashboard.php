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
                        stroke-dasharray="150.8" stroke-dashoffset="<?=(string)(150.8 - (150.8 * ($progressPercent / 100)))?>"
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
                        <a href="/billing/checkout?plan=monthly"
                            class="inline-block bg-[var(--warm-white)] hover:bg-[var(--cream)] text-[var(--ink)] border border-[var(--border-strong)] px-6 py-2.5 rounded-lg text-sm font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2">
                            <?= $isSubscribed ? 'Manage Subscription' : 'View Plans'?>
                        </a>
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

        <!-- Right Column: Form (Profile Details) -->
        <div
            class="bg-white border border-[var(--border)] rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col h-fit">
            <div class="px-8 py-6 border-b border-[var(--border)] bg-[var(--cream)]">
                <h2 class="font-serif text-[17px] font-bold text-[var(--ink)] mb-1">Request Details</h2>
                <p class="text-[14px] text-[var(--ink-light)]">Customize your prompt generation strategy.</p>
            </div>

            <div class="p-8 flex-1 space-y-6">
                <!-- Inputs -->
                <div>
                    <label class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Your Full Name</label>
                    <input type="text" placeholder="e.g. Acme Corp"
                        class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-muted)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none transition-shadow shadow-sm bg-white">
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Your First Name</label>
                    <input type="text" placeholder="e.g. John"
                        class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-muted)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none transition-shadow shadow-sm bg-white">
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Ask For A Referral If Customer
                        Has Already Left A Review?</label>
                    <div class="relative">
                        <select
                            class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none appearance-none transition-shadow shadow-sm bg-white cursor-pointer">
                            <option>No</option>
                            <option>Yes</option>
                        </select>
                        <span
                            class="material-symbols-outlined text-[var(--ink-muted)] absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xl">expand_more</span>
                    </div>
                </div>

                <div>
                    <div class="mb-1.5 flex flex-col">
                        <label class="block text-[13px] font-bold text-[var(--ink)] mb-0.5">Alert Threshold</label>
                        <p class="text-[12px] text-[var(--ink-light)] leading-snug">We'll notify you if your requests
                            drop below this number every 2 weeks</p>
                    </div>
                    <div class="relative mt-2">
                        <select
                            class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none appearance-none transition-shadow shadow-sm bg-white cursor-pointer">
                            <option>1 request</option>
                            <option>5 requests</option>
                            <option>10 requests</option>
                        </select>
                        <span
                            class="material-symbols-outlined text-[var(--ink-muted)] absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xl">expand_more</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Total Review Attempts</label>
                    <div class="relative">
                        <select
                            class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none appearance-none transition-shadow shadow-sm bg-white cursor-pointer">
                            <option>1 reminder</option>
                            <option>2 reminders</option>
                            <option>3 reminders</option>
                        </select>
                        <span
                            class="material-symbols-outlined text-[var(--ink-muted)] absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xl">expand_more</span>
                    </div>
                </div>

                <div>
                    <label class="block text-[13px] font-bold text-[var(--ink)] mb-2">Personalized Image</label>
                    <div
                        class="border-[1.5px] border-dashed border-[var(--border-strong)] rounded-xl p-8 text-center bg-[var(--cream)]/50 hover:bg-[var(--cream)] hover:border-[var(--amber-light)] transition-all cursor-pointer group flex flex-col items-center justify-center min-h-[140px]">
                        <span
                            class="material-symbols-outlined text-3xl text-[var(--border-strong)] mb-3 group-hover:text-[var(--amber-light)] transition-colors">image</span>
                        <p class="text-[14px] font-medium text-[var(--amber)] mb-0.5"><span
                                class="hover:text-[var(--amber-dark)]">Upload a file</span> <span
                                class="text-[var(--ink-light)] font-normal">or drag and drop</span></p>
                        <p class="text-[12px] text-[var(--ink-muted)]">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>