<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Application Class
 * Main application bootstrap and runner
 */
final class App
{
    private Router $router;
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->router = new Router();
        $this->loadRoutes();
    }

    private function loadRoutes(): void
    {
        $GLOBALS['app_router'] = $this->router;

        // Load web routes
        $webRoutes = APP_ROOT . '/routes/web.php';
        if (is_file($webRoutes)) {
            require $webRoutes;
        }

        // Load API routes
        $apiRoutes = APP_ROOT . '/routes/api.php';
        if (is_file($apiRoutes)) {
            require $apiRoutes;
        }
    }

    public function run(): void
    {
        try {
            // Check maintenance mode
            if (maintenance_mode() && !has_role(['admin'])) {
                if (!is_ajax() && !is_api()) {
                    view('maintenance');
                    exit;
                }
                json(['error' => 'Service temporarily unavailable'], 503);
            }
            
            // Handle OPTIONS request for CORS
            if ($this->request->method === 'OPTIONS') {
                header('Access-Control-Allow-Origin: *');
                header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
                header('Access-Control-Allow-Headers: Content-Type, Authorization, X-CSRF-TOKEN');
                exit;
            }
            
            // Verify CSRF for web forms
            if ($this->request->method === 'POST' && !is_api() && !is_ajax()) {
                Csrf::verify();
            }
            
            // Check rate limit
            $rateLimitKey = 'global:' . $this->request->ip;
            if (!rate_limit($rateLimitKey, 100, 60)) {
                abort(429, 'Too many requests');
            }
            
            // Try to authenticate from remember token
            if (!auth_check() && !is_api()) {
                Auth::attemptFromRememberToken();
            }
            
            // Dispatch request
            $response = $this->router->dispatch($this->request);
            
            // Output response
            if ($response !== '' && $response !== null) {
                echo $response;
            }
            
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function handleException(\Throwable $e): void
    {
        log_message('error', $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);
        
        if (config('app.debug')) {
            echo '<pre>';
            echo '<h1>Error: ' . e($e->getMessage()) . '</h1>';
            echo '<p>File: ' . e($e->getFile()) . '</p>';
            echo '<p>Line: ' . $e->getLine() . '</p>';
            echo '<h2>Trace:</h2>';
            echo '<pre>' . e($e->getTraceAsString()) . '</pre>';
            echo '</pre>';
            exit;
        }
        
        if (is_ajax() || is_api()) {
            json(['error' => 'Internal server error'], 500);
        }
        
        http_response_code(500);
        view('errors.500');
    }
}
