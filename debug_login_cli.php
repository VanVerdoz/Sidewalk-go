<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

echo "--- Debugging Login ---\n";

$username = 'admin';
$password = '123456';

echo "Attempting login for: $username / $password\n";

// 1. Check User direct
$user = Pengguna::where('username', $username)->first();
if (!$user) {
    echo "User not found in DB!\n";
    exit;
}
echo "User found: " . $user->username . " (ID: " . $user->id . ")\n";
echo "Stored Hash: " . $user->password . "\n";

// 2. Check Hash manual
if (Hash::check($password, $user->password)) {
    echo "Hash::check PASS\n";
} else {
    echo "Hash::check FAIL\n";
    echo "New Hash would be: " . Hash::make($password) . "\n";
}

// 3. Check Auth::attempt
try {
    $attempt = Auth::guard('web')->attempt(['username' => $username, 'password' => $password]);
    echo "Auth::guard('web')->attempt result: " . ($attempt ? 'TRUE' : 'FALSE') . "\n";
} catch (\Exception $e) {
    echo "Auth::attempt Exception: " . $e->getMessage() . "\n";
}

// 4. Check for multiple users
$count = Pengguna::where('username', $username)->count();
echo "User count for '$username': $count\n";
