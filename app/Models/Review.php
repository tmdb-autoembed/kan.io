<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Review Model
 */
final class Review extends Model
{
    protected string $table = 'reviews';
    protected array $fillable = [
        'theme_id', 'user_id', 'rating', 'comment', 'status',
        'is_verified_purchase', 'helpful_count'
    ];
    protected array $casts = [
        'rating' => 'int',
        'helpful_count' => 'int',
        'is_verified_purchase' => 'bool',
    ];

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function theme(): ?array
    {
        return (new Theme())->find((int)$this->theme_id);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
