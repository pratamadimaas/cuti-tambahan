@extends('layouts.app')
@section('title', 'Data Seksi')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-diagram-3"></i> Data Seksi</h2>
        <a href="{{ route('admin.seksi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Seksi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Seksi</th>
                        <th>Kepala Seksi</th>
                        <th>NIP Kepala Seksi</th>
                        <th class="text-center">Jumlah Pegawai pada Seksi tsb</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seksi as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->nama_seksi }}</td>
                            <td>{{ $s->nama_kepala_seksi }}</td>
                            <td>{{ $s->nip_kepala_seksi ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $s->pegawai_count }} orang</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.seksi.edit', $s->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.seksi.destroy', $s->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus seksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">Belum ada data seksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection