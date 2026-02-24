<?php

namespace App\Http\Controllers;

use App\Models\CutiTambahan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SisaCutiExport;
use App\Exports\PermohonanCutiExport;

class RekapController extends Controller
{
    // ──────────── HELPER ────────────

    private function formatHari(float $nilai): string
    {
        return $nilai == floor($nilai) ? (string)(int)$nilai : number_format($nilai, 1);
    }

    // ──────────── REKAP ────────────

    public function sisaCuti()
    {
        $tahunIni = date('Y');

        $pegawai = Pegawai::with(['cutiTambahan' => function ($query) {
            $query->where('status', 'disetujui');
        }])->get()->map(function ($p) use ($tahunIni) {

            $infoCutiTahunan  = $p->getInfoCutiTahunan($tahunIni);
            $infoCutiTambahan = $p->getInfoCutiTambahan($tahunIni);

            // Cuti Tahunan
            $p->kuota_tahunan    = $this->formatHari((float)$infoCutiTahunan['kuota_awal']);
            $p->terpakai_tahunan = $this->formatHari((float)$infoCutiTahunan['terpakai']);
            $p->sisa_tahunan     = $this->formatHari((float)$infoCutiTahunan['sisa']);

            // Cuti Tambahan
            $p->kuota_tambahan    = $this->formatHari((float)$infoCutiTambahan['kuota_awal']);
            $p->terpakai_tambahan = $this->formatHari((float)$infoCutiTambahan['terpakai']);
            $p->sisa_tambahan     = $this->formatHari((float)$infoCutiTambahan['sisa']);

            return $p;
        });

        return view('admin.rekap.sisa-cuti', compact('pegawai'));
    }

    public function permohonanCuti(Request $request)
    {
        $query = CutiTambahan::with('pegawai');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_nota_dinas', $request->tahun);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_nota_dinas', $request->bulan);
        }

        $permohonan = $query->latest('created_at')->paginate(20);

        return view('admin.rekap.permohonan-cuti', compact('permohonan'));
    }

    public function exportSisaCuti()
    {
        $fileName = 'Rekap_Sisa_Cuti_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new SisaCutiExport(), $fileName);
    }

    public function exportPermohonanCuti(Request $request)
    {
        $fileName = 'Rekap_Permohonan_Cuti_' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(
            new PermohonanCutiExport($request->input('status'), $request->input('tahun'), $request->input('bulan')),
            $fileName
        );
    }
}