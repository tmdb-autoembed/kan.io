<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * LicenseKey Model
 */
final class LicenseKey extends Model
{
    protected string $table = 'license_keys';
    protected array $fillable = [
        'order_id', 'user_id', 'theme_id', 'key', 'status',
        'activations_limit', 'activations_count', 'expires_at',
        'activated_at'
    ];
    protected array $casts = [
        'expires_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active' && (!$this->expires_at || strtotime($this->expires_at) > time());
    }

    public function canActivate(): bool
    {
        return $this->activations_count < $this->activations_limit;
    }

    public function activate(): bool
    {
        if (!$this->canActivate()) {
            return false;
        }
        
        return Database::connection()->prepare("UPDATE {$this->table} SET activations_count = activations_count + 1, activated_at = ? WHERE id = ?")
            ->execute([date('Y-m-d H:i:s'), $this->id]);
    }
}
