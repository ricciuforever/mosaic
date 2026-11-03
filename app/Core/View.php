<?php

namespace App\Core;

use RuntimeException;

class View
{
    public static function render(string $viewName, array $data = []): string
    {
        $relativePath = str_replace('.', '/', $viewName);
        $bladeFile = resource_path("views/{$relativePath}.blade.php");
        $phpFile = resource_path("views/{$relativePath}.php");

        $file = is_file($bladeFile) ? $bladeFile : (is_file($phpFile) ? $phpFile : null);
        if ($file === null) {
            throw new RuntimeException("Vista non trovata: {$viewName}");
        }

        extract($data, EXTR_SKIP);
        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
