<?php
// Cache Buster: 1

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

try {
    // Register the Composer autoloader...
    require __DIR__ . '/../vendor/autoload.php';

    // Bootstrap Laravel and handle the request...
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    $app->handleRequest(Request::capture());
} catch (\Throwable $e) {
    header("HTTP/1.1 500 Internal Server Error");
    echo "<h1>FATAL ERROR: APPLICATION CRASHED</h1>";
    echo "<h3>" . htmlspecialchars($e->getMessage()) . "</h3>";
    echo "<p>File: <strong>" . htmlspecialchars($e->getFile()) . "</strong> on line <strong>" . $e->getLine() . "</strong></p>";
    echo "<pre style='background: #f4f4f4; padding: 15px; border: 1px solid #ddd; overflow: auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    exit;
}
