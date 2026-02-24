<?php

namespace App\Http\Controllers;

use App\Models\CutiTambahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    public function index()
    {
        $permohonan = CutiTambahan::with('pegawai')->latest()->get();
        return view('admin.persetujuan.index', compact('permohonan'));
    }

    public function show(CutiTambahan $cutiTambahan)
    {
        $cutiTambahan->load(['pegawai', 'pegawai.seksi']);
        return view('admin.persetujuan.show', compact('cutiTambahan'));
    }

    public function approve(Request $request, CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->status !== 'menunggu') {
            return back()->with('error', 'Permohonan sudah diproses sebelumnya');
        }

        DB::beginTransaction();
        try {
            $cutiTambahan->update(['status' => 'disetujui']);

            $pegawai = $cutiTambahan->pegawai;
            
            // Kurangi cuti tambahan
            if ($cutiTambahan->cuti_tambahan_jumlah > 0) {
                $pegawai->decrement('sisa_cuti_tambahan', $cutiTambahan->cuti_tambahan_jumlah);
            }
            
            // Kurangi cuti tahunan
            if ($cutiTambahan->cuti_tahunan_jumlah > 0) {
                $pegawai->decrement('sisa_cuti_tahunan', $cutiTambahan->cuti_tahunan_jumlah);
            }

            DB::commit();
            return redirect()->route('admin.persetujuan.index')
                ->with('success', 'Permohonan berhasil disetujui. Sisa cuti pegawai telah dikurangi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui permohonan: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, CutiTambahan $cutiTambahan)
    {
        $request->validate([
            'catatan_admin' => 'required|string', // Ubah dari alasan_penolakan
        ]);

        if ($cutiTambahan->status !== 'menunggu') {
            return back()->with('error', 'Permohonan sudah diproses sebelumnya');
        }

        try {
            $cutiTambahan->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->catatan_admin, // Simpan alasan penolakan
            ]);

            return redirect()->route('admin.persetujuan.index')
                ->with('success', 'Permohonan berhasil ditolak');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menolak permohonan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan persetujuan — kembalikan status ke menunggu & kembalikan sisa cuti
     * Hanya admin yang bisa mengakses (dijaga di route middleware)
     */
    public function cancel(CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->status === 'menunggu') {
            return back()->with('error', 'Permohonan masih menunggu, tidak perlu dibatalkan');
        }

        DB::beginTransaction();
        try {
            // Jika sebelumnya disetujui, kembalikan sisa cuti
            if ($cutiTambahan->status === 'disetujui') {
                // Kembalikan cuti tambahan
                if ($cutiTambahan->cuti_tambahan_jumlah > 0) {
                    $cutiTambahan->pegawai->increment('sisa_cuti_tambahan', $cutiTambahan->cuti_tambahan_jumlah);
                }
                
                // Kembalikan cuti tahunan
                if ($cutiTambahan->cuti_tahunan_jumlah > 0) {
                    $cutiTambahan->pegawai->increment('sisa_cuti_tahunan', $cutiTambahan->cuti_tahunan_jumlah);
                }
            }

            $cutiTambahan->update([
                'status' => 'menunggu',
                'catatan_admin' => null, // Reset catatan admin
            ]);

            DB::commit();
            return redirect()->route('admin.persetujuan.index')
                ->with('success', 'Persetujuan berhasil dibatalkan. Status dikembalikan ke menunggu.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan persetujuan: ' . $e->getMessage());
        }
    }
}