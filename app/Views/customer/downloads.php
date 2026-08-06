<?php /** @var array $downloads */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">My Downloads</h1>

    <?php if(empty($downloads)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">⬇️</div>
        <p class="text-gray-400 text-lg">No downloads yet</p>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach($downloads as $download): ?>
          <div class="glass bento-card rounded-3xl p-6 flex items-center justify-between reveal">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/20 to-pink-500/10 border border-indigo-500/20 flex items-center justify-center text-2xl">
                <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
              </div>
              <div>
                <div class="font-medium"><?= e($download['theme']['name'] ?? 'Theme') ?></div>
                <div class="text-sm text-gray-500"><?= date('M j, Y g:i A', strtotime($download['downloaded_at'])) ?></div>
              </div>
            </div>
            <a href="/download/<?= $download['id'] ?>" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
              Download
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </a>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
