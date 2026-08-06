<?php /** @var array $posts */ ?>
<?php /** @var array $pagination */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Blog Posts</h1>
      <a href="/admin/blog/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        New Post
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </a>
    </div>

    <div class="glass rounded-3xl overflow-hidden reveal">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-glass-border">
            <tr class="text-left text-sm text-gray-400">
              <th class="px-6 py-4 font-medium">Title</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Views</th>
              <th class="px-6 py-4 font-medium">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-glass-border">
            <?php foreach($posts as $post): ?>
              <tr class="hover:bg-white/5 transition-colors">
                <td class="px-6 py-4">
                  <div class="font-medium"><?= e($post['title']) ?></div>
                  <div class="text-sm text-gray-500"><?= e($post['slug']) ?></div>
                </td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>"><?= e($post['status']) ?></span>
                </td>
                <td class="px-6 py-4 text-sm"><?= number_format($post['views'] ?? 0) ?></td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= date('M j, Y', strtotime($post['created_at'])) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
