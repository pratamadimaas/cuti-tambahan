@extends('layouts.app')

@section('title', 'Rekap Permohonan Cuti')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Rekap Permohonan Cuti</h4>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('admin.rekap.permohonan-cuti') }}" method="GET" class="row g-2">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @for ($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">Semua</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $idx => $bulan)
                            <option value="{{ $idx + 1 }}" {{ request('bulan') == ($idx + 1) ? 'selected' : '' }}>{{ $bulan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.rekap.permohonan-cuti') }}" class="btn btn-secondary btn-sm">Reset</a>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" formaction="{{ route('admin.rekap.export-permohonan-cuti') }}" class="btn btn-success btn-sm w-100">
                        <i class="bi bi-file-excel"></i> Export Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Pegawai</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permohonan as $index => $cuti)
                    <tr>
                        <td>{{ $permohonan->firstItem() + $index }}</td>
                        <td>{{ $cuti->pegawai->nama ?? '-' }}</td>
                        <td>{{ $cuti->jenis_cuti ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($cuti->tanggal_selesai)) + 1 }} hari
                        </td>
                        <td>
                            <span class="badge 
                                @if($cuti->status == 'disetujui') bg-success
                                @elseif($cuti->status == 'ditolak') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                {{ ucfirst($cuti->status) }}
                            </span>
                        </td>
                        <td>{{ $cuti->keterangan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $permohonan->links() }}
            </div>
        </div>
    </div>
</div>
@endsection