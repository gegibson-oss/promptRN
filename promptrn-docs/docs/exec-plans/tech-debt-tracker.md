# Tech Debt Tracker

Known shortcuts taken for speed. Must address before scaling.

| Item | Severity | When to Fix | Notes |
|------|----------|-------------|-------|
| No password reset flow | Medium | Before 50 users | Currently: email support. Annoying but manageable at small scale. |
| users.json file locking | Medium | Before 200 concurrent users | PHP flock() needed to prevent write conflicts. Low risk at MVP scale. |
| No rate limiting on auth endpoints | High | Before public launch | login.php needs brute-force protection. Add middleware or Cloudflare rate limit rule. |
| Manual JSON file creation | Low | Phase 2 | Replace with CSV import script. Acceptable for first 10 conditions. |
| No automated tests | Medium | Phase 2 | Add basic smoke tests for auth flow and Stripe webhook handling. |
| Stripe keys in .env only | Low | Ongoing | Fine for single VPS. If multi-server, move to proper secret manager. |
| No error monitoring | Medium | Phase 2 | PHP error_log is basic. Add Sentry or similar before scaling. |
| ConvertKit integration is manual copy/paste | Low | Phase 2 | Automate email capture via ConvertKit API on register. |
| sitemap.php runs on every crawl request | Low | Phase 3 | At 500+ pages, cache sitemap output for 24 hours. |
| No staging environment | Medium | Phase 2 | Currently deploying straight to production. Add staging branch. |
