<?php /** @var Theme[] $themes */ ?>
<?php /** @var array $pagination */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-12 reveal">
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-4">
        <?= $query ? 'Search results for "' . e($query) . '"' : 'All Themes' ?>
      </h1>
      <?php if($query): ?>
        <p class="text-gray-400 text-lg"><?= count($themes) ?> theme(s) found</p>
      <?php endif; ?>
    </div>

    <!-- Filters -->
    <div class="glass rounded-2xl p-4 mb-8 flex flex-wrap items-center gap-4 reveal">
      <form action="/search" method="GET" class="flex-1 min-w-[200px]">
        <div class="relative">
          <input 
            type="text" 
            name="q" 
            value="<?= e($query) ?>" 
            placeholder="Search themes..." 
            class="search-input"
          >
        </div>
      </form>
      <select name="sort" onchange="window.location.href='?sort='+this.value+'<?= $query ? '&q=' . urlencode($query) : '' ?>'" class="glass rounded-xl px-4 py-2.5 text-sm text-gray-300 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
        <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
        <option value="popular" <?= $sort === 'popular' ? 'selected' : '' ?>>Most Popular</option>
        <option value="rating" <?= $sort === 'rating' ? 'selected' : '' ?>>Highest Rated</option>
        <option value="price-low" <?= $sort === 'price-low' ? 'selected' : '' ?>>Price: Low to High</option>
        <option value="price-high" <?= $sort === 'price-high' ? 'selected' : '' ?>>Price: High to Low</option>
      </select>
    </div>

    <?php if(empty($themes)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">🔍</div>
        <p class="text-gray-400 text-lg">No themes found matching your criteria</p>
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

      <!-- Pagination -->
      <?php if($pagination['total_pages'] > 1): ?>
        <div class="mt-12 flex justify-center">
          <div class="pagination">
            <?php if($pagination['has_prev']): ?>
              <a href="?page=<?= $pagination['prev_page'] ?><?= $query ? '&q=' . urlencode($query) : '' ?>">Previous</a>
            <?php endif; ?>
            
            <?php for($i = 1; $i <= $pagination['total_pages']; $i++): ?>
              <?php if($i === $pagination['current_page']): ?>
                <span class="active"><?= $i ?></span>
              <?php else: ?>
                <a href="?page=<?= $i ?><?= $query ? '&q=' . urlencode($query) : '' ?>"><?= $i ?></a>
              <?php endif; ?>
            <?php endfor; ?>
            
            <?php if($pagination['has_next']): ?>
              <a href="?page=<?= $pagination['next_page'] ?><?= $query ? '&q=' . urlencode($query) : '' ?>">Next</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
