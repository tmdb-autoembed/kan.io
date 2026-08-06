<?php
declare(strict_types=1);

namespace ThemeHub\Core;

abstract class Controller
{
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    protected function view(string $view, array $data = [], array $layout = ['layout' => 'app']): string
    {
        extract($data);
        ob_start();

        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        if (!is_file($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }

        require $viewFile;
        $content = ob_get_clean();

        if (!empty($layout)) {
            $layoutName = $layout['layout'] ?? 'app';
            $layoutFile = VIEW_PATH . '/layouts/' . $layoutName . '.php';

            if (is_file($layoutFile)) {
                ob_start();
                require $layoutFile;
                return ob_get_clean();
            }
        }

        return $content;
    }

    protected function json(array $data, int $status = 200): string
    {
        http_response_code($status);
        header('Content-Type: application/json');
        return json_encode($data, JSON_THROW_ON_ERROR);
    }

    protected function redirect(string $to, int $status = 302): static
    {
        http_response_code($status);
        header('Location: ' . $to);
        return $this;
    }

    protected function with(string $key, string $value): string
    {
        $_SESSION['flash'][$key] = $value;
        exit;
    }

    protected function back(): static
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        return $this->redirect($referer);
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $this->request->input($key, $default);
    }

    protected function boolean(string $key): bool
    {
        return filter_var($this->input($key, false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function validate(array $rules): array
    {
        $validator = new Validator($this->request->all(), $rules);
        return $validator->validate();
    }

    protected function include(string $partial, array $data = []): string
    {
        extract($data);
        ob_start();
        $partialFile = VIEW_PATH . '/' . str_replace('.', '/', $partial) . '.php';
        if (!is_file($partialFile)) {
            throw new \RuntimeException("Partial not found: {$partial}");
        }
        require $partialFile;
        return ob_get_clean();
    }
}
