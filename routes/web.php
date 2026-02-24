<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\CutiTambahanController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\KepalaKantorController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SeksiController;
use App\Http\Controllers\BukuTamuController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/monitoring', [CutiTambahanController::class, 'monitoring'])->name('monitoring');

    // PEGAWAI ROUTES
    Route::middleware('check.role:pegawai')->prefix('pegawai')->name('pegawai.')->group(function () {
        Route::get('/profil', [PegawaiController::class, 'profil'])->name('profil');

        // Cuti Tambahan
        Route::resource('cuti-tambahan', CutiTambahanController::class);

        // Cuti Tahunan
        Route::prefix('cuti-tahunan')->name('cuti-tahunan.')->group(function () {
            Route::get('/',                    [CutiTambahanController::class, 'indexTahunan'])->name('index');
            Route::get('/create',              [CutiTambahanController::class, 'createTahunan'])->name('create');
            Route::post('/',                   [CutiTambahanController::class, 'storeTahunan'])->name('store');
            Route::get('/{cutiTahunan}/edit',  [CutiTambahanController::class, 'editTahunan'])->name('edit');
            Route::put('/{cutiTahunan}',       [CutiTambahanController::class, 'updateTahunan'])->name('update');
            Route::delete('/{cutiTahunan}',    [CutiTambahanController::class, 'destroyTahunan'])->name('destroy');
        });

        // Surat
        Route::get('/surat/generate-form/{cutiTambahan}', [SuratController::class, 'showGenerateForm'])->name('surat.form');
        Route::post('/surat/nota-dinas-permohonan/{cutiTambahan}', [SuratController::class, 'generateNotaDinasPermohonan'])->name('surat.nota-permohonan');
        Route::post('/surat/pemberian-cuti/{cutiTambahan}', [SuratController::class, 'generateSuratPemberianCuti'])->name('surat.pemberian');
    });

    // ADMIN ROUTES
    Route::middleware('check.role:admin')->prefix('admin')->name('admin.')->group(function () {
        // Master Pegawai
        Route::resource('pegawai', PegawaiController::class);

        // Seksi
        Route::resource('seksi', SeksiController::class);

        // Persetujuan Cuti
        Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan.index');
        Route::get('/persetujuan/{cutiTambahan}', [PersetujuanController::class, 'show'])->name('persetujuan.show');
        Route::post('/persetujuan/{cutiTambahan}/approve', [PersetujuanController::class, 'approve'])->name('persetujuan.approve');
        Route::post('/persetujuan/{cutiTambahan}/reject', [PersetujuanController::class, 'reject'])->name('persetujuan.reject');
        Route::post('/persetujuan/{cutiTambahan}/cancel', [PersetujuanController::class, 'cancel'])->name('persetujuan.cancel');

        // Generate Surat Admin
        Route::get('/surat/nota-dinas-persetujuan/{cutiTambahan}', [SuratController::class, 'generateNotaDinasPersetujuan'])->name('surat.nota-persetujuan');
        Route::post('/surat/pemberian-cuti/{cutiTambahan}', [SuratController::class, 'generateSuratPemberianCuti'])->name('surat.pemberian');

        // Rekap
        Route::prefix('rekap')->name('rekap.')->group(function () {
            Route::get('/sisa-cuti', [RekapController::class, 'sisaCuti'])->name('sisa-cuti');
            Route::get('/sisa-cuti/export', [RekapController::class, 'exportSisaCuti'])->name('export-sisa-cuti');
            Route::get('/permohonan-cuti', [RekapController::class, 'permohonanCuti'])->name('permohonan-cuti');
            Route::get('/permohonan-cuti/export', [RekapController::class, 'exportPermohonanCuti'])->name('export-permohonan-cuti');
        });

        // Kepala Kantor
        Route::get('/kepala-kantor', [KepalaKantorController::class, 'index'])->name('kepala-kantor.index');
        Route::put('/kepala-kantor', [KepalaKantorController::class, 'update'])->name('kepala-kantor.update');
    });

    // BUKU TAMU ROUTES — Bisa diakses admin & sekre
    Route::middleware('check.role:admin,sekre')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/buku-tamu', [BukuTamuController::class, 'index'])->name('buku-tamu.index');
        Route::get('/buku-tamu/create', [BukuTamuController::class, 'create'])->name('buku-tamu.create');
        Route::post('/buku-tamu', [BukuTamuController::class, 'store'])->name('buku-tamu.store');
        Route::get('/buku-tamu/{bukuTamu}/edit', [BukuTamuController::class, 'edit'])->name('buku-tamu.edit');
        Route::put('/buku-tamu/{bukuTamu}', [BukuTamuController::class, 'update'])->name('buku-tamu.update');
        Route::delete('/buku-tamu/{bukuTamu}', [BukuTamuController::class, 'destroy'])->name('buku-tamu.destroy');
    });
});