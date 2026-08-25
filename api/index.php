<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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
putenv("SESSION_DRIVER=cookie");
putenv("CACHE_STORE=array");
putenv("LOG_CHANNEL=stderr");

// Endpoint diagnostik langsung tanpa Laravel boot
if (isset($_GET['diagnostic']) || (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/diagnostic'))) {
    header('Content-Type: text/plain');
    echo "=== VERCEL PHP DIAGNOSTIC ===\n";
    echo "PHP Version: " . phpversion() . "\n";
    echo "APP_KEY: " . (getenv('APP_KEY') ? 'LOADED' : 'NOT LOADED') . "\n";
    echo "DB_HOST: " . getenv('DB_HOST') . "\n";
    echo "DB_PORT: " . getenv('DB_PORT') . "\n";
    echo "DB_DATABASE: " . getenv('DB_DATABASE') . "\n";
    echo "DB_USERNAME: " . getenv('DB_USERNAME') . "\n\n";
    echo "=== TESTING TIDB CONNECTION ===\n";
    try {
        $host = getenv('DB_HOST') ?: 'gateway01.ap-southeast-1.prod.aws.tidbcloud.com';
        $port = getenv('DB_PORT') ?: '4000';
        $db = getenv('DB_DATABASE') ?: 'test';
        $user = getenv('DB_USERNAME') ?: '2wzR4q2HyxnRSkE.root';
        $pass = getenv('DB_PASSWORD') ?: 'WWZICHTNFOH6r5Ub';
        
        $sslCa = is_file(__DIR__ . '/cacert.pem') ? __DIR__ . '/cacert.pem' : (
            is_file(__DIR__ . '/../cacert.pem') ? __DIR__ . '/../cacert.pem' : null
        );

        echo "CA File Path: " . ($sslCa ?? 'NONE') . " (Exists: " . (file_exists($sslCa ?? '') ? 'YES' : 'NO') . ")\n";

        $pdoOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ];
        if ($sslCa) {
            $pdoOptions[PDO::MYSQL_ATTR_SSL_CA] = $sslCa;
        }
        $pdoOptions[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = false;

        $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass, $pdoOptions);
        echo "PDO Connection: SUCCESS!\n";
        $stmt = $pdo->query("SHOW TABLES");
        echo "Tables in {$db}: " . implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN)) . "\n";
    } catch (\Throwable $e) {
        echo "PDO Connection FAILED: " . $e->getMessage() . "\n";
    }
    exit;
}

// Autoload Composer
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

try {
    // Bootstrap Laravel
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);

    // Otomatis migrasi & seed jika tabel events belum ada
    try {
        if (!Schema::hasTable('events')) {
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
        }
    } catch (\Throwable $migEx) {
        // Biarkan request lanjut jika DB belum siap
    }

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    http_response_code(500);
    echo "<h1>Laravel Error Diagnostik</h1>";
    echo "<p><strong>Pesan:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Lokasi:</strong> " . htmlspecialchars($e->getFile()) . " baris " . $e->getLine() . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
