<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user' => \App\Http\Middleware\UserMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Throwable $e) {
            header("HTTP/1.1 500 Internal Server Error");
            echo "<div style='font-family: sans-serif; padding: 20px; background: #fff5f5; color: #9b2c2c; border: 1px solid #feb2b2; border-radius: 8px;'>";
            echo "<h1 style='margin-top: 0;'>ORIGINAL ERROR (BEFORE VIEW ENGINE BOOT):</h1>";
            echo "<h2 style='font-size: 1.25rem; font-weight: bold;'>" . htmlspecialchars(get_class($e) . ': ' . $e->getMessage()) . "</h2>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " on line <strong>" . $e->getLine() . "</strong></p>";
            echo "<pre style='background: #fff; padding: 15px; border: 1px solid #fed7d7; border-radius: 4px; overflow: auto; font-family: monospace; font-size: 0.875rem;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "</div>";
            exit;
        });
    })->create();

return $app;
