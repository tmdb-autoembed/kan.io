<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * Request Class
 * Handles HTTP request data
 */
final class Request
{
    public string $method;
    public string $path;
    public array $queryParams;
    public array $postParams;
    public array $files;
    public array $headers;
    public string $ip;
    public string $userAgent;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $this->path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $this->queryParams = $_GET ?? [];
        $this->postParams = $_POST ?? [];
        $this->files = $_FILES ?? [];
        $this->headers = $this->getHeaders();
        $this->ip = client_ip();
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    private function getHeaders(): array
    {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $key = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                $headers[$key] = $value;
            }
        }
        return $headers;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $this->postParams[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->postParams[$key] ?? $default;
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function has(string $key): bool
    {
        return isset($this->queryParams[$key]) || isset($this->postParams[$key]);
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->get($key, $default);
    }

    public function all(): array
    {
        return array_merge($this->queryParams, $this->postParams);
    }

    public function only(array $keys): array
    {
        $data = $this->all();
        return array_intersect_key($data, array_flip($keys));
    }

    public function except(array $keys): array
    {
        $data = $this->all();
        return array_diff_key($data, array_flip($keys));
    }

    public function boolean(string $key): bool
    {
        $value = $this->get($key);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function integer(string $key, int $default = 0): int
    {
        $value = $this->get($key, $default);
        return filter_var($value, FILTER_VALIDATE_INT) !== false ? (int)$value : $default;
    }

    public function float(string $key, float $default = 0.0): float
    {
        $value = $this->get($key, $default);
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false ? (float)$value : $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $value = $this->get($key, $default);
        return is_string($value) ? $value : $default;
    }

    public function json(): array
    {
        $contentType = $this->headers['Content-Type'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $data = json_decode(file_get_contents('php://input'), true);
            return is_array($data) ? $data : [];
        }
        return [];
    }

    public function bearerToken(): ?string
    {
        $header = $this->headers['Authorization'] ?? '';
        if (str_starts_with($header, 'Bearer ')) {
            return substr($header, 7);
        }
        return null;
    }

    public function expectsJson(): bool
    {
        return $this->header('Accept') === 'application/json' ||
               $this->header('Content-Type') === 'application/json' ||
               str_starts_with($this->path, '/api/');
    }

    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[$key] ?? $default;
    }

    public function validateUpload(string $key, array $rules): array
    {
        $file = $this->file($key);
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return ['error' => 'File upload failed'];
        }

        $errors = [];
        $maxSize = $rules['max_size'] ?? 5 * 1024 * 1024;
        
        if ($file['size'] > $maxSize) {
            $errors[] = 'File size exceeds maximum limit of ' . format_bytes($maxSize);
        }

        $allowedTypes = $rules['mimes'] ?? ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes, true)) {
            $errors[] = 'Invalid file type. Allowed: ' . implode(', ', $allowedTypes);
        }

        return ['errors' => $errors, 'file' => $file];
    }
}
