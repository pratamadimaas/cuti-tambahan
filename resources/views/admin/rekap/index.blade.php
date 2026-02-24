@extends('layouts.app')

@section('title', 'Rekap Cuti Tambahan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Rekap Cuti Tambahan</h4>

            <form action="{{ route('admin.rekap.export') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- Semua Status --</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="pending">Pending</option>
                </select>
                <select name="tahun" class="form-select form-select-sm">
                    @for ($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" class="btn btn-success btn-sm">Export Excel</button>
            </form>
        </div>
    </div>

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
                        <th>Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cutiTambahan as $index => $cuti)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $cuti->pegawai->nama ?? '-' }}</td>
                        <td>{{ $cuti->jenis_cuti ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_mulai)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_selesai)->format('d/m/Y') }}</td>
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
                        <td colspan="7" class="text-center text-muted">Tidak ada data.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection