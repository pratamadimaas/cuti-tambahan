@extends('layouts.app')

@section('title', 'Permohonan Cuti')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="page-header mb-0">
            <h4>Permohonan Cuti</h4>
            <p>Daftar permohonan cuti tambahan & tahunan Anda</p>
        </div>
        <a href="{{ route('pegawai.cuti-tambahan.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Ajukan Cuti
        </a>
    </div>

    {{-- Info Kuota --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="bi bi-calendar-plus"></i></div>
                <div>
                    <div class="stat-value">{{ $infoCutiTambahan['sisa'] }}</div>
                    <div class="stat-label">Sisa Cuti Tambahan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-calendar2-check"></i></div>
                <div>
                    <div class="stat-value">{{ $infoCutiTahunan['sisa'] }}</div>
                    <div class="stat-label">Sisa Cuti Tahunan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon amber"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="stat-value">{{ $cutiTambahan->where('status', 'menunggu')->count() }}</div>
                    <div class="stat-label">Menunggu</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="stat-value">{{ $cutiTambahan->where('status', 'disetujui')->count() }}</div>
                    <div class="stat-label">Disetujui</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table-modern">
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
                    @forelse($cutiTambahan as $index => $cuti)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="font-mono">{{ $cuti->nomor_nota_dinas }}</td>
                        <td>{{ \Carbon\Carbon::parse($cuti->tanggal_nota_dinas)->translatedFormat('d M Y') }}</td>

                        {{-- Kolom Tanggal Cuti --}}
                        <td style="max-width:240px;">
                            {{-- Cuti Tambahan --}}
                            @if(!empty($cuti->tanggal_cuti_tambahan))
                                @foreach($cuti->tanggal_cuti_tambahan as $tgl)
                                    <span class="badge mb-1 d-inline-block"
                                          style="background:#eef2ff; color:#3b5bdb; font-size:11px; font-weight:500; border-radius:5px;">
                                        {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') }}
                                    </span>
                                @endforeach
                            @endif

                            {{-- Cuti Tahunan (support format lama string & baru {tanggal,sesi}) --}}
                            @if(!empty($cuti->tanggal_cuti_tahunan))
                                @foreach($cuti->tanggal_cuti_tahunan as $item)
                                    @php
                                        $tgl       = is_string($item) ? $item : $item['tanggal'];
                                        $sesi      = is_array($item) ? ($item['sesi'] ?? 'penuh') : 'penuh';
                                        $sesiLabel = match($sesi) {
                                            'pagi'  => ' <em style="font-size:10px;">(Pagi)</em>',
                                            'siang' => ' <em style="font-size:10px;">(Siang)</em>',
                                            default => '',
                                        };
                                    @endphp
                                    <span class="badge mb-1 d-inline-block"
                                          style="background:#ecfdf5; color:#065f46; font-size:11px; font-weight:500; border-radius:5px;">
                                        {{ \Carbon\Carbon::parse($tgl)->translatedFormat('d M Y') }}{!! $sesiLabel !!}
                                    </span>
                                @endforeach
                            @endif

                            @if(empty($cuti->tanggal_cuti_tambahan) && empty($cuti->tanggal_cuti_tahunan))
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Kolom Jumlah --}}
                        <td class="text-center">
                            @if($cuti->cuti_tambahan_jumlah > 0)
                                <span class="badge d-block mb-1" style="background:#eef2ff; color:#3b5bdb; font-size:11px; border-radius:6px;">
                                    {{ $cuti->cuti_tambahan_jumlah }} hr Tambahan
                                </span>
                            @endif
                            @if($cuti->cuti_tahunan_jumlah > 0)
                                @php
                                    // Tampilkan 0.5 / 1 / 1.5 — hilangkan .0 jika bulat
                                    $jumlahTahunan = $cuti->cuti_tahunan_jumlah;
                                    $jumlahTahunanFmt = ($jumlahTahunan == floor($jumlahTahunan))
                                        ? (int) $jumlahTahunan
                                        : number_format($jumlahTahunan, 1);
                                @endphp
                                <span class="badge d-block" style="background:#ecfdf5; color:#065f46; font-size:11px; border-radius:6px;">
                                    {{ $jumlahTahunanFmt }} hr Tahunan
                                </span>
                            @endif
                            @if($cuti->cuti_tambahan_jumlah == 0 && $cuti->cuti_tahunan_jumlah == 0)
                                <span class="text-muted">-</span>
                            @endif
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
                                {{-- Cetak ND --}}
                                <a href="{{ route('pegawai.surat.form', $cuti) }}"
                                   class="btn btn-sm btn-icon"
                                   style="background:#e0f2fe; color:#0369a1; border:none;"
                                   title="Cetak Nota Dinas">
                                    <i class="bi bi-file-earmark-word"></i>
                                </a>

                                @if($cuti->status === 'menunggu')
                                    <a href="{{ route('pegawai.cuti-tambahan.edit', $cuti) }}"
                                       class="btn btn-sm btn-icon"
                                       style="background:#fffbeb; color:#b45309; border:none;"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('pegawai.cuti-tambahan.destroy', $cuti) }}"
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
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem; opacity:.3;"></i>
                            <p class="mt-2 mb-0" style="font-size:13px;">Belum ada permohonan cuti</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection