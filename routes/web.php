<?php
declare(strict_types=1);

use ThemeHub\Core\Router;

$router = $GLOBALS['app_router'] ?? new Router();

// ============================================
// WEB ROUTES
// ============================================

// Home
$router->get('/', 'ThemeHub\Controllers\Shop\ShopController@home');

// Shop
$router->get('/themes', 'ThemeHub\Controllers\Shop\ShopController@home');
$router->get('/theme/{slug}', 'ThemeHub\Controllers\Shop\ShopController@theme');
$router->get('/category/{slug}', 'ThemeHub\Controllers\Shop\ShopController@category');
$router->get('/search', 'ThemeHub\Controllers\Shop\ShopController@search');
$router->post('/search', 'ThemeHub\Controllers\Shop\ShopController@search');

// Cart
$router->get('/cart', 'ThemeHub\Controllers\Shop\ShopController@cart');
$router->post('/cart/add', 'ThemeHub\Controllers\Shop\ShopController@addToCart');
$router->post('/cart/remove', 'ThemeHub\Controllers\Shop\ShopController@removeFromCart');
$router->post('/cart/clear', 'ThemeHub\Controllers\Shop\ShopController@clearCart');

// Wishlist
$router->get('/wishlist', 'ThemeHub\Controllers\Shop\ShopController@wishlist');
$router->post('/wishlist/add', 'ThemeHub\Controllers\Shop\ShopController@addToWishlist');
$router->post('/wishlist/remove', 'ThemeHub\Controllers\Shop\ShopController@removeFromWishlist');

// Checkout
$router->get('/checkout', 'ThemeHub\Controllers\Checkout\CheckoutController@checkout', ['auth']);
$router->post('/checkout/coupon', 'ThemeHub\Controllers\Checkout\CheckoutController@applyCoupon', ['auth']);
$router->post('/checkout/place', 'ThemeHub\Controllers\Checkout\CheckoutController@placeOrder', ['auth']);
$router->get('/checkout/success', 'ThemeHub\Controllers\Checkout\CheckoutController@success', ['auth']);
$router->get('/order/{id}', 'ThemeHub\Controllers\Checkout\CheckoutController@order', ['auth']);
$router->get('/download/{id}', 'ThemeHub\Controllers\Checkout\CheckoutController@download', ['auth']);

// Auth
$router->get('/login', 'ThemeHub\Controllers\Auth\AuthController@login');
$router->post('/login', 'ThemeHub\Controllers\Auth\AuthController@doLogin');
$router->get('/demo-login', 'ThemeHub\Controllers\Auth\AuthController@demoLogin');
$router->get('/register', 'ThemeHub\Controllers\Auth\AuthController@register');
$router->post('/register', 'ThemeHub\Controllers\Auth\AuthController@doRegister');
$router->post('/logout', 'ThemeHub\Controllers\Auth\AuthController@logout');
$router->get('/forgot-password', 'ThemeHub\Controllers\Auth\AuthController@forgotPassword');
$router->post('/forgot-password', 'ThemeHub\Controllers\Auth\AuthController@resetPassword');

// Customer
$router->group(['middleware' => ['auth', 'customer']], function($router) {
    $router->get('/customer', 'ThemeHub\Controllers\Customer\CustomerController@dashboard');
    $router->get('/customer/orders', 'ThemeHub\Controllers\Customer\CustomerController@orders');
    $router->get('/customer/order/{id}', 'ThemeHub\Controllers\Customer\CustomerController@order');
    $router->get('/customer/downloads', 'ThemeHub\Controllers\Customer\CustomerController@downloads');
    $router->get('/customer/wishlist', 'ThemeHub\Controllers\Customer\CustomerController@wishlist');
    $router->get('/customer/profile', 'ThemeHub\Controllers\Customer\CustomerController@profile');
    $router->post('/customer/profile', 'ThemeHub\Controllers\Customer\CustomerController@updateProfile');
    $router->get('/customer/password', 'ThemeHub\Controllers\Customer\CustomerController@password');
    $router->post('/customer/password', 'ThemeHub\Controllers\Customer\CustomerController@updatePassword');
    $router->get('/customer/tickets', 'ThemeHub\Controllers\Customer\CustomerController@tickets');
    $router->get('/customer/tickets/create', 'ThemeHub\Controllers\Customer\CustomerController@createTicket');
    $router->post('/customer/tickets', 'ThemeHub\Controllers\Customer\CustomerController@storeTicket');
    $router->get('/customer/api-tokens', 'ThemeHub\Controllers\Customer\CustomerController@apiTokens');
});

// Vendor
$router->group(['middleware' => ['auth', 'vendor']], function($router) {
    $router->get('/vendor', 'ThemeHub\Controllers\Vendor\VendorController@dashboard');
    $router->get('/vendor/themes', 'ThemeHub\Controllers\Vendor\VendorController@themes');
    $router->get('/vendor/themes/create', 'ThemeHub\Controllers\Vendor\VendorController@createTheme');
    $router->post('/vendor/themes', 'ThemeHub\Controllers\Vendor\VendorController@storeTheme');
    $router->get('/vendor/themes/{id}/edit', 'ThemeHub\Controllers\Vendor\VendorController@editTheme');
    $router->post('/vendor/themes/{id}', 'ThemeHub\Controllers\Vendor\VendorController@updateTheme');
    $router->post('/vendor/themes/{id}/delete', 'ThemeHub\Controllers\Vendor\VendorController@deleteTheme');
    $router->get('/vendor/orders', 'ThemeHub\Controllers\Vendor\VendorController@orders');
    $router->get('/vendor/earnings', 'ThemeHub\Controllers\Vendor\VendorController@earnings');
    $router->get('/vendor/profile', 'ThemeHub\Controllers\Vendor\VendorController@profile');
    $router->post('/vendor/profile', 'ThemeHub\Controllers\Vendor\VendorController@updateProfile');
});

// Admin
$router->group(['middleware' => ['auth', 'admin']], function($router) {
    $router->get('/admin', 'ThemeHub\Controllers\Admin\AdminController@dashboard');
    
    // Themes
    $router->get('/admin/themes', 'ThemeHub\Controllers\Admin\AdminController@themes');
    $router->get('/admin/themes/create', 'ThemeHub\Controllers\Admin\AdminController@createTheme');
    $router->post('/admin/themes', 'ThemeHub\Controllers\Admin\AdminController@storeTheme');
    $router->get('/admin/themes/{id}/edit', 'ThemeHub\Controllers\Admin\AdminController@editTheme');
    $router->post('/admin/themes/{id}', 'ThemeHub\Controllers\Admin\AdminController@updateTheme');
    $router->post('/admin/themes/{id}/delete', 'ThemeHub\Controllers\Admin\AdminController@deleteTheme');
    
    // Categories
    $router->get('/admin/categories', 'ThemeHub\Controllers\Admin\AdminController@categories');
    
    // Orders
    $router->get('/admin/orders', 'ThemeHub\Controllers\Admin\AdminController@orders');
    $router->get('/admin/orders/export', 'ThemeHub\Controllers\Admin\AdminController@exportOrders');
    
    // Customers
    $router->get('/admin/customers', 'ThemeHub\Controllers\Admin\AdminController@customers');
    
    // Reviews
    $router->get('/admin/reviews', 'ThemeHub\Controllers\Admin\AdminController@reviews');
    $router->post('/admin/reviews/{id}/approve', 'ThemeHub\Controllers\Admin\AdminController@approveReview');
    $router->post('/admin/reviews/{id}/reject', 'ThemeHub\Controllers\Admin\AdminController@rejectReview');
    
    // Coupons
    $router->get('/admin/coupons', 'ThemeHub\Controllers\Admin\AdminController@coupons');
    $router->get('/admin/coupons/create', 'ThemeHub\Controllers\Admin\AdminController@createCoupon');
    $router->post('/admin/coupons', 'ThemeHub\Controllers\Admin\AdminController@storeCoupon');
    
    // Blog
    $router->get('/admin/blog', 'ThemeHub\Controllers\Admin\AdminController@blog');
    $router->get('/admin/blog/create', 'ThemeHub\Controllers\Admin\AdminController@createPost');
    $router->post('/admin/blog', 'ThemeHub\Controllers\Admin\AdminController@storePost');
    
    // Pages
    $router->get('/admin/pages', 'ThemeHub\Controllers\Admin\AdminController@pages');
    
    // Media
    $router->get('/admin/media', 'ThemeHub\Controllers\Admin\AdminController@media');
    $router->post('/admin/media/upload', 'ThemeHub\Controllers\Admin\AdminController@uploadMedia');
    
    // Settings
    $router->get('/admin/settings', 'ThemeHub\Controllers\Admin\AdminController@settings');
    $router->post('/admin/settings', 'ThemeHub\Controllers\Admin\AdminController@saveSettings');
    $router->get('/admin/seo', 'ThemeHub\Controllers\Admin\AdminController@seoSettings');
    $router->post('/admin/seo', 'ThemeHub\Controllers\Admin\AdminController@saveSeoSettings');
});

// ============================================
// API ROUTES
// ============================================

$router->group(['prefix' => '/api'], function($router) {
    // Public API
    $router->get('/themes', 'ThemeHub\Controllers\Api\ApiController@themes');
    $router->get('/themes/{slug}', 'ThemeHub\Controllers\Api\ApiController@theme');
    $router->get('/categories', 'ThemeHub\Controllers\Api\ApiController@categories');
    $router->get('/search', 'ThemeHub\Controllers\Api\ApiController@search');
    $router->get('/reviews/{slug}', 'ThemeHub\Controllers\Api\ApiController@reviews');
    $router->get('/settings', 'ThemeHub\Controllers\Api\ApiController@settings');
    
    // Auth API
    $router->post('/auth/login', 'ThemeHub\Controllers\Api\ApiController@authenticate');
    $router->get('/auth/user', 'ThemeHub\Controllers\Api\ApiController@user', ['auth']);
    
    // Authenticated API
    $router->group(['middleware' => ['auth']], function($router) {
        $router->get('/orders', 'ThemeHub\Controllers\Api\ExtendedApiController@orders');
        $router->get('/wishlist', 'ThemeHub\Controllers\Api\ExtendedApiController@wishlist');
        $router->post('/wishlist/add', 'ThemeHub\Controllers\Api\ExtendedApiController@addToWishlist');
        $router->post('/wishlist/remove', 'ThemeHub\Controllers\Api\ExtendedApiController@removeFromWishlist');
    });
});
