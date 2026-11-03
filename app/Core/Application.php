<?php

namespace App\Core;

class Application
{
    private static ?self $instance = null;
    private Router $router;
    private ?Request $currentRequest = null;

    public function __construct()
    {
        $this->router = new Router();
        self::$instance = $this;
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function router(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        if ($this->currentRequest === null) {
            $this->currentRequest = new Request();
        }
        return $this->currentRequest;
    }

    public function bootRoutes(): void
    {
        $router = $this->router;
        require base_path('routes/web.php');
        if (is_file(base_path('routes/api.php'))) {
            require base_path('routes/api.php');
        }
    }

    public function handle(Request $request): Response
    {
        $this->currentRequest = $request;
        Database::ensureBootstrapped();

        try {
            return $this->router->dispatch($request);
        } catch (HttpException $e) {
            if ($request->expectsJson()) {
                return Response::json(['error' => $e->getMessage(), 'status' => $e->getStatusCode()], $e->getStatusCode());
            }
            if ($e->getStatusCode() === 403) {
                return view('errors.403', ['message' => $e->getMessage()], 403);
            }
            return view('errors.404', ['message' => $e->getMessage(), 'path' => $request->path()], $e->getStatusCode());
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return Response::json(['error' => $e->getMessage()], 500);
            }
            return new Response('Errore interno del server: ' . e($e->getMessage()), 500);
        }
    }
}
