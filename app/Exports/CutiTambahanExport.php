<?php

namespace App\Exports;

use App\Models\CutiTambahan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CutiTambahanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $status;
    protected $tahun;

    public function __construct($status = null, $tahun = null)
    {
        $this->status = $status;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = CutiTambahan::with('pegawai');

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->tahun) {
            $query->whereYear('tanggal_nota_dinas', $this->tahun);
        }

        return $query->orderBy('tanggal_nota_dinas', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Nota Dinas',
            'Tanggal Nota Dinas',
            'NIP',
            'Nama Pegawai',
            'Pangkat/Gol',
            'Jabatan',
            'Unit Kerja',
            'Jumlah Hari',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Alasan Cuti',
            'Alamat Cuti',
            'Status',
        ];
    }

    public function map($cutiTambahan): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $cutiTambahan->nomor_nota_dinas,
            Carbon::parse($cutiTambahan->tanggal_nota_dinas)->format('d-m-Y'),
            $cutiTambahan->pegawai->nip,
            $cutiTambahan->pegawai->nama,
            $cutiTambahan->pegawai->pangkat_gol,
            $cutiTambahan->pegawai->jabatan,
            $cutiTambahan->pegawai->unit_kerja,
            $cutiTambahan->cuti_tambahan_jumlah,
            Carbon::parse($cutiTambahan->cuti_tambahan_mulai)->format('d-m-Y'),
            Carbon::parse($cutiTambahan->cuti_tambahan_selesai)->format('d-m-Y'),
            $cutiTambahan->alasan_cuti,
            $cutiTambahan->alamat_cuti,
            ucfirst($cutiTambahan->status),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}