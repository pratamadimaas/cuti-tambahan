@extends('layouts.app')

@section('title', 'Detail Permohonan Cuti Tambahan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-text"></i> Detail Permohonan Cuti Tambahan</h2>
        <a href="{{ route('admin.persetujuan.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Data Pegawai</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nama</th>
                            <td>{{ $cutiTambahan->pegawai->nama }}</td>
                        </tr>
                        <tr>
                            <th>NIP</th>
                            <td>{{ $cutiTambahan->pegawai->nip }}</td>
                        </tr>
                        <tr>
                            <th>Pangkat/Golongan</th>
                            <td>{{ $cutiTambahan->pegawai->pangkat_gol }}</td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>{{ $cutiTambahan->pegawai->jabatan }}</td>
                        </tr>
                        <tr>
                            <th>Unit Kerja</th>
                            <td>{{ $cutiTambahan->pegawai->unit_kerja }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Detail Permohonan</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Tanggal Nota Dinas</th>
                            <td>{{ \Carbon\Carbon::parse($cutiTambahan->tanggal_nota_dinas)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah Hari Cuti Tambahan</th>
                            <td><span class="badge bg-info">{{ $cutiTambahan->cuti_tambahan_jumlah }} hari</span></td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td>{{ \Carbon\Carbon::parse($cutiTambahan->tanggal_mulai)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td>{{ \Carbon\Carbon::parse($cutiTambahan->tanggal_selesai)->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Alamat Selama Cuti</th>
                            <td>{{ $cutiTambahan->alamat_cuti }}</td>
                        </tr>
                        <tr>
                            <th>Diajukan Pada</th>
                            <td>{{ $cutiTambahan->created_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header 
                    @if($cutiTambahan->status === 'menunggu') bg-warning 
                    @elseif($cutiTambahan->status === 'disetujui') bg-success 
                    @else bg-danger 
                    @endif text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Status</h5>
                </div>
                <div class="card-body text-center">
                    @if($cutiTambahan->status === 'menunggu')
                        <i class="bi bi-clock-history" style="font-size: 4rem; color: #ffc107;"></i>
                        <h4 class="mt-3">Menunggu Persetujuan</h4>
                        
                        <div class="d-grid gap-2 mt-4">
                            <form action="{{ route('admin.persetujuan.approve', $cutiTambahan->id) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Yakin ingin menyetujui permohonan ini?')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-lg"></i> Setujui
                                </button>
                            </form>
                            
                            <button type="button" 
                                    class="btn btn-danger w-100"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal">
                                <i class="bi bi-x-lg"></i> Tolak
                            </button>
                        </div>

                    @elseif($cutiTambahan->status === 'disetujui')
                        <i class="bi bi-check-circle" style="font-size: 4rem; color: #198754;"></i>
                        <h4 class="mt-3 text-success">Disetujui</h4>
                        <p class="text-muted">{{ $cutiTambahan->updated_at->format('d F Y H:i') }}</p>

                    @else
                        <i class="bi bi-x-circle" style="font-size: 4rem; color: #dc3545;"></i>
                        <h4 class="mt-3 text-danger">Ditolak</h4>
                        <p class="text-muted">{{ $cutiTambahan->updated_at->format('d F Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Info Cuti</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th>Sisa Cuti Tahunan:</th>
                            <td class="text-end">
                                <span class="badge bg-info">{{ $cutiTambahan->pegawai->sisa_cuti_tahunan }} hari</span>
                            </td>
                        </tr>
                        <tr>
                            <th>Sisa Cuti Tambahan:</th>
                            <td class="text-end">
                                <span class="badge bg-success">{{ $cutiTambahan->pegawai->sisa_cuti_tambahan }} hari</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tolak -->
@if($cutiTambahan->status === 'menunggu')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.persetujuan.reject', $cutiTambahan->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Permohonan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i>
                        Anda akan menolak permohonan cuti tambahan dari <strong>{{ $cutiTambahan->pegawai->nama }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  name="alasan_penolakan" 
                                  rows="4" 
                                  required
                                  placeholder="Masukkan alasan penolakan"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Permohonan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection