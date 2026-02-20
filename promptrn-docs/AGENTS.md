# AGENTS.md

## Purpose
This repository is the system of record for PromptRN (promptrn.com).
AGENTS.md is a map, not a full manual. Follow links to sources of truth.

PromptRN is a nurse-written AI prompt library for patients.
Built Pieter Levels style: one person, PHP flat files, no framework, ship fast.

## Where to Start
- Architecture overview: ARCHITECTURE.md
- Product requirements: docs/product-specs/index.md
- Active execution plans: docs/exec-plans/active/
- Quality & reliability standards: docs/QUALITY_SCORE.md, docs/RELIABILITY.md
- Security constraints: docs/SECURITY.md
- Design beliefs: docs/design-docs/core-beliefs.md

## Operating Principles
- Repository is the system of record.
- All behavior must be discoverable in-repo.
- Plans, decisions, and constraints must be versioned.
- Prefer boring, simple, composable technology. No frameworks.
- PHP + flat JSON files. No database until absolutely necessary.
- SEO is infrastructure, not an afterthought.
- Ship ugly. Validate fast. Polish what works.

## How Work Happens
1. Select or create an execution plan in docs/exec-plans/active/
2. Implement changes aligned with ARCHITECTURE.md
3. Update docs when behavior or constraints change
4. Ensure all pages pass PageSpeed 90+, structured data validates, sitemap updates
5. Push to GitHub — auto-deploys to Hetzner VPS

## Stack at a Glance
- Language: PHP 8.x (no framework)
- Data: JSON flat files in /data
- Hosting: Hetzner VPS (~€4/month) or Render.com
- Payments: Stripe (subscriptions + one-time purchases)
- Email: ConvertKit
- Version control: GitHub (source of truth + deploy trigger)
- Build: None. PHP renders HTML directly. No build step.
