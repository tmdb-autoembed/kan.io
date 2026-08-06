<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Admin;

use ThemeHub\Core\{Controller, Database, Validator, Uploader};
use ThemeHub\Models\{
    Theme, Category, UserModel, Order, Review, Coupon,
    Setting, Post, Page, Media
};

/**
 * Admin Controller
 */
final class AdminController extends Controller
{
    public function dashboard(): string
    {
        auth_require('admin');
        
        $db = Database::connection();
        
        $stats = [
            'total_sales' => (float)$db->query('SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE status = "completed"')->fetch()['total'],
            'total_orders' => (int)$db->query('SELECT COUNT(*) as count FROM orders')->fetch()['count'],
            'total_themes' => (int)$db->query('SELECT COUNT(*) as count FROM themes')->fetch()['count'],
            'total_users' => (int)$db->query('SELECT COUNT(*) as count FROM users')->fetch()['count'],
            'total_reviews' => (int)$db->query('SELECT COUNT(*) as count FROM reviews')->fetch()['count'],
            'pending_reviews' => (int)$db->query('SELECT COUNT(*) as count FROM reviews WHERE status = "pending"')->fetch()['count'],
            'recent_orders' => (new Order())->orderBy('created_at', 'DESC')->limit(10)->get(),
            'recent_users' => (new UserModel())->orderBy('created_at', 'DESC')->limit(10)->get(),
            'top_themes' => $db->query('SELECT * FROM themes ORDER BY sales DESC LIMIT 5')->fetchAll(),
            'recent_reviews' => (new Review())->orderBy('created_at', 'DESC')->limit(10)->get(),
        ];
        
        return $this->view('admin.dashboard', ['stats' => $stats]);
    }

    public function themes(): string
    {
        auth_require('admin');
        
        $page = (int)$this->input('page', 1);
        $paginated = (new Theme())->paginate($page, 20);
        
        return $this->view('admin.themes.index', [
            'themes' => $paginated['items'],
            'pagination' => $paginated['pagination'],
        ]);
    }

    public function createTheme(): string
    {
        auth_require('admin');
        return $this->view('admin.themes.create');
    }

    public function storeTheme(): string
    {
        csrf_verify();
        auth_require('admin');
        
        $rules = [
            'name' => 'required|min:2',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ];
        
        $validator = new Validator($this->request->all(), $rules);
        $validation = $validator->validate();
        
        if (!$validation['valid']) {
            return $this->redirect('/admin/themes/create')->with('error', 'Validation failed');
        }
        
        $data = $this->request->all();
        
        $thumbnail = null;
        if ($this->request->file('thumbnail')) {
            $thumbnail = Uploader::image($this->request->file('thumbnail'), 'thumbnails');
        }
        
        $themeData = [
            'name' => $data['name'],
            'slug' => slug($data['name']),
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'thumbnail' => $thumbnail,
            'demo_url' => $data['demo_url'] ?? '',
            'version' => $data['version'] ?? '1.0.0',
            'license' => $data['license'] ?? 'regular',
            'status' => $data['status'] ?? 'draft',
            'featured' => $data['featured'] ?? 0,
            'trending' => $data['trending'] ?? 0,
            'category_id' => $data['category_id'],
            'developer_id' => auth_user()['id'],
            'compatible_browsers' => $data['compatible_browsers'] ?? '',
            'compatible_php' => $data['compatible_php'] ?? '',
            'created_by' => auth_user()['id'],
        ];
        
        (new Theme())->create($themeData);
        
        return $this->redirect('/admin/themes')->with('success', 'Theme created successfully');
    }

    public function editTheme(int $id): string
    {
        auth_require('admin');
        $theme = (new Theme())->find($id);
        
        if (!$theme) {
            abort(404);
        }
        
        return $this->view('admin.themes.edit', ['theme' => $theme]);
    }

    public function updateTheme(int $id): string
    {
        csrf_verify();
        auth_require('admin');
        
        $theme = (new Theme())->find($id);
        if (!$theme) {
            abort(404);
        }
        
        $data = $this->request->all();
        
        $themeData = [
            'name' => $data['name'],
            'slug' => slug($data['name']),
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'demo_url' => $data['demo_url'] ?? '',
            'version' => $data['version'] ?? '1.0.0',
            'license' => $data['license'] ?? 'regular',
            'status' => $data['status'] ?? 'draft',
            'featured' => $data['featured'] ?? 0,
            'trending' => $data['trending'] ?? 0,
            'category_id' => $data['category_id'],
            'compatible_browsers' => $data['compatible_browsers'] ?? '',
            'compatible_php' => $data['compatible_php'] ?? '',
        ];
        
        if ($this->request->file('thumbnail')) {
            $themeData['thumbnail'] = Uploader::image($this->request->file('thumbnail'), 'thumbnails');
        }
        
        (new Theme())->update($id, $themeData);
        
        return $this->redirect('/admin/themes')->with('success', 'Theme updated successfully');
    }

    public function deleteTheme(int $id): string
    {
        csrf_verify();
        auth_require('admin');
        
        (new Theme())->delete($id);
        
        return $this->redirect('/admin/themes')->with('success', 'Theme deleted successfully');
    }

    public function categories(): string
    {
        auth_require('admin');
        
        $categories = (new Category())->all();
        
        return $this->view('admin.categories.index', ['categories' => $categories]);
    }

    public function orders(): string
    {
        auth_require('admin');
        
        $page = (int)$this->input('page', 1);
        $status = $this->input('status', '');
        
        $query = (new Order())->orderBy('created_at', 'DESC');
        
        if ($status) {
            $query = $query->where('status', $status);
        }
        
        $paginated = $query->paginate($page, 20);
        
        return $this->view('admin.orders.index', [
            'orders' => $paginated['items'],
            'pagination' => $paginated['pagination'],
            'status' => $status,
        ]);
    }

    public function customers(): string
    {
        auth_require('admin');
        
        $page = (int)$this->input('page', 1);
        $role = $this->input('role', 'customer');
        
        $users = (new UserModel())->where('role', $role);
        $paginated = paginate(count($users), $page, 20);
        
        return $this->view('admin.customers.index', [
            'users' => array_slice($users, $paginated['offset'], $paginated['per_page']),
            'pagination' => $paginated,
            'role' => $role,
        ]);
    }

    public function reviews(): string
    {
        auth_require('admin');
        
        $status = $this->input('status', 'pending');
        $reviews = (new Review())->where('status', $status);
        
        return $this->view('admin.reviews.index', [
            'reviews' => $reviews,
            'status' => $status,
        ]);
    }

    public function approveReview(int $id): string
    {
        csrf_verify();
        auth_require('admin');
        
        (new Review())->update($id, ['status' => 'approved']);
        
        return $this->redirect('/admin/reviews')->with('success', 'Review approved');
    }

    public function rejectReview(int $id): string
    {
        csrf_verify();
        auth_require('admin');
        
        (new Review())->update($id, ['status' => 'rejected']);
        
        return $this->redirect('/admin/reviews')->with('success', 'Review rejected');
    }

    public function coupons(): string
    {
        auth_require('admin');
        
        $coupons = (new Coupon())->all();
        
        return $this->view('admin.coupons.index', ['coupons' => $coupons]);
    }

    public function createCoupon(): string
    {
        auth_require('admin');
        return $this->view('admin.coupons.create');
    }

    public function storeCoupon(): string
    {
        csrf_verify();
        auth_require('admin');
        
        $data = $this->request->all();
        
        (new Coupon())->create([
            'code' => strtoupper($data['code']),
            'type' => $data['type'],
            'value' => $data['value'],
            'min_amount' => $data['min_amount'] ?? null,
            'max_amount' => $data['max_amount'] ?? null,
            'starts_at' => $data['starts_at'] ?? null,
            'expires_at' => $data['expires_at'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
            'status' => $data['status'] ?? 'active',
            'created_by' => auth_user()['id'],
        ]);
        
        return $this->redirect('/admin/coupons')->with('success', 'Coupon created successfully');
    }

    public function settings(): string
    {
        auth_require('admin');
        
        $settings = Setting::getAllSettings();
        
        return $this->view('admin.settings', ['settings' => $settings]);
    }

    public function saveSettings(): string
    {
        csrf_verify();
        auth_require('admin');
        
        $data = $this->request->all();
        
        foreach ($data as $key => $value) {
            if (!in_array($key, ['_token', '_method'])) {
                $type = is_array($value) || is_numeric($value) ? 'json' : 'string';
                Setting::set($key, $value, $type);
            }
        }
        
        return $this->redirect('/admin/settings')->with('success', 'Settings saved successfully');
    }

    public function media(): string
    {
        auth_require('admin');
        
        $page = (int)$this->input('page', 1);
        $media = (new Media())->orderBy('created_at', 'DESC')->paginate($page, 30, 'created_at DESC');
        
        return $this->view('admin.media.index', [
            'media' => $media['items'],
            'pagination' => $media['pagination'],
        ]);
    }

    public function uploadMedia(): string
    {
        csrf_verify();
        auth_require('admin');
        
        if ($this->request->file('file')) {
            $media = Media::upload($this->request->file('file'), 'media');
            
            if ($media && is_ajax()) {
                return $this->json([
                    'success' => true,
                    'media' => $media,
                ]);
            }
        }
        
        return $this->redirect('/admin/media')->with('error', 'Upload failed');
    }

    public function blog(): string
    {
        auth_require('admin');
        
        $page = (int)$this->input('page', 1);
        $posts = (new Post())->orderBy('created_at', 'DESC')->paginate($page, 20, 'created_at DESC');
        
        return $this->view('admin.blog.index', [
            'posts' => $posts['items'],
            'pagination' => $posts['pagination'],
        ]);
    }

    public function createPost(): string
    {
        auth_require('admin');
        return $this->view('admin.blog.create');
    }

    public function storePost(): string
    {
        csrf_verify();
        auth_require('admin');
        
        $data = $this->request->all();
        
        $featuredImage = null;
        if ($this->request->file('featured_image')) {
            $featuredImage = Uploader::image($this->request->file('featured_image'), 'blog');
        }
        
        (new Post())->create([
            'title' => $data['title'],
            'slug' => slug($data['title']),
            'excerpt' => $data['excerpt'] ?? '',
            'content' => $data['content'],
            'featured_image' => $featuredImage,
            'author_id' => auth_user()['id'],
            'status' => $data['status'] ?? 'draft',
            'meta_title' => $data['meta_title'] ?? $data['title'],
            'meta_description' => $data['meta_description'] ?? '',
            'published_at' => $data['status'] === 'published' ? date('Y-m-d H:i:s') : null,
        ]);
        
        return $this->redirect('/admin/blog')->with('success', 'Post created successfully');
    }

    public function pages(): string
    {
        auth_require('admin');
        
        $pages = (new Page())->all();
        
        return $this->view('admin.pages.index', ['pages' => $pages]);
    }

    public function seoSettings(): string
    {
        auth_require('admin');
        
        $settings = Setting::getMany([
            'seo_title', 'seo_description', 'seo_keywords', 'seo_og_image',
            'google_analytics', 'google_tag_manager', 'facebook_pixel'
        ]);
        
        return $this->view('admin.seo', ['settings' => $settings]);
    }

    public function saveSeoSettings(): string
    {
        csrf_verify();
        auth_require('admin');
        
        $data = $this->request->all();
        
        foreach ($data as $key => $value) {
            if (!in_array($key, ['_token', '_method'])) {
                Setting::set($key, $value);
            }
        }
        
        return $this->redirect('/admin/seo')->with('success', 'SEO settings saved');
    }

    public function exportOrders(): void
    {
        auth_require('admin');
        
        $orders = (new Order())->all();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Order #', 'Customer', 'Email', 'Total', 'Status', 'Date']);
        
        foreach ($orders as $order) {
            $user = (new UserModel())->find((int)$order['user_id']);
            fputcsv($output, [
                $order['order_number'],
                $user['name'] ?? 'N/A',
                $user['email'] ?? 'N/A',
                $order['total'],
                $order['status'],
                $order['created_at'],
            ]);
        }
        
        fclose($output);
        exit;
    }
}
