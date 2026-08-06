<?php declare(strict_types=1);

use ThemeHub\Core\Auth;
use ThemeHub\Core\Csrf;
use ThemeHub\Core\Session;

try {
    $cartCount = (new \ThemeHub\Models\Cart())->where('user_id', (string)(auth_user()['id'] ?? 0))->count();
} catch (\Throwable) {
    $cartCount = 0;
}
try {
    $wishlistCount = count((new \ThemeHub\Models\Wishlist())->where('user_id', (string)(auth_user()['id'] ?? 0))->get());
} catch (\Throwable) {
    $wishlistCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="<?= Csrf::token() ?>">
  
  <title><?= seo_meta('title') ?> <?= isset($title) && $title !== seo_meta('title') ? '| ' . e($title) : '' ?></title>
  <meta name="description" content="<?= e(seo_meta('description')) ?>">
  <meta name="keywords" content="<?= e(seo_meta('keywords')) ?>">
  
  <!-- Open Graph -->
  <meta property="og:title" content="<?= e(seo_meta('title')) ?>">
  <meta property="og:description" content="<?= e(seo_meta('description')) ?>">
  <meta property="og:image" content="<?= e(seo_meta('og_image')) ?>">
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?= e(url($_SERVER['REQUEST_URI'] ?? '/')) ?>">
  
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e(seo_meta('title')) ?>">
  <meta name="twitter:description" content="<?= e(seo_meta('description')) ?>">
  <meta name="twitter:image" content="<?= e(seo_meta('og_image')) ?>">
  
  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/apple-touch-icon.png') ?>">
  
  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Inter', 'system-ui', 'sans-serif'],
            display: ['Playfair Display', 'serif'],
          },
          colors: {
            deep: '#050508',
            surface: '#0d0d14',
            elevated: '#13131f',
            glass: 'rgba(255,255,255,0.03)',
            'glass-border': 'rgba(255,255,255,0.08)',
            'glass-highlight': 'rgba(255,255,255,0.12)',
          },
          borderRadius: {
            '4xl': '32px',
            '3xl': '28px',
            '2xl': '24px',
            'xl': '20px',
          },
          animation: {
            'float': 'float 20s ease-in-out infinite',
            'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            'glow': 'glow 3s ease-in-out infinite alternate',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translate(0,0) scale(1)' },
              '33%': { transform: 'translate(30px,-30px) scale(1.05)' },
              '66%': { transform: 'translate(-20px,20px) scale(0.95)' },
            },
            glow: {
              '0%': { boxShadow: '0 0 20px rgba(99,102,241,0.3)' },
              '100%': { boxShadow: '0 0 40px rgba(139,92,246,0.5)' },
            }
          }
        }
      }
    }
  </script>
  
  <!-- Custom Styles -->
  <style>
    <?php require VIEW_PATH . '/partials/styles.css'; ?>
  </style>
  
  <!-- Additional Styles -->
  <?php if (isset($additionalStyles)): ?>
    <?= $additionalStyles ?>
  <?php endif; ?>
  
  <?= Csrf::meta() ?>
  
  <!-- Analytics -->
  <?= setting('google_analytics') ?>
  <?= setting('google_tag_manager') ?>
  <?= setting('facebook_pixel') ?>
</head>
<body class="bg-deep text-gray-100 antialiased min-h-screen">
  <?php require VIEW_PATH . '/partials/background.php'; ?>
  <?php require VIEW_PATH . '/partials/navbar.php'; ?>
  
  <main class="relative">
    <?= $content ?>
  </main>
  
  <?php require VIEW_PATH . '/partials/footer.php'; ?>
  
  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.2/dist/ScrollTrigger.min.js"></script>
  <script src="<?= asset('js/app.js') ?>?v=<?= filemtime(PUBLIC_PATH . '/assets/js/app.js') ?>"></script>
  
  <?php if (isset($additionalScripts)): ?>
    <?= $additionalScripts ?>
  <?php endif; ?>
</body>
</html>
