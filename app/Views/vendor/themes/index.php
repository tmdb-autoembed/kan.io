<?php /** @var Theme[] $themes */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">My Themes</h1>
      <a href="/vendor/themes/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        Add Theme
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </a>
    </div>

    <?php if(empty($themes)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No themes created yet</p>
        <a href="/vendor/themes/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 mt-6">
          Create Your First Theme
        </a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach($themes as $theme): ?>
          <div class="theme-card glass rounded-3xl overflow-hidden reveal">
            <div class="relative aspect-[4/3] bg-elevated overflow-hidden">
              <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="theme-image w-full h-full object-cover" loading="lazy">
              <div class="absolute top-4 right-4">
                <span class="badge badge-<?= $theme['status'] === 'published' ? 'success' : ($theme['status'] === 'draft' ? 'warning' : 'primary') ?>"><?= e($theme['status']) ?></span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="font-semibold text-lg mb-2"><?= e($theme['name']) ?></h3>
              <div class="flex items-center justify-between mb-4">
                <span class="text-lg font-bold price-tag">$<?= e($theme['sale_price'] ?? $theme['price']) ?></span>
                <span class="text-sm text-gray-500"><?= number_format($theme['sales'] ?? 0) ?> sales</span>
              </div>
              <div class="flex gap-2">
                <a href="/vendor/themes/<?= $theme['id'] ?>/edit" class="flex-1 magnetic-btn btn-glass py-2.5 rounded-xl text-sm font-medium text-center">Edit</a>
                <form action="/vendor/themes/<?= $theme['id'] ?>/delete" method="POST" onsubmit="return confirm('Are you sure?')" class="flex-1">
                  <?= csrf_field() ?>
                  <button type="submit" class="w-full py-2.5 rounded-xl text-sm font-medium bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition-colors">Delete</button>
                </form>
              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
