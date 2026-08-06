<?php /** @var array $settings */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">SEO Settings</h1>
      <p class="text-gray-400 mt-2">Optimize your site for search engines</p>
    </div>

    <form method="POST" action="/admin/seo" class="space-y-8">
      <?= csrf_field() ?>

      <div class="glass rounded-3xl p-8 reveal">
        <h2 class="text-xl font-semibold mb-6">Meta Information</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta Title</label>
            <input type="text" name="seo_title" value="<?= e($settings['seo_title'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="Site title for SEO">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta Description</label>
            <textarea name="seo_description" rows="3" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none"><?= e($settings['seo_description'] ?? '') ?></textarea>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Meta Keywords</label>
            <input type="text" name="seo_keywords" value="<?= e($settings['seo_keywords'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="keyword1, keyword2, keyword3">
          </div>
        </div>
      </div>

      <div class="glass rounded-3xl p-8 reveal">
        <h2 class="text-xl font-semibold mb-6">Analytics</h2>
        <div class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Google Analytics ID</label>
            <input type="text" name="google_analytics" value="<?= e($settings['google_analytics'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="G-XXXXXXXXXX">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Google Tag Manager ID</label>
            <input type="text" name="google_tag_manager" value="<?= e($settings['google_tag_manager'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="GTM-XXXXXX">
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">Facebook Pixel ID</label>
            <input type="text" name="facebook_pixel" value="<?= e($settings['facebook_pixel'] ?? '') ?>" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="XXXXXXXXXXXXXXXX">
          </div>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Save SEO Settings
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </div>
    </form>
  </div>
</div>
