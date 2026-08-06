<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Ticket Model
 */
final class Ticket extends Model
{
    protected string $table = 'tickets';
    protected array $fillable = [
        'user_id', 'subject', 'priority', 'status',
        'assigned_to', 'closed_at'
    ];
    protected array $casts = [
        'closed_at' => 'datetime',
    ];

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function replies(): array
    {
        return (new TicketReply())->where('ticket_id', (string)$this->id);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }
}
