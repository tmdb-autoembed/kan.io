<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * CSRF Protection
 */
final class Csrf
{
    public static function token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = random_string(32);
        }
        return $_SESSION['csrf_token'];
    }

    public static function check(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function verify(): void
    {
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (!self::check($token)) {
            if (is_ajax() || is_api()) {
                json(['error' => 'CSRF token mismatch'], 419);
            }
            abort(419, 'CSRF Token Mismatch');
        }
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function meta(): string
    {
        return '<meta name="csrf-token" content="' . e(self::token()) . '">';
    }

    public static function regenerate(): void
    {
        $_SESSION['csrf_token'] = random_string(32);
    }
}
