<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'type',
        'siswa_id',
        'produk_id',
        'qty',
        'harga_satuan',
        'total',
        'status',
        'metode',
        'rfid_uid',
        'finger_id',
        'paid_at'
    ];

    // RELASI SISWA
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    // RELASI PRODUK (payment saja)
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}