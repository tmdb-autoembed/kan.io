<?php
namespace App\Core;

final class Auth
{
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $statement = Database::pdo()->prepare('select * from users where email=? and status="active"');
        $statement->execute([$email]);
        $user = $statement->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    public static function require(string $role): void
    {
        $user = self::user();
        if (!$user || ($role !== 'auth' && $user['role'] !== $role && $user['role'] !== 'admin')) {
            header('Location: /login');
            exit;
        }
    }

    public static function logout(): void
    {
        unset($_SESSION['user']);
    }

    public static function apiUser(string $token): ?array
    {
        $statement = Database::pdo()->prepare("select u.* from api_tokens t join users u on u.id=t.user_id where t.token_hash=? and t.expires_at>datetime('now')");
        $statement->execute([hash('sha256', $token)]);
        return $statement->fetch() ?: null;
    }
}
