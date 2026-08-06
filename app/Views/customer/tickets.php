<?php /** @var array $tickets */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 reveal">
      <h1 class="text-4xl lg:text-5xl font-bold tracking-tight">Support Tickets</h1>
      <a href="/customer/tickets/create" class="magnetic-btn btn-gradient px-6 py-3 rounded-full text-sm font-medium text-white inline-flex items-center gap-2">
        New Ticket
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
      </a>
    </div>

    <?php if(empty($tickets)): ?>
      <div class="glass rounded-3xl p-12 text-center reveal">
        <div class="text-6xl mb-4">
        <svg class="w-16 h-16 mx-auto text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
      </div>
        <p class="text-gray-400 text-lg">No support tickets</p>
      </div>
    <?php else: ?>
      <div class="space-y-4">
        <?php foreach($tickets as $ticket): ?>
          <div class="glass bento-card rounded-3xl p-6 reveal">
            <div class="flex justify-between items-start">
              <div>
                <h3 class="font-semibold text-lg mb-1"><?= e($ticket['subject']) ?></h3>
                <p class="text-sm text-gray-500"><?= date('M j, Y', strtotime($ticket['created_at'])) ?></p>
              </div>
              <span class="badge badge-<?= $ticket['status'] === 'open' ? 'warning' : ($ticket['status'] === 'resolved' ? 'success' : 'primary') ?>"><?= e($ticket['status']) ?></span>
            </div>
          </div>
        <?php endforeach ?>
      </div>
    <?php endif; ?>
  </div>
</div>
