<?php
declare(strict_types=1);

use ThemeHub\Core\Router;

$router = $GLOBALS['app_router'] ?? new Router();

$router->group(['prefix' => '/api'], function (Router $router): void {
    // Public API
    $router->get('/themes', 'ThemeHub\Controllers\Api\ApiController@themes');
    $router->get('/themes/{slug}', 'ThemeHub\Controllers\Api\ApiController@theme');
    $router->get('/categories', 'ThemeHub\Controllers\Api\ApiController@categories');
    $router->get('/search', 'ThemeHub\Controllers\Api\ApiController@search');
    $router->get('/reviews/{slug}', 'ThemeHub\Controllers\Api\ExtendedApiController@reviews');
    $router->get('/settings', 'ThemeHub\Controllers\Api\ApiController@settings');

    // Auth API
    $router->post('/auth/login', 'ThemeHub\Controllers\Api\ApiController@authenticate');
    $router->get('/auth/user', 'ThemeHub\Controllers\Api\ApiController@user', ['auth']);

    // Authenticated API
    $router->group(['middleware' => ['auth']], function (Router $router): void {
        $router->get('/orders', 'ThemeHub\Controllers\Api\ExtendedApiController@orders');
        $router->get('/wishlist', 'ThemeHub\Controllers\Api\ExtendedApiController@wishlist');
        $router->post('/wishlist/add', 'ThemeHub\Controllers\Api\ExtendedApiController@addToWishlist');
        $router->post('/wishlist/remove', 'ThemeHub\Controllers\Api\ExtendedApiController@removeFromWishlist');
    });
});
