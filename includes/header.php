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
    <?php endif; ?>
    <link rel="stylesheet" href="/assets/css/main.css">
    <script defer src="/assets/js/main.js"></script>
</head>
<?php $bodyClassAttr = isset($bodyClass) && is_string($bodyClass) && $bodyClass !== '' ? ' class="' . app_h($bodyClass) . '"' : ''; ?>
<body<?= $bodyClassAttr; ?>>
<?php require __DIR__ . '/nav.php'; ?>
<main class="page-content">
