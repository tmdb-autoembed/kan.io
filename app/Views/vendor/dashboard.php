<?php /** @var array $stats */ ?>
<?php /** @var Theme[] $themes */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Vendor Dashboard</h1>
      <p class="text-gray-400 mt-2">Welcome back, <?= e(auth_user()['name']) ?></p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="text-sm text-gray-500 mb-2">Total Themes</div>
        <div class="text-3xl font-bold"><?= $stats['total_themes'] ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="text-sm text-gray-500 mb-2">Total Sales</div>
        <div class="text-3xl font-bold"><?= number_format($stats['total_sales']) ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="text-sm text-gray-500 mb-2">Total Views</div>
        <div class="text-3xl font-bold"><?= number_format($stats['total_views']) ?></div>
      </div>
      <div class="glass bento-card rounded-3xl p-6 reveal">
        <div class="text-sm text-gray-500 mb-2">Downloads</div>
        <div class="text-3xl font-bold"><?= number_format($stats['total_downloads']) ?></div>
      </div>
    </div>

    <div class="glass rounded-3xl p-8 reveal">
      <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold">My Themes</h3>
        <a href="/vendor/themes/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
          Add Theme
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
      </div>
      <div class="space-y-4">
        <?php foreach($themes as $theme): ?>
          <div class="flex items-center justify-between p-4 glass rounded-2xl">
            <div class="flex items-center gap-4">
              <div class="w-16 h-16 rounded-xl overflow-hidden">
                <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="w-full h-full object-cover">
              </div>
              <div>
                <div class="font-medium"><?= e($theme['name']) ?></div>
                <div class="text-sm text-gray-500"><?= e($theme['status']) ?> · <?= number_format($theme['sales'] ?? 0) ?> sales</div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <a href="/vendor/themes/<?= $theme['id'] ?>/edit" class="p-2 glass rounded-lg text-gray-400 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
              </a>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</div>
