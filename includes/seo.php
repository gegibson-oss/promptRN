<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function seo_defaults(array $meta = []): array
{
    $siteMeta = app_read_json_file(app_data_path('meta.json'));
    $siteName = $siteMeta['site_name'] ?? 'PromptRN';
    $description = $siteMeta['site_description'] ?? 'Nurse-written AI health prompts for patients.';

    return [
        'title' => $meta['title'] ?? $siteName,
        'description' => $meta['description'] ?? $description,
        'canonical' => $meta['canonical'] ?? app_url($_SERVER['REQUEST_URI'] ?? '/'),
        'robots' => $meta['robots'] ?? 'index, follow',
    ];
}

function seo_condition_missing_fields(array $condition): array
{
    $requiredPaths = [
        'seo.page_title',
        'seo.meta_description',
        'seo.h1',
        'seo.primary_keyword',
        'last_updated',
        'faqs',
        'author.name',
        'author.credentials',
    ];

    $missing = [];
    foreach ($requiredPaths as $path) {
        $parts = explode('.', $path);
        $value = $condition;

        foreach ($parts as $part) {
            if (!is_array($value) || !array_key_exists($part, $value)) {
                $missing[] = $path;
                continue 2;
            }
            $value = $value[$part];
        }

        if ($value === '' || $value === [] || $value === null) {
            $missing[] = $path;
        }
    }

    return $missing;
}

function seo_render_meta_tags(array $meta): void
{
    $resolved = seo_defaults($meta);
    ?>
    <title><?= app_h((string) $resolved['title']); ?></title>
    <meta name="description" content="<?= app_h((string) $resolved['description']); ?>">
    <link rel="canonical" href="<?= app_h((string) $resolved['canonical']); ?>">
    <meta name="robots" content="<?= app_h((string) $resolved['robots']); ?>">
    <?php
}

function seo_render_condition_schemas(array $condition, array $siteMeta, string $canonicalUrl): void
{
    $authorName = $condition['author']['name'] ?? ($siteMeta['author_name'] ?? 'PromptRN RN Team');
    $authorCredentials = $condition['author']['credentials'] ?? 'Registered Nurse';

    $authorLinkedin = $condition['author']['linkedin'] ?? '';
    $authorExperience = $condition['author']['experience'] ?? '';
    
    $authorSchema = [
        '@type' => 'Person',
        'name' => $authorName,
        'jobTitle' => $authorCredentials,
    ];
    if ((string) $authorExperience !== '') {
        $authorSchema['description'] = $authorExperience;
    }
    if ((string) $authorLinkedin !== '') {
        $authorSchema['sameAs'] = $authorLinkedin;
    }

    $medicalSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalWebPage',
        'name' => $condition['seo']['h1'] ?? ($condition['condition_name'] ?? 'Condition Guide'),
        'headline' => $condition['seo']['h1'] ?? '',
        'description' => $condition['seo']['meta_description'] ?? '',
        'url' => $canonicalUrl,
        'dateModified' => $condition['last_updated'] ?? gmdate('Y-m-d'),
        'author' => $authorSchema,
        'about' => [
            '@type' => 'MedicalCondition',
            'name' => $condition['condition_name'] ?? 'Medical Condition',
            'description' => $condition['clinical_context'] ?? '',
        ],
    ];

    $faqEntities = [];
    $faqs = $condition['faqs'] ?? [];
    if (is_array($faqs)) {
        foreach ($faqs as $faq) {
            $question = (string) ($faq['question'] ?? '');
            $answer = (string) ($faq['answer'] ?? '');
            if ($question === '' || $answer === '') {
                continue;
            }

            $faqEntities[] = [
                '@type' => 'Question',
                'name' => $question,
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $answer,
                ],
            ];
        }
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => app_url('/'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Prompts',
                'item' => app_url('/prompts'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $condition['condition_name'] ?? 'Condition',
                'item' => $canonicalUrl,
            ],
        ],
    ];

    $schemas = [$medicalSchema, $breadcrumbSchema];
    if ($faqEntities !== []) {
        $schemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $faqEntities,
        ];
    }

    foreach ($schemas as $schema) {
        $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            continue;
        }
        echo '<script type="application/ld+json">' . $json . '</script>' . PHP_EOL;
    }
}
