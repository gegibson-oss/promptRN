<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$pageTitle = 'PromptRN Blog';
$metaDescription = 'Long-form health literacy content from PromptRN.';
$canonicalUrl = app_url('/blog');
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>PromptRN Blog</h1>
    <p>No published articles yet. Blog publishing is intentionally deferred until after MVP validation.</p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
