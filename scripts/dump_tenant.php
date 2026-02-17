<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$t = Stancl\Tenancy\Database\Models\Tenant::find($argv[1] ?? 'clinic4');
echo json_encode($t?->data, JSON_PRETTY_PRINT), PHP_EOL;
