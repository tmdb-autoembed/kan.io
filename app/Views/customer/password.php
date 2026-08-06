<?php /** @var array $user */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">Change Password</h1>

    <?php if(flash('error')): ?>
      <div class="glass rounded-2xl p-4 mb-8 border border-red-500/30 bg-red-500/5 reveal">
        <p class="text-red-400 text-sm"><?= e(flash('error')) ?></p>
      </div>
    <?php endif; ?>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="/customer/password" class="space-y-6">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Current Password</label>
          <input type="password" name="current_password" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="••••••••">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">New Password</label>
          <input type="password" name="new_password" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="••••••••">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Confirm New Password</label>
          <input type="password" name="confirm_password" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="••••••••">
        </div>
        <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Update Password
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </form>
    </div>
  </div>
</div>
