<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Siswa extends Authenticatable
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nis',
        'nama',
        'kontak',
        'alamat',
        'foto',
        'saldo',
        'status'
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

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