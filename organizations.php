<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'PromptRN for Organizations | Patient Advocacy & Licensing';
$metaDescription = 'License the PromptRN condition library and AI health tools for your patient advocacy group, wellness program, or health system.';
$canonicalUrl = app_url('/organizations');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-[var(--ink)] pt-20 pb-24 md:pt-32 md:pb-32 px-6 relative overflow-hidden">
    <!-- Abstract background elements -->
    <div
        class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none">
    </div>
    <div
        class="absolute top-0 right-0 w-[600px] h-[600px] bg-[var(--teal)] rounded-full blur-[100px] opacity-20 translate-x-1/3 -translate-y-1/3 pointer-events-none">
    </div>

    <div class="max-w-5xl mx-auto text-center relative z-10">
        <div
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 text-[var(--amber-light)] text-xs font-bold uppercase tracking-wider mb-8 shadow-sm">
            <span class="material-symbols-outlined text-sm">handshake</span>
            B2B Licensing & Partnerships
        </div>

        <h1
            class="text-4xl md:text-5xl lg:text-6xl font-serif font-bold text-white leading-[1.05] tracking-tight mb-6 max-w-4xl mx-auto">
            Scale Patient Empowerment Across Your Entire Population
        </h1>

        <p class="text-[19px] md:text-xl text-white/80 mb-10 leading-relaxed max-w-2xl mx-auto">
            We provide white-labeled condition guides, AI prompting frameworks, and clinical preparation tools to
            patient advocacy groups, non-profits, and forward-thinking health plans.
        </p>

        <div class="flex flex-col md:flex-row items-center justify-center gap-4">
            <a href="mailto:partnerships@promptrn.com"
                class="btn-primary w-full md:w-auto text-lg px-10 py-4 shadow-[0_0_20px_rgba(200,114,26,0.3)] hover:shadow-[0_0_30px_rgba(200,114,26,0.5)]">
                Contact Partnerships
            </a>
            <a href="#how-it-works" class="px-8 py-4 text-white font-bold hover:text-[var(--amber)] transition-colors">
                See How It Works
            </a>
        </div>
    </div>
</section>

<!-- Stats / Social Proof -->
<section class="py-12 bg-white border-b border-[var(--border)]">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 opacity-70">
            <h2
                class="text-sm font-bold uppercase tracking-widest text-[var(--ink-muted)] text-center md:text-left min-w-[200px]">
                Trusted by leaders in patient advocacy:</h2>
            <div class="flex flex-wrap justify-center md:justify-end items-center gap-8 md:gap-16 grayscale">
                <!-- Logos would go here, placeholders for now -->
                <span class="font-serif text-2xl font-bold">National MS Society</span>
                <span class="font-serif text-2xl font-bold">LymeDisease.org</span>
                <span class="font-serif text-2xl font-bold">Pink Ribbon</span>
            </div>
        </div>
    </div>
</section>

<!-- Features Grid -->
<section id="how-it-works" class="py-24 px-6 bg-[var(--cream)]">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-serif font-bold text-[var(--ink)] mb-4">A Turnkey Solution for Patient
                Education</h2>
            <p class="text-lg text-[var(--ink-light)] max-w-2xl mx-auto">Stop reinventing the wheel. License our
                nurse-reviewed frameworks and integrate them directly into your member portals.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">

            <div
                class="bg-white p-8 rounded-2xl shadow-sm border border-[var(--border)] hover:border-[var(--teal)] transition-colors group">
                <div
                    class="w-14 h-14 bg-[var(--teal-light)] rounded-xl flex items-center justify-center text-[var(--teal-dark)] mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">api</span>
                </div>
                <h3 class="text-xl font-bold text-[var(--ink)] mb-3">White-Label API Delivery</h3>
                <p class="text-[var(--ink-light)] leading-relaxed">
                    Pull our exact condition prompts, red-flag questions, and prep frameworks directly into your
                    existing app or website via JSON.
                </p>
            </div>

            <div
                class="bg-white p-8 rounded-2xl shadow-sm border border-[var(--border)] hover:border-[var(--amber)] transition-colors group">
                <div
                    class="w-14 h-14 bg-[var(--amber-pale)] rounded-xl flex items-center justify-center text-[var(--amber-dark)] mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">library_books</span>
                </div>
                <h3 class="text-xl font-bold text-[var(--ink)] mb-3">Custom Condition Packs</h3>
                <p class="text-[var(--ink-light)] leading-relaxed">
                    Need deep-dives into rare diseases? Our clinical team will collaborate with your medical advisory
                    board to author exclusive, branded condition frameworks.
                </p>
            </div>

            <div
                class="bg-white p-8 rounded-2xl shadow-sm border border-[var(--border)] hover:border-[var(--teal)] transition-colors group">
                <div
                    class="w-14 h-14 bg-[var(--teal-light)] rounded-xl flex items-center justify-center text-[var(--teal-dark)] mb-6 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-3xl">subscriptions</span>
                </div>
                <h3 class="text-xl font-bold text-[var(--ink)] mb-3">Bulk Course Licensing</h3>
                <p class="text-[var(--ink-light)] leading-relaxed">
                    Purchase bulk access to our "AI Health Masterclass" at a steep discount to distribute as a premium
                    perk to your donors or members.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- Contact Form / CTA -->
<section class="py-24 px-6 bg-white border-t border-[var(--border)]">
    <div
        class="max-w-4xl mx-auto bg-[var(--ink)] rounded-3xl overflow-hidden shadow-2xl flex flex-col md:flex-row relative">
        <div
            class="absolute top-0 right-0 w-[400px] h-[400px] bg-[var(--teal)] rounded-full blur-[80px] opacity-20 translate-x-1/2 -translate-y-1/2 pointer-events-none">
        </div>

        <div class="w-full md:w-5/12 bg-[var(--teal-dark)] p-10 flex flex-col justify-between relative z-10">
            <div>
                <h3 class="text-2xl font-serif font-bold text-white mb-4">Let's work together.</h3>
                <p class="text-white/80 leading-relaxed mb-8">
                    We only partner with organizations that share our mission: putting control back into the hands of
                    the patient.
                </p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3 text-white/90">
                    <span class="material-symbols-outlined text-[var(--amber)]">mail</span>
                    partnerships@promptrn.com
                </div>
                <div class="flex items-center gap-3 text-white/90">
                    <span class="material-symbols-outlined text-[var(--amber)]">location_on</span>
                    Remote / United States
                </div>
            </div>
        </div>

        <div class="w-full md:w-7/12 p-10 relative z-10">
            <h4 class="text-white font-bold mb-6 text-lg">Leave us a message</h4>
            <!-- Demo form -->
            <form action="#" method="POST" class="space-y-4" onsubmit="event.preventDefault(); alert('Message sent!');">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-white/70 uppercase mb-1">First Name</label>
                        <input type="text"
                            class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:border-transparent transition-all placeholder:text-white/30"
                            placeholder="Jane">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-white/70 uppercase mb-1">Last Name</label>
                        <input type="text"
                            class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:border-transparent transition-all placeholder:text-white/30"
                            placeholder="Doe">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/70 uppercase mb-1">Work Email</label>
                    <input type="email"
                        class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:border-transparent transition-all placeholder:text-white/30"
                        placeholder="jane@organization.org">
                </div>
                <div>
                    <label class="block text-xs font-bold text-white/70 uppercase mb-1">Message</label>
                    <textarea rows="3"
                        class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-[var(--amber)] focus:border-transparent transition-all placeholder:text-white/30 resize-none"
                        placeholder="How can we help?"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full mt-2">
                    Send Message
                </button>
            </form>
        </div>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>