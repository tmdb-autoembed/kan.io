<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Response Class
 * Handles HTTP responses
 */
final class Response
{
    public function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }

    public function view(string $view, array $data = []): void
    {
        extract($data);
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!is_file($viewFile)) {
            throw new \RuntimeException("View not found: {$view}");
        }
        
        require $viewFile;
        exit;
    }

    public function redirect(string $to, int $status = 302): void
    {
        http_response_code($status);
        header('Location: ' . $to);
        exit;
    }

    public function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer);
    }

    public function download(string $file, string $filename = ''): void
    {
        if (!is_file($file)) {
            abort(404, 'File not found');
        }
        
        $filename = $filename ?: basename($file);
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }

    public function status(int $code): self
    {
        http_response_code($code);
        return $this;
    }

    public function header(string $key, string $value): self
    {
        header("{$key}: {$value}");
        return $this;
    }
}
