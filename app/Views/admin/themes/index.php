<?php /** @var Theme[] $themes */ ?>
<?php /** @var array $pagination */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Themes</h1>
      <a href="/admin/themes/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        Add New Theme
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </a>
    </div>

    <?php if(empty($themes)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No themes found</p>
        <a href="/admin/themes/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-white font-medium inline-flex items-center gap-2 mt-6">
          Create Your First Theme
        </a>
      </div>
    <?php else: ?>
      <div class="glass rounded-3xl overflow-hidden reveal">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-glass-border">
              <tr class="text-left text-sm text-gray-400">
                <th class="px-6 py-4 font-medium">Theme</th>
                <th class="px-6 py-4 font-medium">Category</th>
                <th class="px-6 py-4 font-medium">Price</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Sales</th>
                <th class="px-6 py-4 font-medium">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-glass-border">
              <?php foreach($themes as $theme): ?>
                <tr class="hover:bg-white/5 transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                      <div class="w-16 h-16 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="<?= upload($theme['thumbnail'] ?? 'themes/placeholder.webp') ?>" alt="<?= e($theme['name']) ?>" class="w-full h-full object-cover">
                      </div>
                      <div>
                        <div class="font-medium"><?= e($theme['name']) ?></div>
                        <div class="text-sm text-gray-500"><?= e($theme['slug']) ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm"><?= e($theme['category'] ?? 'N/A') ?></td>
                  <td class="px-6 py-4">
                    <div class="font-medium price-tag">$<?= e($theme['sale_price'] ?? $theme['price']) ?></div>
                    <?php if($theme['sale_price']): ?>
                      <div class="text-sm text-gray-500 line-through">$<?= e($theme['price']) ?></div>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <span class="badge badge-<?= $theme['status'] === 'published' ? 'success' : ($theme['status'] === 'draft' ? 'warning' : 'primary') ?>">
                      <?= e($theme['status']) ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm"><?= number_format($theme['sales'] ?? 0) ?></td>
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                      <a href="/theme/<?= e($theme['slug']) ?>" class="p-2 glass rounded-lg text-gray-400 hover:text-white transition-colors" title="View">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                      </a>
                      <a href="/admin/themes/<?= $theme['id'] ?>/edit" class="p-2 glass rounded-lg text-gray-400 hover:text-white transition-colors" title="Edit">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                      </a>
                      <form action="/admin/themes/<?= $theme['id'] ?>/delete" method="POST" onsubmit="return confirm('Are you sure?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="p-2 glass rounded-lg text-red-400 hover:text-red-300 transition-colors" title="Delete">
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <?php if($pagination['total_pages'] > 1): ?>
        <div class="mt-8 flex justify-center">
          <div class="pagination">
            <?php if($pagination['has_prev']): ?>
              <a href="?page=<?= $pagination['prev_page'] ?>">Previous</a>
            <?php endif; ?>
            <?php for($i = 1; $i <= $pagination['total_pages']; $i++): ?>
              <?php if($i === $pagination['current_page']): ?>
                <span class="active"><?= $i ?></span>
              <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
              <?php endif; ?>
            <?php endfor; ?>
            <?php if($pagination['has_next']): ?>
              <a href="?page=<?= $pagination['next_page'] ?>">Next</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
