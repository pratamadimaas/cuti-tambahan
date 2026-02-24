@extends('layouts.app')

@section('title', 'Generate Surat')
@section('page-title', 'Generate Surat')

@section('content')
<div class="page-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4><i class="bi bi-file-earmark-word me-2"></i>Generate Surat</h4>
            <p class="mb-0">Generate dokumen untuk permohonan cuti tambahan</p>
        </div>
        <a href="{{ route('pegawai.cuti-tambahan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- Detail Permohonan --}}
<div class="card mb-3">
    <div class="card-header-clean mb-3">
        <span class="card-title-sm"><i class="bi bi-info-circle me-1 text-primary"></i> Detail Permohonan Cuti</span>
    </div>
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th width="180" class="text-muted" style="font-size: 13px;">Nomor Permohonan</th>
                        <td style="font-size: 13px;">: <strong>{{ $cutiTambahan->nomor_nota_dinas }}</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Tanggal Permohonan</th>
                        <td style="font-size: 13px;">: {{ $cutiTambahan->tanggal_nota_dinas->format('d-m-Y') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Alasan Cuti</th>
                        <td style="font-size: 13px;">: {{ $cutiTambahan->alasan_cuti }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Status</th>
                        <td style="font-size: 13px;">: 
                            <span class="badge 
                                @if($cutiTambahan->status == 'disetujui') bg-success
                                @elseif($cutiTambahan->status == 'ditolak') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($cutiTambahan->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless table-sm">
                    @if($cutiTambahan->cuti_tambahan_jumlah > 0)
                    <tr>
                        <th width="120" class="text-muted" style="font-size: 13px;">Cuti Tambahan</th>
                        <td style="font-size: 13px;">: <strong class="text-success">{{ $cutiTambahan->cuti_tambahan_jumlah }} hari</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Periode</th>
                        <td style="font-size: 13px;">: {{ $cutiTambahan->periode_cuti_tambahan }}</td>
                    </tr>
                    @endif
                    
                    @if($cutiTambahan->cuti_tahunan_jumlah > 0)
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Cuti Tahunan</th>
                        <td style="font-size: 13px;">: <strong class="text-warning">{{ $cutiTambahan->cuti_tahunan_jumlah }} hari</strong></td>
                    </tr>
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Periode</th>
                        <td style="font-size: 13px;">: {{ $cutiTambahan->periode_cuti_tahunan }}</td>
                    </tr>
                    @endif
                    
                    @if($cutiTambahan->cuti_tambahan_jumlah > 0 && $cutiTambahan->cuti_tahunan_jumlah > 0)
                    <tr>
                        <th class="text-muted" style="font-size: 13px;">Total Hari</th>
                        <td style="font-size: 13px;">: <strong class="text-primary">{{ $cutiTambahan->cuti_tambahan_jumlah + $cutiTambahan->cuti_tahunan_jumlah }} hari</strong></td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Info Kuota Cuti --}}
<div class="row g-3 mb-3">
    {{-- Info Cuti Tambahan --}}
    @if($cutiTambahan->cuti_tambahan_jumlah > 0)
    <div class="col-md-6">
        <div class="card h-100" style="border-left: 4px solid #0ca678;">
            <div class="card-body d-flex gap-3" style="padding: 16px 20px;">
                <div style="color: #0ca678; font-size: 22px; flex-shrink: 0; padding-top: 2px;">
                    <i class="bi bi-calendar-plus-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div style="font-size: 13.5px; font-weight: 700; color: #065f46; margin-bottom: 8px;">
                        Informasi Cuti Tambahan Tahun {{ $infoCutiTambahan['tahun'] }}
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Kuota Awal</div>
                            <div style="font-size: 18px; font-weight: 700; color: #0ca678;">
                                {{ $infoCutiTambahan['kuota_awal'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Terpakai</div>
                            <div style="font-size: 18px; font-weight: 700; color: #f59f00;">
                                {{ $infoCutiTambahan['terpakai'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Sisa</div>
                            <div style="font-size: 18px; font-weight: 700; color: {{ $infoCutiTambahan['sisa'] > 5 ? '#0ca678' : '#f03e3e' }};">
                                {{ $infoCutiTambahan['sisa'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2" style="border-top: 1px solid #e5e7eb; font-size: 11.5px; color: #6b7a99;">
                        <i class="bi bi-info-circle me-1"></i> Cuti tambahan adalah hak cuti khusus di luar cuti tahunan
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Info Cuti Tahunan --}}
    @if($cutiTambahan->cuti_tahunan_jumlah > 0)
    <div class="col-md-6">
        <div class="card h-100" style="border-left: 4px solid #3b5bdb;">
            <div class="card-body d-flex gap-3" style="padding: 16px 20px;">
                <div style="color: #3b5bdb; font-size: 22px; flex-shrink: 0; padding-top: 2px;">
                    <i class="bi bi-calendar-event-fill"></i>
                </div>
                <div class="flex-grow-1">
                    <div style="font-size: 13.5px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
                        Informasi Cuti Tahunan Tahun {{ $infoCutiTahunan['tahun'] }}
                    </div>
                    <div class="row g-3">
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Kuota Awal</div>
                            <div style="font-size: 18px; font-weight: 700; color: #3b5bdb;">
                                {{ $infoCutiTahunan['kuota_awal'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Terpakai</div>
                            <div style="font-size: 18px; font-weight: 700; color: #f59f00;">
                                {{ $infoCutiTahunan['terpakai'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                        <div class="col-4">
                            <div style="font-size: 12px; color: #6b7a99; margin-bottom: 4px;">Sisa</div>
                            <div style="font-size: 18px; font-weight: 700; color: {{ $infoCutiTahunan['sisa'] > 5 ? '#0ca678' : '#f03e3e' }};">
                                {{ $infoCutiTahunan['sisa'] }} <span style="font-size: 12px; font-weight: 500;">hari</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 pt-2" style="border-top: 1px solid #e5e7eb; font-size: 11.5px; color: #6b7a99;">
                        <i class="bi bi-info-circle me-1"></i> Cuti tahunan adalah hak cuti reguler 12 hari/tahun
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Generate Surat --}}
<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-file-earmark-word me-1 text-primary"></i> Nota Dinas Permohonan</span>
            </div>
            <div class="card-body pt-0">
                <p class="text-muted mb-3" style="font-size: 13px;">
                    Dokumen permohonan yang ditujukan kepada atasan untuk mengajukan cuti
                </p>
                
                <form action="{{ route('pegawai.surat.nota-permohonan', $cutiTambahan) }}" method="POST">
                    @csrf
                    
                    <div class="alert alert-modern" style="background: #eff6ff; border-left: 3px solid #3b5bdb; padding: 12px 16px; margin-bottom: 16px;">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill" style="color: #3b5bdb; font-size: 16px; flex-shrink: 0;"></i>
                            <div style="font-size: 12.5px; color: #1e40af;">
                                <strong>Catatan Penting:</strong><br>
                                Data cuti yang dimasukkan dalam surat akan otomatis diambil dari permohonan yang sudah tersimpan:
                                <ul class="mb-0 mt-2 ps-3">
                                    @if($cutiTambahan->cuti_tambahan_jumlah > 0)
                                    <li><strong>Cuti Tambahan:</strong> {{ $cutiTambahan->cuti_tambahan_jumlah }} hari ({{ $cutiTambahan->periode_cuti_tambahan }})</li>
                                    @endif
                                    @if($cutiTambahan->cuti_tahunan_jumlah > 0)
                                    <li><strong>Cuti Tahunan:</strong> {{ $cutiTambahan->cuti_tahunan_jumlah }} hari ({{ $cutiTambahan->periode_cuti_tahunan }})</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>


                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download"></i> Download Nota Dinas Permohonan
                        </button>
                        <small class="text-center text-muted" style="font-size: 11px;">
                            Format: Microsoft Word (.docx)
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-file-earmark-word me-1 text-success"></i> Surat Pemberian Cuti</span>
            </div>
            <div class="card-body pt-0">
                <p class="text-muted mb-3" style="font-size: 13px;">
                    Dokumen resmi yang menyatakan cuti telah disetujui dan dapat dilaksanakan
                </p>
                
                @if($cutiTambahan->status === 'disetujui')
                    <form action="{{ route('pegawai.surat.pemberian', $cutiTambahan) }}" method="POST">
                        @csrf
                        
                        <div class="alert alert-modern" style="background: #f0fdf4; border-left: 3px solid #0ca678; padding: 12px 16px; margin-bottom: 16px;">
                            <div class="d-flex gap-2">
                                <i class="bi bi-check-circle-fill" style="color: #0ca678; font-size: 16px; flex-shrink: 0;"></i>
                                <div style="font-size: 12.5px; color: #065f46;">
                                    <strong>Permohonan Disetujui!</strong><br>
                                    Surat ini berisi persetujuan resmi dari atasan untuk melaksanakan cuti dengan rincian:
                                    <ul class="mb-0 mt-2 ps-3">
                                        @if($cutiTambahan->cuti_tambahan_jumlah > 0)
                                        <li><strong>Cuti Tambahan:</strong> {{ $cutiTambahan->cuti_tambahan_jumlah }} hari</li>
                                        @endif
                                        @if($cutiTambahan->cuti_tahunan_jumlah > 0)
                                        <li><strong>Cuti Tahunan:</strong> {{ $cutiTambahan->cuti_tahunan_jumlah }} hari</li>
                                        @endif
                                        <li><strong>Total:</strong> {{ $cutiTambahan->cuti_tambahan_jumlah + $cutiTambahan->cuti_tahunan_jumlah }} hari</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-download"></i> Download Surat Pemberian
                            </button>
                            <small class="text-center text-muted" style="font-size: 11px;">
                                Format: Microsoft Word (.docx)
                            </small>
                        </div>
                    </form>
                @else
                    <div class="alert alert-modern" style="background: #fef2f2; border-left: 3px solid #f03e3e; padding: 16px;">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="bi bi-x-circle-fill" style="font-size: 24px; color: #f03e3e; flex-shrink: 0;"></i>
                            <div>
                                <div style="font-size: 13.5px; font-weight: 700; color: #991b1b; margin-bottom: 6px;">
                                    Belum Dapat Di-download
                                </div>
                                <div style="font-size: 13px; color: #7f1d1d; line-height: 1.6;">
                                    Surat pemberian cuti hanya dapat di-generate setelah permohonan <strong>disetujui</strong> oleh atasan.
                                </div>
                                <div class="mt-3 p-2" style="background: rgba(255,255,255,0.5); border-radius: 6px; font-size: 12px; color: #7f1d1d;">
                                    <strong>Status saat ini:</strong> 
                                    <span class="badge 
                                        @if($cutiTambahan->status == 'ditolak') bg-danger
                                        @else bg-warning text-dark
                                        @endif ms-1">
                                        {{ ucfirst($cutiTambahan->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Informasi Tambahan --}}
<div class="card" style="border-left: 4px solid #f59f00;">
    <div class="card-body d-flex gap-3" style="padding: 18px 22px;">
        <div style="color: #f59f00; font-size: 22px; flex-shrink: 0; padding-top: 2px;">
            <i class="bi bi-book-fill"></i>
        </div>
        <div>
            <div style="font-size: 13.5px; font-weight: 700; color: #92400e; margin-bottom: 8px;">Panduan Penggunaan</div>
            <ul class="mb-0" style="font-size: 13px; color: #78350f; padding-left: 18px; line-height: 1.8;">
                <li><strong>Nota Dinas Permohonan:</strong> Dokumen untuk mengajukan permohonan cuti kepada atasan (dapat di-download kapan saja)</li>
                <li><strong>Surat Pemberian Cuti:</strong> Dokumen resmi persetujuan cuti dari atasan (hanya tersedia setelah disetujui)</li>
                <li><strong>Data Otomatis:</strong> Semua informasi cuti (tambahan & tahunan) yang tersimpan akan otomatis dimasukkan ke dalam surat</li>
                <li><strong>Keterangan Tambahan:</strong> Field opsional untuk menambahkan catatan khusus yang hanya muncul di surat</li>
                <li><strong>Format File:</strong> Semua dokumen dihasilkan dalam format Microsoft Word (.docx) yang dapat diedit</li>
                <li><strong>Informasi Sisa Cuti:</strong> Surat akan otomatis mencantumkan sisa kuota cuti tambahan dan tahunan Anda</li>
            </ul>
        </div>
    </div>
</div>
@endsection