<?php /** @var Category $category */ ?>
<?php /** @var Theme[] $themes */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8 reveal">
      <a href="/" class="hover:text-white transition-colors">Home</a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      <span class="text-white"><?= e($category['name']) ?></span>
    </nav>

    <div class="mb-12 reveal">
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-4"><?= e($category['name']) ?></h1>
      <p class="text-gray-400 text-lg"><?= e($category['description'] ?? 'Browse our collection of ' . $category['name'] . ' themes') ?></p>
    </div>

    <?php if(empty($themes)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No themes found in this category</p>
        <a href="/themes" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 mt-6">
          Browse All Themes
        </a>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach($themes as $theme): ?>
          <?= $this->include('partials/theme-card', ['theme' => $theme]) ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
