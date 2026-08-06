<?php /** @var array $categories */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Categories</h1>
      <button onclick="document.getElementById('create-category-modal').classList.remove('hidden')" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        Add Category
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </button>
    </div>

    <div class="glass rounded-3xl overflow-hidden reveal">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-glass-border">
            <tr class="text-left text-sm text-gray-400">
              <th class="px-6 py-4 font-medium">Name</th>
              <th class="px-6 py-4 font-medium">Slug</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Themes</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-glass-border">
            <?php foreach($categories as $category): ?>
              <tr class="hover:bg-white/5 transition-colors">
                <td class="px-6 py-4 font-medium"><?= e($category['name']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= e($category['slug']) ?></td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $category['status'] === 'active' ? 'success' : 'warning' ?>"><?= e($category['status']) ?></span>
                </td>
                <td class="px-6 py-4 text-sm"><?= $category->themeCount() ?> themes</td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Create Category Modal -->
<div id="create-category-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-6">
  <div class="glass rounded-3xl p-8 max-w-md w-full">
    <h3 class="text-xl font-semibold mb-6">Add Category</h3>
    <form action="/admin/categories" method="POST" class="space-y-4">
      <?= csrf_field() ?>
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
        <input type="text" name="name" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
        <textarea name="description" rows="3" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none"></textarea>
      </div>
      <div class="flex gap-3 pt-4">
        <button type="submit" class="magnetic-btn btn-gradient flex-1 py-3 rounded-full text-white font-medium">Create</button>
        <button type="button" onclick="document.getElementById('create-category-modal').classList.add('hidden')" class="magnetic-btn btn-glass flex-1 py-3 rounded-full text-white font-medium">Cancel</button>
      </div>
    </form>
  </div>
</div>
