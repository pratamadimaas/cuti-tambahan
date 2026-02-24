<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';

    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'pangkat_gol',
        'jabatan',
        'unit_kerja',
        'seksi_id',
        'kuota_cuti_tahunan',      // ← Field baru
        'kuota_cuti_tambahan',     // ← Field baru
        'sisa_cuti_tahunan',
        'sisa_cuti_tambahan',
    ];

    /**
     * Boot method untuk auto-delete user
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($pegawai) {
            if ($pegawai->user) {
                $pegawai->user->delete();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seksi()
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    public function cutiTambahan()
    {
        return $this->hasMany(CutiTambahan::class, 'pegawai_id');
    }

    public function semuaCuti()
    {
        return $this->hasMany(CutiTambahan::class, 'pegawai_id');
    }

    public function cutiTahunan()
    {
        return $this->hasMany(CutiTambahan::class, 'pegawai_id')
                    ->where('cuti_tahunan_jumlah', '>', 0);
    }

    /**
     * Get sisa cuti tambahan
     */
    public function getSisaCutiTambahan($tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        
        // Kuota awal dari database
        $kuotaAwal = $this->kuota_cuti_tambahan ?? 12;
        
        // Hitung total cuti tambahan yang sudah diambil (disetujui) di tahun tersebut
        $cutiDiambil = $this->cutiTambahan()
            ->where('status', 'disetujui')
            ->get()
            ->filter(function($cuti) use ($tahun) {
                if (empty($cuti->tanggal_cuti)) return false;
                
                foreach ($cuti->tanggal_cuti as $tanggal) {
                    if (date('Y', strtotime($tanggal)) == $tahun) {
                        return true;
                    }
                }
                return false;
            })
            ->sum('cuti_tambahan_jumlah');
        
        return max(0, $kuotaAwal - $cutiDiambil);
    }

    /**
     * Get info lengkap cuti tambahan
     */
    public function getInfoCutiTambahan($tahun = null)
    {
        $tahun = $tahun ?? date('Y');
        
        // Kuota awal dari database
        $kuotaAwal = $this->kuota_cuti_tambahan ?? 12;
        
        $cutiDiambil = $this->cutiTambahan()
            ->where('status', 'disetujui')
            ->get()
            ->filter(function($cuti) use ($tahun) {
                if (empty($cuti->tanggal_cuti)) return false;
                foreach ($cuti->tanggal_cuti as $tanggal) {
                    if (date('Y', strtotime($tanggal)) == $tahun) {
                        return true;
                    }
                }
                return false;
            })
            ->sum('cuti_tambahan_jumlah');
        
        $sisaCuti = max(0, $kuotaAwal - $cutiDiambil);
        
        return [
            'kuota_awal' => $kuotaAwal,
            'terpakai'   => $cutiDiambil,
            'sisa'       => $sisaCuti,
            'tahun'      => $tahun
        ];
    }

    /**
     * Get info lengkap cuti tahunan
     */
    public function getInfoCutiTahunan($tahun = null): array
    {
        $tahun = $tahun ?? date('Y');
        
        // Kuota awal dari database
        $kuotaAwal = $this->kuota_cuti_tahunan ?? 12;

        $cutiDiambil = $this->cutiTahunan()
            ->where('status', 'disetujui')
            ->get()
            ->filter(function ($cuti) use ($tahun) {
                if (empty($cuti->tanggal_cuti)) return false;
                foreach ($cuti->tanggal_cuti as $tanggal) {
                    if (date('Y', strtotime($tanggal)) == $tahun) return true;
                }
                return false;
            })
            ->sum('cuti_tahunan_jumlah');

        return [
            'kuota_awal' => $kuotaAwal,
            'terpakai'   => $cutiDiambil,
            'sisa'       => max(0, $kuotaAwal - $cutiDiambil),
            'tahun'      => $tahun,
        ];
    }
}