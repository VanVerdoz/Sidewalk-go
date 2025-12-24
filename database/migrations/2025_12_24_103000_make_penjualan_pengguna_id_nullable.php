<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Mengubah kolom pengguna_id menjadi nullable agar data penjualan tidak hilang saat user dihapus
        // Menggunakan raw SQL agar kompatibel dengan PostgreSQL tanpa perlu doctrine/dbal
        $driver = DB::getDriverName();
        
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE penjualan ALTER COLUMN pengguna_id DROP NOT NULL');
        } else {
            // Fallback untuk MySQL/lainnya jika environment berubah (meski user pakai pgsql)
            Schema::table('penjualan', function (Blueprint $table) {
                $table->unsignedBigInteger('pengguna_id')->nullable()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu revert strict karena akan menyebabkan error jika ada data null
    }
};
