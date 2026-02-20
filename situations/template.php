<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$slug = strtolower(trim((string) ($_GET['slug'] ?? '')));
if (!app_validate_slug($slug)) {
    http_response_code(404);
    $slug = '';
}

$situation = app_read_json_file(app_data_path('situations/' . $slug . '.json'));

if ($slug === '' || $situation === []) {
    http_response_code(404);
    $pageTitle = 'Situation Not Found | PromptRN';
    $metaDescription = 'Requested situation content not found.';
    $canonicalUrl = app_url('/');
    $robots = 'index, follow';
    require __DIR__ . '/../includes/header.php';
    echo '<section><h1>Situation Not Found</h1><p>This situation hub is not available yet.</p></section>';
    require __DIR__ . '/../includes/footer.php';
    exit;
}

$pageTitle = (string) ($situation['seo_title'] ?? ($situation['title'] ?? 'Situation') . ' | PromptRN');
$metaDescription = (string) ($situation['seo_description'] ?? 'PromptRN situation hub.');
$canonicalUrl = app_url('/situations/' . $slug);
$robots = 'index, follow';

require __DIR__ . '/../includes/header.php';
?>
<section>
    <h1><?= app_h((string) ($situation['title'] ?? 'Situation')); ?></h1>
    <p><?= app_h((string) ($situation['summary'] ?? '')); ?></p>
</section>

<section>
    <h2>Related Conditions</h2>
    <ul>
        <?php foreach (($situation['related_conditions'] ?? []) as $conditionSlug): ?>
            <li><a href="/prompts/<?= app_h((string) $conditionSlug); ?>"><?= app_h((string) $conditionSlug); ?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
<?php require __DIR__ . '/../includes/footer.php'; ?>
