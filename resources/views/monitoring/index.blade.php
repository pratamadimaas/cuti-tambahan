@extends('layouts.app')

@section('title', 'Monitoring Cuti Tambahan')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col">
            <h4 class="fw-bold">Monitoring Cuti Tambahan</h4>
            <p class="text-muted mb-0">Daftar seluruh permohonan cuti tambahan</p>
        </div>
        @if(!auth()->user()->isAdmin())
        <div class="col-auto">
            <a href="{{ route('pegawai.cuti-tambahan.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Ajukan Cuti Tambahan
            </a>
        </div>
        @endif
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards (Admin Only) --}}
    @if(auth()->user()->isAdmin())
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="bi bi-hourglass-split fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $cutiTambahan->where('status', 'menunggu')->count() }}</div>
                        <div class="text-muted small">Menunggu</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="bi bi-check-circle fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $cutiTambahan->where('status', 'disetujui')->count() }}</div>
                        <div class="text-muted small">Disetujui</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="bi bi-x-circle fs-4 text-danger"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $cutiTambahan->where('status', 'ditolak')->count() }}</div>
                        <div class="text-muted small">Ditolak</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold">{{ $cutiTambahan->count() }}</div>
                        <div class="text-muted small">Total Permohonan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">#</th>
                            @if(auth()->user()->isAdmin())
                            <th>Pegawai</th>
                            @endif
                            <th>Nomor Permohonan</th>
                            <th>Tgl. Permohonan</th>
                            <th>Periode Cuti</th>
                            <th class="text-center">Jumlah Hari</th>
                            <th>Alasan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cutiTambahan as $index => $item)
                        <tr>
                            <td class="ps-3 text-muted">{{ $index + 1 }}</td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                <div class="fw-semibold">{{ $item->pegawai->nama ?? '-' }}</div>
                                <div class="text-muted small">{{ $item->pegawai->nip ?? '-' }}</div>
                            </td>
                            @endif
                            <td>
                                <span class="font-monospace small">{{ $item->nomor_nota_dinas }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_nota_dinas)->format('d-m-Y') }}</td>
                            <td>{{ $item->periode_cuti }}</td>
                            <td class="text-center">
                                <span class="badge bg-info text-dark">{{ $item->cuti_tambahan_jumlah }} hari</span>
                            </td>
                            <td>
                                <span title="{{ $item->alasan_cuti }}">
                                    {{ \Illuminate\Support\Str::limit($item->alasan_cuti, 40) }}
                                </span>
                            </td>
                            <td class="text-center">
                                {!! $item->status_badge !!}
                            </td>
                            <td class="text-center pe-3">
                                <div class="d-flex justify-content-center gap-1">
                                    {{-- Detail button (modal or show route if exists) --}}
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#detailModal{{ $item->id }}"
                                        title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if(!auth()->user()->isAdmin() && $item->status === 'menunggu')
                                    <a href="{{ route('pegawai.cuti-tambahan.edit', $item) }}"
                                        class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pegawai.cuti-tambahan.destroy', $item) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus permohonan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if(auth()->user()->isAdmin() && $item->status === 'menunggu')
                                    {{-- Approve / Reject buttons for admin --}}
                                    <form action="{{ route('admin.persetujuan.show', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui"
                                            onclick="return confirm('Setujui permohonan ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.persetujuan.show', $item) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak"
                                            onclick="return confirm('Tolak permohonan ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- Detail Modal --}}
                        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Cuti Tambahan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-borderless table-sm">
                                            @if(auth()->user()->isAdmin())
                                            <tr>
                                                <th width="200">Nama Pegawai</th>
                                                <td>{{ $item->pegawai->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIP</th>
                                                <td>{{ $item->pegawai->nip ?? '-' }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Nomor Nota Dinas</th>
                                                <td>{{ $item->nomor_nota_dinas }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Nota Dinas</th>
                                                <td>{{ \Carbon\Carbon::parse($item->tanggal_nota_dinas)->format('d-m-Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Periode Cuti</th>
                                                <td>{{ $item->periode_cuti }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Hari</th>
                                                <td>{{ $item->cuti_tambahan_jumlah }} hari</td>
                                            </tr>
                                            <tr>
                                                <th>Alasan Cuti</th>
                                                <td>{{ $item->alasan_cuti }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat Selama Cuti</th>
                                                <td>{{ $item->alamat_cuti }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{!! $item->status_badge !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Diajukan Pada</th>
                                                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 9 : 8 }}" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada permohonan cuti tambahan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection