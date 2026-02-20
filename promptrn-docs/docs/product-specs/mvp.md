# MVP Scope

Ship the smallest version that proves patients will pay for nurse-written AI health prompts.

---

## Definition of MVP

A working website at promptrn.com where:
- A patient can land on a condition page from Google
- Read 3 free nurse-written prompts with clinical context
- Purchase the full 12-prompt condition pack via Stripe ($9)
- OR subscribe to the full library via Stripe ($17/month)
- Access gated content immediately after payment
- Return to their account to access purchased content

That is it. Nothing else is MVP.

---

## MVP Scope

### Content
- [x] 10 condition pages (manually written, not yet programmatic)
- [x] Each page: 3 free prompts + 9 locked prompts with visible titles
- [x] Each prompt: the prompt text + "why this works" clinical explanation
- [x] Clinical context paragraph per condition (nurse-written, unique)
- [x] FAQ section (4-6 questions per condition, schema-marked)
- [x] Related conditions links (internal linking)
- [x] Author bio with RN credentials on every page

Starting conditions (highest search volume, clearest patient need):
1. Type 2 Diabetes Diagnosis
2. Hypertension (High Blood Pressure)
3. Hypothyroidism
4. High Cholesterol
5. Prediabetes
6. Atrial Fibrillation
7. GERD / Acid Reflux
8. Osteoarthritis
9. Anxiety Disorder (newly diagnosed)
10. Asthma (adult diagnosis)

### Pages
- [x] Homepage
- [x] /prompts index (browse all conditions)
- [x] /prompts/[condition] template (serves all 10 conditions)
- [x] /members/dashboard (post-login landing)
- [x] /members/library (full prompt access for subscribers)
- [x] /auth/login
- [x] /auth/register
- [x] /billing/checkout (redirect to Stripe)
- [x] /billing/webhook (Stripe event handler)
- [x] /billing/success
- [x] /about (nurse credentials, trust page)
- [x] sitemap.php
- [x] robots.txt

### UI Implementation Target
- [x] Public-facing UI matches the required mock spec in docs/design-docs/mock-ui-spec.md

### Auth
- [x] Register with email + password
- [x] Login / logout
- [x] PHP session management
- [x] users.json stored outside web root
- [x] Subscription status field per user

### Billing (Stripe)
- [x] One-time condition pack purchase ($9 default)
- [x] Monthly subscription ($17/month)
- [x] Annual subscription ($99/year)
- [x] Stripe webhook handling: payment_intent.succeeded, customer.subscription.created, customer.subscription.deleted
- [x] Access granted immediately on webhook receipt
- [x] Graceful handling of failed payments

### SEO (Required at MVP, Not Optional)
- [x] Unique title + meta description per condition (from JSON)
- [x] Canonical tags on all pages
- [x] MedicalWebPage schema on all condition pages
- [x] FAQPage schema on all condition pages
- [x] BreadcrumbList schema
- [x] Author schema with RN credentials
- [x] Auto-generated sitemap.php
- [x] robots.txt blocking /data, /private, /auth, /billing, /members
- [x] Clean URL rewrites via .htaccess
- [ ] All pages: PageSpeed ≥ 90 mobile

---

## MVP Non-Scope (Explicitly Deferred)

- Programmatic page generation (manual first, automate after validation)
- Caregiver-specific product line
- Blog / pillar content
- Free prompt generator tool
- Situation hub pages
- Email sequences beyond welcome (ConvertKit basic only)
- Annual billing UI (add after monthly is working)
- Social login
- Admin dashboard
- Password reset flow (add post-MVP)
- Mobile app

---

## MVP Success Criteria

- [ ] 3 paying customers within 30 days of launch
- [ ] At least 1 organic Google visitor per day within 60 days
- [ ] Zero Stripe webhook failures unhandled
- [ ] All 10 condition pages indexed by Google
- [ ] PageSpeed ≥ 90 on all public pages

If 3 paying customers in 30 days: build out to 50 conditions and launch programmatic pipeline.
If 0 paying customers in 30 days: talk to 10 patients. Rebuild the value prop before scaling content.
