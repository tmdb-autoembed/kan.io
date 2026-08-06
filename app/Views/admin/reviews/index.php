<?php /** @var array $reviews */ ?>
<?php /** @var string $status */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight">Reviews</h1>
      <p class="text-gray-400 mt-2">Manage customer reviews</p>
    </div>

    <div class="flex gap-4 mb-8">
      <a href="/admin/reviews?status=pending" class="px-4 py-2 rounded-full text-sm font-medium <?= $status === 'pending' ? 'bg-indigo-500 text-white' : 'glass text-gray-400 hover:text-white' ?> transition-colors">Pending</a>
      <a href="/admin/reviews?status=approved" class="px-4 py-2 rounded-full text-sm font-medium <?= $status === 'approved' ? 'bg-emerald-500 text-white' : 'glass text-gray-400 hover:text-white' ?> transition-colors">Approved</a>
      <a href="/admin/reviews?status=rejected" class="px-4 py-2 rounded-full text-sm font-medium <?= $status === 'rejected' ? 'bg-red-500 text-white' : 'glass text-gray-400 hover:text-white' ?> transition-colors">Rejected</a>
    </div>

    <?php if(empty($reviews)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No reviews found</p>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach($reviews as $review): ?>
          <div class="glass bento-card rounded-3xl p-6 reveal">
            <div class="flex flex-col sm:flex-row justify-between gap-4">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                  <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white font-medium">
                    <?= strtoupper(substr($review['user']['name'] ?? 'U', 0, 1)) ?>
                  </div>
                  <div>
                    <div class="font-medium"><?= e($review['user']['name'] ?? 'Anonymous') ?></div>
                    <div class="flex items-center gap-1"><?= star_rating((float)$review['rating']) ?></div>
                  </div>
                </div>
                <p class="text-gray-400"><?= e($review['comment']) ?></p>
                <div class="text-sm text-gray-500 mt-2"><?= date('M j, Y', strtotime($review['created_at'])) ?></div>
              </div>
              <?php if($status === 'pending'): ?>
                <div class="flex items-center gap-2">
                  <form action="/admin/reviews/<?= $review['id'] ?>/approve" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="magnetic-btn px-4 py-2 rounded-full text-sm font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-500/30 transition-colors">Approve</button>
                  </form>
                  <form action="/admin/reviews/<?= $review['id'] ?>/reject" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="magnetic-btn px-4 py-2 rounded-full text-sm font-medium bg-red-500/20 text-red-400 border border-red-500/30 hover:bg-red-500/30 transition-colors">Reject</button>
                  </form>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
