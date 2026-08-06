<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Payment Model
 */
final class Payment extends Model
{
    protected string $table = 'payments';
    protected array $fillable = [
        'order_id', 'user_id', 'gateway', 'transaction_id',
        'amount', 'currency', 'status', 'metadata', 'paid_at'
    ];
    protected array $casts = [
        'amount' => 'float',
        'metadata' => 'json',
        'paid_at' => 'datetime',
    ];

    public function order(): ?array
    {
        return (new Order())->find((int)$this->order_id);
    }

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}
