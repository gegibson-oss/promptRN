# Mock UI Spec (Required Target)

Reference source: `/Users/grantgibson/Desktop/Burrow Base/PromptRN/PromptRN-web/promptrn-docs/Mock UI.html`

This mock is the visual and layout target for public-facing PromptRN pages, especially condition pages.
Implementation can be adapted to current PHP templates, but the core structure, hierarchy, and tone must match.

## Condition Page Requirements

1. Sticky header with:
- PromptRN wordmark
- Primary nav links
- Strong top-right CTA

2. Breadcrumb strip directly below header:
- Home -> Prompts -> Condition

3. Hero section with two-column layout (desktop):
- Left: condition-focused H1, trust badge, short explanatory intro, quick stats
- Right: purchase card with one-time and subscription options

4. Prompt presentation:
- Distinct free prompts section
- Distinct locked prompts section with visible locked titles
- Clear upgrade CTA adjacent to locked value

5. Trust + authority:
- RN attribution visible on page
- Clinical framing language that emphasizes patient clarity and safety

6. FAQ block:
- 4+ FAQs in expandable or clearly segmented format

7. Related condition links:
- Internal links rendered as a visible list/grid near lower page section

8. Footer:
- Clean legal/utility links and minimal closing brand copy

## Visual Direction Requirements

- Warm clinical palette (cream/warm neutrals + restrained accent tones)
- Serif + sans pairing with clear hierarchy (headline serif, body sans)
- Strong spacing rhythm, visible section boundaries, card-based prompt blocks
- Avoid generic default UI styling; preserve intentional editorial tone from mock

## Responsive Requirements

- Desktop: two-column hero and purchase framing
- Mobile: single-column flow with purchase CTA still visible early
- All core content remains server-rendered HTML (no JS dependency for content)

## Scope Notes

- This mock spec applies to public pages first (`/`, `/prompts`, `/prompts/[slug]`, `/about`)
- Members/auth/billing pages can be visually simpler for MVP as long as usability remains clear
