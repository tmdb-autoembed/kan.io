<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Order Item Model
 */
final class OrderItem extends Model
{
    protected string $table = 'order_items';
    protected array $fillable = [
        'order_id', 'theme_id', 'theme_name', 'theme_price',
        'license_type', 'quantity'
    ];
    protected array $casts = [
        'theme_price' => 'float',
        'quantity' => 'int',
    ];

    public function order(): ?array
    {
        return (new Order())->find((int)$this->order_id);
    }

    public function theme(): ?array
    {
        return (new Theme())->find((int)$this->theme_id);
    }

    public function getTotalAttribute(): float
    {
        return $this->theme_price * $this->quantity;
    }
}
