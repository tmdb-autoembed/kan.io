<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Api;

use ThemeHub\Core\Controller;
use ThemeHub\Models\{Theme, Category, Review, Order, UserModel};

/**
 * Extended API Controller for all resources
 */
final class ExtendedApiController extends Controller
{
    // Orders API
    public function orders(): void
    {
        auth_require();
        
        $userId = auth_user()['id'];
        $role = auth_user()['role'];
        
        if ($role === 'admin') {
            $orders = (new Order())->all();
        } else {
            $orders = (new Order())->where('user_id', (string)$userId)->get();
        }
        
        json([
            'success' => true,
            'data' => array_map(fn($order) => [
                'id' => $order['id'],
                'order_number' => $order['order_number'],
                'status' => $order['status'],
                'total' => $order['total'],
                'created_at' => $order['created_at'],
            ], $orders)
        ]);
    }

    // Wishlist API
    public function wishlist(): void
    {
        auth_require();
        
        $items = (new \ThemeHub\Models\Wishlist())->where('user_id', (string)auth_user()['id'])->get();
        
        json([
            'success' => true,
            'data' => array_map(fn($item) => [
                'id' => $item['id'],
                'theme' => (new Theme())->find((int)$item['theme_id']),
            ], $items)
        ]);
    }

    // Add to wishlist API
    public function addToWishlist(): void
    {
        auth_require();
        
        $themeId = (int)$this->input('theme_id');
        
        $existing = (new \ThemeHub\Models\Wishlist())->where('user_id', (string)auth_user()['id'])
            ->where('theme_id', (string)$themeId)->get();
        
        if (empty($existing)) {
            (new \ThemeHub\Models\Wishlist())->create([
                'user_id' => auth_user()['id'],
                'theme_id' => $themeId,
            ]);
        }
        
        json(['success' => true, 'message' => 'Added to wishlist']);
    }

    // Remove from wishlist API
    public function removeFromWishlist(): void
    {
        auth_require();
        
        $themeId = (int)$this->input('theme_id');
        
        $items = (new \ThemeHub\Models\Wishlist())->where('user_id', (string)auth_user()['id'])
            ->where('theme_id', (string)$themeId)->get();

        foreach ($items as $item) {
            (new \ThemeHub\Models\Wishlist())->delete((int)$item['id']);
        }
        
        json(['success' => true, 'message' => 'Removed from wishlist']);
    }

    // Search API
    public function search(): void
    {
        $query = $this->input('q', '');
        $category = $this->input('category');
        
        if (empty($query)) {
            json(['error' => 'Search query is required'], 400);
        }
        
        $themes = (new Theme())->where('status', 'published')->search($query);
        
        if ($category) {
            $cat = (new Category())->findBy('slug', $category);
            if ($cat) {
                $themes = $themes->where('category_id', (string)$cat['id']);
            }
        }
        
        json([
            'success' => true,
            'data' => array_map(fn($theme) => [
                'id' => $theme['id'],
                'name' => $theme['name'],
                'slug' => $theme['slug'],
                'price' => theme_price((float)$theme['price'], $theme['sale_price'] ? (float)$theme['sale_price'] : null),
                'thumbnail' => upload($theme['thumbnail']),
                'rating' => (float)$theme['rating'],
                'sales' => (int)$theme['sales'],
            ], $themes->get())
        ]);
    }

    // Settings API
    public function settings(): void
    {
        $settings = \ThemeHub\Models\Setting::all();
        
        json([
            'success' => true,
            'data' => $settings
        ]);
    }

    // Reviews API
    public function reviews(string $slug): void
    {
        $theme = (new Theme())->findBy('slug', $slug);
        
        if (!$theme) {
            json(['error' => 'Theme not found'], 404);
        }
        
        $reviews = (new Review())->where('theme_id', (string)$theme['id'])
            ->where('status', 'approved')->get();
        
        json([
            'success' => true,
            'data' => array_map(fn($review) => [
                'id' => $review['id'],
                'rating' => (int)$review['rating'],
                'comment' => $review['comment'],
                'user' => (new UserModel())->find((int)$review['user_id']),
                'created_at' => $review['created_at'],
            ], $reviews)
        ]);
    }
}
