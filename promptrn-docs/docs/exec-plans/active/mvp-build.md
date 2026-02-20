# Execution Plan: MVP Build

## Goal
Ship a working promptrn.com with 10 condition pages, Stripe billing, and user auth.
Validate that patients will pay for nurse-written AI health prompts.

## Non-Goals
- Programmatic page generation (manual first)
- Blog or pillar content
- Free tools
- Situation hub pages
- Admin dashboard
- Password reset

## Acceptance Criteria
- [ ] 10 condition pages live and indexed by Google
- [ ] Every condition page scores ≥ 90 PageSpeed mobile
- [ ] Every condition page has valid MedicalWebPage + FAQPage schema
- [x] User can register, login, and logout
- [x] User can purchase a single condition pack via Stripe ($9)
- [x] User can subscribe monthly via Stripe ($17/month)
- [x] Stripe webhook correctly updates user subscription status
- [x] Gated content is inaccessible without active subscription or purchase
- [x] sitemap.php outputs valid XML covering all 10 condition pages
- [x] robots.txt blocks /data, /private, /auth, /billing, /members
- [x] users.json is outside web root and not accessible via browser
- [x] No API keys or secrets in any tracked file
- [ ] HTTPS live on promptrn.com

## Progress Log

### 2026-02-20
- Completed in-repo MVP scope: templates, auth, Stripe checkout + webhook, gating, robots + sitemap, and public UI alignment to the mock spec.
- Validated locally: PHP syntax checks pass; key pages render; Lighthouse local runs hit 100/100 for performance + SEO on homepage and a condition page.
- Remaining blockers are deployment/external verification: HTTPS live on `promptrn.com`, Google indexing, live PageSpeed, and Rich Results validation.

## Design

### Data Layer
Each condition is a JSON file in /data/conditions/[slug].json.
Schema defined in docs/references/api-contracts.md.
PHP template reads the JSON and renders complete HTML — no JS required for content.

### Auth Layer
PHP native sessions. No JWT. No OAuth for MVP.
users.json in /private/ (outside web root).
Passwords stored as bcrypt hashes. Never plaintext.
auth-check.php is included at the top of every gated page.

### Billing Layer
Stripe Checkout (hosted) — no custom payment form for MVP.
Three Stripe products: pack-single ($9), sub-monthly ($17), sub-annual ($99).
Webhook endpoint: /billing/webhook.php
Webhook verifies Stripe signature before processing.
On subscription_created: set user.subscription_status = "active", user.subscription_expires = period_end.
On subscription_deleted: set status = "cancelled".
On payment_intent.succeeded (pack purchase): add condition slug to user.purchased_packs array.

### SEO Layer
includes/seo.php outputs all meta, canonical, and schema tags.
Called at the top of every public-facing page.
Condition JSON must include: page_title, meta_description, h1, primary_keyword, schema fields.
sitemap.php auto-reads /data/conditions/ directory and outputs fresh XML on every request.

## Steps

### Step 1 — Environment Setup
1. Provision Hetzner VPS (Ubuntu 22.04, PHP 8.2, Apache)
2. Point promptrn.com DNS to VPS
3. Install Let's Encrypt SSL (Certbot)
4. Set up GitHub repo with deploy hook (push to main → pull on server)
5. Configure .htaccess URL rewrites
6. Create /private directory outside web root
7. Add .env file for secrets (Stripe keys, ConvertKit key) — gitignored

### Step 2 — Data Structure
1. Define condition JSON schema (see docs/references/api-contracts.md)
2. Write 10 condition JSON files (nurse-authored content)
3. Write meta.json (site config, condition index)

### Step 3 — Core PHP Templates
Prompt for Codex: "Build a PHP flat-file CMS. Template reads /data/conditions/[slug].json.
Outputs complete SEO-optimised HTML including MedicalWebPage schema, FAQPage schema,
BreadcrumbList schema, canonical tag, meta title and description. Include header.php,
footer.php, and seo.php includes. .htaccess rewrites /prompts/[slug] to template.php?slug=[slug]."

### Step 4 — Auth System
Prompt for Codex: "Build PHP user auth using flat-file storage. Store users as JSON array
in /private/users.json (outside web root). Include: register.php (bcrypt password hash),
login.php (session start), logout.php (session destroy), auth-check.php (include guard
for gated pages that redirects to login if no valid session). Add subscription_status,
subscription_expires, and purchased_packs fields to user record."

### Step 5 — Stripe Billing
Prompt for Codex: "Build Stripe billing integration in PHP. Create billing/checkout.php
that redirects to Stripe Checkout session for either a one-time pack purchase or monthly
subscription. Create billing/webhook.php that verifies Stripe webhook signature, handles
payment_intent.succeeded, customer.subscription.created, and customer.subscription.deleted
events, and updates the corresponding user record in /private/users.json. Create
billing/success.php as the post-payment redirect page."

### Step 6 — Members Area
1. members/dashboard.php — session-gated landing page
2. members/library.php — shows all conditions, unlocked for subscribers
3. Condition template: show locked prompts if no access, full prompts if subscribed or purchased

### Step 7 — Homepage + About
1. Homepage: value prop, browse conditions, pricing section, author trust block
2. About page: full nurse bio, credentials, why PromptRN exists

### Step 8 — SEO Finalisation
1. Verify all 10 pages output valid schema (Google Rich Results Test)
2. Verify PageSpeed ≥ 90 on all pages
3. Submit sitemap to Google Search Console
4. Verify robots.txt is correct
5. Test all canonical tags

### Step 9 — Launch
1. Final security check (see SECURITY.md checklist)
2. Stripe test mode → live mode
3. DNS confirmed, HTTPS live
4. Announce on Twitter/X with nurse story

## Validation
- Tests: Manual test of all Stripe webhook events in test mode before go-live
- SEO: Google Rich Results Test on all 10 condition pages
- Performance: PageSpeed Insights on all 10 condition pages
- Security: Confirm /private/ inaccessible via browser. Confirm .env not in repo.
- UX checks: Full purchase flow on mobile. Full subscribe flow on desktop.

## Risks

| Risk | Mitigation |
|------|------------|
| Stripe webhook fails silently | Log all webhook events to file. Test all event types before launch. |
| users.json write conflicts under concurrent load | Acceptable at MVP scale (<100 users). Add file locking if needed. |
| Google slow to index new domain | Submit sitemap immediately. Build 2–3 backlinks before launch (About page on LinkedIn, etc.) |
| Content too thin to rank | Each condition page must have 800+ words of unique content. Clinical context is non-negotiable. |
| PHP session security | Use session_regenerate_id() on login. HTTPOnly + Secure cookie flags. |

## Decision Log

| Date | Decision | Rationale |
|------|----------|-----------|
| 2025-01-01 | Stripe Checkout (hosted) not custom form | Faster to ship. PCI compliant out of box. Custom form is post-MVP. |
| 2025-01-01 | 10 conditions manually, not programmatic | Validate template and conversion before scaling. |
| 2025-01-01 | No password reset at MVP | Adds complexity. If user loses password, email support. Add post-MVP. |
| 2025-01-01 | Hetzner as first hosting choice | €4/mo, runs PHP natively, Levels uses it. Render.com as fallback. |
