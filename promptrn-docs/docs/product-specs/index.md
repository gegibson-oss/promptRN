# Product Specs Index

- MVP Scope: mvp.md
- Roadmap: roadmap.md

---

## Personas

### Primary: The Newly Diagnosed Patient
- Just received a diagnosis (diabetes, hypertension, thyroid condition, etc.)
- Left their appointment confused, overwhelmed, and with unanswered questions
- Already using ChatGPT or Claude to try to understand their condition — badly
- Motivated, scared, and willing to pay for something that actually helps
- Finds PromptRN via Google search: "AI prompts for [condition] diagnosis"

### Secondary: The Chronic Illness Manager
- Living with a long-term condition and managing it ongoing
- Wants better conversations with specialists, not just GPs
- Uses AI regularly and understands its value
- Most likely to subscribe (vs. one-time purchase) because their need is ongoing

### Tertiary: The Caregiver
- Managing a parent's, child's, or partner's health
- Often more proactive than the patient themselves
- Searches on behalf of someone else: "AI prompts for [condition] for family member"
- Separate product line opportunity post-MVP

---

## Key User Journeys

### Journey 1: Organic → Free → Paid (Core Funnel)
1. Patient searches "AI prompts for type 2 diabetes diagnosis"
2. Lands on /prompts/type-2-diabetes
3. Reads clinical context, tries 3 free prompts — gets real value
4. Sees 9 locked prompts with visible titles
5. Clicks "Unlock All 12 Prompts — $9" or "Get Everything $17/mo"
6. Stripe checkout → success.php → immediate access
7. Email captured, ConvertKit welcome sequence starts

### Journey 2: Free Tool → Email → Paid
1. Patient searches "health AI prompt generator"
2. Lands on /tools/prompt-generator
3. Enters condition, gets 3 free prompts
4. Email gate to get full results
5. Receives email with prompts + link to full condition page
6. Converts to paid from email or return visit

### Journey 3: Direct Subscribe (High Intent)
1. Patient searches "nurse written AI health prompts"
2. Lands on homepage or /prompts index
3. Understands value prop immediately
4. Subscribes directly for full library access
5. Browses library for their specific condition

---

## Non-Functional Requirements

- All public pages: PageSpeed score ≥ 90 (mobile)
- All public pages: Fully rendered HTML, no JS-dependent content
- Stripe webhook processing: < 2 seconds
- Session-based auth: PHP native sessions, no JWT complexity
- Sitemap: Auto-regenerated, includes all condition and situation pages
- Uptime target: 99.5% (single VPS, acceptable for early stage)
- HTTPS: Required. Let's Encrypt via Certbot.
