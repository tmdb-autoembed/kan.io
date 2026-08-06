<div class="min-h-screen flex items-center justify-center py-16 px-6">
  <div class="w-full max-w-md reveal">
    <div class="glass rounded-4xl p-10 text-center">
      <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
      </div>
      <h1 class="text-3xl font-bold mb-4">Reset Password</h1>
      <p class="text-gray-400 mb-8">Enter your email and we'll send you a reset link</p>

      <?php if(flash('success')): ?>
        <div class="glass rounded-2xl p-4 mb-6 border border-emerald-500/30 bg-emerald-500/5">
          <p class="text-emerald-400 text-sm"><?= e(flash('success')) ?></p>
        </div>
      <?php endif; ?>

      <form method="POST" action="/forgot-password" class="space-y-5">
        <?= csrf_field() ?>
        <div>
          <input 
            type="email" 
            name="email" 
            required 
            class="w-full glass rounded-2xl px-5 py-3.5 text-white placeholder-gray-500 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" 
            placeholder="you@example.com"
          >
        </div>
        <button type="submit" class="magnetic-btn btn-gradient w-full py-4 rounded-full text-white font-medium">
          Send Reset Link
        </button>
      </form>

      <p class="text-center text-gray-500 mt-8 text-sm">
        Remember your password? <a href="/login" class="text-indigo-400 hover:text-indigo-300 transition-colors">Sign in</a>
      </p>
    </div>
  </div>
</div>
