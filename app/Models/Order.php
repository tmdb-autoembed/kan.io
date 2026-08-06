<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Order Model
 */
final class Order extends Model
{
    protected string $table = 'orders';
    protected array $fillable = [
        'user_id', 'order_number', 'status', 'payment_status',
        'payment_method', 'subtotal', 'tax', 'discount', 'total',
        'currency', 'billing_name', 'billing_email', 'billing_phone',
        'billing_address', 'billing_city', 'billing_state',
        'billing_country', 'billing_postal_code', 'notes'
    ];
    protected array $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'discount' => 'float',
        'total' => 'float',
    ];

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function items(): array
    {
        return (new OrderItem())->where('order_id', (string)$this->id);
    }

    public function getTotalAttribute(): array
    {
        $items = $this->items();
        $total = array_sum(array_column($items, 'total'));
        
        return [
            'items' => $items,
            'subtotal' => $total,
            'tax' => $this->tax ?? 0,
            'discount' => $this->discount ?? 0,
            'final' => $total + ($this->tax ?? 0) - ($this->discount ?? 0),
        ];
    }

    public function generateOrderNumber(): string
    {
        return 'TH-' . strtoupper(uniqid());
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
