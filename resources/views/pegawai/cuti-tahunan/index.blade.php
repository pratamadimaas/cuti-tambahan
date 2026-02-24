@extends('layouts.app')

@section('title', 'Cuti Tahunan Saya')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header mb-0">
            <h4>Permohonan Cuti Tahunan</h4>
            <p>Daftar permohonan cuti tahunan Anda</p>
        </div>
    </div>

    {{-- Info Kuota --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-calendar2"></i></div>
                <div>
                    <div class="stat-value">{{ $infoCuti['kuota_awal'] }}</div>
                    <div class="stat-label">Kuota Awal</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="bi bi-calendar2-minus"></i></div>
                <div>
                    <div class="stat-value">{{ $infoCuti['terpakai'] }}</div>
                    <div class="stat-label">Sudah Diambil</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-calendar2-check"></i></div>
                <div>
                    <div class="stat-value">{{ $infoCuti['sisa'] }}</div>
                    <div class="stat-label">Sisa Cuti Tahunan</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Permohonan</th>
                        <th>Tanggal Permohonan</th>
                        <th>Tanggal Cuti</th>
                        <th class="text-center">Jumlah</th>
                        <th>Alasan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cutiTahunan as $index => $cuti)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-mono">{{ $cuti->nomor_nota_dinas }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_nota_dinas)->translatedFormat('d M Y') }}</td>
                        <td style="max-width:220px;">
                            @if($cuti->tanggal_cuti)
                                @foreach($cuti->tanggal_cuti as $tgl)
                                    <span class="badge"
                                          style="background:#f0f2f8; color:#1a1f36; font-size:11px; font-weight:500; border-radius:5px; margin-bottom:2px; display:inline-block;">
                                        {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="fw-bold" style="color:#3b5bdb;">
                                {{ $cuti->cuti_tahunan_jumlah }} hari
                            </span>
                        </td>
                        <td>{{ Str::limit($cuti->alasan_cuti, 40) }}</td>
                        <td class="text-center">
                            @php
                                $badgeClass = match($cuti->status) {
                                    'menunggu'  => 'badge-menunggu',
                                    'disetujui' => 'badge-disetujui',
                                    'ditolak'   => 'badge-ditolak',
                                    default     => ''
                                };
                            @endphp
                            <span class="badge-status {{ $badgeClass }}">{{ ucfirst($cuti->status) }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($cuti->status === 'menunggu')
                                    <a href="{{ route('pegawai.cuti-tahunan.edit', $cuti) }}"
                                       class="btn btn-sm btn-icon"
                                       style="background:#fffbeb; color:#b45309; border:none;"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pegawai.cuti-tahunan.destroy', $cuti) }}"
                                          method="POST"
                                          onsubmit="return confirm('Hapus permohonan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-icon"
                                                style="background:#fef2f2; color:#b91c1c; border:none;"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted fst-italic" style="font-size:12px;">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem; opacity:.3;"></i>
                            <p class="mt-2 mb-0" style="font-size:13px;">Belum ada permohonan cuti tahunan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection