<?php /** @var array $orders */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">My Orders</h1>

    <?php if(empty($orders)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No orders yet</p>
        <a href="/themes" class="magnetic-btn btn-gradient px-8 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 mt-6">
          Browse Themes
        </a>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach($orders as $order): ?>
          <div class="glass bento-card rounded-3xl p-6 reveal">
            <div class="flex flex-col sm:flex-row justify-between gap-4">
              <div>
                <a href="/order/<?= $order['id'] ?>" class="font-mono font-bold text-indigo-400 hover:text-indigo-300"><?= e($order['order_number']) ?></a>
                <div class="text-sm text-gray-500 mt-1"><?= date('M j, Y', strtotime($order['created_at'])) ?></div>
              </div>
              <div class="text-right">
                <div class="text-xl font-bold price-tag">$<?= number_format($order['total'], 2) ?></div>
                <span class="badge badge-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger') ?> mt-2"><?= e($order['status']) ?></span>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
