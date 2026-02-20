# ARCHITECTURE.md

## Overview
PromptRN is a flat-file PHP website. There is no framework, no ORM, no build step.
PHP reads JSON files from /data and renders complete HTML pages.
Google sees fully rendered HTML on first request. No JavaScript rendering required.
This is intentional. Speed, simplicity, and SEO are the same decision.

---

## Domains

- **Content** — Condition pages, situation pages, prompt packs
- **Auth** — User registration, login, session management
- **Billing** — Stripe subscriptions and one-time pack purchases
- **SEO** — Sitemap, schema, meta, canonical, internal linking
- **Tools** — Free prompt generator and other SEO-driving utilities
- **Members** — Gated dashboard, library access, account management

---

## File Structure

```
/
├── index.php                  # Homepage
├── prompts/
│   ├── index.php              # Browse all conditions
│   └── template.php           # Single condition page (reads from /data)
├── situations/
│   └── template.php           # Situation hub pages
├── tools/
│   └── prompt-generator.php   # Free SEO tool
├── members/
│   ├── dashboard.php          # Gated member area
│   ├── library.php            # Full prompt library
│   └── account.php            # Manage subscription
├── auth/
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   └── forgot-password.php
├── billing/
│   ├── checkout.php           # Redirects to Stripe
│   ├── webhook.php            # Stripe webhook handler
│   └── success.php            # Post-payment landing
├── sitemap.php                # Auto-generated XML sitemap
├── robots.txt
├── .htaccess                  # URL rewrites
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── nav.php
│   ├── seo.php                # Meta, canonical, schema output
│   ├── auth-check.php         # Session + subscription guard
│   └── stripe.php             # Stripe SDK wrapper
├── data/
│   ├── conditions/
│   │   ├── type-2-diabetes.json
│   │   ├── hypertension.json
│   │   └── ...                # One JSON per condition
│   ├── situations/
│   │   ├── newly-diagnosed.json
│   │   └── ...
│   └── meta.json              # Site-wide config and condition index
├── private/
│   └── users.json             # Stored OUTSIDE web root (see SECURITY.md)
└── assets/
    ├── css/
    └── js/
```

---

## Layering

```
JSON Data Files
     ↓
PHP Template (reads + renders)
     ↓
HTML Output (what Google and users see)
     ↓
Stripe (billing events via webhook)
     ↓
users.json (subscription status updated)
```

Layers only depend forward. Templates read data. Webhooks write to users.
No template writes to users directly. No data file calls Stripe.

---

## Dependency Rules

- Templates may only READ from /data — never write.
- Only billing/webhook.php may WRITE to private/users.json.
- auth/ files may READ and WRITE user sessions and user records.
- No page may access private/ directly — always via includes/auth-check.php.
- Stripe SDK is only ever called through includes/stripe.php.
- External calls (Stripe, ConvertKit) must have timeout + error handling.

---

## URL Structure (SEO Architecture)

Clean, keyword-rich, hyphenated URLs. Enforced via .htaccess rewrites.

```
/prompts/[condition-slug]           # Condition pages (core SEO)
/situations/[situation-slug]        # Hub pages (internal link targets)
/tools/[tool-name]                  # Free tools (backlink magnets)
/blog/[post-slug]                   # Pillar content (authority building)
/members/                           # Gated (noindex)
/auth/                              # Gated (noindex)
/billing/                           # Gated (noindex)
```

All gated pages output `<meta name="robots" content="noindex">`.
/data/ and /private/ are blocked in robots.txt.

---

## SEO Invariants (Enforced on Every Condition Page)

Every page rendered by template.php MUST output:
- `<title>` containing primary keyword (under 60 chars)
- `<meta name="description">` (under 160 chars)
- `<link rel="canonical">`
- `<h1>` containing primary keyword (exactly one per page)
- MedicalWebPage schema with author RN credentials
- FAQPage schema using condition FAQ data
- BreadcrumbList schema
- `<meta name="robots" content="index, follow">`
- Last-updated date visible on page and in schema

These are checked in includes/seo.php. If any field is missing from the JSON,
the page throws a visible error in development and falls back gracefully in production.

---

## Cross-Cutting Concerns

| Concern        | Where Handled              |
|----------------|----------------------------|
| Auth guard     | includes/auth-check.php    |
| SEO output     | includes/seo.php           |
| Stripe calls   | includes/stripe.php        |
| Session start  | includes/header.php        |
| Error logging  | PHP error_log() to file    |
| Email capture  | ConvertKit API via include  |

---

## Invariants (Non-Negotiable)

- No direct file writes outside of auth/ and billing/webhook.php.
- No unvalidated user input is ever written to any file.
- No secrets or API keys in any file tracked by Git.
- All Stripe webhook calls must verify signature before processing.
- All condition pages must serve complete HTML (no client-side rendering of core content).
- PageSpeed score must remain above 90 on mobile for all public pages.
