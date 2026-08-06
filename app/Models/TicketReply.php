<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * TicketReply Model
 */
final class TicketReply extends Model
{
    protected string $table = 'ticket_replies';
    protected array $fillable = ['ticket_id', 'user_id', 'message', 'is_admin'];
}
