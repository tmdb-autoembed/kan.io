<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Tag Model
 */
final class Tag extends Model
{
    protected string $table = 'tags';
    protected array $fillable = ['name', 'slug'];
}
