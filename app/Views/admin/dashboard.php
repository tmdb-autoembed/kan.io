<?php /** @var array $stats */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Dashboard</h1>
      <p class="text-gray-400 mt-2">Welcome back, <?= e(auth_user()['name']) ?></p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-purple-500/10 border border-indigo-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
          </div>
        </div>
        <div class="text-sm text-gray-500 mb-1">Total Sales</div>
        <div class="text-2xl font-bold price-tag">$<?= number_format($stats['total_sales'], 2) ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-teal-500/10 border border-emerald-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
          </div>
        </div>
        <div class="text-sm text-gray-500 mb-1">Total Orders</div>
        <div class="text-2xl font-bold"><?= number_format($stats['total_orders']) ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500/20 to-rose-500/10 border border-pink-500/20 flex items-center justify-center">
            <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
          </div>
        </div>
        <div class="text-sm text-gray-500 mb-1">Total Themes</div>
        <div class="text-2xl font-bold"><?= number_format($stats['total_themes']) ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="flex items-center justify-between mb-4">
          <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500/20 to-blue-500/10 border border-cyan-500/20 flex items-center justify-center text-2xl">👥</div>
        </div>
        <div class="text-sm text-gray-500 mb-1">Total Users</div>
        <div class="text-2xl font-bold"><?= number_format($stats['total_users']) ?></div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Recent Orders -->
      <div class="glass rounded-3xl p-6 reveal">
        <h3 class="text-xl font-semibold mb-6">Recent Orders</h3>
        <div class="space-y-4">
          <?php foreach($stats['recent_orders'] as $order): ?>
            <div class="flex items-center justify-between p-4 glass rounded-2xl">
              <div>
                <div class="font-medium"><?= e($order['order_number']) ?></div>
                <div class="text-sm text-gray-500"><?= date('M j, Y', strtotime($order['created_at'])) ?></div>
              </div>
              <div class="text-right">
                <div class="font-bold price-tag">$<?= number_format($order['total'], 2) ?></div>
                <span class="badge badge-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= e($order['status']) ?></span>
              </div>
            </div>
          <?php endforeach ?>
        </div>
      </div>

      <!-- Top Themes -->
      <div class="glass rounded-3xl p-6 reveal">
        <h3 class="text-xl font-semibold mb-6">Top Themes</h3>
        <div class="space-y-4">
          <?php foreach($stats['top_themes'] as $theme): ?>
            <div class="flex items-center gap-4 p-4 glass rounded-2xl">
              <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="w-full h-full object-cover">
              </div>
              <div class="flex-1 min-w-0">
                <div class="font-medium truncate"><?= e($theme['name']) ?></div>
                <div class="text-sm text-gray-500"><?= number_format($theme['sales']) ?> sales</div>
              </div>
              <div class="font-bold price-tag">$<?= number_format($theme['price'], 2) ?></div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>

    <!-- Pending Reviews -->
    <?php if($stats['pending_reviews'] > 0): ?>
      <div class="mt-8 glass rounded-3xl p-6 reveal">
        <h3 class="text-xl font-semibold mb-4">Pending Reviews</h3>
        <p class="text-gray-400">You have <span class="text-indigo-400 font-medium"><?= $stats['pending_reviews'] ?></span> reviews waiting for approval.</p>
        <a href="/admin/reviews" class="magnetic-btn btn-glass px-6 py-3 rounded-full text-sm font-medium inline-flex items-center gap-2 mt-4">
          Review Now
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>
