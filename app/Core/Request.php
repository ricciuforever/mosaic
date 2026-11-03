<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    public array $query;
    private array $post;
    private array $json = [];
    private array $headers;
    private array $routeParams = [];

    public function __construct(
        ?string $method = null,
        ?string $uri = null,
        ?array $query = null,
        ?array $post = null,
        ?string $rawBody = null,
        ?array $headers = null
    ) {
        $this->method = strtoupper($method ?? ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
        $rawUri = $uri ?? ($_SERVER['REQUEST_URI'] ?? '/');
        $parsedPath = parse_url($rawUri, PHP_URL_PATH) ?: '/';
        // Normalizza se servito sotto /public
        if (str_starts_with($parsedPath, '/public/')) {
            $parsedPath = substr($parsedPath, 7) ?: '/';
        } elseif ($parsedPath === '/public') {
            $parsedPath = '/';
        }
        $this->uri = '/' . trim($parsedPath, '/');
        if ($this->uri !== '/') {
            $this->uri = rtrim($this->uri, '/');
        }

        if ($query !== null) {
            $this->query = $query;
        } else {
            $qs = parse_url($rawUri, PHP_URL_QUERY);
            if ($qs && empty($_GET)) {
                parse_str($qs, $parsedQuery);
                $this->query = $parsedQuery;
            } else {
                $this->query = $_GET;
            }
        }

        $this->post = $post ?? $_POST;

        $body = $rawBody ?? (@file_get_contents('php://input') ?: '');
        if ($body !== '') {
            $decoded = json_decode($body, true);
            if (is_array($decoded)) {
                $this->json = $decoded;
            }
        }

        if (!empty($this->post['_method'])) {
            $this->method = strtoupper((string) $this->post['_method']);
        } elseif (!empty($this->json['_method'])) {
            $this->method = strtoupper((string) $this->json['_method']);
        }

        $this->headers = $headers ?? $this->extractHeaders();
    }

    private function extractHeaders(): array
    {
        $h = [];
        foreach ($_SERVER as $k => $v) {
            if (str_starts_with($k, 'HTTP_')) {
                $name = strtolower(str_replace('_', '-', substr($k, 5)));
                $h[$name] = $v;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $h['content-type'] = $_SERVER['CONTENT_TYPE'];
        }
        return $h;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->uri;
    }

    public function all(): array
    {
        return array_merge($this->query, $this->post, $this->json);
    }

    public function input(string $key, $default = null)
    {
        $all = $this->all();
        return array_key_exists($key, $all) ? $all[$key] : $default;
    }

    public function query(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        return array_key_exists($key, $this->query) ? $this->query[$key] : $default;
    }

    public function boolean(string $key, bool $default = false): bool
    {
        $val = $this->input($key, null);
        if ($val === null) {
            return $default;
        }
        return filter_var($val, FILTER_VALIDATE_BOOLEAN);
    }

    public function header(string $key, $default = null)
    {
        $k = strtolower($key);
        return $this->headers[$k] ?? $default;
    }

    public function expectsJson(): bool
    {
        $accept = strtolower((string) $this->header('accept', ''));
        $xhr = strtolower((string) $this->header('x-requested-with', ''));
        return str_contains($accept, 'application/json')
            || $xhr === 'xmlhttprequest'
            || str_starts_with($this->uri, '/api/');
    }

    public function wantsJson(): bool
    {
        return $this->expectsJson();
    }

    public function setRouteParams(array $params): void
    {
        $this->routeParams = $params;
    }

    public function route(string $key, $default = null)
    {
        return $this->routeParams[$key] ?? $default;
    }
}
