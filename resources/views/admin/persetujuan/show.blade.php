@extends('layouts.app')

@section('title', 'Detail Permohonan Cuti')
@section('page-title', 'Detail Permohonan')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4><i class="bi bi-file-text me-2"></i>Detail Permohonan Cuti</h4>
            <p class="mb-0">Informasi lengkap permohonan cuti pegawai</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.surat.nota-persetujuan', $cutiTambahan) }}" class="btn btn-primary" style="border-radius: 9px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-file-earmark-word"></i> Cetak Nota Dinas Persetujuan
            </a>
            <a href="{{ route('admin.persetujuan.index') }}" class="btn btn-secondary" style="border-radius: 9px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Data Pegawai -->
        <div class="card mb-3">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-person-fill me-2 text-primary"></i>Data Pegawai</span>
            </div>
            <div class="card-body pt-0">
                <table class="table table-borderless table-sm" style="font-size: 13px;">
                    <tr>
                        <th width="35%" class="text-muted" style="font-weight: 600;">Nama</th>
                        <td>: <strong>{{ $cutiTambahan->pegawai->nama }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">NIP</th>
                        <td>: <span class="font-mono">{{ $cutiTambahan->pegawai->nip }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Pangkat/Golongan</th>
                        <td>: {{ $cutiTambahan->pegawai->pangkat_gol }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Jabatan</th>
                        <td>: {{ $cutiTambahan->pegawai->jabatan }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Unit Kerja</th>
                        <td>: {{ $cutiTambahan->pegawai->unit_kerja }}</td>
                    </tr>
                    @if($cutiTambahan->pegawai->seksi)
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Seksi</th>
                        <td>: {{ $cutiTambahan->pegawai->seksi->nama_seksi }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Detail Permohonan -->
        <div class="card mb-3">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-calendar-check me-2" style="color: #3b5bdb;"></i>Detail Permohonan</span>
            </div>
            <div class="card-body pt-0">
                <table class="table table-borderless table-sm" style="font-size: 13px;">
                    <tr>
                        <th width="35%" class="text-muted" style="font-weight: 600;">Nomor Permohonan</th>
                        <td>: <span class="badge bg-secondary font-mono" style="font-size: 11px;">{{ $cutiTambahan->nomor_nota_dinas }}</span></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Tanggal Permohonan</th>
                        <td>: {{ \Carbon\Carbon::parse($cutiTambahan->tanggal_nota_dinas)->translatedFormat('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Alasan Cuti</th>
                        <td>: {{ $cutiTambahan->alasan_cuti }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Alamat Selama Cuti</th>
                        <td>: {{ $cutiTambahan->alamat_cuti }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-weight: 600;">Diajukan Pada</th>
                        <td>: {{ $cutiTambahan->created_at->translatedFormat('d F Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Detail Cuti Tambahan -->
        @if($cutiTambahan->cuti_tambahan_jumlah > 0)
        <div class="card mb-3" style="border-left: 4px solid var(--success);">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-calendar-plus-fill me-2" style="color: var(--success);"></i>Cuti Tambahan</span>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size: 12px; margin-bottom: 6px;">Jumlah Hari</div>
                        <h3 class="mb-0"><span class="badge bg-success" style="font-size: 16px;">{{ $cutiTambahan->cuti_tambahan_jumlah }} hari</span></h3>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size: 12px; margin-bottom: 6px;">Periode</div>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text);">
                            @if($cutiTambahan->tanggal_mulai_tambahan && $cutiTambahan->tanggal_selesai_tambahan)
                                {{ $cutiTambahan->tanggal_mulai_tambahan }}
                                @if($cutiTambahan->tanggal_mulai_tambahan != $cutiTambahan->tanggal_selesai_tambahan)
                                    <br>s/d {{ $cutiTambahan->tanggal_selesai_tambahan }}
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($cutiTambahan->tanggal_cuti_tambahan))
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--border);">
                    <div class="text-muted mb-2" style="font-size: 12px; font-weight: 600;">Tanggal-tanggal Cuti Tambahan:</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($cutiTambahan->tanggal_cuti_tambahan as $item)
                            @php
                                $tgl  = is_array($item) ? $item['tanggal'] : $item;
                                $sesi = is_array($item) ? ($item['sesi'] ?? 'penuh') : 'penuh';
                                $sesiLabel = match($sesi) {
                                    'pagi'  => ' (Pagi)',
                                    'siang' => ' (Siang)',
                                    default => '',
                                };
                            @endphp
                            <span class="badge bg-success" style="font-size: 11.5px; padding: 6px 12px; border-radius: 6px; font-weight: 500;">
                                <i class="bi bi-calendar-day me-1"></i>
                                {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') }}
                                ({{ \Carbon\Carbon::parse($tgl)->translatedFormat('l') }}){{ $sesiLabel }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Detail Cuti Tahunan -->
        @if($cutiTambahan->cuti_tahunan_jumlah > 0)
        <div class="card mb-3" style="border-left: 4px solid var(--warning);">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-calendar-event-fill me-2" style="color: var(--warning);"></i>Cuti Tahunan</span>
            </div>
            <div class="card-body pt-0">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size: 12px; margin-bottom: 6px;">Jumlah Hari</div>
                        <h3 class="mb-0"><span class="badge bg-warning text-dark" style="font-size: 16px;">{{ $cutiTambahan->cuti_tahunan_jumlah }} hari</span></h3>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted" style="font-size: 12px; margin-bottom: 6px;">Periode</div>
                        <div style="font-size: 13px; font-weight: 600; color: var(--text);">
                            @if($cutiTambahan->tanggal_mulai_tahunan && $cutiTambahan->tanggal_selesai_tahunan)
                                {{ $cutiTambahan->tanggal_mulai_tahunan }}
                                @if($cutiTambahan->tanggal_mulai_tahunan != $cutiTambahan->tanggal_selesai_tahunan)
                                    <br>s/d {{ $cutiTambahan->tanggal_selesai_tahunan }}
                                @endif
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>

                @if(!empty($cutiTambahan->tanggal_cuti_tahunan))
                <div class="mt-3 pt-3" style="border-top: 1px solid var(--border);">
                    <div class="text-muted mb-2" style="font-size: 12px; font-weight: 600;">Tanggal-tanggal Cuti Tahunan:</div>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($cutiTambahan->tanggal_cuti_tahunan as $item)
                            @php
                                $tgl  = is_array($item) ? $item['tanggal'] : $item;
                                $sesi = is_array($item) ? ($item['sesi'] ?? 'penuh') : 'penuh';
                                $sesiLabel = match($sesi) {
                                    'pagi'  => ' (Pagi)',
                                    'siang' => ' (Siang)',
                                    default => '',
                                };
                            @endphp
                            <span class="badge bg-warning text-dark" style="font-size: 11.5px; padding: 6px 12px; border-radius: 6px; font-weight: 500;">
                                <i class="bi bi-calendar-day me-1"></i>
                                {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') }}
                                ({{ \Carbon\Carbon::parse($tgl)->translatedFormat('l') }}){{ $sesiLabel }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Catatan Admin (jika ditolak) -->
        @if($cutiTambahan->status === 'ditolak' && $cutiTambahan->catatan_admin)
        <div class="card mb-3" style="border-left: 4px solid var(--danger);">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-exclamation-triangle-fill me-2 text-danger"></i>Alasan Penolakan</span>
            </div>
            <div class="card-body pt-0">
                <div class="alert alert-modern alert-danger mb-0">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    {{ $cutiTambahan->catatan_admin }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Status -->
        <div class="card mb-3">
            <div class="card-body text-center" style="padding: 24px;">
                @if($cutiTambahan->status === 'menunggu')
                    <div class="stat-icon amber mx-auto" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h4 class="mt-3 mb-1" style="font-weight: 700;">Menunggu Persetujuan</h4>
                    <p class="text-muted mb-0" style="font-size: 12px;">Status akan diperbarui setelah direview</p>

                    <div class="d-grid gap-2 mt-4">
                        <form action="{{ route('admin.persetujuan.approve', $cutiTambahan->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menyetujui permohonan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" style="font-weight: 600; border-radius: 9px;">
                                <i class="bi bi-check-lg"></i> Setujui Permohonan
                            </button>
                        </form>

                        <button type="button"
                                class="btn btn-danger w-100"
                                data-bs-toggle="modal"
                                data-bs-target="#rejectModal"
                                style="font-weight: 600; border-radius: 9px;">
                            <i class="bi bi-x-lg"></i> Tolak Permohonan
                        </button>
                    </div>

                @elseif($cutiTambahan->status === 'disetujui')
                    <div class="stat-icon green mx-auto" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h4 class="mt-3 mb-1 text-success" style="font-weight: 700;">Disetujui</h4>
                    <p class="text-muted mb-0" style="font-size: 12px;">{{ $cutiTambahan->updated_at->translatedFormat('d F Y, H:i') }}</p>

                @else
                    <div class="stat-icon red mx-auto" style="width: 64px; height: 64px; font-size: 28px;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <h4 class="mt-3 mb-1 text-danger" style="font-weight: 700;">Ditolak</h4>
                    <p class="text-muted mb-0" style="font-size: 12px;">{{ $cutiTambahan->updated_at->translatedFormat('d F Y, H:i') }}</p>
                @endif
            </div>
        </div>

        <!-- Info Sisa Cuti -->
        <div class="card mb-3">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-pie-chart-fill me-2" style="color: var(--primary);"></i>Sisa Kuota Cuti</span>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex align-items-center justify-content-between p-3 mb-2" style="background: #eff6ff; border-radius: 10px;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">Cuti Tahunan</div>
                        <div style="font-size: 20px; font-weight: 800; color: var(--primary);">{{ $cutiTambahan->pegawai->getInfoCutiTahunan()['sisa'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon blue" style="width: 40px; height: 40px; font-size: 18px;">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between p-3" style="background: #ecfdf5; border-radius: 10px;">
                    <div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 2px;">Cuti Tambahan</div>
                        <div style="font-size: 20px; font-weight: 800; color: var(--success);">{{ $cutiTambahan->pegawai->getInfoCutiTambahan()['sisa'] ?? 0 }}</div>
                    </div>
                    <div class="stat-icon green" style="width: 40px; height: 40px; font-size: 18px;">
                        <i class="bi bi-calendar-plus"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informasi Pengurangan Kuota -->
        @if($cutiTambahan->status === 'disetujui')
        <div class="card" style="border-left: 4px solid #3b5bdb;">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-calculator-fill me-2" style="color: #3b5bdb;"></i>Perhitungan Kuota</span>
            </div>
            <div class="card-body pt-0">
                @if($cutiTambahan->cuti_tambahan_jumlah > 0)
                <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-calendar-plus text-success"></i>
                        <strong style="font-size: 13px;">Cuti Tambahan</strong>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); line-height: 1.8;">
                        Sebelum: <strong>{{ $cutiTambahan->pegawai->getInfoCutiTambahan()['sisa'] + $cutiTambahan->cuti_tambahan_jumlah }} hari</strong><br>
                        Digunakan: <span class="text-danger"><strong>-{{ $cutiTambahan->cuti_tambahan_jumlah }} hari</strong></span><br>
                        Sisa: <strong class="text-success">{{ $cutiTambahan->pegawai->getInfoCutiTambahan()['sisa'] }} hari</strong>
                    </div>
                </div>
                @endif

                @if($cutiTambahan->cuti_tahunan_jumlah > 0)
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-calendar-event text-warning"></i>
                        <strong style="font-size: 13px;">Cuti Tahunan</strong>
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted); line-height: 1.8;">
                        Sebelum: <strong>{{ $cutiTambahan->pegawai->getInfoCutiTahunan()['sisa'] + $cutiTambahan->cuti_tahunan_jumlah }} hari</strong><br>
                        Digunakan: <span class="text-danger"><strong>-{{ $cutiTambahan->cuti_tahunan_jumlah }} hari</strong></span><br>
                        Sisa: <strong class="text-primary">{{ $cutiTambahan->pegawai->getInfoCutiTahunan()['sisa'] }} hari</strong>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Tolak -->
@if($cutiTambahan->status === 'menunggu')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: var(--radius);">
            <form action="{{ route('admin.persetujuan.reject', $cutiTambahan->id) }}" method="POST">
                @csrf
                <div class="modal-header" style="background: var(--accent-soft); border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" style="font-weight: 700; color: var(--danger);">
                        <i class="bi bi-x-circle-fill me-2"></i>Tolak Permohonan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 24px;">
                    <div class="alert alert-modern" style="background: #fffbeb; border-left: 3px solid var(--warning);">
                        <i class="bi bi-exclamation-triangle-fill" style="color: var(--warning); font-size: 16px;"></i>
                        <div style="font-size: 13px;">
                            Anda akan menolak permohonan cuti dari <strong>{{ $cutiTambahan->pegawai->nama }}</strong>
                        </div>
                    </div>

                    <div class="mb-3 p-3" style="background: var(--bg); border-radius: var(--radius-sm);">
                        <strong style="font-size: 13px; color: var(--text);">Detail Permohonan:</strong>
                        <ul class="list-unstyled mt-2 mb-0" style="font-size: 12.5px;">
                            @if($cutiTambahan->cuti_tambahan_jumlah > 0)
                            <li class="mb-1"><i class="bi bi-calendar-plus text-success me-1"></i> Cuti Tambahan: <strong>{{ $cutiTambahan->cuti_tambahan_jumlah }} hari</strong></li>
                            @endif
                            @if($cutiTambahan->cuti_tahunan_jumlah > 0)
                            <li><i class="bi bi-calendar-event text-warning me-1"></i> Cuti Tahunan: <strong>{{ $cutiTambahan->cuti_tahunan_jumlah }} hari</strong></li>
                            @endif
                        </ul>
                    </div>

                    <div class="mb-0">
                        <label class="form-label" style="font-size: 13px; font-weight: 600;">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control"
                                  name="catatan_admin"
                                  rows="4"
                                  required
                                  style="font-size: 13px; border-radius: var(--radius-sm);"
                                  placeholder="Masukkan alasan penolakan yang jelas dan detail..."></textarea>
                        <small class="text-muted" style="font-size: 11.5px;">
                            <i class="bi bi-info-circle"></i> Alasan ini akan dilihat oleh pegawai
                        </small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border); padding: 16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 9px; font-size: 13px; font-weight: 600;">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger" style="border-radius: 9px; font-size: 13px; font-weight: 600;">
                        <i class="bi bi-check"></i> Tolak Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection