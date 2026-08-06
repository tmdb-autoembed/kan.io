<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Blog Post Model
 */
final class Post extends Model
{
    protected string $table = 'posts';
    protected array $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'author_id', 'status', 'views', 'published_at', 'meta_title',
        'meta_description', 'meta_keywords'
    ];
    protected array $casts = [
        'views' => 'int',
        'published_at' => 'datetime',
    ];

    public function author(): ?array
    {
        return (new UserModel())->find((int)$this->author_id);
    }

    public function categories(): array
    {
        $postCategories = (new PostCategory())->where('post_id', (string)$this->id);
        $categoryIds = array_column($postCategories, 'category_id');
        
        $categories = [];
        foreach ($categoryIds as $id) {
            $category = (new Category())->find((int)$id);
            if ($category) {
                $categories[] = $category;
            }
        }
        
        return $categories;
    }

    public function tags(): array
    {
        $postTags = (new PostTag())->where('post_id', (string)$this->id);
        $tagIds = array_column($postTags, 'tag_id');
        
        $tags = [];
        foreach ($tagIds as $id) {
            $tag = (new Tag())->find((int)$id);
            if ($tag) {
                $tags[] = $tag;
            }
        }
        
        return $tags;
    }

    public function comments(): array
    {
        return (new Comment())->where('post_id', (string)$this->id);
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at <=', date('Y-m-d H:i:s'));
    }
}
