<?php
/**
 * router.php
 * A local router script for the PHP built-in web server.
 * This file mimics the Apache .htaccess rewrite rules so you can test clean URLs locally.
 *
 * Usage: php -S localhost:8000 router.php
 */

if (php_sapi_name() !== 'cli-server') {
    die('This script should only be run using the PHP built-in web server (php -S).');
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = ltrim($path, '/');
$fullPath = __DIR__ . '/' . $path;

// 1. Serve static files directly if they exist
if ($path !== '' && file_exists($fullPath) && is_file($fullPath)) {
    return false; // let PHP built-in server handle the file directly (images, css, etc.)
}

// 2. Exact match rules from .htaccess
if ($path === 'sitemap.xml') {
    require __DIR__ . '/sitemap.php';
    exit;
}

if (preg_match('#^about/?$#', $path)) {
    require __DIR__ . '/about.php';
    exit;
}

if (preg_match('#^blog/?$#', $path)) {
    require __DIR__ . '/blog/index.php';
    exit;
}

if (preg_match('#^blog/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    $_GET['situation'] = ''; // Ensure unset variables are not polluted
    require __DIR__ . '/blog/template.php';
    exit;
}

if (preg_match('#^course/?$#', $path)) {
    require __DIR__ . '/course.php';
    exit;
}

if (preg_match('#^organizations/?$#', $path)) {
    require __DIR__ . '/organizations.php';
    exit;
}

if (preg_match('#^tools/prompt-generator/?$#', $path)) {
    require __DIR__ . '/tools/prompt-generator.php';
    exit;
}

if (preg_match('#^members/dashboard/?$#', $path)) {
    require __DIR__ . '/members/dashboard.php';
    exit;
}

if (preg_match('#^members/library/?$#', $path)) {
    require __DIR__ . '/members/library.php';
    exit;
}

if (preg_match('#^members/account/?$#', $path)) {
    require __DIR__ . '/members/account.php';
    exit;
}

// 3. Auth routes
if (preg_match('#^auth/(login|register|logout|forgot-password)/?$#', $path, $matches)) {
    require __DIR__ . '/auth/' . $matches[1] . '.php';
    exit;
}

// 4. Billing routes
if (preg_match('#^billing/(checkout|success|webhook)/?$#', $path, $matches)) {
    require __DIR__ . '/billing/' . $matches[1] . '.php';
    exit;
}

// 5. Prompts routes
if (preg_match('#^prompts/?$#', $path)) {
    require __DIR__ . '/prompts/index.php';
    exit;
}

if (preg_match('#^prompts/([a-z0-9-]+)/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    $_GET['situation'] = $matches[2];
    require __DIR__ . '/prompts/template.php';
    exit;
}

if (preg_match('#^prompts/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    $_GET['situation'] = '';
    require __DIR__ . '/prompts/template.php';
    exit;
}

if (preg_match('#^situations/([a-z0-9-]+)/?$#', $path, $matches)) {
    $_GET['slug'] = $matches[1];
    require __DIR__ . '/situations/template.php';
    exit;
}

// 6. Fallback block internal or sensitive
if (preg_match('#^(data|private|includes)(/|$)#', $path)) {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

// 7. General index.php fallback for directory
if (is_dir($fullPath) && file_exists($fullPath . '/index.php')) {
    require $fullPath . '/index.php';
    exit;
}

// If no route matches, return 404
http_response_code(404);
echo "404 Not Found";
exit;