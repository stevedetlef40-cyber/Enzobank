<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "Current Database: " . Illuminate\Support\Facades\DB::connection()->getDatabaseName() . PHP_EOL;
try {
    Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Connection: SUCCESS" . PHP_EOL;
} catch (\Exception $e) {
    echo "Connection: FAILED (" . $e->getMessage() . ")" . PHP_EOL;
}
