<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Wishlist Model
 */
final class Wishlist extends Model
{
    protected string $table = 'wishlists';
    protected array $fillable = ['user_id', 'theme_id'];

    public function user(): ?array
    {
        return (new UserModel())->find((int)$this->user_id);
    }

    public function theme(): ?array
    {
        return (new Theme())->find((int)$this->theme_id);
    }
}
