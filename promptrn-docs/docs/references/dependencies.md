# Dependencies

Minimal by design. Every dependency is a liability.

---

## Production Dependencies

| Dependency | Purpose | Version | Notes |
|------------|---------|---------|-------|
| PHP | Server language | 8.2+ | No framework. Vanilla PHP only. |
| Apache | Web server | 2.4+ | mod_rewrite required for clean URLs |
| Stripe PHP SDK | Billing | Latest | Only external SDK dependency. Loaded via Composer. |
| Let's Encrypt | SSL | Via Certbot | Free, auto-renewing HTTPS |

---

## External Services

| Service | Purpose | Cost | Notes |
|---------|---------|------|-------|
| Hetzner VPS | Hosting | €4/mo | CX11 instance sufficient for MVP |
| Stripe | Payments | 2.9% + 30¢ per transaction | No monthly fee. Only pay on revenue. |
| ConvertKit | Email | Free up to 1,000 subscribers | Switch to paid plan when list grows |
| GitHub | Code + deploy trigger | Free | Source of truth for all code |
| Google Search Console | SEO monitoring | Free | Required — submit sitemap day one |
| UptimeRobot | Uptime monitoring | Free tier | Add in Phase 2 |

---

## Explicitly Not Used

| Technology | Why Excluded |
|------------|--------------|
| Laravel / Symfony | Adds complexity, build step, framework lock-in |
| Next.js / React | Requires Node.js, build pipeline, JS-rendered content harms SEO |
| MySQL / PostgreSQL | Flat files sufficient. No DB until concurrent writes are a problem. |
| Redis | Not needed at MVP scale |
| Docker | Adds abstraction layer. Direct VPS is simpler and faster to debug. |
| Webpack / Vite | No build step is a feature, not a bug |
| JWT | PHP sessions are sufficient and simpler |
| WordPress | Too heavy. No control over HTML output for SEO. |

---

## Composer (PHP Package Manager)

composer.json requires only:
```json
{
  "require": {
    "stripe/stripe-php": "^12.0"
  }
}
```

No other Composer dependencies. All other code is written directly.
