<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EspScan extends Model
{
    protected $table = 'esp_scan';

    protected $fillable = [
        'produk_id',
        'kode_barang',
        'waktu_scan'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}