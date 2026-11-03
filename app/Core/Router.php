<?php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post(string $uri, callable|array $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put(string $uri, callable|array $action, array $middleware = []): self
    {
        return $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function delete(string $uri, callable|array $action, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    public function addRoute(string $method, string $uri, callable|array $action, array $middleware = []): self
    {
        $normalized = '/' . trim($uri, '/');
        if ($normalized !== '/') {
            $normalized = rtrim($normalized, '/');
        }
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '(?P<$1>[^/]+)', $normalized);
        $regex = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method'     => strtoupper($method),
            'uri'        => $normalized,
            'regex'      => $regex,
            'action'     => $action,
            'middleware' => $middleware,
        ];
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->method();
        $path = $request->path();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            if (preg_match($route['regex'], $path, $matches)) {
                $params = [];
                foreach ($matches as $k => $v) {
                    if (is_string($k)) {
                        $params[$k] = urldecode($v);
                    }
                }
                $request->setRouteParams($params);

                // Esecuzione middleware pipeline
                foreach ($route['middleware'] as $mw) {
                    $res = $this->runMiddleware($mw, $request);
                    if ($res instanceof Response) {
                        return $res;
                    }
                }

                $action = $route['action'];
                if (is_array($action)) {
                    [$class, $methodName] = $action;
                    $controller = new $class();
                    $result = $controller->{$methodName}($request, ...array_values($params));
                } else {
                    $result = $action($request, ...array_values($params));
                }

                if ($result instanceof Response) {
                    return $result;
                }
                if (is_array($result)) {
                    return Response::json($result);
                }
                return new Response((string) $result);
            }
        }

        if ($request->expectsJson()) {
            return Response::json(['error' => 'Endpoint non trovato', 'path' => $path], 404);
        }

        return view('errors.404', ['path' => $path], 404);
    }

    private function runMiddleware(string $mwDefinition, Request $request): ?Response
    {
        [$name, $arg] = array_pad(explode(':', $mwDefinition, 2), 2, null);

        if ($name === 'auth') {
            if (!auth()->check()) {
                if ($request->expectsJson()) {
                    return Response::json(['error' => 'Autenticazione richiesta.'], 401);
                }
                return redirect('/login?redirect=' . urlencode($request->path()));
            }
            return null;
        }

        if ($name === 'role') {
            $middleware = new \App\Http\Middleware\RoleMiddleware();
            return $middleware->handle($request, (string) $arg);
        }

        if ($name === 'permission') {
            $middleware = new \App\Http\Middleware\PermissionMiddleware();
            return $middleware->handle($request, (string) $arg);
        }

        if ($name === 'supplier.gate') {
            $middleware = new \App\Http\Middleware\SupplierPortalGateMiddleware();
            return $middleware->handle($request);
        }

        return null;
    }
}
