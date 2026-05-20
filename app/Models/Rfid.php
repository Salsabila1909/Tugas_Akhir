<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfid extends Model
{
    use HasFactory;

    protected $table = 'rfid';

    protected $fillable = [
        'uid',
        'siswa_id'
    ];

    // RELASI KE SISWA
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}