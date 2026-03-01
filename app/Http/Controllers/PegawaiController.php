<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['user', 'seksi', 'atasan'])->latest()->get()->map(function ($p) {
            $infoCutiTahunan  = $p->getInfoCutiTahunan();
            $infoCutiTambahan = $p->getInfoCutiTambahan();

            $p->sisa_display_tahunan  = $infoCutiTahunan['sisa'];
            $p->sisa_display_tambahan = $infoCutiTambahan['sisa'];

            return $p;
        });

        return view('admin.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        $seksiList   = \App\Models\Seksi::orderBy('nama_seksi')->get();
        $pegawaiList = Pegawai::orderBy('nama')->get();

        return view('admin.pegawai.create', compact('seksiList', 'pegawaiList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'nip'                => 'required|string|unique:pegawai,nip|unique:users,username|max:18',
            'pangkat_gol'        => 'required|string|max:255',
            'jabatan'            => 'required|string|max:255',
            'unit_kerja'         => 'required|string|max:255',
            'seksi_id'           => 'nullable|exists:seksi,id',
            'atasan_id'          => 'nullable|exists:pegawai,id',
            'sisa_cuti_tahunan'  => 'nullable|numeric|min:0',
            'sisa_cuti_tambahan' => 'nullable|numeric|min:0',
        ], [
            'nip.unique' => 'NIP sudah terdaftar dalam sistem.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $request->nip,
                'password' => Hash::make('123456'),
                'role'     => 'pegawai',
            ]);

            $sisaTahunan  = $request->sisa_cuti_tahunan  ?? 12;
            $sisaTambahan = $request->sisa_cuti_tambahan ?? 0;

            Pegawai::create([
                'user_id'             => $user->id,
                'nama'                => $request->nama,
                'nip'                 => $request->nip,
                'pangkat_gol'         => $request->pangkat_gol,
                'jabatan'             => $request->jabatan,
                'unit_kerja'          => $request->unit_kerja,
                'seksi_id'            => $request->seksi_id,
                'atasan_id'           => $request->atasan_id,
                'kuota_cuti_tahunan'  => $sisaTahunan,
                'kuota_cuti_tambahan' => $sisaTambahan,
                'sisa_cuti_tahunan'   => $sisaTahunan,
                'sisa_cuti_tambahan'  => $sisaTambahan,
            ]);

            DB::commit();
            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai berhasil ditambahkan. Username: ' . $request->nip . ', Password: 123456');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan pegawai: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Pegawai $pegawai)
    {
        $seksiList   = \App\Models\Seksi::orderBy('nama_seksi')->get();
        $pegawaiList = Pegawai::where('id', '!=', $pegawai->id)->orderBy('nama')->get();

        return view('admin.pegawai.edit', compact('pegawai', 'seksiList', 'pegawaiList'));
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $request->validate([
            'nama'               => 'required|string|max:255',
            'nip'                => 'required|string|max:18|unique:pegawai,nip,' . $pegawai->id . '|unique:users,username,' . $pegawai->user_id,
            'pangkat_gol'        => 'required|string|max:255',
            'jabatan'            => 'required|string|max:255',
            'unit_kerja'         => 'required|string|max:255',
            'seksi_id'           => 'nullable|exists:seksi,id',
            'atasan_id'          => 'nullable|exists:pegawai,id',
            'sisa_cuti_tahunan'  => 'nullable|numeric|min:0',
            'sisa_cuti_tambahan' => 'nullable|numeric|min:0',
        ]);

        if ($request->atasan_id == $pegawai->id) {
            return back()->with('error', 'Pegawai tidak dapat menjadi atasan dirinya sendiri')->withInput();
        }

        DB::beginTransaction();
        try {
            $pegawai->update($request->only([
                'nama', 'nip', 'pangkat_gol', 'jabatan', 'unit_kerja', 'seksi_id', 'atasan_id'
            ]));

            if ($request->has('sisa_cuti_tahunan')) {
                $pegawai->sisa_cuti_tahunan  = $request->sisa_cuti_tahunan;
                $pegawai->kuota_cuti_tahunan = $request->sisa_cuti_tahunan;
            }
            if ($request->has('sisa_cuti_tambahan')) {
                $pegawai->sisa_cuti_tambahan  = $request->sisa_cuti_tambahan;
                $pegawai->kuota_cuti_tambahan = $request->sisa_cuti_tambahan;
            }

            $pegawai->save();
            $pegawai->user->update(['username' => $request->nip]);

            DB::commit();
            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Data pegawai berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui pegawai: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Pegawai $pegawai)
    {
        DB::beginTransaction();
        try {
            $pegawai->delete();
            DB::commit();
            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Pegawai dan akun user berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pegawai: ' . $e->getMessage());
        }
    }

    public function profil()
    {
        $pegawai = auth()->user()->pegawai;
        return view('pegawai.profil', compact('pegawai'));
    }

    /**
     * Reset password pegawai ke 123456 (khusus admin)
     */
    public function resetPassword(Pegawai $pegawai)
    {
        try {
            $pegawai->user->update([
                'password' => Hash::make('123456'),
            ]);

            return redirect()->route('admin.pegawai.index')
                ->with('success', 'Password ' . $pegawai->nama . ' berhasil direset ke 123456');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage());
        }
    }
}