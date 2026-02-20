# Execution Plan: Programmatic SEO Pipeline

## Goal
Scale from 10 hand-crafted condition pages to 500+ auto-generated pages
without sacrificing content quality or SEO uniqueness.

## Non-Goals
- Replacing the nurse-authored clinical context (always manual)
- Auto-publishing without review
- Any content that fails the "is this genuinely useful?" test

## Acceptance Criteria
- [ ] Pipeline can generate a valid condition JSON from a CSV row + AI-assisted draft
- [ ] All generated pages pass the same SEO checklist as hand-crafted pages
- [ ] Each page has a unique clinical_context paragraph (nurse-reviewed, not templated)
- [ ] Internal linking auto-populates from related_conditions array
- [ ] sitemap.php automatically includes all new pages
- [ ] No two condition pages could be mistaken for each other with slug removed
- [ ] Google does not manual-action or sandbox the new pages (monitor Search Console)

## Design

### Content Creation Workflow
1. Identify condition slug and primary keyword (CSV input)
2. Use AI (Claude/ChatGPT) to draft prompt pack for condition
3. Nurse reviews and edits — especially clinical_context and why_it_works fields
4. Approved content entered into JSON file
5. File pushed to /data/conditions/ → page is live automatically
6. sitemap.php regenerates on next crawl

### Uniqueness Requirements Per Page
Every condition page MUST have three genuinely unique elements:
1. clinical_context — what patients with THIS condition specifically misunderstand
2. patient_fears — the 3–5 fears specific to THIS diagnosis (not generic health anxiety)
3. why_it_works explanations — clinical reasoning specific to THIS condition's prompts

These cannot be AI-generated without nurse review. They are the moat.

### Scale Target
- Phase 2: 100 conditions
- Phase 3: 500 conditions
- Long term: 1,000+ (every ICD-10 category with significant patient search volume)

## Steps

1. Build CSV template with all required JSON fields as columns
2. Identify 100 high-volume conditions by search demand (Google Keyword Planner + Ahrefs)
3. For each condition: AI draft → nurse review → JSON file
4. Build import script: CSV row → validated JSON file (checks all required fields present)
5. QA: spot-check 10% of pages for uniqueness and clinical accuracy
6. Push batch to /data/conditions/
7. Submit updated sitemap to Google Search Console
8. Monitor Search Console for indexing rate and any manual actions

## Validation
- Tests: Import script validates all required JSON fields before writing file
- SEO: Spot-check 10% with Rich Results Test
- Quality: Nurse reviews 100% of clinical_context paragraphs before publish
- Metrics: Track impressions per new page in Search Console after 60 days

## Risks

| Risk | Mitigation |
|------|------------|
| Thin content triggers Google spam filter | clinical_context is always human-written. Why_it_works is always nurse-reviewed. Minimum 800 words per page enforced in import script. |
| Conditions too similar to each other | patient_fears and clinical_context must differ meaningfully. Import script flags conditions with <30% unique word count vs existing pages. |
| Scaling faster than quality allows | Hard limit: nurse reviews all clinical_context before publish. Batch size max 20 per week. |

## Decision Log

| Date | Decision | Rationale |
|------|----------|-----------|
| 2025-01-01 | Nurse reviews all clinical content before publish | This is the product differentiator. Speed is not worth compromising it. |
| 2025-01-01 | CSV → JSON import script, not a CMS | Simplest tool that works. No new dependencies. Fits flat-file philosophy. |
