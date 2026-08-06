<div class="min-h-screen flex items-center justify-center py-16 px-6">
  <div class="w-full max-w-md reveal">
    <div class="glass rounded-4xl p-10">
      <div class="text-center mb-10">
        <a href="/" class="text-2xl font-bold bg-gradient-to-r from-indigo-400 to-pink-400 bg-clip-text text-transparent">ThemeHub</a>
        <h1 class="text-3xl font-bold mt-6 mb-2">Create account</h1>
        <p class="text-gray-400">Join the marketplace today</p>
      </div>

      <?php if(flash('error')): ?>
        <div class="glass rounded-2xl p-4 mb-6 border border-red-500/30 bg-red-500/5">
          <p class="text-red-400 text-sm"><?= e(flash('error')) ?></p>
        </div>
      <?php endif; ?>

      <form method="POST" action="/register" class="space-y-5">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Name</label>
          <input 
            type="text" 
            name="name" 
            value="<?= e(old('name')) ?>" 
            required 
            class="w-full glass rounded-2xl px-5 py-3.5 text-white placeholder-gray-500 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" 
            placeholder="Your name"
          >
        </div>
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
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
          <input 
            type="password" 
            name="password_confirmation" 
            required 
            class="w-full glass rounded-2xl px-5 py-3.5 text-white placeholder-gray-500 border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" 
            placeholder="••••••••"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">I want to</label>
          <select name="role" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
            <option value="customer">Buy Themes as Customer</option>
            <option value="vendor">Sell Themes as Vendor</option>
          </select>
        </div>
        <button type="submit" class="magnetic-btn btn-gradient w-full py-4 rounded-full text-white font-medium inline-flex items-center justify-center gap-2 group">
          Create Account
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </form>

      <p class="text-center text-gray-500 mt-8 text-sm">
        Already have an account? <a href="/login" class="text-indigo-400 hover:text-indigo-300 transition-colors">Sign in</a>
      </p>
    </div>
  </div>
</div>
