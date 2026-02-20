# Core Beliefs

These are the principles PromptRN is built on.
When a decision is unclear, return here.

---

## On Technology

**The repository is the system of record.**
If it is not in the repo, it does not exist.

**Favor boring, legible technology.**
PHP has been running the web for 30 years. Flat JSON files need no migration.
No framework means no framework upgrades, no breaking changes, no build pipeline.
The correct response to "should we add a database?" is "do we actually need one yet?"

**No build step is a feature.**
Push to GitHub. It deploys. That's it. Every layer of abstraction is a layer of failure.

**SEO is infrastructure.**
Every architectural decision has an SEO consequence. Fast HTML > JavaScript-rendered content.
Clean URLs > query strings. Flat files > database queries. These are the same decision.

**Enforce invariants mechanically.**
If an SEO field can be missing, it will be missing. Use code to make errors impossible,
not documentation to make them unlikely.

---

## On Product

**Solve the problem you lived.**
This product exists because nurses watch patients leave appointments confused and unprepared.
Every prompt should pass the test: would I have wanted a patient to use this?

**The patient is the customer.**
Not the hospital. Not the insurer. Not the clinic. The patient who got a diagnosis
this week and is sitting at home trying to understand what it means.

**Free prompts do the SEO work. Paid prompts pay the bills.**
Give away enough to prove real value. Gate enough to make a subscription obvious.
The free tier is not charity — it is the distribution strategy.

**Credibility is the moat.**
Any developer can generate health prompts. Only a nurse can write the clinical context,
catch the dangerous assumptions, and explain why a specific question gets a better answer.
The RN credentials on every page are not a design choice — they are the product.

---

## On Growth

**Programmatic SEO compounds. Cold outreach does not.**
One well-built condition page ranks forever. One cold email works once.
Build the asset, not the activity.

**Ship the smallest possible version first.**
10 condition pages manually validated beats 500 thin pages auto-generated.
Prove the template works before scaling it.

**The email list is owned distribution.**
Twitter followers can be banned. Search rankings can drop. Email is yours.
Every page should be trying to capture an email before the visitor leaves.

**Architectural drift is a bug.**
The moment the codebase stops matching this document, this document is wrong.
Update docs when behavior changes — not after, not never.

---

## On Scale

**One person can run this.**
Pieter Levels runs million-dollar businesses alone. The product should require
no employees, no agencies, no ongoing human labor beyond content creation.
Automation is not a nice-to-have — it is a design requirement.

**€4/month infrastructure is not a constraint. It is a goal.**
High margins fund the next project. Keep the stack cheap until revenue demands otherwise.
