# Decisions Log

All major architectural and product decisions, versioned.

| Date | Decision | Rationale |
|------|----------|-----------|
| 2025-01-01 | PHP flat files, no framework | Levels stack. No build step, no framework upgrades, maximum SEO control. Codex generates clean PHP. |
| 2025-01-01 | JSON flat files instead of database | No schema migrations, no DB hosting cost, git-versionable content, trivially fast reads for PHP. Add DB only if concurrent writes become a problem. |
| 2025-01-01 | Hetzner VPS over GitHub Pages | GitHub Pages is static-only, no PHP. Hetzner €4/mo runs PHP natively. Render.com is fallback. |
| 2025-01-01 | Stripe over Lemon Squeezy | Stripe has better brand recognition for medical/health context, more robust webhook reliability, and wider documentation. Lemon Squeezy is acceptable fallback. |
| 2025-01-01 | Freemium model (3 free, rest gated) | Cold SEO traffic converts poorly to subscriptions. Free prompts prove value before asking for payment. Titles of locked prompts visible to create desire. |
| 2025-01-01 | One-time pack + subscription dual pricing | One-time purchase ($9–29) captures users unwilling to subscribe. Subscription ($17/mo or $99/yr) captures power users. Reduces abandonment at checkout. |
| 2025-01-01 | ConvertKit for email | Simple API, good deliverability, built for creators, cheap at small scale. Every page captures email before exit. |
| 2025-01-01 | Condition pages as primary SEO unit | "AI prompts for [condition]" is high-intent, commercial, and underserved. 500+ conditions = 500+ indexable pages from one template. |
| 2025-01-01 | Author schema with RN credentials on every page | Google E-E-A-T for health content weights medical credentials heavily. RN authorship is a ranking signal, not just a trust signal. |
| 2025-01-01 | MedicalWebPage + FAQPage schema on all condition pages | Rich results in search (FAQ dropdowns) increase CTR without changing rankings. Health schema signals category to Google. |
| 2025-01-01 | users.json stored outside web root | Security invariant. User data including password hashes and subscription status must never be web-accessible. |
| 2025-01-01 | No JavaScript for core page content | Google must see complete content on first crawl. JS-rendered content risks incomplete indexing. All prompts render in PHP/HTML. Copy button is the only JS dependency. |
