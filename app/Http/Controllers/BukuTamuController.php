<?php

namespace App\Http\Controllers;

use App\Models\BukuTamu;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuTamuController extends Controller
{
    public function index(Request $request)
    {
        $query = BukuTamu::with('pegawaiKppn')->latest('waktu_kunjungan');

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_kunjungan', $request->tanggal);
        }
        if ($request->filled('satker')) {
            $query->where('nama_satker', 'like', '%' . $request->satker . '%');
        }
        if ($request->filled('pegawai_id')) {
            $query->where('pegawai_kppn_id', $request->pegawai_id);
        }

        $bukuTamu  = $query->paginate(15)->withQueryString();
        $pegawaiList = Pegawai::orderBy('nama')->get();

        return view('admin.buku-tamu.index', compact('bukuTamu', 'pegawaiList'));
    }

    public function create()
    {
        $pegawaiList = Pegawai::orderBy('nama')->get();
        $kantorList  = ['Front Office', 'Seksi Vera', 'Seksi Bank', 'Seksi Pencairan Dana', 'Seksi MSKI', 'Kepala Kantor'];

        return view('admin.buku-tamu.create', compact('pegawaiList', 'kantorList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_satker'      => 'required|string|max:255',
            'nama_tamu'        => 'required|string|max:255',
            'pegawai_kppn_id'  => 'required|exists:pegawai,id',
            'kantor'           => 'required|string|max:255',
            'foto_base64'      => 'required|string',   // base64 dari kamera
            'keterangan'       => 'nullable|string|max:1000',
        ]);

        // Decode & simpan foto base64
        $base64 = $request->foto_base64;
        // Hapus prefix "data:image/jpeg;base64," dll
        if (str_contains($base64, ',')) {
            $base64 = substr($base64, strpos($base64, ',') + 1);
        }
        $fotoData  = base64_decode($base64);
        $namaFile  = 'buku-tamu/' . uniqid('tamu_') . '.jpg';
        Storage::disk('public')->put($namaFile, $fotoData);

        BukuTamu::create([
            'nama_satker'     => $request->nama_satker,
            'nama_tamu'       => $request->nama_tamu,
            'pegawai_kppn_id' => $request->pegawai_kppn_id,
            'kantor'          => $request->kantor,
            'foto_path'       => $namaFile,
            'keterangan'      => $request->keterangan,
            'waktu_kunjungan' => now(),   // ← otomatis server time
        ]);

        return redirect()->route('admin.buku-tamu.index')
            ->with('success', 'Data buku tamu berhasil disimpan.');
    }

    public function destroy(BukuTamu $bukuTamu)
    {
        Storage::disk('public')->delete($bukuTamu->foto_path);
        $bukuTamu->delete();

        return back()->with('success', 'Data berhasil dihapus.');
    }
    public function edit(BukuTamu $bukuTamu)
{
    $pegawaiList = Pegawai::orderBy('nama')->get();
    $kantorList  = ['Front Office', 'Seksi Vera', 'Seksi Bank', 'Seksi Pencairan Dana', 'Seksi MSKI', 'Kepala Kantor'];

    return view('admin.buku-tamu.edit', compact('bukuTamu', 'pegawaiList', 'kantorList'));
}

public function update(Request $request, BukuTamu $bukuTamu)
{
    $request->validate([
        'nama_satker'     => 'required|string|max:255',
        'nama_tamu'       => 'required|string|max:255',
        'pegawai_kppn_id' => 'required|exists:pegawai,id',
        'kantor'          => 'required|string|max:255',
        'foto_base64'     => 'nullable|string',
        'keterangan'      => 'nullable|string|max:1000',
    ]);

    // Ganti foto hanya jika ada foto baru
    if ($request->filled('foto_base64')) {
        Storage::disk('public')->delete($bukuTamu->foto_path);

        $base64 = $request->foto_base64;
        if (str_contains($base64, ',')) {
            $base64 = substr($base64, strpos($base64, ',') + 1);
        }
        $namaFile = 'buku-tamu/' . uniqid('tamu_') . '.jpg';
        Storage::disk('public')->put($namaFile, base64_decode($base64));
        $bukuTamu->foto_path = $namaFile;
    }

    $bukuTamu->nama_satker     = $request->nama_satker;
    $bukuTamu->nama_tamu       = $request->nama_tamu;
    $bukuTamu->pegawai_kppn_id = $request->pegawai_kppn_id;
    $bukuTamu->kantor          = $request->kantor;
    $bukuTamu->keterangan      = $request->keterangan;
    $bukuTamu->save();

    return redirect()->route('admin.buku-tamu.index')
        ->with('success', 'Data buku tamu berhasil diperbarui.');
}
}