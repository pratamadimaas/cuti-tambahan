<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuTamu extends Model
{
    protected $table = 'buku_tamu';

    protected $fillable = [
        'nama_satker',
        'nama_tamu',
        'pegawai_kppn_id',
        'kantor',
        'foto_path',
        'keterangan',
        'waktu_kunjungan',
    ];

    protected $casts = [
        'waktu_kunjungan' => 'datetime',
    ];

    public function pegawaiKppn()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_kppn_id');
    }
}