<div class="min-h-screen flex items-center justify-center py-16 px-6">
  <div class="w-full max-w-md reveal">
    <div class="glass rounded-4xl p-10">
      <div class="text-center mb-10">
        <a href="/" class="flex items-center justify-center gap-2 text-2xl font-bold bg-gradient-to-r from-indigo-400 to-pink-400 bg-clip-text text-transparent">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path></svg>
          ThemeHub
        </a>
        <h1 class="text-3xl font-bold mt-6 mb-2">Welcome back</h1>
        <p class="text-gray-400">Sign in to your account to continue</p>
      </div>

      <?php if(flash('error')): ?>
        <div class="glass rounded-2xl p-4 mb-6 border border-red-500/30 bg-red-500/5">
          <p class="text-red-400 text-sm"><?= e(flash('error')) ?></p>
        </div>
      <?php endif; ?>

      <?php if(flash('success')): ?>
        <div class="glass rounded-2xl p-4 mb-6 border border-emerald-500/30 bg-emerald-500/5">
          <p class="text-emerald-400 text-sm"><?= e(flash('success')) ?></p>
        </div>
      <?php endif; ?>

      <form method="POST" action="/login" class="space-y-5">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
          <input 
            type="email" 
            name="email" 
            value="<?= e(old('email')) ?>" 
            required 
            class="w-full glass rounded-2xl px-5 py-3.5 text-white placeholder-gray-500 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" 
            placeholder="you@example.com"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
          <input 
            type="password" 
            name="password" 
            required 
            class="w-full glass rounded-2xl px-5 py-3.5 text-white placeholder-gray-500 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" 
            placeholder="••••••••"
          >
        </div>
        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2">
            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-glass-border bg-transparent text-indigo-500 focus:ring-indigo-500">
            <span class="text-sm text-gray-400">Remember me</span>
          </label>
          <a href="/forgot-password" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">Forgot password?</a>
        </div>
        <button type="submit" class="magnetic-btn btn-gradient w-full py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2 group">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
          Sign In
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </form>

      <!-- One-Click Login as Demo User -->
      <div class="mt-8 pt-8 border-t border-glass-border">
        <p class="text-xs text-gray-500 text-center mb-4 uppercase tracking-wider">Quick Demo Login</p>
        <div class="grid grid-cols-3 gap-3">
          <a href="/demo-login?role=admin" class="flex flex-col items-center gap-2 p-4 glass rounded-2xl hover:bg-white/5 transition-colors group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-red-500 to-pink-500 flex items-center justify-center text-white text-sm font-bold group-hover:scale-110 transition-transform">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-white transition-colors">Admin</span>
          </a>
          <a href="/demo-login?role=vendor" class="flex flex-col items-center gap-2 p-4 glass rounded-2xl hover:bg-white/5 transition-colors group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center text-white text-sm font-bold group-hover:scale-110 transition-transform">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-white transition-colors">Vendor</span>
          </a>
          <a href="/demo-login?role=customer" class="flex flex-col items-center gap-2 p-4 glass rounded-2xl hover:bg-white/5 transition-colors group">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-500 to-green-500 flex items-center justify-center text-white text-sm font-bold group-hover:scale-110 transition-transform">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <span class="text-xs font-medium text-gray-400 group-hover:text-white transition-colors">Customer</span>
          </a>
        </div>
      </div>

      <p class="text-center text-gray-500 mt-8 text-sm">
        Don't have an account? <a href="/register" class="text-indigo-400 hover:text-indigo-300 transition-colors">Create one</a>
      </p>
    </div>
  </div>
</div>
