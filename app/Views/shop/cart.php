<?php /** @var array $cartItems */ ?>
<?php /** @var float $total */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">Shopping Cart</h1>

    <?php if(empty($cartItems)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg mb-6">Your cart is empty</p>
        <a href="/themes" class="magnetic-btn btn-gradient px-8 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Browse Themes
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
      </div>
    <?php else: ?>
      <div class="space-y-4 mb-8">
        <?php foreach($cartItems as $item): ?>
          <?php $theme = $item['theme']; ?>
          <div class="glass bento-card rounded-3xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-6 reveal">
            <div class="w-24 h-24 rounded-2xl bg-elevated overflow-hidden flex-shrink-0">
              <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="w-full h-full object-cover">
            </div>
            <div class="flex-1 min-w-0">
              <h3 class="font-semibold text-lg mb-1"><?= e($theme['name']) ?></h3>
              <p class="text-sm text-gray-500"><?= e($theme['category'] ?? 'Theme') ?></p>
            </div>
            <div class="text-right">
              <div class="text-lg font-bold price-tag">$<?= e($item['price']) ?></div>
              <form action="/cart/remove" method="POST" class="mt-2">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition-colors">Remove</button>
              </form>
            </div>
          </div>
        <?php endforeach ?>
      </div>

      <div class="glass rounded-3xl p-8 reveal">
        <div class="flex items-center justify-between mb-6">
          <span class="text-gray-400 text-lg">Subtotal</span>
          <span class="text-3xl font-bold price-tag">$<?= number_format($total, 2) ?></span>
        </div>
        <p class="text-gray-500 text-sm mb-6">Tax and discounts will be calculated at checkout</p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="/checkout" class="magnetic-btn btn-gradient flex-1 py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2 group">
            Proceed to Checkout
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </a>
          <a href="/themes" class="magnetic-btn btn-glass flex-1 py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2">
            Continue Shopping
          </a>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
