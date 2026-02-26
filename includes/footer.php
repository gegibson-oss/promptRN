<?php
declare(strict_types=1);
?>
</main>
<footer class="bg-[var(--ink)] text-white/60 py-12 px-6 mt-auto border-t border-white/10">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
            <div class="col-span-1 md:col-span-1">
                <span class="font-serif font-bold text-2xl text-white block mb-4">Prompt<span
                        class="text-[var(--amber)]">RN</span></span>
                <p class="text-sm leading-relaxed mb-6">
                    Empowering patients with nurse-authored AI tools to bridge the gap in healthcare communication.
                </p>
                <div class="flex gap-4">
                    <a class="hover:text-white transition-colors" href="#"><span
                            class="material-symbols-outlined">mail</span></a>
                    <a class="hover:text-white transition-colors" href="#"><span
                            class="material-symbols-outlined">forum</span></a>
                </div>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 uppercase text-xs tracking-wider">Platform</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-white transition-colors" href="/prompts">Browse Conditions</a></li>
                    <li><a class="hover:text-white transition-colors" href="/tools/prompt-generator">Free Tools</a></li>
                    <li><a class="hover:text-white transition-colors" href="/billing/checkout?plan=monthly">Pricing</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 uppercase text-xs tracking-wider">Company</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-white transition-colors" href="/about">About Us</a></li>
                    <li><a class="text-[var(--amber-light)] font-bold hover:text-white transition-colors"
                            href="/organizations">B2B & Licensing</a></li>
                    <li><a class="hover:text-white transition-colors" href="/about">Careers</a></li>
                    <li><a class="hover:text-white transition-colors" href="/about">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold mb-4 uppercase text-xs tracking-wider">Legal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a class="hover:text-white transition-colors" href="/about">Privacy Policy</a></li>
                    <li><a class="hover:text-white transition-colors" href="/about">Terms of Service</a></li>
                    <li><a class="hover:text-white transition-colors" href="/about">Medical Disclaimer</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
            <div>
                &copy;
                <?= app_h((string)date('Y')); ?> PromptRN · Written by nurses, for patients
            </div>
            <div class="text-white/40">
                PromptRN does not provide medical advice. Always consult a professional.
            </div>
        </div>
    </div>
</footer>
</body>

</html>