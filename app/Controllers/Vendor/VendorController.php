<?php
declare(strict_types=1);

namespace ThemeHub\Controllers\Vendor;

use ThemeHub\Core\{Controller, Validator, Uploader};
use ThemeHub\Models\{Theme, Order, Download, UserModel};

/**
 * Vendor Controller
 */
final class VendorController extends Controller
{
    public function dashboard(): string
    {
        auth_require('vendor');
        
        $userId = (string)auth_user()['id'];
        
        $stats = [
            'total_themes' => (new Theme())->count('developer_id = ?', [$userId]),
            'total_sales' => (new Theme())->where('developer_id', $userId),
            'total_views' => (new Theme())->where('developer_id', $userId),
            'total_downloads' => (new Download())->count('theme_id IN (SELECT id FROM themes WHERE developer_id = ?)', [$userId]),
        ];
        
        $themes = (new Theme())->where('developer_id', $userId);
        
        return $this->view('vendor.dashboard', [
            'stats' => $stats,
            'themes' => $themes,
        ]);
    }

    public function themes(): string
    {
        auth_require('vendor');
        
        $userId = (string)auth_user()['id'];
        $themes = (new Theme())->where('developer_id', $userId);
        
        return $this->view('vendor.themes.index', ['themes' => $themes]);
    }

    public function createTheme(): string
    {
        auth_require('vendor');
        
        $categories = (new \ThemeHub\Models\Category())->where('status', 'active');
        
        return $this->view('vendor.themes.create', ['categories' => $categories]);
    }

    public function storeTheme(): string
    {
        csrf_verify();
        auth_require('vendor');
        
        $rules = [
            'name' => 'required|min:2',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ];
        
        $validator = new Validator($this->request->all(), $rules);
        $validation = $validator->validate();
        
        if (!$validation['valid']) {
            return $this->redirect('/vendor/themes/create')->with('error', 'Validation failed');
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
        
        return $this->redirect('/vendor/themes')->with('success', 'Theme created successfully');
    }

    public function editTheme(int $id): string
    {
        auth_require('vendor');
        
        $theme = (new Theme())->find($id);
        
        if (!$theme || $theme['developer_id'] != auth_user()['id']) {
            abort(404);
        }
        
        $categories = (new \ThemeHub\Models\Category())->where('status', 'active');
        
        return $this->view('vendor.themes.edit', [
            'theme' => $theme,
            'categories' => $categories,
        ]);
    }

    public function updateTheme(int $id): string
    {
        csrf_verify();
        auth_require('vendor');
        
        $theme = (new Theme())->find($id);
        
        if (!$theme || $theme['developer_id'] != auth_user()['id']) {
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
        
        return $this->redirect('/vendor/themes')->with('success', 'Theme updated successfully');
    }

    public function deleteTheme(int $id): string
    {
        csrf_verify();
        auth_require('vendor');
        
        $theme = (new Theme())->find($id);
        
        if ($theme && $theme['developer_id'] == auth_user()['id']) {
            (new Theme())->delete($id);
        }
        
        return $this->redirect('/vendor/themes')->with('success', 'Theme deleted successfully');
    }

    public function orders(): string
    {
        auth_require('vendor');
        
        $orders = (new Order())->where('status', 'completed');
        
        return $this->view('vendor.orders', ['orders' => $orders]);
    }

    public function earnings(): string
    {
        auth_require('vendor');
        
        return $this->view('vendor.earnings');
    }

    public function profile(): string
    {
        auth_require('vendor');
        
        return $this->view('vendor.profile', ['user' => auth_user()]);
    }

    public function updateProfile(): string
    {
        csrf_verify();
        auth_require('vendor');
        
        $data = $this->request->all();
        
        (new UserModel())->update(auth_user()['id'], [
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
        
        return $this->redirect('/vendor/profile')->with('success', 'Profile updated successfully');
    }
}
