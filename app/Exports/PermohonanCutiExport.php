<?php

namespace App\Exports;

use App\Models\CutiTambahan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PermohonanCutiExport implements FromQuery, WithHeadings, WithMapping
{
    protected $status;
    protected $tahun;
    protected $bulan;

    public function __construct($status = null, $tahun = null, $bulan = null)
    {
        $this->status = $status;
        $this->tahun = $tahun;
        $this->bulan = $bulan;
    }

    public function query()
    {
        $query = CutiTambahan::with('pegawai');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->tahun) {
            $query->whereYear('tanggal_nota_dinas', $this->tahun);
        }

        if ($this->bulan) {
            $query->whereMonth('tanggal_nota_dinas', $this->bulan);
        }

        return $query->latest('created_at');
    }

    public function headings(): array
    {
        return [
            'Nama Pegawai',
            'Nomor Nota Dinas',
            'Tanggal Nota Dinas',
            'Jumlah Hari',
            'Periode Cuti',
            'Status',
            'Alasan Cuti'
        ];
    }

    public function map($cuti): array
    {
        return [
            $cuti->pegawai->nama ?? '-',
            $cuti->nomor_nota_dinas ?? '-',
            $cuti->tanggal_nota_dinas ? Carbon::parse($cuti->tanggal_nota_dinas)->format('d/m/Y') : '-',
            $cuti->cuti_tambahan_jumlah ?? 0,
            $cuti->periode_cuti ?? '-',
            ucfirst($cuti->status),
            $cuti->alasan_cuti ?? '-'
        ];
    }
}