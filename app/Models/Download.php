<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Download Model
 */
final class Download extends Model
{
    protected string $table = 'downloads';
    protected array $fillable = [
        'user_id', 'order_id', 'theme_id', 'ip_address',
        'user_agent', 'downloaded_at'
    ];
    protected array $casts = [
        'downloaded_at' => 'datetime',
    ];
}
