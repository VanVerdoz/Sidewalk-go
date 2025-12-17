<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestStokDetail extends Model
{
    protected $table = 'request_stok_detail';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'produk_id',
        'jumlah',
    ];

    public function request()
    {
        return $this->belongsTo(RequestStok::class, 'request_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
