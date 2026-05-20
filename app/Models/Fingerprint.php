<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    protected $table = 'fingerprint';

    protected $fillable = [
        'siswa_id',
        'finger_id'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}