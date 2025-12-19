<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Optional;

$raiderObj = optional(null);
$username = $raiderObj->username;
echo "Username: " . var_export($username, true) . "\n";
echo "Isset: " . (isset($raiderObj->username) ? 'true' : 'false') . "\n";

$raiderNama = ($raiderObj->nama_lengkap ?? $raiderObj->username ?? '-') . (isset($raiderObj->username) ? ' (' . $raiderObj->username . ')' : '');
echo "Result: $raiderNama\n";

class User { public $username = 'jdoe'; public $nama_lengkap = null; }
$user = new User();
$raiderObj = optional($user);
$raiderNama = ($raiderObj->nama_lengkap ?? $raiderObj->username ?? '-') . (isset($raiderObj->username) ? ' (' . $raiderObj->username . ')' : '');
echo "Result 2: $raiderNama\n";
