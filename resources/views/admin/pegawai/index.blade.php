@extends('layouts.app')

@section('title', 'Data Pegawai')
@section('page-title', 'Data Pegawai')

@section('content')
<div class="page-header">
    <h4><i class="bi bi-people me-2"></i>Data Pegawai</h4>
    <p>Kelola data seluruh pegawai beserta informasi cuti</p>
</div>

<div class="card">
    <div class="card-header-clean py-3">
        <span class="card-title-sm">Daftar Pegawai</span>
        <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Tambah Pegawai
        </a>
    </div>

    <div class="p-3">
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width:4%">No</th>
                        <th style="width:14%">NIP</th>
                        <th style="width:16%">Nama</th>
                        <th style="width:12%">Pangkat/Gol</th>
                        <th style="width:14%">Jabatan</th>
                        <th style="width:14%">Atasan</th>
                        <th style="width:8%" class="text-center">Cuti Tahunan</th>
                        <th style="width:8%" class="text-center">Cuti Tambahan</th>
                        <th style="width:10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pegawai as $index => $p)
                        <tr>
                            <td class="text-muted" style="font-size:12px;">{{ $index + 1 }}</td>
                            <td class="font-mono">{{ $p->nip }}</td>
                            <td>
                                <div style="font-weight:600;font-size:13.5px;">{{ $p->nama }}</div>
                            </td>
                            <td style="font-size:13px;">{{ $p->pangkat_gol }}</td>
                            <td style="font-size:13px;">{{ $p->jabatan }}</td>
                            <td>
                                @if($p->atasan)
                                    <div style="font-size:13px;font-weight:500;">{{ $p->atasan->nama }}</div>
                                    <div style="font-size:11px;color:var(--text-muted);">{{ $p->atasan->jabatan }}</div>
                                @else
                                    <span style="font-size:12px;color:var(--text-muted);font-style:italic;">— Kepala Kantor</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge-status badge-disetujui">{{ $p->sisa_display_tahunan }} hari</span>
                            </td>
                            <td class="text-center">
                                <span class="badge-status badge-menunggu">{{ $p->sisa_display_tambahan }} hari</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-1">

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.pegawai.edit', $p->id) }}"
                                       class="btn btn-warning btn-icon"
                                       title="Edit Pegawai">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Reset Password --}}
                                    <form action="{{ route('admin.pegawai.reset-password', $p->id) }}" method="POST"
                                          onsubmit="return confirm('Reset password {{ addslashes($p->nama) }} ke 123456?')"
                                          style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-icon"
                                                style="background:#e0f2fe;color:#0369a1;border:1px solid #bae6fd;"
                                                title="Reset Password ke 123456">
                                            <i class="bi bi-key"></i>
                                        </button>
                                    </form>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.pegawai.destroy', $p->id) }}"
                                          method="POST"
                                          style="display:inline;"
                                          onsubmit="return confirm('Yakin ingin menghapus {{ addslashes($p->nama) }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-icon"
                                                title="Hapus Pegawai">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-people" style="font-size:2.5rem;color:#d1d5db;"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size:13px;">Belum ada data pegawai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection