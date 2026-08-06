<?php /** @var array $orders */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">Vendor Orders</h1>

    <?php if(empty($orders)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No orders yet</p>
      </div>
    <?php else: ?>
      <div class="glass rounded-3xl overflow-hidden reveal">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-glass-border">
              <tr class="text-left text-sm text-gray-400">
                <th class="px-6 py-4 font-medium">Order #</th>
                <th class="px-6 py-4 font-medium">Customer</th>
                <th class="px-6 py-4 font-medium">Total</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Date</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-glass-border">
              <?php foreach($orders as $order): ?>
                <tr class="hover:bg-white/5 transition-colors">
                  <td class="px-6 py-4">
                    <a href="/order/<?= $order['id'] ?>" class="font-mono font-medium text-indigo-400 hover:text-indigo-300"><?= e($order['order_number']) ?></a>
                  </td>
                  <td class="px-6 py-4">
                    <div class="font-medium"><?= e($order['user']['name'] ?? 'N/A') ?></div>
                  </td>
                  <td class="px-6 py-4 font-medium price-tag">$<?= number_format($order['total'], 2) ?></td>
                  <td class="px-6 py-4">
                    <span class="badge badge-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= e($order['status']) ?></span>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-400"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
