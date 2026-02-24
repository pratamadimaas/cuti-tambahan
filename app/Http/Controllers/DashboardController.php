<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\CutiTambahan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Sekre langsung redirect ke buku tamu
        if ($user->role === 'sekre') {
            return redirect()->route('admin.buku-tamu.index');
        }

        if ($user->isAdmin()) {
            $data = [
                'totalPegawai'        => Pegawai::count(),
                'totalPermohonan'     => CutiTambahan::count(),
                'permohonanMenunggu'  => CutiTambahan::where('status', 'menunggu')->count(),
                'permohonanDisetujui' => CutiTambahan::where('status', 'disetujui')->count(),
                'permohonanDitolak'   => CutiTambahan::where('status', 'ditolak')->count(),
            ];
            return view('admin.dashboard', $data);
        }

        // Pegawai Dashboard
        $pegawai          = $user->pegawai->load('seksi');
        $infoCutiTambahan = $pegawai->getInfoCutiTambahan();
        $infoCutiTahunan  = $pegawai->getInfoCutiTahunan();

        $data = [
            'pegawai'             => $pegawai,

            // Cuti Tambahan
            'totalPermohonan'     => $pegawai->cutiTambahan()->count(),
            'permohonanMenunggu'  => $pegawai->cutiTambahan()->where('status', 'menunggu')->count(),
            'permohonanDisetujui' => $pegawai->cutiTambahan()->where('status', 'disetujui')->count(),
            'sisaCutiTambahan'    => $infoCutiTambahan['sisa'],
            'kuotaCutiTambahan'   => $infoCutiTambahan['kuota_awal'],
            'terpakaicutiTambahan'=> $infoCutiTambahan['terpakai'],

            // Cuti Tahunan
            'totalPermohonanTahunan'     => $pegawai->cutiTahunan()->count(),
            'permohonanTahunanMenunggu'  => $pegawai->cutiTahunan()->where('status', 'menunggu')->count(),
            'permohonanTahunanDisetujui' => $pegawai->cutiTahunan()->where('status', 'disetujui')->count(),
            'sisaCutiTahunan'    => $infoCutiTahunan['sisa'],
            'kuotaCutiTahunan'   => $infoCutiTahunan['kuota_awal'],
            'terpakaiCutiTahunan'=> $infoCutiTahunan['terpakai'],
        ];

        return view('pegawai.dashboard', $data);
    }
}