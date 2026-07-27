<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

function log_bootstrap_failure(Throwable|array $error): void
{
    $message = $error instanceof Throwable
        ? sprintf(
            "[%s] %s: %s in %s:%s\n%s\n\n",
            date('Y-m-d H:i:s'),
            $error::class,
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
            $error->getTraceAsString()
        )
        : sprintf(
            "[%s] PHP %s: %s in %s:%s\n\n",
            date('Y-m-d H:i:s'),
            $error['type'] ?? 'error',
            $error['message'] ?? 'Unknown error',
            $error['file'] ?? 'unknown',
            $error['line'] ?? 'unknown'
        );

    $logPath = __DIR__.'/../storage/logs/bootstrap-error.log';

    if (@file_put_contents($logPath, $message, FILE_APPEND | LOCK_EX) === false) {
        error_log($message);
    }
}

register_shutdown_function(function (): void {
    $error = error_get_last();

    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        log_bootstrap_failure($error);
    }
});

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

try {
    // Register the Composer autoloader...
    require __DIR__.'/../vendor/autoload.php';

    // Bootstrap Laravel and handle the request...
    /** @var Application $app */
    $app = require_once __DIR__.'/../bootstrap/app.php';

    $app->handleRequest(Request::capture());
} catch (Throwable $e) {
    log_bootstrap_failure($e);

    if (! headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=UTF-8');
    }

    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>500 - Server Error</title></head><body><h1>Terjadi gangguan pada server</h1><p>Silakan hubungi pengelola website.</p></body></html>';
}
