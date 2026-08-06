<?php /** @var Theme $theme */ ?>
<div class="theme-card glass rounded-3xl overflow-hidden group">
  <div class="relative aspect-[4/3] bg-elevated overflow-hidden">
    <img 
      src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" 
      alt="<?= e($theme['name']) ?>" 
      class="theme-image w-full h-full object-cover"
      loading="lazy"
    >
    <div class="absolute inset-0 bg-gradient-to-t from-deep/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
    
    <!-- Quick Actions -->
    <div class="quick-actions absolute bottom-4 left-4 right-4 flex gap-2">
      <a href="/theme/<?= e($theme['slug']) ?>" class="flex-1 btn-gradient py-2.5 rounded-xl text-sm font-medium text-white text-center">View Details</a>
      <button onclick="addToCart(<?= $theme['id'] ?>)" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-colors" title="Add to cart">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
      </button>
    </div>
  </div>
  
  <div class="p-6">
    <div class="flex items-center gap-2 mb-2">
      <span class="badge badge-primary"><?= e($theme['category'] ?? 'Theme') ?></span>
      <?php if($theme['featured']): ?>
        <span class="badge badge-warning">Featured</span>
      <?php endif; ?>
    </div>
    
    <h3 class="font-semibold text-lg mb-2 group-hover:text-indigo-400 transition-colors line-clamp-2">
      <a href="/theme/<?= e($theme['slug']) ?>"><?= e($theme['name']) ?></a>
    </h3>
    
    <div class="flex items-center gap-1 mb-3">
      <?= star_rating((float)$theme['rating']) ?>
      <span class="text-sm text-gray-400 ml-1">(<?= $theme['reviews_count'] ?? 0 ?>)</span>
    </div>
    
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="text-lg font-bold price-tag">$<?= e($theme['sale_price'] ?? $theme['price']) ?></span>
        <?php if($theme['sale_price']): ?>
          <span class="text-sm text-gray-500 line-through">$<?= e($theme['price']) ?></span>
        <?php endif; ?>
      </div>
      <div class="flex items-center gap-1 text-sm text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        <?= $theme['sales'] ?? 0 ?>
      </div>
    </div>
  </div>
</div>

<script>
function addToCart(themeId) {
  fetch('/cart/add', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: 'theme_id=' + themeId + '&_token=' + document.querySelector('meta[name="csrf-token"]').content
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      showToast(data.message || 'Added to cart', 'success');
    }
  })
  .catch(error => {
    showToast('Failed to add to cart', 'error');
  });
}
</script>
