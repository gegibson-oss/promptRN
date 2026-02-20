<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: application/xml; charset=utf-8');

$conditionFiles = glob(app_data_path('conditions/*.json')) ?: [];

$urls = [
    ['loc' => app_url('/'), 'lastmod' => gmdate('Y-m-d')],
    ['loc' => app_url('/about'), 'lastmod' => gmdate('Y-m-d')],
    ['loc' => app_url('/prompts'), 'lastmod' => gmdate('Y-m-d')],
    ['loc' => app_url('/tools/prompt-generator'), 'lastmod' => gmdate('Y-m-d')],
    ['loc' => app_url('/blog'), 'lastmod' => gmdate('Y-m-d')],
];

foreach ($conditionFiles as $filePath) {
    $condition = app_read_json_file((string) $filePath);
    $slug = (string) ($condition['slug'] ?? pathinfo((string) $filePath, PATHINFO_FILENAME));
    if (!app_validate_slug($slug)) {
        continue;
    }

    $lastmod = (string) ($condition['last_updated'] ?? gmdate('Y-m-d'));
    if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $lastmod)) {
        $lastmod = gmdate('Y-m-d');
    }

    $urls[] = [
        'loc' => app_url('/prompts/' . $slug),
        'lastmod' => $lastmod,
    ];
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?= app_h($url['loc']); ?></loc>
    <lastmod><?= app_h($url['lastmod']); ?></lastmod>
  </url>
<?php endforeach; ?>
</urlset>
