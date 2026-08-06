<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Create Blog Post</h1>
    </div>

    <div class="glass rounded-3xl p-8 reveal">
      <form method="POST" action="/admin/blog" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
          <input type="text" name="title" required class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors" placeholder="Enter post title">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Excerpt</label>
          <textarea name="excerpt" rows="2" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none" placeholder="Short description"></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
          <textarea name="content" rows="10" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors resize-none" placeholder="Write your post content..."></textarea>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Featured Image</label>
          <input type="file" name="featured_image" accept="image/*" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
          <select name="status" class="w-full glass rounded-2xl px-5 py-3.5 text-white border border-glass-border focus:border-indigo-500/50 focus:outline-none transition-colors">
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>
        <button type="submit" class="magnetic-btn btn-gradient px-8 py-4 rounded-full text-white font-medium inline-flex items-center gap-2 group">
          Publish Post
          <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
        </button>
      </form>
    </div>
  </div>
</div>
