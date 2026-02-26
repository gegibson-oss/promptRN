<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'How to Use AI to Navigate Your Health | PromptRN Course';
$metaDescription = 'Master the art of using AI tools like ChatGPT to understand lab results, interpret doctor notes, and advocate for your health in our expert-led video course.';
$canonicalUrl = app_url('/course');
$robots = 'index, follow';

$ogImage = '/assets/img/course-hero.png';
$extraHeadTags = '<meta property="og:image" content="' . app_h(app_url($ogImage)) . '">' . "\n" .
    '<meta name="twitter:card" content="summary_large_image">';

require __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-[var(--cream)] pt-20 pb-24 md:pt-32 md:pb-32 px-6 relative overflow-hidden">
    <!-- Abstract background elements -->
    <div
        class="absolute top-0 right-0 w-[800px] h-[800px] bg-[var(--teal-light)] rounded-full blur-[100px] opacity-40 translate-x-1/3 -translate-y-1/3 pointer-events-none">
    </div>
    <div
        class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-[var(--amber-pale)] rounded-full blur-[100px] opacity-60 -translate-x-1/2 translate-y-1/2 pointer-events-none">
    </div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 relative z-10">

        <div class="flex-1 text-center md:text-left">
            <div
                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 border border-[var(--border)] text-[var(--teal-dark)] text-xs font-bold uppercase tracking-wider mb-8 backdrop-blur-sm shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[var(--amber)] animate-pulse"></span>
                PRE-LAUNCH ENROLLMENT
            </div>

            <h1
                class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-[var(--ink)] leading-[1.05] tracking-tight mb-6">
                Turn AI Into Your Personal Healthcare Advocate
            </h1>

            <p class="text-[19px] md:text-xl text-[var(--ink-light)] mb-10 leading-relaxed max-w-2xl mx-auto md:mx-0">
                A step-by-step masterclass teaching patients how to securely use AI to decipher medical jargon, prepare
                for short doctor visits, and research diagnoses safely.
            </p>

            <div class="flex flex-col sm:flex-row items-center gap-4 justify-center md:justify-start">
                <a href="/billing/checkout?plan=course"
                    class="btn-primary w-full sm:w-auto text-lg px-10 py-4 group relative overflow-hidden">
                    <div
                        class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out">
                    </div>
                    <span class="relative z-10 flex items-center gap-2">
                        Get Full Lifetime Access <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </span>
                </a>
                <span class="text-[var(--ink-light)] font-bold px-4">$49 <span
                        class="text-sm font-normal line-through text-[var(--ink-muted)]">$99</span></span>
            </div>

            <ul class="flex flex-col sm:flex-row items-center justify-center md:justify-start gap-x-8 gap-y-3 mt-10">
                <li class="flex items-center gap-2 text-sm font-medium text-[var(--ink-muted)]">
                    <span class="material-symbols-outlined text-[var(--amber)] text-xl">play_circle</span>
                    2.5 Hours of Video
                </li>
                <li class="flex items-center gap-2 text-sm font-medium text-[var(--ink-muted)]">
                    <span class="material-symbols-outlined text-[var(--amber)] text-xl">school</span>
                    Taught by RNs
                </li>
                <li class="flex items-center gap-2 text-sm font-medium text-[var(--ink-muted)]">
                    <span class="material-symbols-outlined text-[var(--amber)] text-xl">all_inclusive</span>
                    Lifetime Access
                </li>
            </ul>
        </div>

        <div class="flex-1 w-full max-w-2xl relative">
            <div
                class="absolute inset-0 bg-gradient-to-tr from-[var(--teal)] to-[var(--amber)] opacity-10 blur-2xl rounded-[3rem] transform -rotate-6 scale-105 pointer-events-none">
            </div>
            <div
                class="rounded-2xl border-4 border-white/50 bg-white shadow-2xl relative overflow-hidden aspect-[4/3] transform transition-transform duration-700 hover:scale-[1.02]">
                <img src="/assets/img/course-hero.png"
                    alt="How to Use AI to Navigate Your Health Video Course on a Tablet"
                    class="w-full h-full object-cover">
                <!-- Play Button Overlay -->
                <div
                    class="absolute inset-0 bg-[var(--ink)]/10 flex items-center justify-center hover:bg-transparent transition-colors group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-white/90 backdrop-blur-md rounded-full shadow-lg flex items-center justify-center text-[var(--amber)] group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-4xl ml-2">play_arrow</span>
                    </div>
                </div>
            </div>

            <!-- Floating badge -->
            <div
                class="absolute -bottom-6 -left-6 bg-white border border-[var(--border)] p-4 rounded-xl shadow-xl flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img src="https://ui-avatars.com/api/?name=MK&background=1A6B6B&color=fff"
                        class="w-10 h-10 rounded-full border-2 border-white" alt="Student">
                    <img src="https://ui-avatars.com/api/?name=JD&background=C8721A&color=fff"
                        class="w-10 h-10 rounded-full border-2 border-white" alt="Student">
                    <img src="https://ui-avatars.com/api/?name=Sarah&background=1A1612&color=fff"
                        class="w-10 h-10 rounded-full border-2 border-white" alt="Student">
                </div>
                <div>
                    <div class="flex text-[var(--amber)] mb-0.5">
                        <span class="material-symbols-outlined text-[14px]"
                            style="font-variation-settings:'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[14px]"
                            style="font-variation-settings:'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[14px]"
                            style="font-variation-settings:'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[14px]"
                            style="font-variation-settings:'FILL' 1;">star</span>
                        <span class="material-symbols-outlined text-[14px]"
                            style="font-variation-settings:'FILL' 1;">star</span>
                    </div>
                    <p class="text-xs font-bold text-[var(--ink)]">400+ Users Empowered</p>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Curriculum Section -->
<section class="py-24 px-6 bg-white border-y border-[var(--border)] relative">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-[var(--ink)] mb-4">Course Curriculum</h2>
            <p class="text-lg text-[var(--ink-light)] max-w-2xl mx-auto">Skip the generic ChatGPT tutorials. We focus
                specifically on safe, actionable medical prompting that actually moves the needle in your care.</p>
        </div>

        <div class="bg-[var(--warm-white)] border border-[var(--border)] rounded-2xl p-2 md:p-8 shadow-sm">

            <!-- Module 1 -->
            <div
                class="p-6 border-b border-[var(--border-strong)]/50 last:border-0 hover:bg-white rounded-xl transition-colors group">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 bg-[var(--teal-light)] text-[var(--teal-dark)] rounded-lg flex items-center justify-center font-serif font-bold text-xl shrink-0 group-hover:bg-[var(--teal)] group-hover:text-white transition-colors">
                        01</div>
                    <div>
                        <h3 class="text-xl font-bold text-[var(--ink)] mb-2">The Basics: Security & Hallucinations</h3>
                        <p class="text-[var(--ink-light)] mb-4">Learn how to strip PII (Personally Identifiable
                            Information) before pasting lab results, and how to verify if an AI is hallucinating a
                            medical fact.</p>
                        <div class="flex items-center gap-4 text-sm font-medium text-[var(--ink-muted)]">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">smart_display</span> 3 Videos</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">schedule</span> 25 mins</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module 2 -->
            <div
                class="p-6 border-b border-[var(--border-strong)]/50 last:border-0 hover:bg-white rounded-xl transition-colors group">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 bg-[var(--teal-light)] text-[var(--teal-dark)] rounded-lg flex items-center justify-center font-serif font-bold text-xl shrink-0 group-hover:bg-[var(--teal)] group-hover:text-white transition-colors">
                        02</div>
                    <div>
                        <h3 class="text-xl font-bold text-[var(--ink)] mb-2">Translating "Doctor Speak"</h3>
                        <p class="text-[var(--ink-light)] mb-4">A live walkthrough of pasting a confusing MRI
                            radiologist report or surgical summary into AI and extracting a 5th-grade reading level
                            summary.</p>
                        <div class="flex items-center gap-4 text-sm font-medium text-[var(--ink-muted)]">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">smart_display</span> 4 Videos</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">schedule</span> 40 mins</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module 3 -->
            <div
                class="p-6 border-b border-[var(--border-strong)]/50 last:border-0 hover:bg-white rounded-xl transition-colors group">
                <div class="flex items-start gap-4">
                    <div
                        class="w-12 h-12 bg-[var(--teal-light)] text-[var(--teal-dark)] rounded-lg flex items-center justify-center font-serif font-bold text-xl shrink-0 group-hover:bg-[var(--teal)] group-hover:text-white transition-colors">
                        03</div>
                    <div>
                        <h3 class="text-xl font-bold text-[var(--ink)] mb-2">Preparing for the 15-Minute Appt</h3>
                        <p class="text-[var(--ink-light)] mb-4">How to use AI to generate the 3 highest-priority
                            questions you must ask your specialist before they leave the room.</p>
                        <div class="flex items-center gap-4 text-sm font-medium text-[var(--ink-muted)]">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">smart_display</span> 2 Videos</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">schedule</span> 22 mins</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Module 4 -->
            <div
                class="p-6 border-b border-[var(--border-strong)]/50 last:border-0 hover:bg-white rounded-xl transition-colors group relative overflow-hidden">
                <div
                    class="absolute -right-10 -top-10 w-32 h-32 bg-[var(--amber-light)] rounded-full blur-2xl opacity-50">
                </div>
                <div class="flex items-start gap-4 relative z-10">
                    <div
                        class="w-12 h-12 bg-[var(--teal-light)] text-[var(--teal-dark)] rounded-lg flex items-center justify-center font-serif font-bold text-xl shrink-0 group-hover:bg-[var(--amber)] group-hover:text-white transition-colors">
                        04</div>
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-bold text-[var(--ink)]">Writing Appeals & Prior Auths</h3>
                            <span
                                class="bg-[var(--amber-pale)] text-[var(--amber-dark)] text-[10px] uppercase font-bold tracking-wider px-2 py-1 rounded border border-[var(--amber-light)]">Most
                                Popular</span>
                        </div>
                        <p class="text-[var(--ink-light)] mb-4">Learn the exact prompt structures to have AI draft a
                            compelling insurance appeal letter using medical justification, completely bypassing hold
                            times.</p>
                        <div class="flex items-center gap-4 text-sm font-medium text-[var(--ink-muted)]">
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">smart_display</span> 5 Videos</span>
                            <span class="flex items-center gap-1"><span
                                    class="material-symbols-outlined text-[16px]">schedule</span> 55 mins</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Instructor Section -->
<section class="py-24 px-6 bg-[var(--cream)] relative overflow-hidden">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <div class="w-full md:w-1/3 relative">
            <div
                class="absolute inset-0 bg-[var(--teal)] blur-2xl opacity-20 transform translate-x-4 translate-y-4 rounded-full">
            </div>
            <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?q=80&w=800&auto=format&fit=crop"
                alt="Nurse Instructor"
                class="w-full aspect-square object-cover rounded-2xl shadow-xl border-4 border-white relative z-10 grayscale-[20%] hover:grayscale-0 transition-all duration-500">
        </div>
        <div class="w-full md:w-2/3">
            <h2 class="font-serif text-[var(--teal-dark)] text-lg font-bold uppercase tracking-widest mb-2">Your
                Instructor</h2>
            <h3 class="text-3xl md:text-4xl font-serif font-bold text-[var(--ink)] mb-6">Built by Clinical Experts Who
                Know The System is Broken</h3>
            <p class="text-lg text-[var(--ink-light)] mb-6 leading-relaxed">
                As nurses, we sit beside patients every day who are handed a terrifying diagnosis and a stack of
                confusing discharge papers. We watch them go home and fall through the cracks of a fractured healthcare
                system.
            </p>
            <p class="text-lg text-[var(--ink-light)] mb-8 leading-relaxed">
                We built this course because Large Language Models (LLMs) are the great equalizer. When used correctly,
                they give every patient a world-class clinical researcher and advocate right in their pocket.
            </p>

            <a href="/about"
                class="inline-flex items-center gap-2 text-[var(--amber-dark)] font-bold hover:text-[var(--amber)] transition-colors">
                Learn more about our mission <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </div>
</section>

<!-- Pricing CTA -->
<section class="py-24 px-6 bg-[var(--ink)] text-center relative overflow-hidden border-t-8 border-[var(--teal)]">
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none">
    </div>
    <div
        class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--teal)] rounded-full blur-[150px] opacity-20 pointer-events-none">
    </div>

    <div class="max-w-3xl mx-auto relative z-10">
        <h2 class="text-4xl md:text-5xl font-serif font-bold text-white mb-6">Stop Googling Your Symptoms. Start
            Controlling Your Care.</h2>
        <p class="text-xl text-white/80 mb-12 max-w-2xl mx-auto leading-relaxed">
            Gain the ultimate superpower for navigating modern medicine. Instant lifetime access to all core modules and
            future material updates.
        </p>

        <div
            class="bg-white/5 border border-white/10 p-8 md:p-12 rounded-3xl backdrop-blur-sm max-w-xl mx-auto shadow-2xl">
            <h3 class="text-white font-bold tracking-widest uppercase text-sm mb-4 text-[var(--amber)]">Introductory
                Pricing</h3>
            <div class="flex items-baseline justify-center gap-2 mb-8">
                <span class="text-6xl font-serif font-bold text-white">$49</span>
                <span class="text-xl text-white/50 line-through">$99</span>
            </div>

            <a href="/billing/checkout?plan=course"
                class="btn-primary w-full text-xl py-5 shadow-[0_0_30px_rgba(200,114,26,0.5)]">
                Enroll Now
            </a>

            <div class="mt-6 flex items-center justify-center gap-2 text-white/60 text-sm">
                <span class="material-symbols-outlined text-[16px]">lock</span>
                Secure payment via Stripe
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>