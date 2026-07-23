<?php
namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $path, array $action, array $middleware = []): void
    {
        $this->add('GET', $path, $action, $middleware);
    }

    public function post(string $path, array $action, array $middleware = []): void
    {
        $this->add('POST', $path, $action, $middleware);
    }

    private function add(string $method, string $path, array $action, array $middleware): void
    {
        $this->routes[] = compact('method', 'path', 'action', 'middleware');
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method === 'HEAD' ? 'GET' : $request->method;
        foreach ($this->routes as $route) {
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $route['path']);
            if ($route['method'] === $method && preg_match('#^' . $pattern . '$#', $request->path, $matches)) {
                array_shift($matches);
                foreach ($route['middleware'] as $middleware) {
                    Auth::require($middleware);
                }
                [$controller, $methodName] = $route['action'];
                return (new $controller)->$methodName($request, ...$matches);
            }
        }

        http_response_code(404);
        return (new Controller())->view('errors/404');
    }
}
