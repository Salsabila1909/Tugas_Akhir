<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kode_barang',
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'status',
    ];

    public function scans()
    {
        return $this->hasMany(EspScan::class);
    }
}