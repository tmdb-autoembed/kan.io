<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Create Coupon</h1>
    </div>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="/admin/coupons" class="space-y-6">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Coupon Code</label>
            <input type="text" name="code" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors uppercase" placeholder="WELCOME10">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
            <select name="type" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <option value="percent">Percentage</option>
              <option value="fixed">Fixed Amount</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Value</label>
            <input type="number" step="0.01" name="value" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="10">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Amount ($)</label>
            <input type="number" step="0.01" name="min_amount" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="50">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Starts At</label>
            <input type="datetime-local" name="starts_at" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Expires At</label>
            <input type="datetime-local" name="expires_at" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Usage Limit</label>
            <input type="number" name="usage_limit" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="100">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
            <select name="status" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="flex items-center gap-4 pt-4">
          <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
            Create Coupon
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
          </button>
          <a href="/admin/coupons" class="magnetic-btn btn-glass px-8 py-4 rounded-full text-white font-medium">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
