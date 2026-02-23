<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/seo.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => app_is_https(),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

$seoMeta = [
    'title' => $pageTitle ?? null,
    'description' => $metaDescription ?? null,
    'canonical' => $canonicalUrl ?? null,
    'robots' => $robots ?? null,
];
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php seo_render_meta_tags($seoMeta); ?>
    <?php if (isset($extraHeadTags) && is_string($extraHeadTags) && $extraHeadTags !== ''): ?>
        <?= $extraHeadTags; ?>
    <?php
endif; ?>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style type="text/tailwindcss">
        :root {
          --cream: #FAF7F2;
          --warm-white: #FFFEF9;
          --ink: #1A1612;
          --ink-light: #4A4540;
          --ink-muted: #8A8480;
          --amber: #C8721A;
          --amber-dark: #A65D12;
          --amber-light: #F4E4CC;
          --amber-pale: #FDF6EC;
          --teal: #1A6B6B;
          --teal-light: #E8F4F4;
          --teal-dark: #124F4F;
          --border: #E8E2D8;
          --border-strong: #D4CCC0;
        }
        body {
          font-family: 'DM Sans', sans-serif;
          background-color: var(--cream);
          color: var(--ink);
          -webkit-font-smoothing: antialiased;
        }
        h1, h2, h3, h4, h5, h6, .font-serif {
          font-family: 'Lora', serif;
        }
        .btn-primary {
          @apply inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-bold rounded-lg shadow-sm text-white bg-[var(--amber)] hover:bg-[var(--amber-dark)] transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--amber)];
        }
        .btn-secondary {
          @apply inline-flex items-center justify-center px-8 py-4 border border-[var(--border-strong)] text-lg font-bold rounded-lg shadow-sm text-[var(--ink)] bg-white hover:bg-[var(--warm-white)] hover:border-[var(--amber)] transition-all duration-200 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--amber)];
        }
        .category-card:hover {
            border-color: var(--amber);
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
        }
    </style>
    <link rel="stylesheet" href="/assets/css/main.css">
    <script defer src="/assets/js/main.js"></script>
</head>
<?php
$baseClasses = 'bg-[var(--cream)] text-[var(--ink)] antialiased min-h-screen flex flex-col';
$bodyClassAttr = isset($bodyClass) && is_string($bodyClass) && $bodyClass !== '' ? ' class="' . $baseClasses . ' ' . app_h($bodyClass) . '"' : ' class="' . $baseClasses . '"';
?>
<body<?= $bodyClassAttr; ?>>
<?php require __DIR__ . '/nav.php'; ?>
<main class="flex-grow">
