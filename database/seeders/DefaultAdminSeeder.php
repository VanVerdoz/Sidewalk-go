<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        if (!Pengguna::where('username', 'admin')->exists()) {
            Pengguna::create([
                'username' => 'admin',
                'password' => Hash::make('123456'),
                'nama_lengkap' => 'Administrator',
                'role' => 'admin',
                'status' => 'aktif'
            ]);
        }
    }
}
