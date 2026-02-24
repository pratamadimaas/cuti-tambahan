<?php

namespace App\Http\Controllers;

use App\Models\CutiTambahan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CutiTambahanController extends Controller
{
    // ════════════════════════════════
    //  CUTI TAMBAHAN
    // ════════════════════════════════

    public function index()
    {
        $pegawai          = auth()->user()->pegawai;
        $cutiTambahan     = $pegawai->semuaCuti()->latest()->get();
        $infoCutiTambahan = $pegawai->getInfoCutiTambahan();
        $infoCutiTahunan  = $pegawai->getInfoCutiTahunan();

        return view('pegawai.cuti-tambahan.index', compact(
            'cutiTambahan', 'pegawai', 'infoCutiTambahan', 'infoCutiTahunan'
        ));
    }

    public function create()
    {
        $pegawai          = auth()->user()->pegawai;
        $infoCutiTambahan = $pegawai->getInfoCutiTambahan();
        $infoCutiTahunan  = $pegawai->getInfoCutiTahunan();
        return view('pegawai.cuti-tambahan.create', compact('pegawai', 'infoCutiTambahan', 'infoCutiTahunan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_nota_dinas'             => 'required|date',
            'tanggal_cuti_tambahan'          => 'nullable|array',
            'tanggal_cuti_tambahan.*'        => 'nullable|date',
            'tanggal_cuti_tahunan'           => 'nullable|array',
            'tanggal_cuti_tahunan.*.tanggal' => 'nullable|date',
            'tanggal_cuti_tahunan.*.sesi'    => 'nullable|in:penuh,pagi,siang',
            'alasan_cuti'                    => 'required|string',
            'alamat_cuti'                    => 'required|string',
        ]);

        $pegawai = auth()->user()->pegawai;

        $tanggalTambahan = collect($request->tanggal_cuti_tambahan ?? [])
            ->filter()->unique()->sort()->values()->toArray();

        $tanggalTahunan = collect($request->tanggal_cuti_tahunan ?? [])
            ->filter(fn($item) => !empty($item['tanggal']))
            ->map(fn($item) => [
                'tanggal' => $item['tanggal'],
                'sesi'    => $item['sesi'] ?? 'penuh',
            ])
            ->unique('tanggal')
            ->sortBy('tanggal')
            ->values()
            ->toArray();

        // Validasi minimal satu tanggal dipilih
        if (empty($tanggalTambahan) && empty($tanggalTahunan)) {
            return back()->with('error', 'Pilih minimal satu tanggal cuti (tambahan atau tahunan).')->withInput();
        }

        // Validasi Sabtu/Minggu untuk cuti tahunan
        foreach ($tanggalTahunan as $item) {
            $day = Carbon::parse($item['tanggal'])->dayOfWeek;
            if ($day === 0 || $day === 6) {
                return back()->with('error', 'Cuti tahunan tidak dapat diajukan pada hari Sabtu atau Minggu.')->withInput();
            }
        }

        // Hitung jumlah hari tahunan (0.5 untuk setengah hari)
        $jumlahTahunan = collect($tanggalTahunan)->sum(fn($item) => $item['sesi'] === 'penuh' ? 1 : 0.5);

        // Validasi sisa cuti tahunan
        if (!empty($tanggalTahunan)) {
            $infoCutiTahunan = $pegawai->getInfoCutiTahunan();
            if ($jumlahTahunan > $infoCutiTahunan['sisa']) {
                return back()->with('error', 'Jumlah cuti tahunan melebihi sisa (' . $infoCutiTahunan['sisa'] . ' hari).')->withInput();
            }
        }

        $tahun          = date('Y');
        $bulan          = date('m');
        $lastNomor      = CutiTambahan::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count();
        $nomorUrut      = str_pad($lastNomor + 1, 3, '0', STR_PAD_LEFT);
        $nomorNotaDinas = "ND-CT/{$nomorUrut}/KEP/{$bulan}/{$tahun}";

        // Gabungkan semua tanggal untuk kolom tanggal_cuti
        $tanggalTahunanPlain = collect($tanggalTahunan)->pluck('tanggal')->toArray();
        $semuaTanggal        = array_unique(array_merge($tanggalTambahan, $tanggalTahunanPlain));
        sort($semuaTanggal);

        try {
            CutiTambahan::create([
                'pegawai_id'            => $pegawai->id,
                'nomor_nota_dinas'      => $nomorNotaDinas,
                'tanggal_nota_dinas'    => $request->tanggal_nota_dinas,
                'cuti_tambahan_jumlah'  => count($tanggalTambahan),
                'cuti_tahunan_jumlah'   => $jumlahTahunan,
                'tanggal_cuti'          => $semuaTanggal,
                'tanggal_cuti_tambahan' => $tanggalTambahan,
                'tanggal_cuti_tahunan'  => $tanggalTahunan,
                'alasan_cuti'           => $request->alasan_cuti,
                'alamat_cuti'           => $request->alamat_cuti,
                'status'                => 'menunggu',
            ]);

            return redirect()->route('pegawai.cuti-tambahan.index')
                ->with('success', 'Permohonan cuti berhasil diajukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengajukan permohonan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTambahan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat diubah');

        return view('pegawai.cuti-tambahan.edit', compact('cutiTambahan'));
    }

    public function update(Request $request, CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTambahan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat diubah');

        $request->validate([
            'tanggal_nota_dinas' => 'required|date',
            'tanggal_cuti'       => 'required|array|min:1',
            'tanggal_cuti.*'     => 'required|date',
            'alasan_cuti'        => 'required|string',
            'alamat_cuti'        => 'required|string',
        ]);

        $tanggalCuti = collect($request->tanggal_cuti)->filter()->unique()->sort()->values()->toArray();

        try {
            $cutiTambahan->update([
                'tanggal_nota_dinas'   => $request->tanggal_nota_dinas,
                'cuti_tambahan_jumlah' => count($tanggalCuti),
                'tanggal_cuti'         => $tanggalCuti,
                'alasan_cuti'          => $request->alasan_cuti,
                'alamat_cuti'          => $request->alamat_cuti,
            ]);

            return redirect()->route('pegawai.cuti-tambahan.index')
                ->with('success', 'Permohonan cuti tambahan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui permohonan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTambahan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat dihapus');

        try {
            $cutiTambahan->delete();
            return redirect()->route('pegawai.cuti-tambahan.index')
                ->with('success', 'Permohonan cuti tambahan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus permohonan: ' . $e->getMessage());
        }
    }

    public function monitoring()
    {
        if (auth()->user()->isAdmin()) {
            $cutiTambahan = CutiTambahan::with('pegawai')->latest()->get();
        } else {
            $pegawai      = auth()->user()->pegawai;
            $cutiTambahan = $pegawai->cutiTambahan()->latest()->get();
        }

        return view('monitoring.index', compact('cutiTambahan'));
    }

    // ════════════════════════════════
    //  CUTI TAHUNAN
    // ════════════════════════════════

    public function indexTahunan()
    {
        $pegawai     = auth()->user()->pegawai;
        $cutiTahunan = $pegawai->cutiTahunan()->latest()->get();
        $infoCuti    = $pegawai->getInfoCutiTahunan();

        return view('pegawai.cuti-tahunan.index', compact('cutiTahunan', 'pegawai', 'infoCuti'));
    }

    public function createTahunan()
    {
        $pegawai  = auth()->user()->pegawai;
        $infoCuti = $pegawai->getInfoCutiTahunan();
        return view('pegawai.cuti-tahunan.create', compact('pegawai', 'infoCuti'));
    }

    public function storeTahunan(Request $request)
    {
        $request->validate([
            'tanggal_nota_dinas' => 'required|date',
            'tanggal_cuti'       => 'required|array|min:1',
            'tanggal_cuti.*'     => 'required|date',
            'alasan_cuti'        => 'required|string',
            'alamat_cuti'        => 'required|string',
        ]);

        $pegawai     = auth()->user()->pegawai;
        $tanggalCuti = collect($request->tanggal_cuti)->filter()->unique()->sort()->values()->toArray();
        $jumlah      = count($tanggalCuti);

        // Cek sisa cuti tahunan
        $infoCuti = $pegawai->getInfoCutiTahunan();
        if ($jumlah > $infoCuti['sisa']) {
            return back()->with('error', 'Jumlah cuti melebihi sisa cuti tahunan (' . $infoCuti['sisa'] . ' hari)')->withInput();
        }

        $tahun          = date('Y');
        $bulan          = date('m');
        $lastNomor      = CutiTambahan::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count();
        $nomorUrut      = str_pad($lastNomor + 1, 3, '0', STR_PAD_LEFT);
        $nomorNotaDinas = "ND-TAH/{$nomorUrut}/KEP/{$bulan}/{$tahun}";

        try {
            CutiTambahan::create([
                'pegawai_id'           => $pegawai->id,
                'nomor_nota_dinas'     => $nomorNotaDinas,
                'tanggal_nota_dinas'   => $request->tanggal_nota_dinas,
                'cuti_tambahan_jumlah' => 0,
                'cuti_tahunan_jumlah'  => $jumlah,
                'tanggal_cuti'         => $tanggalCuti,
                'alasan_cuti'          => $request->alasan_cuti,
                'alamat_cuti'          => $request->alamat_cuti,
                'status'               => 'menunggu',
            ]);

            return redirect()->route('pegawai.cuti-tahunan.index')
                ->with('success', 'Permohonan cuti tahunan berhasil diajukan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengajukan permohonan: ' . $e->getMessage())->withInput();
        }
    }

    public function editTahunan(CutiTambahan $cutiTahunan)
    {
        if ($cutiTahunan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTahunan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat diubah');

        $infoCuti = auth()->user()->pegawai->getInfoCutiTahunan();
        return view('pegawai.cuti-tahunan.edit', compact('cutiTahunan', 'infoCuti'));
    }

    public function updateTahunan(Request $request, CutiTambahan $cutiTahunan)
    {
        if ($cutiTahunan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTahunan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat diubah');

        $request->validate([
            'tanggal_nota_dinas' => 'required|date',
            'tanggal_cuti'       => 'required|array|min:1',
            'tanggal_cuti.*'     => 'required|date',
            'alasan_cuti'        => 'required|string',
            'alamat_cuti'        => 'required|string',
        ]);

        $tanggalCuti = collect($request->tanggal_cuti)->filter()->unique()->sort()->values()->toArray();

        try {
            $cutiTahunan->update([
                'tanggal_nota_dinas'  => $request->tanggal_nota_dinas,
                'cuti_tahunan_jumlah' => count($tanggalCuti),
                'tanggal_cuti'        => $tanggalCuti,
                'alasan_cuti'         => $request->alasan_cuti,
                'alamat_cuti'         => $request->alamat_cuti,
            ]);

            return redirect()->route('pegawai.cuti-tahunan.index')
                ->with('success', 'Permohonan cuti tahunan berhasil diperbarui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function destroyTahunan(CutiTambahan $cutiTahunan)
    {
        if ($cutiTahunan->pegawai_id !== auth()->user()->pegawai->id) abort(403);
        if ($cutiTahunan->status !== 'menunggu') return back()->with('error', 'Permohonan yang sudah diproses tidak dapat dihapus');

        try {
            $cutiTahunan->delete();
            return redirect()->route('pegawai.cuti-tahunan.index')
                ->with('success', 'Permohonan cuti tahunan berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}