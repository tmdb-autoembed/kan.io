<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * BlogCategory Model
 */
final class BlogCategory extends Model
{
    protected string $table = 'blog_categories';
    protected array $fillable = ['name', 'slug', 'description'];
}
