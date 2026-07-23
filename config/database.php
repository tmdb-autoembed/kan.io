<?php
return [
    'connection' => envv('DB_CONNECTION', 'sqlite'),
    'sqlite' => [
        'path' => envv('DB_DATABASE', dirname(__DIR__) . '/database/marketplace.sqlite'),
    ],
];
