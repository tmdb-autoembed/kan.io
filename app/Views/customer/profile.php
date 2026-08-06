<?php /** @var array $user */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">My Profile</h1>

    <?php if(flash('success')): ?>
      <div class="glass rounded-2xl p-4 mb-8 border border-emerald-500/30 bg-emerald-500/5 reveal">
        <p class="text-emerald-400 text-sm"><?= e(flash('success')) ?></p>
      </div>
    <?php endif; ?>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="/customer/profile" class="space-y-6">
        <?= csrf_field() ?>
        <div class="flex items-center gap-6 mb-8">
          <div class="w-20 h-20 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
            <?= strtoupper(substr($user['name'], 0, 1)) ?>
          </div>
          <div>
            <h2 class="text-2xl font-bold"><?= e($user['name']) ?></h2>
            <p class="text-gray-400"><?= e($user['email']) ?></p>
            <span class="badge badge-primary capitalize mt-2"><?= e($user['role']) ?></span>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
            <input type="text" name="name" value="<?= e($user['name']) ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
            <input type="email" name="email" value="<?= e($user['email']) ?>" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
        </div>
        <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Update Profile
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </form>
    </div>
  </div>
</div>
