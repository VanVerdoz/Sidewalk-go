<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('nama_produk', 100);
            $table->decimal('harga', 12);
            $table->string('kategori', 50)->nullable();
            $table->text('deskripsi')->nullable(); 
            $table->string('status')->default('aktif');
            $table->timestamp('created_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
