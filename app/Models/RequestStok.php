<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStok extends Model
{
    protected $table = 'request_stok';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'cabang_id',
        'raider_id',
        'status',
        'catatan',
        'tanggal'
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function raider()
    {
        return $this->belongsTo(Pengguna::class, 'raider_id');
    }

    public function details()
    {
        return $this->hasMany(RequestStokDetail::class, 'request_id', 'id');
    }
}
