<?php /** @var string $token */ ?>

<div class="min-h-screen py-8">
  <div class="max-w-4xl mx-auto px-6 lg:px-8">
    <h1 class="text-4xl lg:text-5xl font-bold tracking-tight mb-12 reveal">API Tokens</h1>

    <div class="glass rounded-3xl p-8 reveal">
      <h3 class="text-xl font-semibold mb-4">Your API Token</h3>
      <p class="text-gray-400 mb-6">Use this token to authenticate API requests. Keep it secure and never share it publicly.</p>
      
      <div class="glass rounded-2xl p-4 mb-6">
        <code class="text-sm text-indigo-400 break-all"><?= e($token) ?></code>
      </div>
      
      <div class="glass rounded-2xl p-6">
        <h4 class="font-medium mb-4">Example Usage</h4>
        <pre class="text-sm text-gray-400 overflow-x-auto">curl -X GET "<?= url('api/themes') ?>" \
  -H "Authorization: Bearer <?= e($token) ?>" \
  -H "Accept: application/json"</pre>
      </div>
    </div>
  </div>
</div>
