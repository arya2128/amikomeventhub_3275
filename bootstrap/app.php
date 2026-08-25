<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
        
        $middleware->validateCsrfTokens(except: [
            '/midtrans/callback',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, $request) {
            return response(
                "<!DOCTYPE html><html><head><title>Debug Error</title></head><body style='font-family:sans-serif;padding:30px;background:#f8fafc;'>"
                . "<h1 style='color:#dc2626;'>Laravel Exception: " . htmlspecialchars($e->getMessage()) . "</h1>"
                . "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " <strong>Line:</strong> " . $e->getLine() . "</p>"
                . "<pre style='background:#1e293b;color:#f8fafc;padding:20px;border-radius:12px;overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>"
                . "</body></html>",
                500
            );
        });
    })->create();
