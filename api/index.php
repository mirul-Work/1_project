<?php

/*
|--------------------------------------------------------------------------
| Vercel Entry Point
|--------------------------------------------------------------------------
*/

define('LARAVEL_START', microtime(true));

// Autoload
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Fix for Vercel Read-Only Filesystem
// We modify the storage path to use /tmp which is writable
$app->useStoragePath('/tmp/storage');

// Create the folder structure in /tmp if it doesn't exist
// Vercel serverless functions have a writable /tmp directory
if (!is_dir('/tmp/storage')) {
    mkdir('/tmp/storage', 0777, true);
    mkdir('/tmp/storage/app', 0777, true);
    mkdir('/tmp/storage/framework', 0777, true);
    mkdir('/tmp/storage/framework/cache', 0777, true);
    mkdir('/tmp/storage/framework/sessions', 0777, true);
    mkdir('/tmp/storage/framework/views', 0777, true);
    mkdir('/tmp/storage/logs', 0777, true);
}

// Run App
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);