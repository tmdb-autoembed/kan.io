<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Subscription Model
 */
final class Subscription extends Model
{
    protected string $table = 'subscriptions';
    protected array $fillable = ['email', 'name', 'status', 'subscribed_at', 'unsubscribed_at'];
    protected array $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
