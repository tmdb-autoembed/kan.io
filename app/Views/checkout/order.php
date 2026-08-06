<?php /** @var array $order */ ?>
<?php /** @var array $items */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-12 reveal">
      <div class="w-20 h-20 rounded-full bg-emerald-500/10 flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
      </div>
      <h1 class="text-4xl font-bold mb-4">Order Confirmed!</h1>
      <p class="text-gray-400 text-lg">Thank you for your purchase. Your order has been placed successfully.</p>
    </div>

    <div class="glass rounded-3xl p-8 mb-8 reveal">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
          <div class="text-sm text-gray-500 mb-1">Order Number</div>
          <div class="text-2xl font-bold"><?= e($order['order_number']) ?></div>
        </div>
        <div class="text-right">
          <div class="text-sm text-gray-500 mb-1">Total</div>
          <div class="text-2xl font-bold price-tag">$<?= number_format($order['total'], 2) ?></div>
        </div>
      </div>

      <div class="border-t border-glass-border pt-6">
        <h3 class="font-semibold mb-4">Order Items</h3>
        <div class="space-y-4">
          <?php foreach($items as $item): ?>
            <div class="flex justify-between items-center">
              <div>
                <div class="font-medium"><?= e($item['theme_name']) ?></div>
                <div class="text-sm text-gray-500">License: <?= e($item['license_type']) ?></div>
              </div>
              <div class="font-medium">$<?= number_format($item['theme_price'], 2) ?></div>
            </div>
          <?php endforeach ?>
        </div>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 justify-center reveal">
      <a href="/customer/orders" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2">
        View Orders
      </a>
      <a href="/themes" class="magnetic-btn btn-glass px-8 py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2">
        Continue Shopping
      </a>
    </div>
  </div>
</div>
