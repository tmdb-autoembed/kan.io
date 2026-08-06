<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * ActivityLog Model
 */
final class ActivityLog extends Model
{
    protected string $table = 'activity_logs';
    protected array $fillable = [
        'user_id', 'action', 'subject_type', 'subject_id',
        'properties', 'ip_address', 'user_agent'
    ];
    protected array $casts = [
        'properties' => 'json',
    ];
}
