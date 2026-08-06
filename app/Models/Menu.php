<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Menu Model
 */
final class Menu extends Model
{
    protected string $table = 'menus';
    protected array $fillable = ['name', 'location', 'items'];
    protected array $casts = [
        'items' => 'json',
    ];
}
