<?php /** @var array $coupons */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Coupons</h1>
      <a href="/admin/coupons/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        Create Coupon
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </a>
    </div>

    <?php if(empty($coupons)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No coupons created yet</p>
      </div>
    <?php else: ?>
      <div class="glass rounded-3xl overflow-hidden reveal">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-glass-border">
              <tr class="text-left text-sm text-gray-400">
                <th class="px-6 py-4 font-medium">Code</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">Value</th>
                <th class="px-6 py-4 font-medium">Usage</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Expires</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-glass-border">
              <?php foreach($coupons as $coupon): ?>
                <tr class="hover:bg-white/5 transition-colors">
                  <td class="px-6 py-4">
                    <span class="font-mono font-bold text-indigo-400"><?= e($coupon['code']) ?></span>
                  </td>
                  <td class="px-6 py-4 text-sm capitalize"><?= e($coupon['type']) ?></td>
                  <td class="px-6 py-4">
                    <span class="font-medium"><?= $coupon['type'] === 'percent' ? $coupon['value'] . '%' : '$' . $coupon['value'] ?></span>
                  </td>
                  <td class="px-6 py-4 text-sm"><?= $coupon['usage_count'] ?? 0 ?> / <?= $coupon['usage_limit'] ?? '∞' ?></td>
                  <td class="px-6 py-4">
                    <span class="badge badge-<?= $coupon['status'] === 'active' ? 'success' : 'warning' ?>"><?= e($coupon['status']) ?></span>
                  </td>
                  <td class="px-6 py-4 text-sm"><?= $coupon['expires_at'] ? date('M j, Y', strtotime($coupon['expires_at'])) : 'Never' ?></td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
