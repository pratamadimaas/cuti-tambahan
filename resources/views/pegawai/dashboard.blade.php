@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="page-header mb-0">
        <h4>Dashboard</h4>
        <p>Selamat datang, {{ $pegawai->nama }}</p>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-calendar-check"></i></div>
            <div>
                <div class="stat-value">{{ $sisaCutiTambahan }}</div>
                <div class="stat-label">Sisa Cuti Tambahan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-calendar2-check"></i></div>
            <div>
                <div class="stat-value">{{ $sisaCutiTahunan }}</div>
                <div class="stat-label">Sisa Cuti Tahunan</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $permohonanMenunggu + $permohonanTahunanMenunggu }}</div>
                <div class="stat-label">Permohonan Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $permohonanDisetujui + $permohonanTahunanDisetujui }}</div>
                <div class="stat-label">Permohonan Disetujui</div>
            </div>
        </div>
    </div>
</div>

{{-- INFORMASI PEGAWAI --}}
<div class="card mb-4">
    <div class="card-body p-0">
        <div class="card-header-clean pt-4 pb-3 px-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-badge fs-5 text-primary"></i>
                <span class="card-title-sm">Informasi Pegawai</span>
            </div>
        </div>
        <div class="p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <table class="table table-borderless mb-0" style="font-size: 13.5px;">
                        <tr>
                            <th class="text-muted fw-500 ps-0" width="160">Nama</th>
                            <td class="fw-semibold">{{ $pegawai->nama }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">NIP</th>
                            <td class="font-mono">{{ $pegawai->nip }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Pangkat / Gol</th>
                            <td>{{ $pegawai->pangkat_gol }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Jabatan</th>
                            <td>{{ $pegawai->jabatan }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Unit Kerja</th>
                            <td>{{ $pegawai->unit_kerja }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless mb-0" style="font-size: 13.5px;">
                        <tr>
                            <th class="text-muted fw-500 ps-0" width="160">Seksi</th>
                            <td>
                                @if($pegawai->seksi)
                                    <span class="badge" style="background:#eef2ff; color:#3b5bdb; font-size:12px; font-weight:600; border-radius:6px; padding:4px 10px;">
                                        {{ $pegawai->seksi->nama_seksi }}
                                    </span>
                                @else
                                    <span class="text-muted fst-italic">Belum ditentukan</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Kepala Seksi</th>
                            <td>
                                @if($pegawai->seksi)
                                    <span class="fw-semibold">{{ $pegawai->seksi->nama_kepala_seksi }}</span>
                                    <small class="text-muted d-block font-mono">{{ $pegawai->seksi->nip_kepala_seksi ?? '-' }}</small>
                                @else
                                    <span class="text-muted fst-italic">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Sisa Cuti Tambahan</th>
                            <td><span class="fw-bold" style="color:#0ca678;">{{ $sisaCutiTambahan }} hari</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-500 ps-0">Sisa Cuti Tahunan</th>
                            <td><span class="fw-bold" style="color:#3b5bdb;">{{ $sisaCutiTahunan }} hari</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- RINGKASAN CUTI --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body p-0">
                <div class="card-header-clean pt-4 pb-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-plus fs-5 text-primary"></i>
                        <span class="card-title-sm">Cuti Tambahan</span>
                    </div>
                    <a href="{{ route('pegawai.cuti-tambahan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px;">
                        <span class="text-muted">Kuota Awal</span>
                        <span class="fw-semibold">{{ $kuotaCutiTambahan }} hari</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px;">
                        <span class="text-muted">Sudah Diambil</span>
                        <span class="fw-semibold text-danger">{{ $terpakaicutiTambahan }} hari</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" style="font-size:13.5px;">
                        <span class="text-muted">Sisa</span>
                        <span class="fw-bold" style="color:#0ca678;">{{ $sisaCutiTambahan }} hari</span>
                    </div>
                    <div class="progress" style="height:6px; border-radius:10px;">
                        <div class="progress-bar" style="width:{{ $kuotaCutiTambahan > 0 ? ($terpakaicutiTambahan / $kuotaCutiTambahan * 100) : 0 }}%; background:#0ca678; border-radius:10px;"></div>
                    </div>
                    <small class="text-muted d-block mt-1">{{ $kuotaCutiTambahan > 0 ? round($terpakaicutiTambahan / $kuotaCutiTambahan * 100) : 0 }}% terpakai</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body p-0">
                <div class="card-header-clean pt-4 pb-3 px-4 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-calendar2-check fs-5 text-primary"></i>
                        <span class="card-title-sm">Cuti Tahunan</span>
                    </div>
                    <a href="{{ route('pegawai.cuti-tahunan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="p-4">
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px;">
                        <span class="text-muted">Kuota Awal</span>
                        <span class="fw-semibold">{{ $kuotaCutiTahunan }} hari</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px;">
                        <span class="text-muted">Sudah Diambil</span>
                        <span class="fw-semibold text-danger">{{ $terpakaiCutiTahunan }} hari</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" style="font-size:13.5px;">
                        <span class="text-muted">Sisa</span>
                        <span class="fw-bold" style="color:#3b5bdb;">{{ $sisaCutiTahunan }} hari</span>
                    </div>
                    <div class="progress" style="height:6px; border-radius:10px;">
                        <div class="progress-bar" style="width:{{ $kuotaCutiTahunan > 0 ? ($terpakaiCutiTahunan / $kuotaCutiTahunan * 100) : 0 }}%; background:#3b5bdb; border-radius:10px;"></div>
                    </div>
                    <small class="text-muted d-block mt-1">{{ $kuotaCutiTahunan > 0 ? round($terpakaiCutiTahunan / $kuotaCutiTahunan * 100) : 0 }}% terpakai</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- INFORMASI PENTING --}}
<div class="card">
    <div class="card-body p-0">
        <div class="card-header-clean pt-4 pb-3 px-4 border-bottom">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-info-circle fs-5 text-primary"></i>
                <span class="card-title-sm">Informasi Penting</span>
            </div>
        </div>
        <div class="p-4">
            <ul class="mb-0" style="font-size: 13.5px; line-height: 2;">
                <li>Sisa cuti tahunan dan tambahan akan otomatis berkurang setelah permohonan disetujui oleh kepegawaian</li>
                <li>Permohonan yang sudah disetujui hanya dapat diubah atau dihapus oleh kepegawaian</li>
                <li>Cuti tahunan dan cuti tambahan diajukan secara bersamaan</li>
                <li>Aplikasi ini hanya untuk pendamping aplikasi satu kemenkeu terkait cuti tahunan, apabila terdapat perbedaan informasi, silahkan hubungi kepegawaian</li>
            </ul>
        </div>
    </div>
</div>

@endsection