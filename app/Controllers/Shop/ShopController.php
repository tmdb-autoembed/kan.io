<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Shop;

use ThemeHub\Core\{Controller, Database, Request};
use ThemeHub\Models\{Theme, Category, Review, Wishlist, Cart};

final class ShopController extends Controller
{
    public function home(Request $r): string
    {
        $featured = (new Theme())->where('status', 'published')->where('featured', '1')->get();
        $trending = (new Theme())->where('status', 'published')->where('trending', '1')->get();
        $latest = (new Theme())->where('status', 'published')->orderBy('created_at', 'DESC')->limit(12)->get();
        $categories = (new Category())->where('status', 'active')->get();

        return $this->view('shop.home', [
            'featured' => $featured,
            'trending' => $trending,
            'latest' => $latest,
            'categories' => $categories,
        ]);
    }

    public function theme(Request $r, string $slug): string
    {
        $theme = (new Theme())->findBy('slug', $slug);

        if (!$theme || $theme['status'] !== 'published') {
            http_response_code(404);
            return $this->view('errors.404');
        }

        (new Theme())->update($theme['id'], ['views' => $theme['views'] + 1]);

        $related = (new Theme())->where('category_id', (string)$theme['category_id'])
            ->where('id', (string)$theme['id'], '!=')
            ->limit(4)->get();

        $reviews = (new Review())->where('theme_id', (string)$theme['id'])
            ->where('status', 'approved')->get();

        return $this->view('shop.theme', [
            'theme' => $theme,
            'related' => $related,
            'reviews' => $reviews,
        ]);
    }

    public function category(Request $r, string $slug): string
    {
        $category = (new Category())->findBy('slug', $slug);

        if (!$category) {
            http_response_code(404);
            return $this->view('errors.404');
        }

        $themes = (new Theme())->where('category_id', (string)$category['id'])
            ->where('status', 'published')->get();

        return $this->view('shop.category', [
            'category' => $category,
            'themes' => $themes,
        ]);
    }

    public function search(Request $r): string
    {
        $query = $this->input('q', '');
        $category = $this->input('category', '');
        $sort = $this->input('sort', 'newest');
        $page = (int)$this->input('page', 1);

        $themeQuery = (new Theme())->where('status', 'published');

        if ($query) {
            $themeQuery = $themeQuery->search($query);
        }

        if ($category) {
            $cat = (new Category())->findBy('slug', $category);
            if ($cat) {
                $themeQuery = $themeQuery->where('category_id', (string)$cat['id']);
            }
        }

        $orderBy = match ($sort) {
            'popular' => 'views DESC',
            'rating' => 'rating DESC',
            'price-low' => 'price ASC',
            'price-high' => 'price DESC',
            default => 'created_at DESC',
        };

        $paginated = $themeQuery->paginate($page, 20, $orderBy);

        return $this->view('shop.search', [
            'themes' => $paginated['items'],
            'pagination' => $paginated['pagination'],
            'query' => $query,
            'category' => $category,
            'sort' => $sort,
        ]);
    }

    public function cart(Request $r): string
    {
        $cartItems = [];
        $total = 0;

        if (auth_check()) {
            $cartItems = (new Cart())->where('user_id', (string)auth_user()['id'])->get();
        } else {
            $cartItems = (new Cart())->where('session_id', session_id())->get();
        }

        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $this->view('shop.cart', [
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }

    public function addToCart(Request $r): string
    {
        csrf_verify();

        $themeId = (int)$this->input('theme_id');
        $theme = (new Theme())->find($themeId);

        if (!$theme) {
            if (is_ajax()) {
                return $this->json(['error' => 'Theme not found'], 404);
            }
            return $this->redirect('/cart')->with('error', 'Theme not found');
        }

        $userId = auth_user()['id'] ?? null;
        $sessionId = session_id();

        $db = Database::connection();
        $stmt = $db->prepare("SELECT * FROM cart_items WHERE theme_id = ? AND (user_id = ? OR session_id = ?) LIMIT 1");
        $stmt->execute([$themeId, $userId, $sessionId]);
        $existing = $stmt->fetch();

        if ($existing) {
            $db->prepare("UPDATE cart_items SET quantity = quantity + 1 WHERE id = ?")
                ->execute([$existing['id']]);
        } else {
            (new Cart())->create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'theme_id' => $themeId,
                'quantity' => 1,
                'price' => $theme['sale_price'] ?? $theme['price'],
            ]);
        }

        if (is_ajax()) {
            return $this->json(['success' => true, 'message' => 'Added to cart']);
        }

        return $this->redirect('/cart')->with('success', 'Added to cart');
    }

    public function removeFromCart(Request $r): string
    {
        $itemId = (int)$this->input('id');
        $item = (new Cart())->find($itemId);

        if ($item && (auth_check() && $item['user_id'] == auth_user()['id'])) {
            (new Cart())->delete($itemId);
        }

        return $this->redirect('/cart')->with('success', 'Item removed from cart');
    }

    public function clearCart(Request $r): string
    {
        if (auth_check()) {
            $userId = auth_user()['id'];
            Database::connection()->prepare("DELETE FROM cart_items WHERE user_id = ?")
                ->execute([$userId]);
        } else {
            Database::connection()->prepare("DELETE FROM cart_items WHERE session_id = ?")
                ->execute([session_id()]);
        }

        return $this->redirect('/cart')->with('success', 'Cart cleared');
    }

    public function wishlist(Request $r): string
    {
        auth_require();

        $items = (new Wishlist())->where('user_id', (string)auth_user()['id'])->get();

        return $this->view('shop.wishlist', ['items' => $items]);
    }

    public function addToWishlist(Request $r): string
    {
        csrf_verify();
        auth_require();

        $themeId = (int)$this->input('theme_id');

        $existing = (new Wishlist())->where('user_id', (string)auth_user()['id'])
            ->where('theme_id', (string)$themeId)->get();

        if (empty($existing)) {
            (new Wishlist())->create([
                'user_id' => auth_user()['id'],
                'theme_id' => $themeId,
            ]);
        }

        if (is_ajax()) {
            return $this->json(['success' => true]);
        }

        return $this->redirect('/wishlist')->with('success', 'Added to wishlist');
    }

    public function removeFromWishlist(Request $r): string
    {
        $itemId = (int)$this->input('id');
        $item = (new Wishlist())->find($itemId);

        if ($item && $item['user_id'] == auth_user()['id']) {
            (new Wishlist())->delete($itemId);
        }

        return $this->redirect('/wishlist')->with('success', 'Removed from wishlist');
    }
}
