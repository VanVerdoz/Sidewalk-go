<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

$user = Pengguna::where('username', 'admin')->first();

if (!$user) {
    echo "User admin not found. Creating...\n";
    $user = new Pengguna();
    $user->username = 'admin';
    $user->nama_lengkap = 'Administrator';
    $user->role = 'admin';
    $user->status = 'aktif';
}

// Reset credentials strictly
$user->username = 'admin'; // Ensure no whitespace
$user->password = Hash::make('123456');
$user->save();

echo "Admin user updated.\n";
echo "Username: " . $user->username . "\n";
echo "Password Hash: " . $user->password . "\n";
echo "Test Check: " . (Hash::check('123456', $user->password) ? 'PASS' : 'FAIL') . "\n";
