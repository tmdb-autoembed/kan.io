<?php /** @var array $items */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">My Wishlist</h1>

    <?php if(empty($items)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg mb-6">Your wishlist is empty</p>
        <a href="/themes" class="magnetic-btn btn-gradient px-8 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Browse Themes
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($items as $item): ?>
          <?php $theme = $item['theme']; ?>
          <div class="theme-card glass rounded-3xl overflow-hidden group reveal">
            <div class="relative aspect-[4/3] bg-elevated overflow-hidden">
              <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="theme-image w-full h-full object-cover" loading="lazy">
              <div class="absolute inset-0 bg-gradient-to-t from-deep/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
              <div class="quick-actions absolute bottom-4 left-4 right-4">
                <a href="/theme/<?= e($theme['slug']) ?>" class="block w-full btn-gradient py-2.5 rounded-xl text-sm font-medium text-white text-center">View Details</a>
              </div>
            </div>
            <div class="p-6">
              <h3 class="font-semibold text-lg mb-2 group-hover:text-indigo-400 transition-colors line-clamp-2">
                <a href="/theme/<?= e($theme['slug']) ?>"><?= e($theme['name']) ?></a>
              </h3>
              <div class="flex items-center justify-between">
                <span class="text-lg font-bold price-tag">$<?= e($theme['sale_price'] ?? $theme['price']) ?></span>
                <form action="/wishlist/remove" method="POST">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id" value="<?= $item['id'] ?>">
                  <button type="submit" class="text-sm text-red-400 hover:text-red-300 transition-colors">Remove</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
