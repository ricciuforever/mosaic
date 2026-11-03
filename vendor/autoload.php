<?php
/**
 * PSR-4 Autoloader & Bootstrap Bridge per Mosaic Luxury Concierge (Laravel / Plesk / MySQL).
 */

spl_autoload_register(function (string $class): void {
    $root = dirname(__DIR__);
    $prefixes = [
        'App\\'                  => $root . '/app/',
        'Database\\Seeders\\'    => $root . '/database/seeders/',
        'Database\\Migrations\\' => $root . '/database/migrations/',
        'Tests\\'                => $root . '/tests/',
        'Spatie\\Permission\\'   => $root . '/app/Core/Spatie/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once dirname(__DIR__) . '/bootstrap/helpers.php';
