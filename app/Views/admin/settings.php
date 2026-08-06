<?php /** @var array $settings */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Settings</h1>
      <p class="text-gray-400 mt-2">Manage your application settings</p>
    </div>

    <form method="POST" action="/admin/settings" class="space-y-8">
      <?= csrf_field() ?>

      <!-- General Settings -->
      <div class="glass rounded-3xl p-8 reveal">
        <h2 class="text-xl font-semibold mb-6">General</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Site Name</label>
            <input type="text" name="site_name" value="<?= e($settings['site_name'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Site Description</label>
            <textarea name="site_description" rows="3" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none"><?= e($settings['site_description'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Site URL</label>
            <input type="url" name="site_url" value="<?= e($settings['site_url'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
        </div>
      </div>

      <!-- Email Settings -->
      <div class="glass rounded-3xl p-8 reveal">
        <h2 class="text-xl font-semibold mb-6">Email Settings</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">From Email</label>
            <input type="email" name="mail_from_email" value="<?= e($settings['mail_from_email'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">From Name</label>
            <input type="text" name="mail_from_name" value="<?= e($settings['mail_from_name'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
        </div>
      </div>

      <!-- Payment Settings -->
      <div class="glass rounded-3xl p-8 reveal">
        <h2 class="text-xl font-semibold mb-6">Payment Settings</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Stripe Public Key</label>
            <input type="text" name="stripe_public_key" value="<?= e($settings['stripe_public_key'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Stripe Secret Key</label>
            <input type="password" name="stripe_secret_key" value="<?= e($settings['stripe_secret_key'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">PayPal Client ID</label>
            <input type="text" name="paypal_client_id" value="<?= e($settings['paypal_client_id'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
          </div>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Save Settings
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </div>
    </form>
  </div>
</div>
