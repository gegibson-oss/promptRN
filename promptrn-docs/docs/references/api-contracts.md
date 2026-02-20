# API Contracts & Data Schemas

---

## Condition JSON Schema

File location: /data/conditions/[slug].json
One file per condition. Slug must be URL-safe, hyphenated, lowercase.

```json
{
  "slug": "type-2-diabetes-diagnosis",
  "condition_name": "Type 2 Diabetes",
  "situation": "newly-diagnosed",

  "seo": {
    "page_title": "AI Prompts for Type 2 Diabetes Diagnosis | Nurse-Written — PromptRN",
    "meta_description": "Confused after your Type 2 diabetes diagnosis? 12 nurse-written AI prompts to understand your condition, medications, and next steps. Written by an RN with 12 years experience.",
    "h1": "12 AI Prompts to Help You Understand Your Type 2 Diabetes Diagnosis",
    "primary_keyword": "AI prompts for type 2 diabetes diagnosis",
    "secondary_keywords": [
      "ChatGPT prompts diabetes",
      "understand diabetes diagnosis",
      "newly diagnosed type 2 diabetes help"
    ]
  },

  "clinical_context": "Most patients leave their diabetes diagnosis appointment with an A1C number and a prescription but without understanding what either means. This is not your doctor's fault — they had 10 minutes. These prompts are designed to fill the gap: they help you ask AI the specific questions that turn a frightening diagnosis into something you understand and can act on.",

  "patient_fears": [
    "Will I need insulin injections?",
    "Is this my fault?",
    "Can I reverse this or is it permanent?",
    "What do my blood sugar numbers actually mean?",
    "How will this affect the rest of my life?"
  ],

  "author": {
    "name": "Sarah Mitchell RN",
    "credentials": "Registered Nurse",
    "experience": "12 years in endocrinology and primary care",
    "linkedin": "https://linkedin.com/in/sarahmitchellrn"
  },

  "free_prompts": [
    {
      "id": "p01",
      "title": "Understanding What's Actually Happening in Your Body",
      "prompt": "I was just diagnosed with Type 2 diabetes and my A1C is [insert your number]. Please explain in simple terms what this number means, what is currently happening in my body, and what the difference is between someone who has prediabetes versus Type 2. I have no medical background so please avoid jargon.",
      "why_it_works": "Patients almost universally don't understand what their A1C represents. Anchoring the explanation to your specific number produces a personalised answer. The no-jargon instruction is critical — without it, AI defaults to clinical language that leaves patients more confused."
    },
    {
      "id": "p02",
      "title": "Understanding Your Medications",
      "prompt": "My doctor prescribed [medication name, e.g. Metformin 500mg twice daily]. Please explain: what this medication actually does in my body, why it was chosen over other options, what side effects I should genuinely watch for versus ones that are rare, and what I should do if I experience stomach upset in the first few weeks.",
      "why_it_works": "Metformin GI side effects cause many newly diagnosed patients to stop taking their medication before it can work. This prompt surfaces the critical clinical nuance that GI effects are common and temporary — information that makes a real difference in medication adherence."
    },
    {
      "id": "p03",
      "title": "What Questions to Ask at Your Next Appointment",
      "prompt": "I have a follow-up appointment about my Type 2 diabetes. My A1C is [number], I am [age] years old, and I also have [any other conditions]. Give me a specific list of the 10 most important questions I should ask, ordered by priority, including questions about my target A1C, how we will track progress, warning signs, and whether my other conditions affect my diabetes management.",
      "why_it_works": "Most patients go into follow-up appointments without a prepared list and leave having only answered the doctor's questions. This prompt creates a personalised agenda. The priority ordering ensures the most important questions get asked if time runs out."
    }
  ],

  "paid_prompts": [
    {
      "id": "p04",
      "title": "Decoding Your Blood Sugar Numbers at Home",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p05",
      "title": "Building a Diabetes-Friendly Meal Plan (Your Way)",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p06",
      "title": "Understanding Long-Term Complications (and How to Prevent Them)",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p07",
      "title": "Can I Reverse My Diabetes? Asking the Right Way",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p08",
      "title": "Explaining Your Diagnosis to Family Members",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p09",
      "title": "Exercise, Blood Sugar, and What You Need to Know First",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p10",
      "title": "What Warning Signs Mean You Need to Call Your Doctor",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p11",
      "title": "Navigating Diabetes and Mental Health",
      "prompt": "...",
      "why_it_works": "..."
    },
    {
      "id": "p12",
      "title": "Tracking Progress and Celebrating Small Wins",
      "prompt": "...",
      "why_it_works": "..."
    }
  ],

  "faqs": [
    {
      "question": "Can AI actually help me understand my diabetes diagnosis?",
      "answer": "Yes, when used with the right prompts. The key is asking specific, structured questions rather than vague ones. These nurse-designed prompts are built around the exact gaps in understanding that patients have after diagnosis."
    },
    {
      "question": "Will I need insulin for Type 2 diabetes?",
      "answer": "Not necessarily at first. Type 2 is often managed initially with lifestyle changes and oral medications like Metformin. Whether insulin eventually becomes part of your treatment depends on how your body responds over time."
    },
    {
      "question": "Is this a substitute for talking to my doctor?",
      "answer": "No. These prompts help you understand your diagnosis and prepare better questions for your doctor. They are education and preparation, not medical advice."
    },
    {
      "question": "Which AI should I use these prompts with?",
      "answer": "These prompts work with ChatGPT (GPT-4 or better), Claude, and Gemini. Always verify important health information with your healthcare provider."
    }
  ],

  "related_conditions": [
    "prediabetes",
    "hypertension",
    "high-cholesterol",
    "metabolic-syndrome"
  ],

  "related_situations": [
    "understanding-lab-results",
    "talking-to-your-doctor",
    "managing-chronic-illness",
    "medication-questions"
  ],

  "stripe_product_id": "prod_xxxxx",
  "pack_price_usd": 9,
  "last_updated": "2025-01-15"
}
```

---

## User Record Schema

File location: /private/users.json (OUTSIDE web root)

```json
{
  "users": [
    {
      "id": "usr_01",
      "email": "patient@example.com",
      "password_hash": "$2y$10$...",
      "created_at": "2025-01-15T10:00:00Z",
      "subscription_status": "active",
      "subscription_plan": "monthly",
      "subscription_expires": "2025-02-15T10:00:00Z",
      "stripe_customer_id": "cus_xxxxx",
      "stripe_subscription_id": "sub_xxxxx",
      "purchased_packs": [
        "type-2-diabetes-diagnosis",
        "hypertension"
      ],
      "convertkit_subscriber_id": "12345"
    }
  ]
}
```

subscription_status values: "free" | "active" | "cancelled" | "past_due"
subscription_plan values: "monthly" | "annual" | null

---

## Stripe Products

| Product | Stripe Type | Price | Description |
|---------|-------------|-------|-------------|
| Single Condition Pack | payment_intent | $9 | One-time access to one condition's full 12-prompt pack |
| Monthly Subscription | subscription | $17/mo | Full library access, all conditions |
| Annual Subscription | subscription | $99/yr | Full library access, billed annually |

---

## Stripe Webhook Events Handled

| Event | Action |
|-------|--------|
| payment_intent.succeeded | Add condition slug to user.purchased_packs |
| customer.subscription.created | Set subscription_status = "active", set expires |
| customer.subscription.updated | Update plan and expires date |
| customer.subscription.deleted | Set subscription_status = "cancelled" |
| invoice.payment_failed | Set subscription_status = "past_due", trigger email |

---

## meta.json Schema

File location: /data/meta.json

```json
{
  "site_name": "PromptRN",
  "site_url": "https://promptrn.com",
  "site_description": "Nurse-written AI health prompts for patients",
  "author_name": "Sarah Mitchell RN",
  "conditions": [
    {
      "slug": "type-2-diabetes-diagnosis",
      "condition_name": "Type 2 Diabetes",
      "situation": "newly-diagnosed",
      "prompt_count": 12,
      "last_updated": "2025-01-15"
    }
  ],
  "situations": [
    "newly-diagnosed",
    "understanding-lab-results",
    "talking-to-your-doctor",
    "managing-chronic-illness",
    "medication-questions",
    "preparing-for-surgery"
  ]
}
```
