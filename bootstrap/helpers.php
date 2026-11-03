<?php

use App\Core\Application;
use App\Core\Auth;
use App\Core\Database;
use App\Core\Request;
use App\Core\Response;
use App\Core\View;

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string
    {
        $root = dirname(__DIR__);
        return $path !== '' ? $root . DIRECTORY_SEPARATOR . ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR) : $root;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = ''): string
    {
        return base_path('public' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
    }
}

if (!function_exists('database_path')) {
    function database_path(string $path = ''): string
    {
        return base_path('database' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
    }
}

if (!function_exists('storage_path')) {
    function storage_path(string $path = ''): string
    {
        return base_path('storage' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
    }
}

if (!function_exists('resource_path')) {
    function resource_path(string $path = ''): string
    {
        return base_path('resources' . ($path !== '' ? '/' . ltrim($path, '/') : ''));
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        static $loaded = false;
        static $vars = [];

        if (!$loaded) {
            $loaded = true;
            $envFile = base_path('.env');
            if (is_file($envFile) && is_readable($envFile)) {
                $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                        continue;
                    }
                    [$k, $v] = explode('=', $line, 2);
                    $k = trim($k);
                    $v = trim($v);
                    if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
                        $v = substr($v, 1, -1);
                    }
                    $vars[$k] = $v;
                }
            }
        }

        $val = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
        if ($val === false || $val === null) {
            if (array_key_exists($key, $vars)) {
                $val = $vars[$key];
            } else {
                return $default;
            }
        }

        if (is_string($val)) {
            $lower = strtolower($val);
            if ($lower === 'true' || $lower === '(true)') return true;
            if ($lower === 'false' || $lower === '(false)') return false;
            if ($lower === 'null' || $lower === '(null)') return null;
            if ($lower === 'empty' || $lower === '(empty)') return '';
        }

        return $val;
    }
}

if (!function_exists('config')) {
    function config(?string $key = null, $default = null)
    {
        static $configs = [];
        if (empty($configs)) {
            foreach (glob(base_path('config/*.php')) ?: [] as $file) {
                $name = basename($file, '.php');
                $configs[$name] = require $file;
            }
        }
        if ($key === null) {
            return $configs;
        }
        $segments = explode('.', $key);
        $current = $configs;
        foreach ($segments as $segment) {
            if (is_array($current) && array_key_exists($segment, $current)) {
                $current = $current[$segment];
            } else {
                return $default;
            }
        }
        return $current;
    }
}

if (!function_exists('e')) {
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES, 'UTF-8', false);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return '/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url(string $path = '', array $query = []): string
    {
        $u = '/' . ltrim($path, '/');
        if (!empty($query)) {
            $u .= (str_contains($u, '?') ? '&' : '?') . http_build_query($query);
        }
        return $u;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
            @session_start();
        }
        if (empty($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(16));
        }
        return $_SESSION['_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

if (!function_exists('request')) {
    function request(?string $key = null, $default = null)
    {
        $req = Application::getInstance()->getRequest();
        if ($key === null) {
            return $req;
        }
        return $req->input($key, $default);
    }
}

if (!function_exists('auth')) {
    function auth(): Auth
    {
        return Auth::instance();
    }
}

if (!function_exists('view')) {
    function view(string $template, array $data = [], int $status = 200): Response
    {
        return new Response(View::render($template, $data), $status, ['Content-Type' => 'text/html; charset=UTF-8']);
    }
}

if (!function_exists('response')) {
    function response(string $content = '', int $status = 200, array $headers = []): Response
    {
        return new Response($content, $status, $headers);
    }
}

if (!function_exists('redirect')) {
    function redirect(string $to, int $status = 302): Response
    {
        return Response::redirect($to, $status);
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = ''): Response
    {
        throw new \App\Core\HttpException($code, $message);
    }
}

if (!function_exists('format_eur')) {
    function format_eur(float|int $amount): string
    {
        return '€' . number_format(round((float) $amount), 0, ',', '.');
    }
}
