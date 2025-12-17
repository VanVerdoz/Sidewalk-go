<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE request_stok DROP CONSTRAINT IF EXISTS fk_id_cabang');
        DB::statement('ALTER TABLE request_stok ALTER COLUMN cabang_id TYPE BIGINT');
        DB::statement('ALTER TABLE request_stok ADD CONSTRAINT fk_id_cabang FOREIGN KEY (cabang_id) REFERENCES cabang(id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE request_stok DROP CONSTRAINT IF EXISTS fk_id_cabang');
        DB::statement('ALTER TABLE request_stok ALTER COLUMN cabang_id TYPE INTEGER');
        DB::statement('ALTER TABLE request_stok ADD CONSTRAINT fk_id_cabang FOREIGN KEY (cabang_id) REFERENCES cabang(id)');
    }
};

