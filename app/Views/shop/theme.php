<?php /** @var Theme $theme */ ?>
<?php /** @var Theme[] $related */ ?>
<?php /** @var Review[] $reviews */ ?>

<div class="min-h-screen py-16">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8 reveal">
      <a href="/" class="hover:text-white transition-colors">Home</a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      <a href="/category/<?= e($theme['category'] ?? '') ?>" class="hover:text-white transition-colors"><?= e($theme['category'] ?? 'Themes') ?></a>
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
      <span class="text-white"><?= e($theme['name']) ?></span>
    </nav>

    <div class="grid lg:grid-cols-2 gap-12 items-start">
      <!-- Images -->
      <div class="reveal">
        <div class="aspect-[4/3] rounded-4xl overflow-hidden glass">
          <img 
            src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" 
            alt="<?= e($theme['name']) ?>" 
            class="w-full h-full object-cover"
          >
        </div>
      </div>

      <!-- Details -->
      <div class="reveal">
        <span class="inline-block text-xs font-semibold tracking-[0.15em] text-indigo-400 uppercase mb-4">
          <?= e($theme['category'] ?? 'Theme') ?>
        </span>
        <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-4"><?= e($theme['name']) ?></h1>
        
        <div class="flex items-center gap-4 mb-6">
          <div class="flex items-center gap-1">
            <?= star_rating((float)$theme['rating']) ?>
            <span class="text-sm text-gray-400 ml-1"><?= $theme['rating'] ?> (<?= $theme['reviews_count'] ?? 0 ?> reviews)</span>
          </div>
        </div>

        <p class="text-gray-400 text-lg leading-relaxed mb-8"><?= e($theme['description']) ?></p>

        <!-- Price -->
        <div class="flex items-baseline gap-4 mb-8">
          <span class="text-4xl font-bold price-tag">$<?= e($theme['sale_price'] ?? $theme['price']) ?></span>
          <?php if($theme['sale_price']): ?>
            <span class="text-xl text-gray-500 line-through">$<?= e($theme['price']) ?></span>
            <span class="badge badge-danger">-<?= round((($theme['price'] - $theme['sale_price']) / $theme['price']) * 100) ?>%</span>
          <?php endif; ?>
        </div>

        <!-- Meta Info -->
        <div class="glass rounded-2xl p-6 mb-8 space-y-4">
          <div class="flex justify-between">
            <span class="text-gray-400">Version</span>
            <span class="font-medium"><?= e($theme['version'] ?? '1.0.0') ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">License</span>
            <span class="font-medium capitalize"><?= e($theme['license'] ?? 'Regular') ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Compatible Browsers</span>
            <span class="font-medium"><?= e($theme['compatible_browsers'] ?? 'All modern browsers') ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Last Updated</span>
            <span class="font-medium"><?= $theme['last_updated'] ? date('M j, Y', strtotime($theme['last_updated'])) : 'N/A' ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Sales</span>
            <span class="font-medium"><?= number_format($theme['sales'] ?? 0) ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-400">Developer</span>
            <span class="font-medium"><?= e($theme['developer']['name'] ?? 'ThemeHub') ?></span>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-col gap-3">
          <a href="/checkout?theme=<?= $theme['id'] ?>" class="magnetic-btn btn-gradient w-full py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2 group">
            Buy Now
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </a>
          <a href="<?= e($theme['demo_url'] ?? '#') ?>" target="_blank" class="magnetic-btn btn-glass w-full py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2">
            Live Demo
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
          </a>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="mt-20 reveal">
      <div class="border-b border-glass-border mb-8">
        <nav class="flex gap-8">
          <button class="tab-btn pb-4 text-sm font-medium text-indigo-400 border-b-2 border-indigo-400" data-tab="description">Description</button>
          <button class="tab-btn pb-4 text-sm font-medium text-gray-400 hover:text-white transition-colors" data-tab="features">Features</button>
          <button class="tab-btn pb-4 text-sm font-medium text-gray-400 hover:text-white transition-colors" data-tab="reviews">Reviews (<?= count($reviews) ?>)</button>
        </nav>
      </div>

      <div id="tab-description" class="tab-content">
        <div class="glass rounded-3xl p-8">
          <h3 class="text-2xl font-semibold mb-4">About this theme</h3>
          <div class="text-gray-400 leading-relaxed space-y-4">
            <?= $theme['description'] ?>
            <p>This premium theme is built with modern web technologies and follows best practices for performance, accessibility, and SEO. It's fully responsive and looks great on all devices.</p>
          </div>
        </div>
      </div>

      <div id="tab-features" class="tab-content hidden">
        <div class="glass rounded-3xl p-8">
          <h3 class="text-2xl font-semibold mb-4">Theme Features</h3>
          <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-400">
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Responsive Design
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              SEO Optimized
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Cross-browser Compatible
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Fast Performance
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Easy Customization
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
              Premium Support
            </li>
          </ul>
        </div>
      </div>

      <div id="tab-reviews" class="tab-content hidden">
        <div class="space-y-4">
          <?php if(empty($reviews)): ?>
            <div class="glass rounded-3xl p-8 text-center">
              <p class="text-gray-400">No reviews yet. Be the first to review this theme!</p>
            </div>
          <?php else: ?>
            <?php foreach($reviews as $review): ?>
              <div class="glass rounded-3xl p-6">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white font-medium">
                    <?= strtoupper(substr($review['user']['name'] ?? 'U', 0, 1)) ?>
                  </div>
                  <div>
                    <div class="font-medium"><?= e($review['user']['name'] ?? 'Anonymous') ?></div>
                    <div class="flex items-center gap-1"><?= star_rating((float)$review['rating']) ?></div>
                  </div>
                </div>
                <p class="text-gray-400"><?= e($review['comment']) ?></p>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Related Themes -->
    <?php if(!empty($related)): ?>
      <div class="mt-20 reveal">
        <h3 class="text-2xl font-semibold mb-8">Related Themes</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <?php foreach($related as $relatedTheme): ?>
            <?= $this->include('partials/theme-card', ['theme' => $relatedTheme]) ?>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabBtns = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const tab = this.dataset.tab;
      
      tabBtns.forEach(b => {
        b.classList.remove('text-indigo-400', 'border-b-2', 'border-indigo-400');
        b.classList.add('text-gray-400');
      });
      
      this.classList.remove('text-gray-400');
      this.classList.add('text-indigo-400', 'border-b-2', 'border-indigo-400');
      
      tabContents.forEach(content => {
        content.classList.add('hidden');
      });
      
      document.getElementById('tab-' + tab).classList.remove('hidden');
    });
  });
});
</script>
