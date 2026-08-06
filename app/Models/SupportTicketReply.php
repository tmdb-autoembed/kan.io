<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * SupportTicketReply Model
 */
final class SupportTicketReply extends Model
{
    protected string $table = 'support_ticket_replies';
    protected array $fillable = ['ticket_id', 'user_id', 'message', 'is_admin'];
}
