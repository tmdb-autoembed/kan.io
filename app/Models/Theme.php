<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Theme Model
 */
final class Theme extends Model
{
    protected string $table = 'themes';
    protected array $fillable = [
        'name', 'slug', 'description', 'price', 'sale_price',
        'thumbnail', 'images', 'demo_url', 'download_file', 'version',
        'license', 'status', 'featured', 'trending', 'views', 'sales',
        'rating', 'reviews_count', 'category_id', 'developer_id',
        'compatible_browsers', 'compatible_php', 'last_updated',
        'created_by', 'meta_title', 'meta_description', 'meta_keywords'
    ];
    protected array $casts = [
        'images' => 'json',
        'price' => 'float',
        'sale_price' => 'float',
        'featured' => 'bool',
        'trending' => 'bool',
        'views' => 'int',
        'sales' => 'int',
        'rating' => 'float',
        'reviews_count' => 'int',
    ];

    public function category(): ?array
    {
        return (new Category())->find((int)$this->category_id);
    }

    public function developer(): ?array
    {
        return (new UserModel())->find((int)$this->developer_id);
    }

    public function reviews(): array
    {
        return (new Review())->where('theme_id', $this->id);
    }

    public function averageRating(): float
    {
        $reviews = $this->reviews();
        if (empty($reviews)) {
            return 0.0;
        }
        
        $sum = array_sum(array_column($reviews, 'rating'));
        return round($sum / count($reviews), 1);
    }

    public function getPriceAttribute(): array
    {
        return theme_price((float)$this->price, $this->sale_price ? (float)$this->sale_price : null);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', 1);
    }

    public function scopeTrending($query)
    {
        return $query->where('trending', 1);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('name LIKE ? OR description LIKE ?', ["%{$search}%", "%{$search}%"]);
    }

    public function incrementViews(): bool
    {
        return Database::connection()->prepare("UPDATE {$this->table} SET views = views + 1 WHERE id = ?")->execute([$this->id]);
    }
}
