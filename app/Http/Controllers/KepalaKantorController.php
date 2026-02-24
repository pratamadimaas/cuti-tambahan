<?php

namespace App\Http\Controllers;

use App\Models\KepalaKantor;
use Illuminate\Http\Request;

class KepalaKantorController extends Controller
{
    public function index()
    {
        $kepalaKantor = KepalaKantor::getActive();
        return view('admin.kepala-kantor.index', compact('kepalaKantor'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:18',
            'pangkat_gol' => 'nullable|string|max:255',
        ]);

        $kepalaKantor = KepalaKantor::getActive();

        if ($kepalaKantor) {
            $kepalaKantor->update($request->only(['nama', 'nip', 'pangkat_gol']));
            $message = 'Data Kepala Kantor berhasil diperbarui';
        } else {
            KepalaKantor::create($request->only(['nama', 'nip', 'pangkat_gol']));
            $message = 'Data Kepala Kantor berhasil ditambahkan';
        }

        return redirect()->route('admin.kepala-kantor.index')
            ->with('success', $message);
    }
}