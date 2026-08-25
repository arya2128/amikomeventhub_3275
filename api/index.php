<?php

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;

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
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
}
if (!is_dir($storagePath . '/bootstrap/cache')) {
    @mkdir($storagePath . '/bootstrap/cache', 0755, true);
}

// Cari path CA certificate yang valid di server
$caCandidates = [
    '/etc/pki/tls/certs/ca-bundle.crt',
    '/etc/ssl/certs/ca-certificates.crt',
    '/etc/ssl/certs/ca-bundle.crt',
    '/etc/ssl/cert.pem',
    __DIR__ . '/cacert.pem',
    __DIR__ . '/../cacert.pem',
];

$validCa = null;
foreach ($caCandidates as $path) {
    if (file_exists($path) && filesize($path) > 1000) {
        $validCa = $path;
        break;
    }
}

// Injeksi environment variables yang valid
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("SESSION_DRIVER=database");
putenv("SESSION_LIFETIME=120");
putenv("SESSION_EXPIRE_ON_CLOSE=false");
putenv("SESSION_SECURE_COOKIE=true");
putenv("SESSION_SAME_SITE=lax");
putenv("SESSION_DOMAIN=");
putenv("SESSION_COOKIE=amikomeventhub_session");
putenv("BCRYPT_ROUNDS=12");
putenv("CACHE_STORE=array");
putenv("LOG_CHANNEL=stderr");
putenv("APP_URL=https://amikomeventhub-3275.vercel.app");
putenv("GOOGLE_REDIRECT_URI=https://amikomeventhub-3275.vercel.app/auth/google/callback");
putenv("APP_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
putenv("APP_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
putenv("APP_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
putenv("APP_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
putenv("APP_EVENTS_CACHE={$storagePath}/bootstrap/cache/events.php");
putenv("DB_CONNECTION=mysql");
putenv("DB_HOST=gateway01.ap-southeast-1.prod.aws.tidbcloud.com");
putenv("DB_PORT=4000");
putenv("DB_DATABASE=test");
putenv("DB_USERNAME=2wzR4q2HyxnRSkE.root");
putenv("DB_PASSWORD=zUMvCAU2I2hW5bq6");
putenv("APP_KEY=base64:NUkjAOZDMmnte8y9PpfFobjXekgkvs/x3gYdhCSohwA=");
putenv("APP_DEBUG=true");
if ($validCa) {
    putenv("MYSQL_ATTR_SSL_CA={$validCa}");
}

// Autoload Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

// Rebind PackageManifest ke folder /tmp yang writable
$app->singleton(PackageManifest::class, function () use ($app, $storagePath) {
    return new PackageManifest(
        new Filesystem,
        $app->basePath(),
        $storagePath . '/bootstrap/cache/packages.php'
    );
});

$app->handleRequest(Request::capture());
