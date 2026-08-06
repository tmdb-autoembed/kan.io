<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * PostCategory Model
 */
final class PostCategory extends Model
{
    protected string $table = 'post_categories';
    protected array $fillable = ['post_id', 'category_id'];
}
