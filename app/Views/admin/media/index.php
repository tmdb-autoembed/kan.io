<?php /** @var array $media */ ?>
<?php /** @var array $pagination */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Media Library</h1>
      <p class="text-gray-400 mt-2">Manage your media files</p>
    </div>

    <div class="glass rounded-3xl p-8 mb-8 reveal">
      <form action="/admin/media/upload" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="flex items-center gap-4">
          <input type="file" name="file" accept="image/*" required class="flex-1 glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30">
          <button type="submit" class="magnetic-btn btn-gradient px-8 py-3.5 rounded-full text-white font-medium">Upload</button>
        </div>
      </form>
    </div>

    <?php if(empty($media)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">🖼️</div>
        <p class="text-gray-400 text-lg">No media files uploaded yet</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        <?php foreach($media as $item): ?>
          <div class="glass bento-card rounded-2xl overflow-hidden reveal">
            <div class="aspect-square bg-elevated">
              <img src="<?= e($item['url']) ?>" alt="<?= e($item['original_name']) ?>" class="w-full h-full object-cover">
            </div>
            <div class="p-4">
              <div class="text-sm font-medium truncate"><?= e($item['original_name']) ?></div>
              <div class="text-xs text-gray-500"><?= format_bytes($item['size']) ?></div>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
