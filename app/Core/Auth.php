<?php
declare(strict_types=1);

namespace ThemeHub\Core;

use ThemeHub\Models\UserModel;

/**
 * Authentication Manager
 * Handles user authentication, sessions, and JWT
 */
final class Auth
{
    public static function user(): ?array
    {
        return Session::get('user') ?? null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user ? (int)$user['id'] : null;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = (new UserModel())->where('email', $email);
        $user = $user[0] ?? null;
        
        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return false;
            }
            
            unset($user['password']);
            Session::set('user', $user);
            Session::regenerate(true);
            
            if ($remember) {
                $token = random_string(64);
                (new UserModel())->update($user['id'], ['remember_token' => hash('sha256', $token)]);
                setcookie('remember_token', $token, time() + 86400 * 30, '/', '', false, true);
            }
            
            log_message('info', "User logged in: {$user['email']}");
            return true;
        }
        
        log_message('warning', "Failed login attempt for: {$email}");
        return false;
    }

    public static function login(array $user): void
    {
        unset($user['password']);
        Session::set('user', $user);
        Session::regenerate(true);
    }

    public static function logout(): void
    {
        $user = self::user();
        if ($user) {
            (new UserModel())->update($user['id'], ['remember_token' => null]);
        }
        
        Session::destroy();
        setcookie('remember_token', '', time() - 3600, '/');
        
        log_message('info', 'User logged out');
    }

    public static function require(string|array $roles = []): void
    {
        if (!self::check()) {
            if (is_ajax() || is_api()) {
                json(['error' => 'Unauthorized'], 401);
            }
            redirect('/login');
        }
        
        if (!empty($roles) && !self::hasRole($roles)) {
            if (is_ajax() || is_api()) {
                json(['error' => 'Forbidden'], 403);
            }
            abort(403, 'Forbidden');
        }
    }

    public static function hasRole(string|array $roles): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }
        
        $roles = (array) $roles;
        return in_array($user['role'], $roles, true);
    }

    public static function apiUser(string $token): ?array
    {
        $payload = verify_jwt($token, config('jwt.secret', env('JWT_SECRET', 'change-me-jwt-secret')));
        
        if (!$payload) {
            return null;
        }
        
        return (new UserModel())->find((int)$payload['sub']);
    }

    public static function generateApiToken(int $userId, int $expiresIn = 86400 * 30): string
    {
        $secret = config('jwt.secret', env('JWT_SECRET', 'change-me-jwt-secret'));
        $payload = [
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + $expiresIn,
            'type' => 'api'
        ];
        
        return generate_jwt($payload, $secret);
    }

    public static function attemptFromRememberToken(): bool
    {
        $token = $_COOKIE['remember_token'] ?? null;
        
        if (!$token) {
            return false;
        }
        
        $hash = hash('sha256', $token);
        $user = (new UserModel())->where('remember_token', $hash);
        $user = $user[0] ?? null;
        
        if ($user) {
            unset($user['password']);
            Session::set('user', $user);
            return true;
        }
        
        return false;
    }
}
