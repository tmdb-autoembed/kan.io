<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;
use ThemeHub\Models\Permission;

/**
 * User Model
 */
final class UserModel extends Model
{
    protected string $table = 'users';
    protected string $primaryKey = 'id';
    protected array $fillable = [
        'name', 'email', 'password', 'role', 'status', 'avatar',
        'email_verified_at', 'remember_token', 'two_factor_secret',
        'two_factor_recovery_codes', 'last_login_at', 'last_login_ip'
    ];
    protected array $hidden = ['password', 'two_factor_secret', 'two_factor_recovery_codes'];
    protected array $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    public function findByEmail(string $email): ?array
    {
        return $this->findBy('email', $email);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function hasVerifiedEmail(): bool
    {
        return !empty($this->email_verified_at);
    }

    public function markEmailAsVerified(): bool
    {
        return $this->update($this->id, ['email_verified_at' => date('Y-m-d H:i:s')]);
    }

    public function updateLastLogin(): bool
    {
        return $this->update($this->id, [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => client_ip()
        ]);
    }

    public function getPermissions(): array
    {
        $permissions = (new Permission())->where('role', $this->role ?? '')->get();
        return array_column($permissions, 'permission');
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->getPermissions();
        return in_array($permission, $permissions, true) || in_array('*', $permissions, true);
    }
}
