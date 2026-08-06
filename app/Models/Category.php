<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Category Model
 */
final class Category extends Model
{
    protected string $table = 'categories';
    protected array $fillable = [
        'name', 'slug', 'description', 'icon', 'image',
        'parent_id', 'status', 'sort_order', 'meta_title', 'meta_description'
    ];
    protected array $casts = [
        'sort_order' => 'int',
    ];

    public function parent(): ?array
    {
        return $this->parent_id ? $this->find((int)$this->parent_id) : null;
    }

    public function children(): array
    {
        return $this->where('parent_id', $this->id);
    }

    public function themes(int $limit = 12, int $offset = 0): array
    {
        return (new Theme())->where('category_id', $this->id);
    }

    public function themeCount(): int
    {
        return (new Theme())->count('category_id = ?', [(string)$this->id]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
