<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

function cli_fail(string $message): never
{
    fwrite(STDERR, $message . PHP_EOL);
    exit(1);
}

function csv_rows(string $path): array
{
    $handle = fopen($path, 'rb');
    if ($handle === false) {
        cli_fail('Could not open CSV: ' . $path);
    }

    $headers = fgetcsv($handle, 0, ',', '"', '\\');
    if (!is_array($headers) || $headers === []) {
        fclose($handle);
        cli_fail('CSV header row missing.');
    }

    $headers = array_map(static fn ($h) => trim((string) $h), $headers);

    $rows = [];
    while (($row = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
        if ($row === [null] || $row === []) {
            continue;
        }

        $assoc = [];
        foreach ($headers as $index => $header) {
            $assoc[$header] = trim((string) ($row[$index] ?? ''));
        }

        $rows[] = $assoc;
    }

    fclose($handle);
    return $rows;
}

function parse_pipe_list(string $value): array
{
    $parts = array_map('trim', explode('|', $value));
    return array_values(array_filter($parts, static fn ($p) => $p !== ''));
}

function parse_prompt_blocks(string $value): array
{
    $prompts = [];
    $chunks = array_values(array_filter(array_map('trim', explode('||', $value)), static fn ($p) => $p !== ''));

    foreach ($chunks as $index => $chunk) {
        $parts = array_map('trim', explode('::', $chunk, 3));
        if (count($parts) !== 3) {
            continue;
        }

        [$title, $prompt, $why] = $parts;
        if ($title === '' || $prompt === '' || $why === '') {
            continue;
        }

        $prompts[] = [
            'id' => 'p' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
            'title' => $title,
            'prompt' => $prompt,
            'why_it_works' => $why,
        ];
    }

    return $prompts;
}

function parse_faq_blocks(string $value): array
{
    $faqs = [];
    $chunks = array_values(array_filter(array_map('trim', explode('||', $value)), static fn ($p) => $p !== ''));

    foreach ($chunks as $chunk) {
        $parts = array_map('trim', explode('::', $chunk, 2));
        if (count($parts) !== 2) {
            continue;
        }

        [$question, $answer] = $parts;
        if ($question === '' || $answer === '') {
            continue;
        }

        $faqs[] = [
            'question' => $question,
            'answer' => $answer,
        ];
    }

    return $faqs;
}

function words(string $text): array
{
    $normalized = strtolower((string) preg_replace('/[^a-z0-9\s]+/i', ' ', $text));
    $tokens = preg_split('/\s+/', trim($normalized)) ?: [];
    $filtered = [];

    foreach ($tokens as $token) {
        if (strlen($token) >= 3) {
            $filtered[] = $token;
        }
    }

    return $filtered;
}

function page_word_count(array $condition): int
{
    $parts = [
        (string) ($condition['clinical_context'] ?? ''),
        implode(' ', $condition['patient_fears'] ?? []),
    ];

    foreach (($condition['free_prompts'] ?? []) as $prompt) {
        $parts[] = (string) ($prompt['title'] ?? '');
        $parts[] = (string) ($prompt['prompt'] ?? '');
        $parts[] = (string) ($prompt['why_it_works'] ?? '');
    }

    foreach (($condition['paid_prompts'] ?? []) as $prompt) {
        $parts[] = (string) ($prompt['title'] ?? '');
        $parts[] = (string) ($prompt['prompt'] ?? '');
        $parts[] = (string) ($prompt['why_it_works'] ?? '');
    }

    foreach (($condition['faqs'] ?? []) as $faq) {
        $parts[] = (string) ($faq['question'] ?? '');
        $parts[] = (string) ($faq['answer'] ?? '');
    }

    return count(words(implode(' ', $parts)));
}

function uniqueness_token_set(array $condition): array
{
    $parts = [(string) ($condition['clinical_context'] ?? '')];
    $parts[] = implode(' ', $condition['patient_fears'] ?? []);

    foreach (array_merge($condition['free_prompts'] ?? [], $condition['paid_prompts'] ?? []) as $prompt) {
        $parts[] = (string) ($prompt['why_it_works'] ?? '');
    }

    return array_values(array_unique(words(implode(' ', $parts))));
}

function uniqueness_ratio(array $candidateTokens, array $existingTokens): float
{
    if ($candidateTokens === []) {
        return 0.0;
    }

    $candidateSet = array_fill_keys($candidateTokens, true);
    $existingSet = array_fill_keys($existingTokens, true);
    $intersection = array_intersect_key($candidateSet, $existingSet);

    $overlap = count($intersection);
    return 1.0 - ($overlap / count($candidateSet));
}

function existing_condition_tokens(string $candidateSlug): array
{
    $tokenMap = [];

    foreach (glob(app_data_path('conditions/*.json')) ?: [] as $path) {
        $condition = app_read_json_file((string) $path);
        $slug = (string) ($condition['slug'] ?? pathinfo((string) $path, PATHINFO_FILENAME));
        if ($slug === '' || $slug === $candidateSlug) {
            continue;
        }

        $tokenMap[$slug] = uniqueness_token_set($condition);
    }

    return $tokenMap;
}

function validate_condition(array $condition): array
{
    $errors = [];

    if (!app_validate_slug((string) ($condition['slug'] ?? ''))) {
        $errors[] = 'Invalid slug format.';
    }

    $required = [
        'condition_name',
        'clinical_context',
        'stripe_product_id',
        'last_updated',
    ];

    foreach ($required as $field) {
        if (!isset($condition[$field]) || trim((string) $condition[$field]) === '') {
            $errors[] = 'Missing required field: ' . $field;
        }
    }

    $seo = $condition['seo'] ?? [];
    foreach (['page_title', 'meta_description', 'h1', 'primary_keyword'] as $field) {
        if (!is_array($seo) || trim((string) ($seo[$field] ?? '')) === '') {
            $errors[] = 'Missing SEO field: seo.' . $field;
        }
    }

    if (count($condition['free_prompts'] ?? []) < 3) {
        $errors[] = 'At least 3 free_prompts required.';
    }

    if (count($condition['paid_prompts'] ?? []) < 9) {
        $errors[] = 'At least 9 paid_prompts required.';
    }

    if (count($condition['faqs'] ?? []) < 4) {
        $errors[] = 'At least 4 FAQs required.';
    }

    $wordCount = page_word_count($condition);
    if ($wordCount < 800) {
        $errors[] = 'Minimum 800-word page content required. Found: ' . $wordCount;
    }

    $candidateTokens = uniqueness_token_set($condition);
    $existingMap = existing_condition_tokens((string) ($condition['slug'] ?? ''));
    foreach ($existingMap as $slug => $tokens) {
        $ratio = uniqueness_ratio($candidateTokens, $tokens);
        if ($ratio < 0.30) {
            $errors[] = 'Uniqueness too low vs ' . $slug . ' (' . number_format($ratio * 100, 1) . '% unique).';
        }
    }

    return $errors;
}

function build_condition_from_row(array $row): array
{
    $slug = strtolower((string) ($row['slug'] ?? ''));
    $freePrompts = parse_prompt_blocks((string) ($row['free_prompts'] ?? ''));
    $paidPrompts = parse_prompt_blocks((string) ($row['paid_prompts'] ?? ''));

    $offset = count($freePrompts);
    foreach ($paidPrompts as $index => $prompt) {
        $paidPrompts[$index]['id'] = 'p' . str_pad((string) ($offset + $index + 1), 2, '0', STR_PAD_LEFT);
    }

    return [
        'slug' => $slug,
        'condition_name' => (string) ($row['condition_name'] ?? ''),
        'situation' => (string) ($row['situation'] ?? 'newly-diagnosed'),
        'seo' => [
            'page_title' => (string) ($row['seo_page_title'] ?? ''),
            'meta_description' => (string) ($row['seo_meta_description'] ?? ''),
            'h1' => (string) ($row['seo_h1'] ?? ''),
            'primary_keyword' => (string) ($row['seo_primary_keyword'] ?? ''),
            'secondary_keywords' => parse_pipe_list((string) ($row['seo_secondary_keywords'] ?? '')),
        ],
        'clinical_context' => (string) ($row['clinical_context'] ?? ''),
        'patient_fears' => parse_pipe_list((string) ($row['patient_fears'] ?? '')),
        'author' => [
            'name' => (string) ($row['author_name'] ?? ''),
            'credentials' => (string) ($row['author_credentials'] ?? ''),
            'experience' => (string) ($row['author_experience'] ?? ''),
            'linkedin' => (string) ($row['author_linkedin'] ?? ''),
        ],
        'free_prompts' => $freePrompts,
        'paid_prompts' => $paidPrompts,
        'faqs' => parse_faq_blocks((string) ($row['faqs'] ?? '')),
        'related_conditions' => parse_pipe_list((string) ($row['related_conditions'] ?? '')),
        'related_situations' => parse_pipe_list((string) ($row['related_situations'] ?? '')),
        'stripe_product_id' => (string) ($row['stripe_product_id'] ?? ''),
        'pack_price_usd' => (int) ($row['pack_price_usd'] ?? 9),
        'last_updated' => (string) ($row['last_updated'] ?? gmdate('Y-m-d')),
    ];
}

function update_meta_index(array $condition): bool
{
    $metaPath = app_data_path('meta.json');
    $meta = app_read_json_file($metaPath);
    if (!isset($meta['conditions']) || !is_array($meta['conditions'])) {
        $meta['conditions'] = [];
    }

    $entry = [
        'slug' => $condition['slug'],
        'condition_name' => $condition['condition_name'],
        'situation' => $condition['situation'],
        'prompt_count' => count($condition['free_prompts']) + count($condition['paid_prompts']),
        'last_updated' => $condition['last_updated'],
    ];

    $updated = false;
    foreach ($meta['conditions'] as $index => $existing) {
        if (($existing['slug'] ?? '') === $condition['slug']) {
            $meta['conditions'][$index] = $entry;
            $updated = true;
            break;
        }
    }

    if (!$updated) {
        $meta['conditions'][] = $entry;
    }

    usort($meta['conditions'], static fn ($a, $b) => strcmp((string) ($a['condition_name'] ?? ''), (string) ($b['condition_name'] ?? '')));

    return app_write_json_file($metaPath, $meta);
}

$argv = $_SERVER['argv'] ?? [];
if (count($argv) < 2) {
    cli_fail('Usage: php scripts/import-conditions.php /path/to/conditions.csv [--dry-run]');
}

$csvPath = $argv[1];
$dryRun = in_array('--dry-run', $argv, true);

if (!is_readable($csvPath)) {
    cli_fail('CSV not readable: ' . $csvPath);
}

$rows = csv_rows($csvPath);
if ($rows === []) {
    cli_fail('CSV contains no data rows.');
}

$total = 0;
foreach ($rows as $line => $row) {
    $condition = build_condition_from_row($row);
    $errors = validate_condition($condition);

    if ($errors !== []) {
        fwrite(STDERR, 'Row ' . ($line + 2) . ' (' . ($condition['slug'] ?? 'unknown') . ') failed:' . PHP_EOL);
        foreach ($errors as $error) {
            fwrite(STDERR, '  - ' . $error . PHP_EOL);
        }
        exit(1);
    }

    if (!$dryRun) {
        $outputPath = app_condition_path((string) $condition['slug']);
        if (!app_write_json_file($outputPath, $condition)) {
            cli_fail('Failed writing condition file: ' . $outputPath);
        }

        if (!update_meta_index($condition)) {
            cli_fail('Failed updating meta.json for slug: ' . $condition['slug']);
        }
    }

    $total++;
}

$mode = $dryRun ? 'Dry run complete' : 'Import complete';
echo $mode . ': ' . $total . ' row(s) validated.' . PHP_EOL;
