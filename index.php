<?php
declare(strict_types=1);

use ThemeHub\Core\App;

require __DIR__ . '/app/bootstrap.php';

$app = new App();
$app->run();
