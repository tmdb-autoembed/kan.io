<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * SupportTicket Model
 */
final class SupportTicket extends Model
{
    protected string $table = 'support_tickets';
    protected array $fillable = [
        'user_id', 'subject', 'priority', 'status',
        'assigned_to', 'closed_at'
    ];
    protected array $casts = [
        'closed_at' => 'datetime',
    ];
}
