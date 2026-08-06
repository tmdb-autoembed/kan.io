<?php /** @var array $theme */ ?>
<?php /** @var Category[] $categories */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Edit Theme</h1>
    </div>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="/vendor/themes/<?= $theme['id'] ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Theme Name</label>
            <input type="text" name="name" value="<?= e($theme['name']) ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
            <textarea name="description" rows="4" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none"><?= e($theme['description'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
            <select name="category_id" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <?php foreach($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= ($theme['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= e($category['name']) ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?= e($theme['price']) ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Sale Price ($)</label>
            <input type="number" step="0.01" name="sale_price" value="<?= e($theme['sale_price'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Demo URL</label>
            <input type="url" name="demo_url" value="<?= e($theme['demo_url'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Thumbnail</label>
            <input type="file" name="thumbnail" accept="image/*" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30">
            <?php if($theme['thumbnail'] ?? ''): ?>
              <div class="mt-4">
                <img src="<?= upload($theme['thumbnail']) ?>" alt="Current thumbnail" class="w-32 h-32 object-cover rounded-xl">
              </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="flex items-center gap-4 pt-4">
          <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
            Update Theme
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </button>
          <a href="/vendor/themes" class="magnetic-btn btn-glass px-8 py-4 rounded-full text-white font-medium">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
