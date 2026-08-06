<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Page Model
 */
final class Page extends Model
{
    protected string $table = 'pages';
    protected array $fillable = [
        'title', 'slug', 'content', 'status', 'meta_title',
        'meta_description', 'sort_order'
    ];
    protected array $casts = [
        'sort_order' => 'int',
    ];

    public function isActive(): bool
    {
        return $this->status === 'published';
    }
}
