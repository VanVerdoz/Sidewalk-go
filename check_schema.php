<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "Checking Schema...\n";
$tables = ['request_stok', 'request_stok_detail', 'cabang', 'pengguna', 'produk'];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table '$table' exists.\n";
        $columns = Schema::getColumnListing($table);
        echo "Columns: " . implode(', ', $columns) . "\n";
    } else {
        echo "Table '$table' DOES NOT EXIST!\n";
    }
}
