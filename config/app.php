<?php
declare(strict_types=1);

return [
    'app' => [
        'name' => env('APP_NAME', 'ThemeHub'),
        'url' => env('APP_URL', 'http://localhost:8000'),
        'debug' => filter_var(env('APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN),
        'version' => '1.0.0',
        'timezone' => env('APP_TIMEZONE', 'UTC'),
        'locale' => env('APP_LOCALE', 'en'),
        'description' => 'Premium Theme Marketplace - Discover the best themes and templates',
    ],
    'database' => [
        'connection' => env('DB_CONNECTION', 'sqlite'),
        'sqlite' => [
            'path' => env('DB_DATABASE', APP_ROOT . '/database/themehub.sqlite'),
        ],
        'mysql' => [
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'themehub'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ],
    ],
    'mail' => [
        'from_email' => env('MAIL_FROM', 'noreply@themehub.com'),
        'from_name' => env('MAIL_FROM_NAME', 'ThemeHub'),
        'driver' => env('MAIL_DRIVER', 'sendmail'),
        'host' => env('MAIL_HOST', 'localhost'),
        'port' => env('MAIL_PORT', '587'),
        'username' => env('MAIL_USERNAME', ''),
        'password' => env('MAIL_PASSWORD', ''),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    ],
    'jwt' => [
        'secret' => env('JWT_SECRET', 'themehub-jwt-secret-key-change-in-production'),
        'algorithm' => 'HS256',
        'expires' => 86400 * 30,
    ],
    'upload' => [
        'max_size' => 10 * 1024 * 1024,
        'allowed_images' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'allowed_documents' => ['application/pdf', 'application/zip'],
        'thumbnail_sizes' => [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [800, 600],
        ],
    ],
    'cache' => [
        'enabled' => env('CACHE_ENABLED', true),
        'ttl' => 3600,
    ],
    'rate_limit' => [
        'enabled' => env('RATE_LIMIT_ENABLED', true),
        'max_requests' => 100,
        'window' => 60,
    ],
];
