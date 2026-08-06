<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * PostTag Model
 */
final class PostTag extends Model
{
    protected string $table = 'post_tags';
    protected array $fillable = ['post_id', 'tag_id'];
}
