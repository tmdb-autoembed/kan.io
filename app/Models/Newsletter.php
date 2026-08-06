<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Newsletter Model
 */
final class Newsletter extends Model
{
    protected string $table = 'newsletters';
    protected array $fillable = ['email', 'name', 'status', 'subscribed_at'];
    protected array $casts = [
        'subscribed_at' => 'datetime',
    ];
}
