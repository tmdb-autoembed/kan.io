<?php /** @var array $users */ ?>
<?php /** @var string $role */ ?>
<?php /** @var array $pagination */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-7xl mx-auto px-6 lg:px-8">
    <div class="mb-8 reveal">
      <h1 class="text-3xl lg:text-4xl font-bold tracking-tight capitalize"><?= $role ?>s</h1>
      <p class="text-gray-400 mt-2">Manage <?= $role ?> accounts</p>
    </div>

    <div class="glass rounded-3xl overflow-hidden reveal">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="border-b border-glass-border">
            <tr class="text-left text-sm text-gray-400">
              <th class="px-6 py-4 font-medium">User</th>
              <th class="px-6 py-4 font-medium">Email</th>
              <th class="px-6 py-4 font-medium">Role</th>
              <th class="px-6 py-4 font-medium">Status</th>
              <th class="px-6 py-4 font-medium">Joined</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-glass-border">
            <?php foreach($users as $user): ?>
              <tr class="hover:bg-white/5 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white font-medium">
                      <?= strtoupper(substr($user['name'], 0, 1)) ?>
                    </div>
                    <div class="font-medium"><?= e($user['name']) ?></div>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm"><?= e($user['email']) ?></td>
                <td class="px-6 py-4">
                  <span class="badge badge-primary capitalize"><?= e($user['role']) ?></span>
                </td>
                <td class="px-6 py-4">
                  <span class="badge badge-<?= $user['status'] === 'active' ? 'success' : 'warning' ?>"><?= e($user['status']) ?></span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400"><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
              </tr>
            <?php endforeach ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
