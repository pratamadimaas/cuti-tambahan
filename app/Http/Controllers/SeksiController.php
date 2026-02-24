<?php

namespace App\Http\Controllers;

use App\Models\Seksi;
use Illuminate\Http\Request;

class SeksiController extends Controller
{
    public function index()
    {
        $seksi = Seksi::withCount('pegawai')->latest()->get();
        return view('admin.seksi.index', compact('seksi'));
    }

    public function create()
    {
        return view('admin.seksi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_seksi'        => 'required|string|max:255',
            'nama_kepala_seksi' => 'required|string|max:255',
            'nip_kepala_seksi'  => 'nullable|string|max:18',
        ]);

        Seksi::create($request->only(['nama_seksi', 'nama_kepala_seksi', 'nip_kepala_seksi']));

        return redirect()->route('admin.seksi.index')
            ->with('success', 'Seksi berhasil ditambahkan');
    }

    public function edit(Seksi $seksi)
    {
        return view('admin.seksi.edit', compact('seksi'));
    }

    public function update(Request $request, Seksi $seksi)
    {
        $request->validate([
            'nama_seksi'        => 'required|string|max:255',
            'nama_kepala_seksi' => 'required|string|max:255',
            'nip_kepala_seksi'  => 'nullable|string|max:18',
        ]);

        $seksi->update($request->only(['nama_seksi', 'nama_kepala_seksi', 'nip_kepala_seksi']));

        return redirect()->route('admin.seksi.index')
            ->with('success', 'Data seksi berhasil diperbarui');
    }

    public function destroy(Seksi $seksi)
    {
        try {
            $seksi->delete();
            return redirect()->route('admin.seksi.index')
                ->with('success', 'Seksi berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus seksi: ' . $e->getMessage());
        }
    }
}