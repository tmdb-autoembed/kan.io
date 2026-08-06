<?php
/** @var Theme[] $featured */
/** @var Theme[] $trending */
/** @var Theme[] $latest */
/** @var Category[] $categories */
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center overflow-hidden">
  <div class="absolute inset-0 bg-gradient-to-br from-deep via-surface to-elevated"></div>
  <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[100px] animate-float"></div>
  <div class="absolute top-[60%] right-[-5%] w-[400px] h-[400px] bg-purple-500/10 rounded-full blur-[100px] animate-float" style="animation-delay:-7s"></div>
  <div class="absolute bottom-10 left-[30%] w-[300px] h-[300px] bg-pink-500/10 rounded-full blur-[100px] animate-float" style="animation-delay:-14s"></div>
  
  <div class="relative max-w-7xl mx-auto px-6 lg:px-8 py-32">
    <div class="grid lg:grid-cols-2 gap-16 items-center">
      <div class="reveal">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass mb-8">
          <span class="w-2 h-2 rounded-full bg-cyan-400 shadow-[0_0_10px_rgba(34,211,238,0.8)]"></span>
          <span class="text-sm text-gray-400">Premium Theme Marketplace</span>
        </div>
        <h1 class="text-5xl lg:text-7xl font-bold leading-[1.1] tracking-tight mb-6">
          Crafting <span class="text-gradient font-display italic">digital experiences</span> that define tomorrow
        </h1>
        <p class="text-lg text-gray-400 leading-relaxed mb-10 max-w-xl">
          Discover premium themes and templates crafted with precision. Build stunning websites with our curated collection.
        </p>
        <div class="flex flex-wrap gap-4">
          <a href="#marketplace" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
            Explore Themes
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </a>
          <a href="/register" class="magnetic-btn btn-glass px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2">
            Become a Vendor
          </a>
        </div>
      </div>
      
      <div class="relative reveal">
        <div class="glass rounded-4xl p-8 relative z-10 transform -rotate-2 hover:rotate-0 transition-transform duration-500">
          <div class="flex justify-between items-center mb-6">
            <div>
              <div class="text-sm text-gray-500 mb-1">Total Themes</div>
              <div class="text-4xl font-bold"><?= count($latest) ?>+</div>
            </div>
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-xl">↑</div>
          </div>
          <div class="h-1.5 bg-white/5 rounded-full overflow-hidden mb-6">
            <div class="h-full w-[98%] bg-gradient-to-r from-indigo-500 to-pink-500 rounded-full"></div>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div class="glass rounded-2xl p-4">
              <div class="text-xs text-gray-500 mb-1">Categories</div>
              <div class="font-semibold"><?= count($categories) ?></div>
            </div>
            <div class="glass rounded-2xl p-4">
              <div class="text-xs text-gray-500 mb-1">Downloads</div>
              <div class="font-semibold">50K+</div>
            </div>
          </div>
        </div>
        <div class="glass rounded-4xl p-6 absolute top-[10%] right-[5%] w-72 opacity-80 z-0 transform rotate-3">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-cyan-400 to-indigo-500"></div>
            <div>
              <div class="font-semibold text-sm">Live Updates</div>
              <div class="text-xs text-gray-500">New themes weekly</div>
            </div>
          </div>
          <div class="flex gap-2">
            <div class="flex-1 h-8 bg-gradient-to-t from-cyan-500/20 to-transparent rounded-md"></div>
            <div class="flex-1 h-12 bg-gradient-to-t from-indigo-500/20 to-transparent rounded-md"></div>
            <div class="flex-1 h-6 bg-gradient-to-t from-purple-500/20 to-transparent rounded-md"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Categories Section -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <span class="inline-block text-xs font-semibold tracking-[0.2em] text-indigo-400 uppercase mb-4">Categories</span>
      <h2 class="text-4xl lg:text-5xl font-bold tracking-tight">Browse by <span class="text-gradient font-display italic">Category</span></h2>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal">
      <?php foreach($categories as $category): ?>
        <a href="/category/<?= e($category['slug']) ?>" class="glass bento-card rounded-3xl p-6 text-center group">
          <div class="text-4xl mb-3 group-hover:scale-110 transition-transform">📁</div>
          <h3 class="font-semibold mb-1 group-hover:text-indigo-400 transition-colors"><?= e($category['name']) ?></h3>
          <p class="text-sm text-gray-500"><?= (new \ThemeHub\Models\Theme())->where('category_id', (string)$category['id'])->count() ?> themes</p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Featured Themes -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <span class="inline-block text-xs font-semibold tracking-[0.2em] text-indigo-400 uppercase mb-4">Featured</span>
      <h2 class="text-4xl lg:text-5xl font-bold tracking-tight">Handpicked <span class="text-gradient font-display italic">Themes</span></h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach($featured as $theme): ?>
        <?= $this->include('partials/theme-card', ['theme' => $theme]) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Trending Themes -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <span class="inline-block text-xs font-semibold tracking-[0.2em] text-pink-400 uppercase mb-4">Trending</span>
      <h2 class="text-4xl lg:text-5xl font-bold tracking-tight">Popular <span class="text-gradient font-display italic">Right Now</span></h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach($trending as $theme): ?>
        <?= $this->include('partials/theme-card', ['theme' => $theme]) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Latest Themes -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="text-center mb-16 reveal">
      <span class="inline-block text-xs font-semibold tracking-[0.2em] text-purple-400 uppercase mb-4">Latest</span>
      <h2 class="text-4xl lg:text-5xl font-bold tracking-tight">New <span class="text-gradient font-display italic">Arrivals</span></h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach($latest as $theme): ?>
        <?= $this->include('partials/theme-card', ['theme' => $theme]) ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Stats Section -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="glass rounded-4xl p-12 reveal">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
        <div class="text-center">
          <div class="text-4xl lg:text-5xl font-bold text-gradient mb-2">50K+</div>
          <div class="text-gray-400">Downloads</div>
        </div>
        <div class="text-center">
          <div class="text-4xl lg:text-5xl font-bold text-gradient mb-2">12K+</div>
          <div class="text-gray-400">Customers</div>
        </div>
        <div class="text-center">
          <div class="text-4xl lg:text-5xl font-bold text-gradient mb-2">500+</div>
          <div class="text-gray-400">Themes</div>
        </div>
        <div class="text-center">
          <div class="text-4xl lg:text-5xl font-bold text-gradient mb-2">4.9</div>
          <div class="text-gray-400">Rating</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-20 relative">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="glass rounded-4xl p-12 text-center reveal bg-gradient-to-br from-indigo-500/10 to-pink-500/5">
      <span class="inline-block text-xs font-semibold tracking-[0.15em] text-indigo-400 uppercase mb-4">Ready to start?</span>
      <h2 class="text-3xl lg:text-5xl font-bold tracking-tight mb-6">Launch your project <span class="text-gradient font-display italic">today</span></h2>
      <p class="text-gray-400 max-w-lg mx-auto mb-10 text-lg">Join thousands of developers and designers who trust ThemeHub for their projects.</p>
      <div class="flex flex-wrap gap-4 justify-center">
        <a href="/register" class="magnetic-btn btn-gradient px-10 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Create Account
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </a>
        <a href="/themes" class="magnetic-btn btn-glass px-10 py-4 rounded-full text-white font-medium inline-flex items-center gap-2">
          Browse Themes
        </a>
      </div>
    </div>
  </div>
</section>
