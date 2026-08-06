<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Admin;
use ThemeHub\Core\{Controller, Database, Request};
use ThemeHub\Models\{Theme, Category, Order, UserModel, Setting, Review, Coupon, Post, Page, Media, Vendor};

class AdminController extends Controller
{
    public function dashboard()
    {
        $db = Database::pdo();
        $sales = $db->query('select coalesce(sum(total),0) t from orders')->fetch()['t'];
        return $this->view('admin/dashboard', [
            'stats' => [
                'total_sales' => $sales,
                'total_orders' => (new Order)->count(),
                'total_themes' => (new Theme)->count(),
                'total_users' => (new UserModel)->count(),
                'recent_orders' => (new Order)->orderBy('created_at', 'DESC')->limit(5)->get(),
                'top_themes' => (new Theme)->orderBy('sales', 'DESC')->limit(5)->get(),
                'pending_reviews' => (new Review)->where('status', 'pending')->count(),
            ]
        ]);
    }

    public function themes()
    {
        $page = max(1, (int)($this->input('page', 1)));
        $result = (new Theme)->orderBy('created_at', 'DESC')->paginate($page, 10);
        return $this->view('admin/themes/index', [
            'themes' => $result['items'],
            'pagination' => $result['pagination']
        ]);
    }

    public function createTheme()
    {
        return $this->view('admin/themes/create', [
            'theme' => null,
            'categories' => (new Category)->where('status', 'active')->get()
        ]);
    }

    public function storeTheme(Request $r)
    {
        csrf_verify();
        $data = [
            'name' => $r->input('name'),
            'slug' => strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $r->input('name')), '-')),
            'description' => $r->input('description', ''),
            'price' => (float)($r->input('price', 0)),
            'sale_price' => (float)($r->input('sale_price', 0)),
            'category_id' => (int)($r->input('category_id', 1)),
            'version' => $r->input('version', '1.0.0'),
            'license' => $r->input('license', 'regular'),
            'demo_url' => $r->input('demo_url', ''),
            'status' => $r->input('status', 'draft'),
            'featured' => (bool)($r->input('featured', false)),
            'trending' => (bool)($r->input('trending', false)),
            'created_by' => auth_user()['id'] ?? 1,
        ];

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['thumbnail'], 'themes');
            if ($upload) {
                $data['thumbnail'] = $upload;
            }
        }

        (new Theme)->create($data);
        return $this->redirect('/admin/themes')->with('success', 'Theme created successfully');
    }

    public function editTheme(Request $r, int $id)
    {
        $theme = (new Theme)->find($id);
        if (!$theme) {
            return $this->redirect('/admin/themes')->with('error', 'Theme not found');
        }
        return $this->view('admin/themes/create', [
            'theme' => $theme,
            'categories' => (new Category)->where('status', 'active')->get()
        ]);
    }

    public function updateTheme(Request $r, int $id)
    {
        csrf_verify();
        $theme = (new Theme)->find($id);
        if (!$theme) {
            return $this->redirect('/admin/themes')->with('error', 'Theme not found');
        }

        $data = [
            'name' => $r->input('name'),
            'description' => $r->input('description', ''),
            'price' => (float)($r->input('price', 0)),
            'sale_price' => (float)($r->input('sale_price', 0)),
            'category_id' => (int)($r->input('category_id', 1)),
            'version' => $r->input('version', '1.0.0'),
            'license' => $r->input('license', 'regular'),
            'demo_url' => $r->input('demo_url', ''),
            'status' => $r->input('status', 'draft'),
            'featured' => (bool)($r->input('featured', false)),
            'trending' => (bool)($r->input('trending', false)),
        ];

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['thumbnail'], 'themes');
            if ($upload) {
                $data['thumbnail'] = $upload;
            }
        }

        (new Theme)->update($id, $data);
        return $this->redirect('/admin/themes')->with('success', 'Theme updated successfully');
    }

    public function deleteTheme(Request $r, int $id)
    {
        csrf_verify();
        (new Theme)->delete($id);
        return $this->redirect('/admin/themes')->with('success', 'Theme deleted successfully');
    }

    public function categories()
    {
        $categories = (new Category)->orderBy('sort_order', 'ASC')->get();
        return $this->view('admin/categories/index', ['categories' => $categories]);
    }

    public function orders()
    {
        $orders = (new Order)->orderBy('created_at', 'DESC')->limit(50)->get();
        return $this->view('admin/orders/index', ['orders' => $orders]);
    }

    public function exportOrders()
    {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=orders.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Order #', 'Customer', 'Total', 'Status', 'Payment', 'Date']);
        foreach ((new Order)->orderBy('created_at', 'DESC')->limit(100)->get() as $o) {
            fputcsv($out, [$o['order_number'], $o['billing_name'] ?? 'N/A', $o['total'], $o['status'], $o['payment_method'] ?? 'N/A', $o['created_at'] ?? '']);
        }
        fclose($out);
        return '';
    }

    public function customers()
    {
        $users = (new UserModel)->where('role', 'customer')->orderBy('created_at', 'DESC')->limit(50)->get();
        return $this->view('admin/customers/index', ['users' => $users, 'role' => 'customer']);
    }

    public function reviews()
    {
        $status = $this->input('status', 'pending');
        $reviews = (new Review)->where('status', $status)->orderBy('created_at', 'DESC')->limit(50)->get();
        return $this->view('admin/reviews/index', ['reviews' => $reviews, 'status' => $status]);
    }

    public function approveReview(Request $r, int $id)
    {
        csrf_verify();
        (new Review)->update($id, ['status' => 'approved']);
        return $this->redirect('/admin/reviews')->with('success', 'Review approved');
    }

    public function rejectReview(Request $r, int $id)
    {
        csrf_verify();
        (new Review)->update($id, ['status' => 'rejected']);
        return $this->redirect('/admin/reviews')->with('success', 'Review rejected');
    }

    public function coupons()
    {
        $coupons = (new Coupon)->orderBy('created_at', 'DESC')->get();
        return $this->view('admin/coupons/index', ['coupons' => $coupons]);
    }

    public function createCoupon()
    {
        return $this->view('admin/coupons/create');
    }

    public function storeCoupon(Request $r)
    {
        csrf_verify();
        $data = [
            'code' => strtoupper($r->input('code')),
            'type' => $r->input('type', 'percent'),
            'value' => (float)($r->input('value', 0)),
            'min_amount' => (float)($r->input('min_amount', 0)),
            'starts_at' => $r->input('starts_at') ? date('Y-m-d H:i:s', strtotime($r->input('starts_at'))) : null,
            'expires_at' => $r->input('expires_at') ? date('Y-m-d H:i:s', strtotime($r->input('expires_at'))) : null,
            'usage_limit' => (int)($r->input('usage_limit', 0)),
            'status' => $r->input('status', 'active'),
            'created_by' => auth_user()['id'] ?? 1,
        ];
        (new Coupon)->create($data);
        return $this->redirect('/admin/coupons')->with('success', 'Coupon created successfully');
    }

    public function blog()
    {
        $posts = (new Post)->orderBy('created_at', 'DESC')->limit(20)->get();
        return $this->view('admin/blog/index', ['posts' => $posts]);
    }

    public function createPost()
    {
        return $this->view('admin/blog/create');
    }

    public function storePost(Request $r)
    {
        csrf_verify();
        $data = [
            'title' => $r->input('title'),
            'slug' => strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $r->input('title')), '-')),
            'excerpt' => $r->input('excerpt', ''),
            'content' => $r->input('content', ''),
            'status' => $r->input('status', 'draft'),
            'author_id' => auth_user()['id'] ?? 1,
        ];

        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['featured_image'], 'blog');
            if ($upload) {
                $data['featured_image'] = $upload;
            }
        }

        (new Post)->create($data);
        return $this->redirect('/admin/blog')->with('success', 'Post created successfully');
    }

    public function pages()
    {
        $pages = (new Page)->orderBy('sort_order', 'ASC')->get();
        return $this->view('admin/pages/index', ['pages' => $pages]);
    }

    public function media()
    {
        $media = (new Media)->orderBy('created_at', 'DESC')->limit(50)->get();
        return $this->view('admin/media/index', ['media' => $media]);
    }

    public function uploadMedia(Request $r)
    {
        csrf_verify();
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload = upload_file($_FILES['file'], 'media');
            if ($upload) {
                return $this->redirect('/admin/media')->with('success', 'Media uploaded successfully');
            }
        }
        return $this->redirect('/admin/media')->with('error', 'Upload failed');
    }

    public function seoSettings()
    {
        return $this->view('admin/seo', [
            'settings' => [
                'seo_title' => Setting::get('seo_title', ''),
                'seo_description' => Setting::get('seo_description', ''),
                'seo_keywords' => Setting::get('seo_keywords', ''),
                'google_analytics' => Setting::get('google_analytics', ''),
                'google_tag_manager' => Setting::get('google_tag_manager', ''),
                'facebook_pixel' => Setting::get('facebook_pixel', ''),
            ]
        ]);
    }

    public function saveSeoSettings(Request $r)
    {
        csrf_verify();
        foreach ($r->all() as $key => $value) {
            if (is_string($value)) {
                Setting::set($key, $value);
            }
        }
        return $this->redirect('/admin/seo')->with('success', 'SEO settings saved successfully');
    }
}