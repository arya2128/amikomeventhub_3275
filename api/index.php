<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Setup folder writable di /tmp untuk Serverless Vercel
$storagePath = '/tmp/storage';
if (!is_dir($storagePath)) {
    @mkdir($storagePath, 0755, true);
    @mkdir($storagePath . '/framework/views', 0755, true);
    @mkdir($storagePath . '/framework/cache', 0755, true);
    @mkdir($storagePath . '/framework/cache/data', 0755, true);
    @mkdir($storagePath . '/framework/sessions', 0755, true);
    @mkdir($storagePath . '/logs', 0755, true);
    @mkdir($storagePath . '/app/public', 0755, true);
}

putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");

// Autoload Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
