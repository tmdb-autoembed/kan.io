<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Permission Model
 */
final class Permission extends Model
{
    protected string $table = 'permissions';
    protected array $fillable = ['role', 'permission'];
}
