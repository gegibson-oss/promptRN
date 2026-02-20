# Reliability Standards

## SLIs (Service Level Indicators)

| Metric | Target | Current |
|--------|--------|---------|
| Page load time (TTFB) | < 300ms | TBD at launch |
| PageSpeed score (mobile) | ≥ 90 | Required before launch |
| Stripe webhook processing | < 2s | TBD |
| Uptime | ≥ 99.5% | Monitor via UptimeRobot |
| Error rate (PHP errors) | < 0.1% | Monitor via error_log |

---

## Requirements

- All public pages must serve complete HTML with no JS dependency for core content
- sitemap.php must always return valid XML (catch errors, never output broken XML)
- Stripe webhooks must be idempotent — processing the same event twice must not double-grant access
- users.json writes must use file locking (flock) to prevent corruption under concurrent requests
- .env file must exist on server — if missing, site must fail loudly (not silently use wrong keys)

---

## Failure Playbooks

### VPS Goes Down
1. Check Hetzner status page
2. SSH into server, check Apache error logs: `tail -f /var/log/apache2/error.log`
3. Check disk space: `df -h`
4. Restart Apache if needed: `sudo systemctl restart apache2`
5. If unrecoverable: restore from GitHub (all code is in repo, users.json backed up separately)

### Stripe Webhook Fails
1. Check Stripe Dashboard → Developers → Webhooks for failed events
2. Check /logs/webhook.log on server for PHP errors
3. Stripe retries failed webhooks automatically for 72 hours
4. Manually re-trigger from Stripe dashboard if needed
5. Verify STRIPE_WEBHOOK_SECRET in .env matches Stripe dashboard signing secret

### User Reports No Access After Payment
1. Check Stripe Dashboard — confirm payment succeeded
2. Check webhook log — confirm webhook was received and processed
3. Check users.json — confirm subscription_status = "active" for their email
4. If webhook was missed: manually update user record + email Stripe to confirm retry
5. Offer manual access grant while investigating

### PHP Session Issues (Users Getting Logged Out)
1. Check PHP session.gc_maxlifetime setting (default 1440s = 24 min — likely too short)
2. Set session.gc_maxlifetime = 86400 in php.ini (24 hours)
3. Ensure session_start() is called consistently in header.php

---

## Backup Policy (MVP)
- All code: GitHub (primary backup)
- users.json: Manual daily backup to local machine until automated solution in Phase 2
- JSON condition files: GitHub (they are code, tracked in repo)
- .env file: Not in GitHub. Keep local copy. Document all required env vars in SECURITY.md.
