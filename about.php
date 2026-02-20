<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About PromptRN';
$metaDescription = 'PromptRN delivers nurse-written AI prompts that help patients understand health conditions and next steps.';
$canonicalUrl = app_url('/about');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<section class="about-hero">
    <span class="hero-badge">About PromptRN</span>
    <h1>About PromptRN</h1>
    <p class="hero-lead">PromptRN exists to close the gap between a short clinic visit and the real questions patients still have once they get home.</p>
    <p class="hero-lead">Every prompt pack is written from a nursing perspective focused on patient understanding, safety, and practical follow-through.</p>
</section>

<section>
    <h2>Author and Credentials</h2>
    <div class="about-grid">
        <article class="about-card">
            <h3>Sarah Mitchell RN</h3>
            <p>Registered Nurse with 12 years in endocrinology, chronic disease education, and primary care coordination.</p>
        </article>
        <article class="about-card">
            <h3>Clinical Focus</h3>
            <p>Helping newly diagnosed patients understand medications, labs, symptom escalation, and next-step decisions.</p>
        </article>
        <article class="about-card">
            <h3>PromptRN Mission</h3>
            <p>Make AI outputs safer and more useful for patients through clinically grounded prompt structure.</p>
        </article>
    </div>
</section>

<section>
    <h2>Clinical Philosophy</h2>
    <ul>
        <li>Plain language over jargon.</li>
        <li>Actionable next steps over generic advice.</li>
        <li>Patient confidence and informed questions as core outcomes.</li>
        <li>AI as an education aid, never a replacement for medical care.</li>
    </ul>
</section>

<section>
    <h2>Important Safety Note</h2>
    <p>PromptRN content is educational and preparation-focused. It does not diagnose, prescribe, or replace care from licensed clinicians.</p>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
