<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    public $timestamps = false;

    protected $table = 'cabang';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'status',
    ];

    public function stok()
    {
        return $this->hasMany(Stok::class, 'cabang_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            // Format tanggal: YYYYMMDD
            $today = date('Ymd');

            // Ambil ID terakhir di tabel CABANG
            $last = Cabang::where('id', 'LIKE', $today . '%')
                ->orderBy('id', 'desc')
                ->first();

            // Nomor urut
            if ($last) {
                $num = intval(substr($last->id, -4)) + 1; 
            } else {
                $num = 1;
            }

            // Gabungkan YYYYMMDD + nomor urut 4 digit
            $generatedId = $today . str_pad($num, 4, '0', STR_PAD_LEFT);

            // Set ID
            $model->id = $generatedId;
        });
    }
}
