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
    $condition = app_read_json_file((string)$filePath);
    $slug = (string)($condition['slug'] ?? pathinfo((string)$filePath, PATHINFO_FILENAME));
    if (!app_validate_slug($slug)) {
        continue;
    }

    $conditionMtime = @filemtime((string)$filePath);
    if ($conditionMtime === false) {
        $conditionMtime = time();
    }

    $lastmodCondition = gmdate('Y-m-d', $conditionMtime);

    $urls[] = [
        'loc' => app_url('/prompts/' . $slug),
        'lastmod' => $lastmodCondition,
    ];

    $situations = app_situation_slugs();
    foreach ($situations as $sitSlug) {
        $sitSlug = (string)$sitSlug;

        $sitPath = app_data_path('situations/' . $sitSlug . '.json');
        if (is_file($sitPath)) {
            $sitMtime = @filemtime($sitPath);
            if ($sitMtime === false) {
                $sitMtime = time();
            }

            $comboMtime = max($conditionMtime, $sitMtime);

            $urls[] = [
                'loc' => app_url('/prompts/' . $slug . '/' . $sitSlug),
                'lastmod' => gmdate('Y-m-d', $comboMtime),
            ];
        }
    }
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($urls as $url): ?>
    <url>
        <loc>
            <?= app_h((string)$url['loc']); ?>
        </loc>
        <lastmod>
            <?= app_h((string)$url['lastmod']); ?>
        </lastmod>
    </url>
    <?php
endforeach; ?>
</urlset>