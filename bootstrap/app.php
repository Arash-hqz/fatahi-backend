<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CrosMiddleware;
use App\Http\Middleware\JsonMiddleware;

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
        // Global middleware stack
        $middleware->use([
            CrosMiddleware::class,
        ]);

        // API group specific middleware
        $middleware->appendToGroup('api', [
            JsonMiddleware::class,
        ]);

        // Route middleware aliases (if needed in route definitions)
        $middleware->alias([
            'cors' => CrosMiddleware::class,
            'force.json' => JsonMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
