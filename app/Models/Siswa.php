<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'rfid_id',
        'nis',
        'nama',
        'kontak',
        'alamat',
        'foto',
        'saldo',
        'status'
    ];

    public function rfid()
    {
        return $this->hasOne(Rfid::class);
    }

        public function fingerprint()
    {
        return $this->hasOne(Fingerprint::class);
    }

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }
}