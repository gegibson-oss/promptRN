<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$pageTitle = 'About PromptRN';
$metaDescription = 'PromptRN delivers nurse-written AI prompts that help patients understand health conditions and next steps.';
$canonicalUrl = app_url('/about');
$robots = 'index, follow';

require __DIR__ . '/includes/header.php';
?>
<section>
    <h1>About PromptRN</h1>
    <p>PromptRN exists to close the gap between a short clinic visit and the real questions patients have once they get home.</p>
    <p>Every prompt pack is written from a nursing perspective focused on patient understanding, safety, and practical follow-through.</p>
</section>

<section>
    <h2>Author</h2>
    <p><strong>Sarah Mitchell RN</strong> is a Registered Nurse with 12 years of experience in endocrinology, chronic disease education, and primary care coordination.</p>
    <p>Her work has centered on helping newly diagnosed patients understand medications, lab trends, symptom escalation, and behavior change in realistic daily routines.</p>
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
