<?php /** @var array|null $theme */ ?>
<?php /** @var Category[] $categories */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight"><?= $theme ? 'Edit Theme' : 'Create New Theme' ?></h1>
      <p class="text-gray-400 mt-2"><?= $theme ? 'Update theme information' : 'Add a new theme to the marketplace' ?></p>
    </div>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="<?= $theme ? '/admin/themes/' . $theme['id'] : '/admin/themes' ?>" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <?php if($theme): ?>
          <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Theme Name</label>
            <input type="text" name="name" value="<?= e($theme['name'] ?? '') ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="Enter theme name">
          </div>
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
            <textarea name="description" rows="4" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none" placeholder="Describe your theme"><?= e($theme['description'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
            <select name="category_id" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <?php foreach($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= ($theme['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                  <?= e($category['name']) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Price ($)</label>
            <input type="number" step="0.01" name="price" value="<?= e($theme['price'] ?? '') ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="0.00">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Sale Price ($)</label>
            <input type="number" step="0.01" name="sale_price" value="<?= e($theme['sale_price'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="0.00">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Version</label>
            <input type="text" name="version" value="<?= e($theme['version'] ?? '1.0.0') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="1.0.0">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Demo URL</label>
            <input type="url" name="demo_url" value="<?= e($theme['demo_url'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="https://example.com">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">License</label>
            <select name="license" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <option value="regular" <?= ($theme['license'] ?? '') === 'regular' ? 'selected' : '' ?>>Regular</option>
              <option value="extended" <?= ($theme['license'] ?? '') === 'extended' ? 'selected' : '' ?>>Extended</option>
              <option value="developer" <?= ($theme['license'] ?? '') === 'developer' ? 'selected' : '' ?>>Developer</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select name="status" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <option value="draft" <?= ($theme['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
              <option value="published" <?= ($theme['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
              <option value="scheduled" <?= ($theme['status'] ?? '') === 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
            </select>
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
            <?= $theme ? 'Update Theme' : 'Create Theme' ?>
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </button>
          <a href="/admin/themes" class="magnetic-btn btn-glass px-8 py-4 rounded-full text-white font-medium">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
