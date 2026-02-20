<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$pageTitle = 'Blog Post Not Found';
$metaDescription = 'PromptRN blog article.';
$canonicalUrl = app_url('/blog/' . $slug);
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1>Blog Template</h1>
    <p>Post slug: <code><?= app_h($slug); ?></code></p>
    <p>Connect this template to a flat-file post source in a later implementation step.</p>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
