<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/csrf.php';

$generatedPrompt = null;
$condition = '';
$goal = '';
$whoFor = 'Myself';
$email = '';
$error = null;

csrf_start_session_if_needed();

if (app_request_is_post()) {
    $condition = trim((string)($_POST['condition'] ?? ''));
    $goal = trim((string)($_POST['goal'] ?? ''));
    $whoFor = trim((string)($_POST['who_for'] ?? 'Myself'));
    $email = trim((string)($_POST['email'] ?? ''));

    if (!csrf_validate($_POST['csrf_token'] ?? null)) {
        $error = 'Security token invalid. Please refresh and try again.';
    }
    elseif ($condition !== '' && $goal !== '' && $email !== '') {
        require_once __DIR__ . '/../includes/convertkit.php';
        convertkit_subscribe_email($email);

        $target = $whoFor === 'Myself' ? 'I was' : ($whoFor === 'My Child' ? 'My child was' : ($whoFor === 'My Parent' ? 'My parent was' : 'Someone I care for was'));
        $targetSelf = $whoFor === 'Myself' ? 'me' : 'them';

        $generatedPrompt = "{$target} recently diagnosed with {$condition}. Help me understand {$goal} in simple language, list the most important questions to ask the doctor next, and highlight urgent warning signs that require medical attention.";
    }
    else {
        $error = 'All fields including email are required.';
    }
}

$pageTitle = 'Free Health Prompt Generator | PromptRN';
$metaDescription = 'Generate a starter health prompt to use with ChatGPT or Claude.';
$canonicalUrl = app_url('/tools/prompt-generator');
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<div class="max-w-[1100px] mx-auto px-6 py-12 md:py-16">
    <!-- Header Row -->
    <div class="mb-10">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-[var(--ink)] tracking-tight leading-tight mb-2">
            Free Prompt Generator
        </h1>
        <p class="text-[var(--ink-light)] text-base max-w-2xl">Create a customized, structured prompt for ChatGPT or
            Claude to get clear, medically-accurate answers about your condition.</p>
    </div>

    <!-- Error Message -->
    <?php if ($error !== null): ?>
    <div class="bg-red-50 text-red-800 border border-red-200 rounded-xl p-4 mb-8 text-sm font-medium">
        <?= app_h($error); ?>
    </div>
    <?php
endif; ?>

    <!-- Two Columns Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-[1fr_minmax(380px,460px)] gap-8">

        <!-- Left Column: Marketing / Value Prop -->
        <div class="space-y-5">
            <div
                class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm relative opacity-95 transition-all hover:opacity-100 hover:border-[var(--amber)]">
                <div
                    class="absolute top-6 right-6 flex items-center gap-2 text-[var(--teal-dark)] text-sm font-bold uppercase tracking-wider bg-[var(--teal-light)] px-3 py-1 rounded">
                    <span class="w-1.5 h-1.5 rounded-full bg-[var(--teal-dark)]"></span>
                    Evidence Based
                </div>
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--teal-light)] text-[var(--teal-dark)] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-xl">verified</span>
                    </div>
                    <div class="pr-36">
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Stop Googling Your Symptoms</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed">Generic AI prompts give generic,
                            often scary advice. Our generator structures your request to get <strong>focused, practical
                                insights</strong> designed by registered nurses.</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-white border border-[var(--border)] rounded-xl p-6 shadow-sm relative opacity-95 transition-all hover:opacity-100 hover:border-[var(--amber)]">
                <div class="flex items-start gap-5">
                    <div
                        class="w-10 h-10 rounded-full bg-[var(--cream)] border border-[var(--border-strong)] text-[var(--ink-light)] flex items-center justify-center shrink-0 mt-0.5">
                        <span class="material-symbols-outlined text-xl">lock</span>
                    </div>
                    <div>
                        <h3 class="font-serif font-bold text-[var(--ink)] text-lg mb-2">Privacy First</h3>
                        <p class="text-[var(--ink-light)] text-sm leading-relaxed">We do not store your generated
                            prompts or share your health information. Use your generated prompt securely in your own AI
                            tool once it is revealed.</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-[var(--amber-pale)] border border-[var(--amber-light)] rounded-xl p-5 flex items-center gap-4 mt-8 transition-transform hover:-translate-y-0.5 shadow-sm">
                <div
                    class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-[var(--amber)] shrink-0 shadow-sm border border-[var(--amber-light)]">
                    <span class="material-symbols-outlined text-xl">auto_awesome</span>
                </div>
                <p class="text-sm text-[var(--ink-dark)]">Need more depth? Check out our <a href="/prompts"
                        class="text-[var(--amber-dark)] font-bold hover:underline">premium condition packs</a> for 100+
                    expert-written prompts per diagnosis.</p>
            </div>
        </div>

        <!-- Right Column: Form or Result -->
        <div
            class="bg-white border border-[var(--border)] rounded-xl shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col h-fit">
            <?php if ($generatedPrompt !== null): ?>
            <!-- Result State -->
            <div class="px-8 py-6 border-b border-[var(--border)] bg-[var(--teal-light)] relative overflow-hidden">
                <div class="absolute -right-4 -top-8 text-[var(--teal)] opacity-10">
                    <span class="material-symbols-outlined" style="font-size: 140px;">check_circle</span>
                </div>
                <h2 class="font-serif text-[17px] font-bold text-[var(--teal-dark)] mb-1 relative z-10">Your Custom
                    Prompt</h2>
                <p class="text-[14px] text-[var(--teal-dark)] opacity-90 relative z-10">Ready to copy and paste.</p>
            </div>
            <div class="p-8">
                <div
                    class="bg-[var(--cream)] border border-[var(--border)] rounded-lg p-5 mb-6 shadow-inner relative group">
                    <div
                        class="absolute -top-3 -left-2 bg-[var(--amber)] text-white text-[10px] font-bold px-2 py-0.5 rounded shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                        PROMPT</div>
                    <p id="prompt-text"
                        class="text-[var(--ink)] text-[15px] leading-relaxed whitespace-pre-wrap font-medium">
                        <?= app_h($generatedPrompt); ?>
                    </p>
                </div>
                <button id="copy-btn"
                    class="w-full bg-[var(--ink)] hover:bg-[var(--ink-light)] text-white px-6 py-3.5 rounded-lg text-[15px] font-bold transition-all shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--ink)] flex items-center justify-center gap-2 hover:-translate-y-0.5">
                    <span class="material-symbols-outlined text-[18px]">content_copy</span>
                    <span id="copy-text">Copy to Clipboard</span>
                </button>
                <a href="/tools/prompt-generator"
                    class="block w-full text-center mt-5 text-[14px] font-bold text-[var(--ink-muted)] hover:text-[var(--amber-dark)] transition-colors inline-flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">refresh</span>
                    Generate Another
                </a>
            </div>
            <script>
                document.getElementById('copy-btn').addEventListener('click', function () {
                    const text = document.getElementById('prompt-text').innerText;
                    navigator.clipboard.writeText(text).then(function () {
                        const btn = document.getElementById('copy-btn');
                        const btnText = document.getElementById('copy-text');
                        const originalText = btnText.innerText;

                        btn.classList.remove('bg-[var(--ink)]', 'hover:bg-[var(--ink-light)]');
                        btn.classList.add('bg-[var(--teal)]', 'hover:bg-[var(--teal-dark)]');
                        btnText.innerText = 'Copied Successfully!';
                        setTimeout(() => {
                            btn.classList.add('bg-[var(--ink)]', 'hover:bg-[var(--ink-light)]');
                            btn.classList.remove('bg-[var(--teal)]', 'hover:bg-[var(--teal-dark)]');
                            btnText.innerText = originalText;
                        }, 2500);
                    });
                });
            </script>
            <?php
else: ?>
            <!-- Form State -->
            <div class="px-8 py-6 border-b border-[var(--border)] bg-[var(--cream)] flex justify-between items-center">
                <div>
                    <h2 class="font-serif text-[17px] font-bold text-[var(--ink)] mb-1">Build Your Prompt</h2>
                    <p class="text-[14px] text-[var(--ink-light)]">Tell us what you need help with.</p>
                </div>
                <!-- Step Indicator -->
                <div class="flex items-center gap-1.5 shrink-0">
                    <div id="step-dot-1" class="w-2.5 h-2.5 rounded-full bg-[var(--amber)]"></div>
                    <div id="step-dot-2" class="w-2.5 h-2.5 rounded-full bg-[var(--border-strong)]"></div>
                </div>
            </div>

            <form id="generator-form" method="post" action="/tools/prompt-generator" class="flex-1 flex flex-col">
                <input type="hidden" name="csrf_token" value="<?= app_h(csrf_token()); ?>">

                <!-- Step 1: Details -->
                <div id="step-1" class="p-8 space-y-6">
                    <div>
                        <label for="condition" class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Diagnosis /
                            Condition</label>
                        <input id="condition" name="condition" type="text" required placeholder="e.g. Type 2 Diabetes"
                            value="<?= app_h($condition); ?>"
                            class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-muted)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none transition-shadow shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="goal" class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">What is your main
                            goal today?</label>
                        <input id="goal" name="goal" type="text" required
                            placeholder="e.g. Understanding new medications" value="<?= app_h($goal); ?>"
                            class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] placeholder:text-[var(--ink-muted)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none transition-shadow shadow-sm bg-white">
                    </div>

                    <div>
                        <label for="who_for" class="block text-[13px] font-bold text-[var(--ink)] mb-1.5">Who is this
                            for?</label>
                        <div class="relative">
                            <select id="who_for" name="who_for"
                                class="w-full border border-[var(--border-strong)] rounded-lg px-3.5 py-2.5 text-[14px] text-[var(--ink)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none appearance-none transition-shadow shadow-sm bg-white cursor-pointer font-medium">
                                <option value="Myself" <?=$whoFor==='Myself' ? 'selected' : ''?>>Myself</option>
                                <option value="My Child" <?=$whoFor==='My Child' ? 'selected' : ''?>>My Child</option>
                                <option value="My Parent" <?=$whoFor==='My Parent' ? 'selected' : ''?>>My Parent
                                </option>
                                <option value="Other Family Member" <?=$whoFor==='Other Family Member' ? 'selected' : ''
                                   ?>>Other Family Member</option>
                            </select>
                            <span
                                class="material-symbols-outlined text-[var(--ink-muted)] absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-xl">expand_more</span>
                        </div>
                    </div>

                    <button type="button" id="next-btn"
                        class="w-full bg-[var(--amber)] hover:bg-[var(--amber-dark)] text-white px-6 py-3.5 rounded-lg text-[15px] font-bold transition-all shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2 flex items-center justify-center gap-2 mt-2 hover:-translate-y-0.5">
                        <span>Next Step</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                </div>

                <!-- Step 2: Email -->
                <div id="step-2"
                    class="p-8 space-y-6 hidden flex-col justify-center min-h-[360px] animate-[fadeIn_0.3s_ease-out]">
                    <div class="text-center mb-2">
                        <div
                            class="w-14 h-14 bg-[var(--amber-pale)] border border-[var(--amber-light)] rounded-full flex items-center justify-center mx-auto mb-4 text-[var(--amber-dark)] shadow-sm">
                            <span class="material-symbols-outlined text-2xl">mail</span>
                        </div>
                        <h3 class="font-serif font-bold text-[var(--ink)] text-xl mb-2">Where should we send tips?</h3>
                        <p class="text-[14px] text-[var(--ink-light)] leading-relaxed">Enter your email to unlock your
                            prompt and receive occasional expert healthcare navigation tips.</p>
                    </div>

                    <div>
                        <label for="email" class="sr-only">Email Address</label>
                        <input id="email" name="email" type="email" placeholder="you@example.com"
                            value="<?= app_h($email); ?>"
                            class="w-full font-medium text-center border-2 border-[var(--border-strong)] rounded-lg px-4 py-3 text-[15px] text-[var(--ink)] placeholder:text-[var(--ink-muted)] focus:ring-2 focus:ring-[var(--amber)] focus:border-[var(--amber)] focus:outline-none transition-shadow shadow-sm bg-white">
                    </div>

                    <div class="flex gap-3">
                        <button type="button" id="back-btn"
                            class="w-1/3 bg-[var(--warm-white)] hover:bg-[var(--cream)] text-[var(--ink)] border border-[var(--border-strong)] px-4 py-3.5 rounded-lg text-[15px] font-bold transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[var(--amber)] flex items-center justify-center">
                            Back
                        </button>
                        <button type="submit"
                            class="w-2/3 bg-[var(--amber)] hover:bg-[var(--amber-dark)] text-white px-6 py-3.5 rounded-lg text-[15px] font-bold transition-all hover:-translate-y-0.5 shadow-sm hover:shadow focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:ring-offset-2 flex items-center justify-center gap-2">
                            <span>Reveal</span>
                            <span class="material-symbols-outlined text-[18px]">lock_open</span>
                        </button>
                    </div>
                    <p class="text-[11px] text-center text-[var(--ink-muted)] uppercase tracking-wider font-bold mt-2 hover:text-[var(--ink-light)] transition-colors cursor-pointer"
                        title="We will never sell your personal information.">No Spam. Unsubscribe Anytime.</p>
                </div>
            </form>

            <script>
                document.addEvenntentLoaded', () => {
                const stepe                                  const stepe                              const dot1 = document.getElementById('st                        const dot2 = document.getElementById('ste                        onst nextBtn = document.getElementById('next                          backBtn = document.getElementById('back - bt                             ionInput = document.getElementById('cond                                nput = document.getElementById('goal'                                    cument.getElementById('email');

                                        ick', () => {
                                         tion t                           if (!conditionInp                                               tionInput.r                                         re                                                if (!goalInput                                        goalInput.reportVa                                   n;
                                                     to Step 2
                                                                                                                              mo                                           ot--                                                            st                         var(--border -                                   )                          //                         ed only on                                            block
                                                     out to allo                                          setTimeout(() => em                                   ckBtn.addEventListener('click', () => {
                                 ail requ                                   emailInpu                                     // Transition to Step 1
                    step1.styl                                             te dots
                                                      )]');
                                                  er - strong)]');
                o[var(--a                                              Prevent form submit on                                                             keydown', function                                              Enter') tDefault(nextBtn.c                                });
                });
            </script>
            <style>
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            </style>
            <?php
endif; ?>
        </div>
    </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>