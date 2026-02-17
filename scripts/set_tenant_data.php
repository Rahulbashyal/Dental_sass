<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$t = Stancl\Tenancy\Database\Models\Tenant::find($argv[1] ?? 'clinic4');
var_export($t->getAttributes());
$t->update(['data' => ['config' => ['mail' => ['default' => 'smtp', 'mailers' => ['smtp' => ['host' => 'smtp.example.test']]]]]]);
echo "updated\n";
var_export(Stancl\Tenancy\Database\Models\Tenant::find($argv[1] ?? 'clinic4')->getAttributes());
