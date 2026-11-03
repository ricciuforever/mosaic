<?php

/**
 * Mosaic · Luxury Experience Concierge
 * Front Controller per Apache / Nginx / Cloud1 Plesk & PHP built-in server.
 */

// Supporto static file per PHP built-in development server (php -S)
if (PHP_SAPI === 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $file = __DIR__ . $url;
    if ($url !== '/' && is_file($file)) {
        return false;
    }
}

$app = require __DIR__ . '/../bootstrap/app.php';
$request = new \App\Core\Request();
$response = $app->handle($request);
$response->send();
