<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seksi extends Model
{
    protected $table = 'seksi';

    protected $fillable = [
        'nama_seksi',
        'nama_kepala_seksi',
        'nip_kepala_seksi',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'seksi_id');
    }
}