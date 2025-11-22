<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// If running in the testing environment, load .env.testing when present.
$env = getenv('APP_ENV') ?: ($_ENV['APP_ENV'] ?? null);
if ($env === 'testing') {
    $testingEnvPath = dirname(__DIR__) . '/.env.testing';
    if (file_exists($testingEnvPath)) {
        \Dotenv\Dotenv::createImmutable(dirname(__DIR__), '.env.testing')->safeLoad();
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
