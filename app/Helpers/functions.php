<?php
declare(strict_types=1);

/**
 * ThemeHub Helper Functions
 */

// Environment helper
if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
}

// Config helper
if (!function_exists('config')) {
    function config(string $key, mixed $default = null): mixed {
        $parts = explode('.', $key);
        $value = $GLOBALS['config'] ?? [];
        
        foreach ($parts as $part) {
            if (!is_array($value) || !isset($value[$part])) {
                return $default;
            }
            $value = $value[$part];
        }
        
        return $value;
    }
}

// URL helper
if (!function_exists('url')) {
    function url(string $path = ''): string {
        $baseUrl = rtrim((string)config('app.url'), '/');
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

// Asset helper
if (!function_exists('asset')) {
    function asset(string $path): string {
        return url('assets/' . ltrim($path, '/'));
    }
}

// Upload helper
if (!function_exists('upload')) {
    function upload(string $path): string {
        return url('uploads/' . ltrim($path, '/'));
    }
}

// Escape helper
if (!function_exists('e')) {
    function e(mixed $value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

// CSRF token helper
if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string {
        return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
    }
}

// Old input helper
if (!function_exists('old')) {
    function old(string $key, mixed $default = ''): mixed {
        return $_SESSION['old'][$key] ?? $default;
    }
}

// Flash message helper
if (!function_exists('flash')) {
    function flash(string $key, mixed $value = null): mixed {
        if ($value === null) {
            $message = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        $_SESSION['flash'][$key] = $value;
        return null;
    }
}

// dd helper
if (!function_exists('dd')) {
    function dd(mixed ...$vars): never {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die;
    }
}

// JSON response helper
if (!function_exists('json_response')) {
    function json_response(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }
}

// Generate slug helper
if (!function_exists('slug')) {
    function slug(string $text): string {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = strtolower($text);
        return empty($text) ? 'n-a' : $text;
    }
}

// Currency helper
if (!function_exists('currency')) {
    function currency(float $amount, string $currency = 'USD'): string {
        $symbols = [
            'USD' => '$', 'EUR' => '€', 'GBP' => '£', 'INR' => '₹',
            'JPY' => '¥', 'CNY' => '¥', 'AUD' => 'A$', 'CAD' => 'C$'
        ];
        $symbol = $symbols[$currency] ?? $currency;
        return $symbol . number_format($amount, 2);
    }
}

// Pagination helper
if (!function_exists('paginate')) {
    function paginate(int $total, int $page = 1, int $perPage = 20): array {
        $totalPages = (int)ceil($total / $perPage);
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        
        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'offset' => $offset,
            'has_prev' => $page > 1,
            'has_next' => $page < $totalPages,
            'prev_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $totalPages ? $page + 1 : null,
        ];
    }
}

// Verify CSRF helper
if (!function_exists('verify_csrf')) {
    function verify_csrf(string $token): bool {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Generate JWT helper
if (!function_exists('generate_jwt')) {
    function generate_jwt(array $payload, string $secret): string {
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $headerEncoded = base64_encode(json_encode($header));
        $payloadEncoded = base64_encode(json_encode($payload));
        $signature = base64_encode(hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true));
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signature;
    }
}

// Verify JWT helper
if (!function_exists('verify_jwt')) {
    function verify_jwt(string $token, string $secret): ?array {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return null;
        }
        
        [$headerEncoded, $payloadEncoded, $signature] = $parts;
        $expectedSignature = base64_encode(hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, $secret, true));
        
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }
        
        $payload = json_decode(base64_decode($payloadEncoded), true);
        
        if ($payload['exp'] < time()) {
            return null;
        }
        
        return $payload;
    }
}

// Rate limiter
if (!function_exists('rate_limit')) {
    function rate_limit(string $key, int $maxRequests = 60, int $window = 60): bool {
        $cacheFile = STORAGE_PATH . '/cache/ratelimit_' . md5($key) . '.json';
        $now = time();
        
        $data = [];
        if (is_file($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true) ?: [];
        }
        
        $data = array_filter($data, fn($time) => $time > $now - $window);
        $data[] = $now;
        
        if (count($data) > $maxRequests) {
            return false;
        }
        
        file_put_contents($cacheFile, json_encode($data));
        return true;
    }
}

// Send email helper
if (!function_exists('send_email')) {
    function send_email(string $to, string $subject, string $view, array $data = []): bool {
        $body = view($view, $data, true);
        
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . (config('mail.from_name', 'ThemeHub')) . ' <' . (config('mail.from_email', 'noreply@themehub.com')) . '>',
            'Reply-To: ' . (config('mail.from_email', 'noreply@themehub.com')),
            'X-Mailer: ThemeHub/' . (config('app.version', '1.0.0'))
        ];
        
        return mail($to, $subject, $body, implode("\r\n", $headers));
    }
}

// View render helper
if (!function_exists('view')) {
    function view(string $view, array $data = [], bool $return = false): ?string {
        extract($data);
        ob_start();
        $viewFile = VIEW_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!is_file($viewFile)) {
            throw new \Exception("View not found: {$view}");
        }
        
        require $viewFile;
        $output = ob_get_clean();
        
        if ($return) {
            return $output;
        }
        
        echo $output;
        return null;
    }
}

// Redirect helper
if (!function_exists('redirect')) {
    function redirect(string $to, int $status = 302): void {
        http_response_code($status);
        header('Location: ' . $to);
        exit;
    }
}

// Back helper
if (!function_exists('back')) {
    function back(): void {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        redirect($referer);
    }
}

// Asset version helper
if (!function_exists('asset_version')) {
    function asset_version(string $file): string {
        $filePath = PUBLIC_PATH . '/' . $file;
        if (is_file($filePath)) {
            return asset($file) . '?v=' . filemtime($filePath);
        }
        return asset($file);
    }
}

// Cache helper
if (!function_exists('cache')) {
    function cache(string $key, mixed $value = null, int $ttl = 3600): mixed {
        $cacheFile = STORAGE_PATH . '/cache/' . md5($key) . '.cache';
        
        if ($value === null) {
            if (!is_file($cacheFile)) {
                return null;
            }
            $data = json_decode(file_get_contents($cacheFile), true);
            if ($data['expires'] < time()) {
                unlink($cacheFile);
                return null;
            }
            return $data['value'];
        }
        
        $data = [
            'value' => $value,
            'expires' => time() + $ttl
        ];
        
        if (!is_dir(dirname($cacheFile))) {
            mkdir(dirname($cacheFile), 0755, true);
        }
        
        file_put_contents($cacheFile, json_encode($data));
        return $value;
    }
}

// Log helper
if (!function_exists('log_message')) {
    function log_message(string $level, string $message, array $context = []): void {
        $logFile = STORAGE_PATH . '/logs/' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? json_encode($context) : '';
        $line = "[{$timestamp}] [{$level}] {$message} {$contextStr}\n";
        
        file_put_contents($logFile, $line, FILE_APPEND | LOCK_EX);
    }
}

// Debug helper
if (!function_exists('debug')) {
    function debug(mixed $var, bool $die = false): void {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
        
        if ($die) {
            die;
        }
    }
}

// Client IP helper
if (!function_exists('client_ip')) {
    function client_ip(): string {
        $headers = [
            'HTTP_CF_CONNECTING_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_REAL_IP',
            'REMOTE_ADDR'
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim($ips[0]);
            }
        }
        
        return '0.0.0.0';
    }
}

// Sanitize helper
if (!function_exists('sanitize')) {
    function sanitize(string $string): string {
        return htmlspecialchars(strip_tags(trim($string)), ENT_QUOTES, 'UTF-8');
    }
}

// Random string helper
if (!function_exists('random_string')) {
    function random_string(int $length = 32): string {
        return bin2hex(random_bytes($length / 2));
    }
}

// Check if request is AJAX
if (!function_exists('is_ajax')) {
    function is_ajax(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}

// Check if request is API
if (!function_exists('is_api')) {
    function is_api(): bool {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        return str_starts_with($path, '/api/');
    }
}

// Abort helper
if (!function_exists('abort')) {
    function abort(int $status, string $message = ''): void {
        http_response_code($status);
        if ($message) {
            echo $message;
        }
        exit;
    }
}

// Verify CSRF for web forms
if (!function_exists('csrf_verify')) {
    function csrf_verify(): void {
        $token = $_POST['_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (!verify_csrf($token)) {
            if (is_ajax() || is_api()) {
                json_response(['error' => 'CSRF token mismatch'], 419);
            }
            abort(419, 'CSRF Token Mismatch');
        }
    }
}

// Check authentication
if (!function_exists('auth_check')) {
    function auth_check(): bool {
        return !empty($_SESSION['user_id']);
    }
}

// Get authenticated user
if (!function_exists('auth_user')) {
    function auth_user(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

// Check if user has role
if (!function_exists('has_role')) {
    function has_role(string|array $roles): bool {
        $user = auth_user();
        if (!$user) {
            return false;
        }
        
        $roles = (array) $roles;
        return in_array($user['role'], $roles, true);
    }
}

// Require authentication
if (!function_exists('auth_require')) {
    function auth_require(string|array $roles = []): void {
        if (!auth_check()) {
            if (is_ajax() || is_api()) {
                json_response(['error' => 'Unauthorized'], 401);
            }
            redirect('/login');
        }
        
        if (!empty($roles) && !has_role($roles)) {
            if (is_ajax() || is_api()) {
                json_response(['error' => 'Forbidden'], 403);
            }
            abort(403, 'Forbidden');
        }
    }
}

// Number format compact
if (!function_exists('compact_number')) {
    function compact_number(int|float $number): string {
        if ($number >= 1000000) {
            return round($number / 1000000, 1) . 'M';
        }
        if ($number >= 1000) {
            return round($number / 1000, 1) . 'K';
        }
        return (string)$number;
    }
}

// Generate breadcrumb
if (!function_exists('breadcrumbs')) {
    function breadcrumbs(): array {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $parts = array_filter(explode('/', $path));
        $breadcrumbs = [['title' => 'Home', 'url' => '/']];
        $url = '';
        
        foreach ($parts as $part) {
            $url .= '/' . $part;
            $title = ucfirst(str_replace('-', ' ', $part));
            $breadcrumbs[] = ['title' => $title, 'url' => $url];
        }
        
        return $breadcrumbs;
    }
}

// SEO Meta helper
if (!function_exists('seo_meta')) {
    function seo_meta(string $type = 'title'): string {
        $settings = \ThemeHub\Models\Setting::getMany([
            'seo_title', 'seo_description', 'seo_keywords', 'seo_og_image'
        ]);
        
        return match ($type) {
            'title' => $settings['seo_title'] ?? config('app.name', 'ThemeHub'),
            'description' => $settings['seo_description'] ?? config('app.description', 'Premium Theme Marketplace'),
            'keywords' => $settings['seo_keywords'] ?? 'themes, templates, marketplace',
            'og_image' => $settings['seo_og_image'] ?? asset('images/og-image.jpg'),
            default => ''
        };
    }
}

// Get setting helper
if (!function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed {
        return \ThemeHub\Models\Setting::getValue($key, $default);
    }
}

// Theme price helper
if (!function_exists('theme_price')) {
    function theme_price(float $price, ?float $salePrice = null, string $currency = 'USD'): array {
        $hasDiscount = $salePrice !== null && $salePrice < $price;
        $finalPrice = $hasDiscount ? $salePrice : $price;
        $discountPercent = $hasDiscount ? round((($price - $salePrice) / $price) * 100) : 0;
        
        return [
            'original' => $price,
            'sale' => $salePrice,
            'final' => $finalPrice,
            'has_discount' => $hasDiscount,
            'discount_percent' => $discountPercent,
            'formatted' => currency($finalPrice, $currency),
            'original_formatted' => $hasDiscount ? currency($price, $currency) : null,
        ];
    }
}

// Check maintenance mode
if (!function_exists('maintenance_mode')) {
    function maintenance_mode(): bool {
        try {
            return (bool)setting('maintenance_mode', false);
        } catch (\Throwable) {
            return false;
        }
    }
}

// Star rating helper
if (!function_exists('star_rating')) {
    function star_rating(float $rating, int $max = 5): string {
        $html = '';
        for ($i = 1; $i <= $max; $i++) {
            if ($i <= floor($rating)) {
                $html .= '<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
            } elseif ($i - 0.5 <= $rating) {
                $html .= '<svg class="w-5 h-5 text-yellow-400 fill-current" viewBox="0 0 20 20"><defs><linearGradient id="half"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="#d1d5db"/></linearGradient></defs><path fill="url(#half)" d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
            } else {
                $html .= '<svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>';
            }
        }
        return $html;
    }
}

// JSON helper for API
if (!function_exists('json')) {
    function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_THROW_ON_ERROR);
        exit;
    }
}

// Format bytes helper
if (!function_exists('format_bytes')) {
    function format_bytes(int $bytes, int $precision = 2): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// Truncate text helper
if (!function_exists('truncate')) {
    function truncate(string $text, int $length = 100, string $suffix = '...'): string {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }
}
