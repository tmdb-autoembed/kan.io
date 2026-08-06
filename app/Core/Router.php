<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Router Class
 * Handles HTTP routing with middleware support
 */
final class Router
{
    private array $routes = [];
    private array $middleware = [];
    private string $notFoundHandler = '';

    public function get(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('GET', $path, $action, $middleware);
    }

    public function post(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('POST', $path, $action, $middleware);
    }

    public function put(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('PUT', $path, $action, $middleware);
    }

    public function delete(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('DELETE', $path, $action, $middleware);
    }

    public function patch(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('PATCH', $path, $action, $middleware);
    }

    public function any(string $path, callable|array|string $action, array $middleware = []): void
    {
        $this->add('GET', $path, $action, $middleware);
        $this->add('POST', $path, $action, $middleware);
        $this->add('PUT', $path, $action, $middleware);
        $this->add('DELETE', $path, $action, $middleware);
        $this->add('PATCH', $path, $action, $middleware);
    }

    public function group(array $attributes, callable $callback): void
    {
        $previousMiddleware = $this->middleware;
        $previousNotFound = $this->notFoundHandler;
        
        if (isset($attributes['middleware'])) {
            $this->middleware = array_merge($this->middleware, (array)$attributes['middleware']);
        }
        
        if (isset($attributes['notFound'])) {
            $this->notFoundHandler = $attributes['notFound'];
        }
        
        $callback($this);
        
        $this->middleware = $previousMiddleware;
        $this->notFoundHandler = $previousNotFound;
    }

    public function prefix(string $prefix, callable $callback): void
    {
        $this->group(['prefix' => $prefix], $callback);
    }

    public function name(string $name, callable $callback): void
    {
        // Route naming for URL generation
    }

    private function add(string $method, string $path, callable|array|string $action, array $middleware = []): void
    {
        // Convert string action "Controller@method" to array
        if (is_string($action) && str_contains($action, '@')) {
            $action = explode('@', $action, 2);
        }
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'action' => $action,
            'middleware' => array_merge($this->middleware, $middleware),
        ];
    }

    public function dispatch(Request $request): mixed
    {
        $method = $request->method;
        $path = $request->path;
        
        // Remove trailing slash
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method && $route['method'] !== 'ANY') {
                continue;
            }

            $pattern = $this->compilePattern($route['path']);
            
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                
                // Run middleware
                foreach ($route['middleware'] as $middleware) {
                    if (is_string($middleware)) {
                        $result = $this->runNamedMiddleware($middleware, $request);
                        if ($result === false) {
                            return '';
                        }
                    } elseif (is_callable($middleware)) {
                        $result = $middleware($request);
                        if ($result === false) {
                            return '';
                        }
                    }
                }
                
                // Execute action
                return $this->executeAction($route['action'], $request, $matches);
            }
        }

        // 404 Not Found
        if ($this->notFoundHandler) {
            return $this->executeAction($this->notFoundHandler, $request, []);
        }

        http_response_code(404);
        return view('errors.404');
    }

    private function runNamedMiddleware(string $name, Request $request): bool
    {
        switch ($name) {
            case 'auth':
                if (!auth_check()) {
                    if (is_ajax() || is_api()) {
                        json(['error' => 'Unauthorized'], 401);
                    }
                    http_response_code(302);
                    header('Location: /login');
                    exit;
                }
                return true;
            case 'admin':
                if (!has_role(['admin'])) {
                    abort(403, 'Forbidden');
                }
                return true;
            case 'vendor':
                if (!has_role(['vendor', 'admin'])) {
                    abort(403, 'Forbidden');
                }
                return true;
            case 'customer':
                if (!has_role(['customer', 'admin'])) {
                    abort(403, 'Forbidden');
                }
                return true;
            default:
                if (class_exists($name)) {
                    $instance = new $name();
                    if (method_exists($instance, 'handle')) {
                        return $instance->handle($request) !== false;
                    }
                }
                return true;
        }
    }

    private function compilePattern(string $path): string
    {
        $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function executeAction(callable|array $action, Request $request, array $params): mixed
    {
        if (is_callable($action)) {
            return call_user_func_array($action, array_merge([$request], $params));
        }

        if (is_array($action) && count($action) === 2) {
            [$controller, $method] = $action;

            // If already fully qualified class name, use directly
            $controllerClass = str_contains($controller, '\\') ? $controller : 'ThemeHub\\Controllers\\' . $controller;

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException("Controller not found: {$controllerClass}");
            }
            
            $instance = new $controllerClass();
            
            if (!method_exists($instance, $method)) {
                throw new \RuntimeException("Method not found: {$controller}::{$method}");
            }
            
            return $instance->$method($request, ...$params);
        }

        throw new \RuntimeException('Invalid action format');
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
