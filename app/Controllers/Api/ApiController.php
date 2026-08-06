<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Api;

use ThemeHub\Core\{Auth, Controller};
use ThemeHub\Models\{Theme, Category, Review, Order, UserModel};

/**
 * API Controller
 */
final class ApiController extends Controller
{
    public function themes(): void
    {
        $page = (int)($this->input('page', 1));
        $limit = (int)($this->input('limit', 20));
        $category = $this->input('category');
        $search = $this->input('q');
        
        $query = (new Theme())->where('status', 'published');
        
        if ($category) {
            $cat = (new Category())->findBy('slug', $category);
            if ($cat) {
                $query = $query->where('category_id', (string)$cat['id']);
            }
        }
        
        if ($search) {
            $query = $query->search($search);
        }
        
        $themes = $query->all();
        
        json([
            'success' => true,
            'data' => array_map(fn($theme) => $this->transformTheme($theme), $themes),
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => count($themes),
            ]
        ]);
    }

    public function theme(string $slug): void
    {
        $theme = (new Theme())->findBy('slug', $slug);
        
        if (!$theme || $theme['status'] !== 'published') {
            json(['error' => 'Theme not found'], 404);
        }
        
        json([
            'success' => true,
            'data' => $this->transformTheme($theme, true),
        ]);
    }

    public function categories(): void
    {
        $categories = (new Category())->where('status', 'active')->get();
        
        json([
            'success' => true,
            'data' => array_map(fn($cat) => [
                'id' => $cat['id'],
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => $cat['description'],
                'icon' => $cat['icon'],
                'theme_count' => (new Category())->find($cat['id'])['theme_count'] ?? 0,
            ], $categories)
        ]);
    }

    public function search(): void
    {
        $query = $this->input('q', '');
        $category = $this->input('category');
        $sort = $this->input('sort', 'newest');
        
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
            'data' => array_map(fn($theme) => $this->transformTheme($theme), $themes->get()),
        ]);
    }

    public function authenticate(): void
    {
        $email = $this->input('email');
        $password = $this->input('password');
        
        if (!$email || !$password) {
            json(['error' => 'Email and password are required'], 400);
        }
        
        $user = (new UserModel())->findByEmail($email);
        
        if ($user && password_verify($password, $user['password']) && $user['status'] === 'active') {
            $token = Auth::generateApiToken((int)$user['id']);
            
            json([
                'success' => true,
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ]
            ]);
        }
        
        json(['error' => 'Invalid credentials'], 401);
    }

    public function user(): void
    {
        $token = $this->request->bearerToken();
        
        if (!$token) {
            json(['error' => 'Unauthorized'], 401);
        }
        
        $user = Auth::apiUser($token);
        
        if (!$user) {
            json(['error' => 'Invalid token'], 401);
        }
        
        json([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'avatar' => $user['avatar'],
            ]
        ]);
    }

    private function transformTheme(array $theme, bool $detailed = false): array
    {
        $data = [
            'id' => $theme['id'],
            'name' => $theme['name'],
            'slug' => $theme['slug'],
            'description' => $theme['description'],
            'price' => theme_price((float)$theme['price'], $theme['sale_price'] ? (float)$theme['sale_price'] : null),
            'thumbnail' => upload($theme['thumbnail']),
            'demo_url' => $theme['demo_url'],
            'version' => $theme['version'],
            'license' => $theme['license'],
            'rating' => (float)$theme['rating'],
            'reviews_count' => (int)$theme['reviews_count'],
            'sales' => (int)$theme['sales'],
            'views' => (int)$theme['views'],
            'category_id' => (int)$theme['category_id'],
        ];
        
        if ($detailed) {
            $data['images'] = $theme['images'] ? json_decode($theme['images'], true) : [];
            $data['compatible_browsers'] = $theme['compatible_browsers'];
            $data['compatible_php'] = $theme['compatible_php'];
            $data['last_updated'] = $theme['last_updated'];
            $data['developer'] = (new UserModel())->find((int)$theme['developer_id']);
            $data['category'] = (new Category())->find((int)$theme['category_id']);
            $data['reviews'] = (new Review())->where('theme_id', (string)$theme['id'])->get();
        }
        
        return $data;
    }
}
