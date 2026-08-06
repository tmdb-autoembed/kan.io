<?php
declare(strict_types=1);

/**
 * ThemeHub Bootstrap & Autoloader
 */

// Application constants
define('APP_ROOT', dirname(__DIR__));
define('APP_PATH', APP_ROOT . '/app');
define('CONFIG_PATH', APP_ROOT . '/config');
define('STORAGE_PATH', APP_ROOT . '/storage');
define('PUBLIC_PATH', APP_ROOT . '/public');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('VIEW_PATH', APP_PATH . '/Views');

// Load .env file
$envFile = APP_ROOT . '/.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if (!putenv("{$key}={$value}")) {
            $_ENV[$key] = $value;
        }
    }
}

// Autoloader
spl_autoload_register(function (string $class): void {
    $prefix = 'ThemeHub\\';
    $baseDir = APP_PATH . '/';
    
    if (str_starts_with($class, $prefix)) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

// Load helpers
$helpers = glob(APP_PATH . '/Helpers/*.php');
foreach ($helpers as $helper) {
    require $helper;
}

// Load config
$configFiles = glob(CONFIG_PATH . '/*.php');
foreach ($configFiles as $configFile) {
    $key = basename($configFile, '.php');
    $GLOBALS['config'][$key] = require $configFile;
}

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 86400 * 30,
        'path' => '/',
        'domain' => '',
        'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
