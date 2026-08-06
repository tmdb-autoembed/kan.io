<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Comment Model
 */
final class Comment extends Model
{
    protected string $table = 'comments';
    protected array $fillable = [
        'post_id', 'user_id', 'parent_id', 'content', 'status',
        'is_approved', 'ip_address'
    ];
    protected array $casts = [
        'is_approved' => 'bool',
    ];

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function post(): ?array
    {
        return (new Post())->find((int)$this->post_id);
    }

    public function parent(): ?array
    {
        return $this->parent_id ? $this->find((int)$this->parent_id) : null;
    }

    public function replies(): array
    {
        return $this->where('parent_id', (string)$this->id);
    }
}
