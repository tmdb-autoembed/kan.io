<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

final class Cart extends Model
{
    protected string $table = 'cart_items';
    protected array $fillable = [
        'user_id', 'session_id', 'theme_id', 'quantity', 'price'
    ];
    protected array $casts = [
        'quantity' => 'int',
        'price' => 'float',
    ];
}
