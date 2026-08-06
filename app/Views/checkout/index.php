<?php /** @var array $cartItems */ ?>
<?php /** @var float $subtotal */ ?>
<?php /** @var float $tax */ ?>
<?php /** @var float $discount */ ?>
<?php /** @var float $total */ ?>
<?php /** @var Coupon|null $coupon */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-6xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">Checkout</h1>

    <div class="grid lg:grid-cols-3 gap-8">
      <!-- Checkout Form -->
      <div class="lg:col-span-2 reveal">
        <div class="glass rounded-3xl p-8">
          <h2 class="text-2xl font-semibold mb-6">Billing Details</h2>
          <form method="POST" action="/checkout/place" class="space-y-6">
            <?= csrf_field() ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                <input type="text" name="billing_name" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="John Doe">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                <input type="email" name="billing_email" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="john@example.com">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Phone</label>
                <input type="tel" name="billing_phone" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="+1 234 567 8900">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Country</label>
                <input type="text" name="billing_country" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="United States">
              </div>
              <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-300 mb-2">Address</label>
                <input type="text" name="billing_address" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="123 Main Street">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">City</label>
                <input type="text" name="billing_city" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="New York">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Postal Code</label>
                <input type="text" name="billing_postal_code" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="10001">
              </div>
            </div>

            <h2 class="text-2xl font-semibold mb-6 mt-8">Payment Method</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <label class="glass rounded-2xl p-4 border border-glass-border cursor-pointer hover:border-indigo-500/50 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500/10">
                <input type="radio" name="payment_method" value="stripe" checked class="sr-only">
                <div class="font-medium text-sm">Credit Card</div>
                <div class="text-xs text-gray-500 mt-1">Stripe</div>
              </label>
              <label class="glass rounded-2xl p-4 border border-glass-border cursor-pointer hover:border-indigo-500/50 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500/10">
                <input type="radio" name="payment_method" value="paypal" class="sr-only">
                <div class="font-medium text-sm">PayPal</div>
                <div class="text-xs text-gray-500 mt-1">Pay with PayPal</div>
              </label>
              <label class="glass rounded-2xl p-4 border border-glass-border cursor-pointer hover:border-indigo-500/50 transition-colors has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-500/10">
                <input type="radio" name="payment_method" value="cod" class="sr-only">
                <div class="font-medium text-sm">Cash on Delivery</div>
                <div class="text-xs text-gray-500 mt-1">Pay later</div>
              </label>
            </div>

            <button type="submit" class="magnetic-btn btn-gradient w-full py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2 group mt-8">
              Place Order
              <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </button>
          </form>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="lg:col-span-1 reveal">
        <div class="glass rounded-3xl p-8 sticky top-24">
          <h3 class="text-xl font-semibold mb-6">Order Summary</h3>
          <div class="space-y-4 mb-6">
            <?php foreach($cartItems as $item): ?>
              <?php $theme = $item['theme']; ?>
              <div class="flex justify-between items-center">
                <div class="flex-1">
                  <div class="font-medium text-sm"><?= e($theme['name']) ?></div>
                  <div class="text-xs text-gray-500">Qty: <?= $item['quantity'] ?></div>
                </div>
                <div class="font-medium">$<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
              </div>
            <?php endforeach ?>
          </div>
          <div class="border-t border-glass-border pt-4 space-y-3">
            <div class="flex justify-between text-gray-400">
              <span>Subtotal</span>
              <span>$<?= number_format($subtotal, 2) ?></span>
            </div>
            <div class="flex justify-between text-gray-400">
              <span>Tax</span>
              <span>$<?= number_format($tax, 2) ?></span>
            </div>
            <?php if($discount > 0): ?>
              <div class="flex justify-between text-emerald-400">
                <span>Discount</span>
                <span>-$<?= number_format($discount, 2) ?></span>
              </div>
            <?php endif; ?>
            <div class="flex justify-between text-xl font-bold pt-3 border-t border-glass-border">
              <span>Total</span>
              <span class="price-tag">$<?= number_format($total, 2) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
