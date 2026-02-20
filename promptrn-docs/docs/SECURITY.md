# Security

## Principles
- Least privilege — no file or process gets more access than it needs
- No secrets in the repo — ever, including accidentally
- Defense in depth — multiple layers, not one strong wall
- Patient data is sensitive — treat it accordingly

---

## Required Environment Variables (.env — never committed to Git)

```
STRIPE_SECRET_KEY=sk_live_...
STRIPE_PUBLISHABLE_KEY=pk_live_...
STRIPE_WEBHOOK_SECRET=whsec_...
CONVERTKIT_API_KEY=...
APP_ENV=production
APP_SECRET=... (random 32-char string for misc signing)
```

Add .env to .gitignore before first commit. Verify it is not tracked:
`git ls-files .env` should return nothing.

---

## Requirements

### Input Validation
- All user input sanitized before use: htmlspecialchars() on output, never trust $_POST/$_GET directly
- Email addresses validated with filter_var($email, FILTER_VALIDATE_EMAIL)
- Condition slugs validated against known slugs in meta.json before file read (prevent path traversal)
- Stripe webhook payload validated with stripe_verify_signature() before any processing

### Authentication
- Passwords stored as bcrypt hashes only: password_hash($password, PASSWORD_BCRYPT)
- Session regenerated on login: session_regenerate_id(true)
- Session cookie flags: HTTPOnly, Secure, SameSite=Strict
- CSRF tokens on all POST forms (register, login, account changes)
- Login endpoint: rate limit to 10 attempts per IP per 15 minutes (Cloudflare rule or PHP counter)

### Authorization
- Every gated page includes auth-check.php as first line after session_start()
- auth-check.php verifies: session exists, user record exists, subscription is active or pack purchased
- No security through obscurity — gated pages must actively check, not just assume

### File System
- users.json stored in /private/ which must be above the Apache web root
- /data/ directory: Apache must not serve .json files directly (add .htaccess: Deny from all)
- /includes/ directory: Apache must not serve .php files directly when accessed without the parent template

### Stripe Webhook Security
- Always verify Stripe-Signature header using Stripe SDK
- Reject any webhook request that fails signature verification (return 400)
- Log all webhook events with timestamp and event ID
- Ensure idempotency: check if event ID has already been processed before acting

### Security Headers (Apache .htaccess)
```apache
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
```

---

## Threat Model

| Threat | Risk | Mitigation |
|--------|------|------------|
| Account takeover via brute force | High | Rate limit login endpoint. Bcrypt slows hash comparison. |
| Path traversal via condition slug | High | Whitelist slugs against meta.json before file_get_contents. |
| Stripe webhook forgery | High | Signature verification required. Reject unsigned requests. |
| Token/session hijacking | Medium | Secure + HTTPOnly + SameSite cookie flags. HTTPS only. |
| users.json direct access | High | Stored outside web root. Apache cannot serve it. |
| Secrets committed to GitHub | Critical | .env gitignored. Pre-commit hook to scan for keys (add in Phase 2). |
| XSS via user input | Medium | All user-generated output through htmlspecialchars(). |
| CSRF on account changes | Medium | CSRF tokens on all state-changing forms. |
| Data breach of users.json | Medium | Bcrypt passwords. No payment data stored (Stripe handles it). |

---

## Pre-Launch Security Checklist

- [x] .env is in .gitignore and not tracked
- [ ] users.json is outside Apache web root
- [x] /data/ directory has .htaccess denying direct JSON access
- [x] All Stripe webhook calls verify signature
- [x] Login form has rate limiting active
- [x] All POST forms have CSRF tokens
- [ ] HTTPS is live and HTTP redirects to HTTPS
- [x] Security headers set in .htaccess
- [x] session_regenerate_id() called on login
- [x] Session cookies are HTTPOnly + Secure
- [x] Condition slug input validated against whitelist
- [ ] No error messages expose file paths or system info to users
- [x] PHP display_errors = Off in production
- [x] PHP log_errors = On, errors logged to file
