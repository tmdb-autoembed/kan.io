<?php /** @var array $orders */ ?>
<?php /** @var string $status */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Orders</h1>
      <a href="/admin/orders/export" class="magnetic-btn btn-glass px-6 py-3 rounded-full text-sm font-medium inline-flex items-center gap-2">
        Export CSV
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
      </a>
    </div>

    <div class="glass rounded-3xl overflow-hidden reveal">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-glass-border">
            <tr class="text-left text-sm text-gray-400">
              <th class="px-6 py-4 font-medium">Order #</th>
              <th class="px-6 py-4 font-medium">Customer</th>
              <th class="px-6 py-4 font-medium">Total</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Payment</th>
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
                  <div class="text-sm text-gray-500"><?= e($order['user']['email'] ?? 'N/A') ?></div>
                </td>
                <td class="px-6 py-4 font-medium price-tag">$<?= number_format($order['total'], 2) ?></td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $order['status'] === 'completed' ? 'success' : ($order['status'] === 'pending' ? 'warning' : 'danger') ?>"><?= e($order['status']) ?></span>
                </td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $order['payment_status'] === 'paid' ? 'success' : ($order['payment_status'] === 'pending' ? 'warning' : 'danger') ?>"><?= e($order['payment_status']) ?></span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
