<?php

namespace App\Exports;

use App\Models\Pegawai;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SisaCutiExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $tahunIni = date('Y');
        
        return Pegawai::with(['cutiTambahan' => function($query) {
            $query->where('status', 'disetujui');
        }])->get()->map(function($p) use ($tahunIni) {
            // Hitung total cuti yang disetujui di tahun ini
            $cutiDiambil = $p->cutiTambahan->filter(function($cuti) use ($tahunIni) {
                if (empty($cuti->tanggal_cuti)) return false;
                
                foreach ($cuti->tanggal_cuti as $tanggal) {
                    if (date('Y', strtotime($tanggal)) == $tahunIni) {
                        return true;
                    }
                }
                return false;
            })->sum('cuti_tambahan_jumlah');
            
            $p->kuota_awal = 12;
            $p->cuti_disetujui = $cutiDiambil;
            $p->sisa_cuti = $p->kuota_awal - $cutiDiambil;
            
            return $p;
        });
    }

    public function headings(): array
    {
        return [
            'NIP',
            'Nama Pegawai',
            'Jabatan',
            'Kuota Awal',
            'Cuti Diambil',
            'Sisa Cuti'
        ];
    }

    public function map($pegawai): array
    {
        return [
            $pegawai->nip ?? '-',
            $pegawai->nama,
            $pegawai->jabatan ?? '-',
            $pegawai->kuota_awal,
            $pegawai->cuti_disetujui,
            $pegawai->sisa_cuti
        ];
    }
}