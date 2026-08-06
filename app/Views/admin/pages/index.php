<?php /** @var array $pages */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Pages</h1>
    </div>

    <div class="glass rounded-3xl overflow-hidden reveal">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-glass-border">
            <tr class="text-left text-sm text-gray-400">
              <th class="px-6 py-4 font-medium">Title</th>
              <th class="px-6 py-4 font-medium">Slug</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Updated</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-glass-border">
            <?php foreach($pages as $page): ?>
              <tr class="hover:bg-white/5 transition-colors">
                <td class="px-6 py-4 font-medium"><?= e($page['title']) ?></td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= e($page['slug']) ?></td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $page['status'] === 'published' ? 'success' : 'warning' ?>"><?= e($page['status']) ?></span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= date('M j, Y', strtotime($page['updated_at'])) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
