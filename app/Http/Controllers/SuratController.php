<?php

namespace App\Http\Controllers;

use App\Models\CutiTambahan;
use App\Models\KepalaKantor;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class SuratController extends Controller
{
    public function generateNotaDinasPermohonan(Request $request, CutiTambahan $cutiTambahan)
    {
        if (auth()->user()->isPegawai() && $cutiTambahan->pegawai_id !== auth()->user()->pegawai->id) {
            abort(403, 'Unauthorized');
        }

        try {
            $pegawai      = $cutiTambahan->pegawai->load(['seksi', 'atasan']);
            $kepalaKantor = KepalaKantor::getActive();
            $seksi        = $pegawai->seksi;
            $atasan       = $pegawai->atasan;

            $infoCutiTambahan    = $pegawai->getInfoCutiTambahan();
            $sisaTambahanSebelum = $infoCutiTambahan['sisa'];
            $sisaTambahanSetelah = $sisaTambahanSebelum - $cutiTambahan->cuti_tambahan_jumlah;

            $infoCutiTahunan    = $pegawai->getInfoCutiTahunan();
            $sisaTahunanSebelum = $infoCutiTahunan['sisa'];
            $sisaTahunanSetelah = $sisaTahunanSebelum - $cutiTambahan->cuti_tahunan_jumlah;

            $templatePath = storage_path('app/templates/nota_dinas_permohonan_cuti_tambahan.docx');
            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template tidak ditemukan. Silakan hubungi administrator.');
            }

            $template = new TemplateProcessor($templatePath);

            $template->setValue('NOMOR_ND',   $cutiTambahan->nomor_nota_dinas);
            $template->setValue('TANGGAL_ND', Carbon::parse($cutiTambahan->tanggal_nota_dinas)->translatedFormat('d F Y'));

            $template->setValue('NAMA',        $pegawai->nama);
            $template->setValue('NIP',         $pegawai->nip);
            $template->setValue('PANGKAT_GOL', $pegawai->pangkat_gol);
            $template->setValue('JABATAN',     $pegawai->jabatan);
            $template->setValue('UNIT_KERJA',  $pegawai->unit_kerja);

            $template->setValue('NAMA_SEKSI',        $seksi ? $seksi->nama_seksi        : '-');
            $template->setValue('NAMA_KEPALA_SEKSI', $seksi ? $seksi->nama_kepala_seksi : '-');
            $template->setValue('NIP_KEPALA_SEKSI',  $seksi ? ($seksi->nip_kepala_seksi ?? '-') : '-');

            // Data Atasan - jika ada atasan pakai atasan, jika tidak pakai kepala kantor
            if ($atasan) {
                $template->setValue('NAMA_ATASAN',        $atasan->nama);
                $template->setValue('NIP_ATASAN',         $atasan->nip);
                $template->setValue('PANGKAT_GOL_ATASAN', $atasan->pangkat_gol);
                $template->setValue('JABATAN_ATASAN',     $atasan->jabatan);
            } else {
                // Jika tidak ada atasan, gunakan Kepala Kantor
                $template->setValue('NAMA_ATASAN',        $kepalaKantor ? $kepalaKantor->nama        : '-');
                $template->setValue('NIP_ATASAN',         $kepalaKantor ? $kepalaKantor->nip         : '-');
                $template->setValue('PANGKAT_GOL_ATASAN', $kepalaKantor ? $kepalaKantor->pangkat_gol : '-');
                $template->setValue('JABATAN_ATASAN',     'Kepala Kantor');
            }

            // Cuti Tambahan
            $template->setValue('CUTI_TAMBAHAN',              $cutiTambahan->periode_cuti_tambahan);
            $template->setValue('CUTI_TAMBAHAN_JUMLAH',       $cutiTambahan->cuti_tambahan_jumlah);
            $template->setValue('KUOTA_CUTI_TAMBAHAN',        $infoCutiTambahan['kuota_awal']);
            $template->setValue('CUTI_TAMBAHAN_TERPAKAI',     $infoCutiTambahan['terpakai']);
            $template->setValue('SISA_CUTI_TAMBAHAN_SEBELUM', $sisaTambahanSebelum);
            $template->setValue('SISA_CUTI_TAMBAHAN_SETELAH', $sisaTambahanSetelah);
            $template->setValue('TANGGAL_MULAI_TAMBAHAN',     $cutiTambahan->tanggal_mulai_tambahan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAMBAHAN',   $cutiTambahan->tanggal_selesai_tambahan ?? '-');

            // Cuti Tahunan
            $template->setValue('CUTI_TAHUNAN',              $cutiTambahan->periode_cuti_tahunan);
            $template->setValue('CUTI_TAHUNAN_JUMLAH',       $cutiTambahan->cuti_tahunan_jumlah);
            $template->setValue('KUOTA_CUTI_TAHUNAN',        $infoCutiTahunan['kuota_awal']);
            $template->setValue('CUTI_TAHUNAN_TERPAKAI',     $infoCutiTahunan['terpakai']);
            $template->setValue('SISA_CUTI_TAHUNAN_SEBELUM', $sisaTahunanSebelum);
            $template->setValue('SISA_CUTI_TAHUNAN_SETELAH', $sisaTahunanSetelah);
            $template->setValue('TANGGAL_MULAI_TAHUNAN',     $cutiTambahan->tanggal_mulai_tahunan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAHUNAN',   $cutiTambahan->tanggal_selesai_tahunan ?? '-');

            // Tanggal Gabungan (jika diperlukan)
            $template->setValue('TANGGAL_MULAI',   $cutiTambahan->tanggal_mulai   ?? '-');
            $template->setValue('TANGGAL_SELESAI', $cutiTambahan->tanggal_selesai ?? '-');

            $template->setValue('CUTI_TAHUNAN_INFO', $request->input('cuti_tahunan_info', '-'));
            $template->setValue('ALASAN_CUTI',       $cutiTambahan->alasan_cuti);
            $template->setValue('ALAMAT_CUTI',       $cutiTambahan->alamat_cuti);
            $template->setValue('NAMA_KEPALA_KANTOR', $kepalaKantor ? $kepalaKantor->nama : '-');

            $fileName   = 'Nota_Dinas_Permohonan_' . $pegawai->nip . '_' . time() . '.docx';
            $outputPath = storage_path('app/generated/' . $fileName);

            if (!file_exists(storage_path('app/generated'))) {
                mkdir(storage_path('app/generated'), 0755, true);
            }

            $template->saveAs($outputPath);
            return response()->download($outputPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate dokumen: ' . $e->getMessage());
        }
    }

    public function generateNotaDinasPersetujuan(CutiTambahan $cutiTambahan)
    {
        if ($cutiTambahan->status !== 'disetujui') {
            return back()->with('error', 'Hanya permohonan yang disetujui yang dapat digenerate suratnya');
        }

        try {
            $pegawai      = $cutiTambahan->pegawai->load(['seksi', 'atasan']);
            $kepalaKantor = KepalaKantor::getActive();
            $seksi        = $pegawai->seksi;
            $atasan       = $pegawai->atasan;

            $infoCutiTambahan = $pegawai->getInfoCutiTambahan();
            $infoCutiTahunan  = $pegawai->getInfoCutiTahunan();

            $templatePath = storage_path('app/templates/nota_dinas_persetujuan_cuti_tambahan.docx');
            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template tidak ditemukan. Silakan hubungi administrator.');
            }

            $template = new TemplateProcessor($templatePath);

            $nomorPersetujuan = str_replace('ND-CT', 'ND-SJ', $cutiTambahan->nomor_nota_dinas);

            $template->setValue('NOMOR_ND',   $nomorPersetujuan);
            $template->setValue('TANGGAL_ND', Carbon::now()->translatedFormat('d F Y'));

            $template->setValue('NAMA',        $pegawai->nama);
            $template->setValue('NIP',         $pegawai->nip);
            $template->setValue('PANGKAT_GOL', $pegawai->pangkat_gol);
            $template->setValue('JABATAN',     $pegawai->jabatan);
            $template->setValue('UNIT_KERJA',  $pegawai->unit_kerja);

            $template->setValue('NAMA_SEKSI',        $seksi ? $seksi->nama_seksi        : '-');
            $template->setValue('NAMA_KEPALA_SEKSI', $seksi ? $seksi->nama_kepala_seksi : '-');
            $template->setValue('NIP_KEPALA_SEKSI',  $seksi ? ($seksi->nip_kepala_seksi ?? '-') : '-');

            // Data Atasan - jika ada atasan pakai atasan, jika tidak pakai kepala kantor
            if ($atasan) {
                $template->setValue('NAMA_ATASAN',        $atasan->nama);
                $template->setValue('NIP_ATASAN',         $atasan->nip);
                $template->setValue('PANGKAT_GOL_ATASAN', $atasan->pangkat_gol);
                $template->setValue('JABATAN_ATASAN',     $atasan->jabatan);
            } else {
                $template->setValue('NAMA_ATASAN',        $kepalaKantor ? $kepalaKantor->nama        : '-');
                $template->setValue('NIP_ATASAN',         $kepalaKantor ? $kepalaKantor->nip         : '-');
                $template->setValue('PANGKAT_GOL_ATASAN', $kepalaKantor ? $kepalaKantor->pangkat_gol : '-');
                $template->setValue('JABATAN_ATASAN',     'Kepala Kantor');
            }

            // Cuti Tambahan
            $template->setValue('CUTI_TAMBAHAN',            $cutiTambahan->periode_cuti_tambahan);
            $template->setValue('CUTI_TAMBAHAN_JUMLAH',     $cutiTambahan->cuti_tambahan_jumlah);
            $template->setValue('KUOTA_CUTI_TAMBAHAN',      $infoCutiTambahan['kuota_awal']);
            $template->setValue('CUTI_TAMBAHAN_TERPAKAI',   $infoCutiTambahan['terpakai']);
            $template->setValue('SISA_CUTI_TAMBAHAN',       $infoCutiTambahan['sisa']);
            $template->setValue('TANGGAL_MULAI_TAMBAHAN',   $cutiTambahan->tanggal_mulai_tambahan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAMBAHAN', $cutiTambahan->tanggal_selesai_tambahan ?? '-');

            // Cuti Tahunan
            $template->setValue('CUTI_TAHUNAN',            $cutiTambahan->periode_cuti_tahunan);
            $template->setValue('CUTI_TAHUNAN_JUMLAH',     $cutiTambahan->cuti_tahunan_jumlah);
            $template->setValue('KUOTA_CUTI_TAHUNAN',      $infoCutiTahunan['kuota_awal']);
            $template->setValue('CUTI_TAHUNAN_TERPAKAI',   $infoCutiTahunan['terpakai']);
            $template->setValue('SISA_CUTI_TAHUNAN',       $infoCutiTahunan['sisa']);
            $template->setValue('TANGGAL_MULAI_TAHUNAN',   $cutiTambahan->tanggal_mulai_tahunan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAHUNAN', $cutiTambahan->tanggal_selesai_tahunan ?? '-');

            // Tanggal Gabungan (jika diperlukan)
            $template->setValue('TANGGAL_MULAI',   $cutiTambahan->tanggal_mulai   ?? '-');
            $template->setValue('TANGGAL_SELESAI', $cutiTambahan->tanggal_selesai ?? '-');

            $template->setValue('ALASAN_CUTI', $cutiTambahan->alasan_cuti);
            $template->setValue('ALAMAT_CUTI', $cutiTambahan->alamat_cuti);

            $template->setValue('NAMA_KEPALA_KANTOR',        $kepalaKantor ? $kepalaKantor->nama        : '-');
            $template->setValue('NIP_KEPALA_KANTOR',         $kepalaKantor ? $kepalaKantor->nip         : '-');
            $template->setValue('PANGKAT_GOL_KEPALA_KANTOR', $kepalaKantor ? $kepalaKantor->pangkat_gol : '-');

            $fileName   = 'Nota_Dinas_Persetujuan_' . $pegawai->nip . '_' . time() . '.docx';
            $outputPath = storage_path('app/generated/' . $fileName);

            if (!file_exists(storage_path('app/generated'))) {
                mkdir(storage_path('app/generated'), 0755, true);
            }

            $template->saveAs($outputPath);
            return response()->download($outputPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate dokumen: ' . $e->getMessage());
        }
    }

    public function generateSuratPemberianCuti(Request $request, CutiTambahan $cutiTambahan)
    {
        if (auth()->user()->isPegawai() && $cutiTambahan->status !== 'disetujui') {
            return back()->with('error', 'Surat pemberian cuti hanya dapat digenerate setelah disetujui');
        }

        try {
            $pegawai      = $cutiTambahan->pegawai->load(['seksi', 'atasan']);
            $kepalaKantor = KepalaKantor::getActive();
            $seksi        = $pegawai->seksi;
            $atasan       = $pegawai->atasan;

            $infoCutiTambahan = $pegawai->getInfoCutiTambahan();
            $infoCutiTahunan  = $pegawai->getInfoCutiTahunan();

            $templatePath = storage_path('app/templates/surat_pemberian_cuti_tambahan.docx');
            if (!file_exists($templatePath)) {
                return back()->with('error', 'Template tidak ditemukan. Silakan hubungi administrator.');
            }

            $template = new TemplateProcessor($templatePath);

            $template->setValue('NOMOR_SURAT',   'B-' . $cutiTambahan->nomor_nota_dinas);
            $template->setValue('TANGGAL_SURAT', Carbon::now()->translatedFormat('d F Y'));

            $template->setValue('NAMA',        $pegawai->nama);
            $template->setValue('NIP',         $pegawai->nip);
            $template->setValue('PANGKAT_GOL', $pegawai->pangkat_gol);
            $template->setValue('JABATAN',     $pegawai->jabatan);
            $template->setValue('UNIT_KERJA',  $pegawai->unit_kerja);

            $template->setValue('NAMA_SEKSI',        $seksi ? $seksi->nama_seksi        : '-');
            $template->setValue('NAMA_KEPALA_SEKSI', $seksi ? $seksi->nama_kepala_seksi : '-');
            $template->setValue('NIP_KEPALA_SEKSI',  $seksi ? ($seksi->nip_kepala_seksi ?? '-') : '-');

            // Data Atasan - jika ada atasan pakai atasan, jika tidak pakai kepala kantor
            if ($atasan) {
                $template->setValue('NAMA_ATASAN',        $atasan->nama);
                $template->setValue('NIP_ATASAN',         $atasan->nip);
                $template->setValue('PANGKAT_GOL_ATASAN', $atasan->pangkat_gol);
                $template->setValue('JABATAN_ATASAN',     $atasan->jabatan);
            } else {
                $template->setValue('NAMA_ATASAN',        $kepalaKantor ? $kepalaKantor->nama        : '-');
                $template->setValue('NIP_ATASAN',         $kepalaKantor ? $kepalaKantor->nip         : '-');
                $template->setValue('PANGKAT_GOL_ATASAN', $kepalaKantor ? $kepalaKantor->pangkat_gol : '-');
                $template->setValue('JABATAN_ATASAN',     'Kepala Kantor');
            }

            // Cuti Tambahan
            $template->setValue('CUTI_TAMBAHAN',              $cutiTambahan->periode_cuti_tambahan);
            $template->setValue('CUTI_TAMBAHAN_JUMLAH',       $cutiTambahan->cuti_tambahan_jumlah);
            $template->setValue('KUOTA_CUTI_TAMBAHAN',        $infoCutiTambahan['kuota_awal']);
            $template->setValue('CUTI_TAMBAHAN_TERPAKAI',     $infoCutiTambahan['terpakai']);
            $template->setValue('SISA_CUTI_TAMBAHAN',         $infoCutiTambahan['sisa']);
            $template->setValue('SISA_CUTI_TAMBAHAN_SETELAH', $infoCutiTambahan['sisa']);
            $template->setValue('TANGGAL_MULAI_TAMBAHAN',     $cutiTambahan->tanggal_mulai_tambahan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAMBAHAN',   $cutiTambahan->tanggal_selesai_tambahan ?? '-');

            // Cuti Tahunan
            $template->setValue('CUTI_TAHUNAN',              $cutiTambahan->periode_cuti_tahunan);
            $template->setValue('CUTI_TAHUNAN_JUMLAH',       $cutiTambahan->cuti_tahunan_jumlah);
            $template->setValue('KUOTA_CUTI_TAHUNAN',        $infoCutiTahunan['kuota_awal']);
            $template->setValue('CUTI_TAHUNAN_TERPAKAI',     $infoCutiTahunan['terpakai']);
            $template->setValue('SISA_CUTI_TAHUNAN',         $infoCutiTahunan['sisa']);
            $template->setValue('SISA_CUTI_TAHUNAN_SETELAH', $infoCutiTahunan['sisa']);
            $template->setValue('TANGGAL_MULAI_TAHUNAN',     $cutiTambahan->tanggal_mulai_tahunan   ?? '-');
            $template->setValue('TANGGAL_SELESAI_TAHUNAN',   $cutiTambahan->tanggal_selesai_tahunan ?? '-');

            // Tanggal Gabungan (jika diperlukan)
            $template->setValue('TANGGAL_MULAI',   $cutiTambahan->tanggal_mulai   ?? '-');
            $template->setValue('TANGGAL_SELESAI', $cutiTambahan->tanggal_selesai ?? '-');

            $template->setValue('CUTI_TAHUNAN_INFO', $request->input('cuti_tahunan_info', '-'));
            $template->setValue('ALASAN_CUTI',       $cutiTambahan->alasan_cuti);
            $template->setValue('ALAMAT_CUTI',       $cutiTambahan->alamat_cuti);

            $template->setValue('NAMA_KEPALA_KANTOR',        $kepalaKantor ? $kepalaKantor->nama        : '-');
            $template->setValue('NIP_KEPALA_KANTOR',         $kepalaKantor ? $kepalaKantor->nip         : '-');
            $template->setValue('PANGKAT_GOL_KEPALA_KANTOR', $kepalaKantor ? $kepalaKantor->pangkat_gol : '-');

            $fileName   = 'Surat_Pemberian_Cuti_' . $pegawai->nip . '_' . time() . '.docx';
            $outputPath = storage_path('app/generated/' . $fileName);

            if (!file_exists(storage_path('app/generated'))) {
                mkdir(storage_path('app/generated'), 0755, true);
            }

            $template->saveAs($outputPath);
            return response()->download($outputPath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate dokumen: ' . $e->getMessage());
        }
    }

    public function showGenerateForm(CutiTambahan $cutiTambahan)
    {
        if (auth()->user()->isPegawai() && $cutiTambahan->pegawai_id !== auth()->user()->pegawai->id) {
            abort(403, 'Unauthorized');
        }

        $infoCutiTambahan = $cutiTambahan->pegawai->getInfoCutiTambahan();
        $infoCutiTahunan  = $cutiTambahan->pegawai->getInfoCutiTahunan();

        return view('surat.generate-form', compact('cutiTambahan', 'infoCutiTambahan', 'infoCutiTahunan'));
    }
}