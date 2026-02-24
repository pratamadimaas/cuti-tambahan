{{-- resources/views/admin/buku-tamu/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rekap Buku Tamu')
@section('page-title', 'Buku Tamu')
@section('page-subtitle', 'Rekap Kunjungan')

@section('content')
<div class="page-header d-flex align-items-center justify-content-between">
    <div>
        <h4>Rekap Buku Tamu</h4>
        <p>Daftar seluruh kunjungan satker ke KPPN</p>
    </div>
    <a href="{{ route('admin.buku-tamu.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Input Tamu
    </a>
</div>

{{-- FILTER --}}
<div class="card mb-4 p-3">
    <form method="GET" action="{{ route('admin.buku-tamu.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:12px;">Tanggal</label>
            <input type="date" name="tanggal" class="form-control form-control-sm"
                   value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:12px;">Nama Satker</label>
            <input type="text" name="satker" class="form-control form-control-sm"
                   value="{{ request('satker') }}" placeholder="Cari satker...">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" style="font-size:12px;">Pegawai KPPN</label>
            <select name="pegawai_id" class="form-select form-select-sm">
                <option value="">— Semua —</option>
                @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}" {{ request('pegawai_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>
            <a href="{{ route('admin.buku-tamu.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
        </div>
    </form>
</div>

{{-- TABEL --}}
<div class="card">
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Satker</th>
                    <th>Nama Tamu</th>
                    <th>Bertemu</th>
                    <th>Konsultasi</th>
                    <th>Waktu Kunjungan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bukuTamu as $tamu)
                <tr>
                    <td>
                        <img src="{{ Storage::url($tamu->foto_path) }}"
                             alt="Foto"
                             style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #e4e8f0;">
                    </td>
                    <td>
                        <div style="font-weight:600;font-size:13px;">{{ $tamu->nama_satker }}</div>
                    </td>
                    <td>{{ $tamu->nama_tamu }}</td>
                    <td>{{ $tamu->pegawaiKppn->nama ?? '-' }}</td>
                    <td>
                        <span class="badge-status badge-menunggu">{{ $tamu->keterangan }}</span>
                    </td>
                    <td>
                        <span class="font-mono">
                            {{ \Carbon\Carbon::parse($tamu->waktu_kunjungan)->locale('id')->isoFormat('D MMM Y, HH:mm') }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            {{-- Kirim WhatsApp --}}
                            @php
                                $pesan = "📘 *Buku Tamu KPPN*\n"
                                    . "📅 Tanggal: " . \Carbon\Carbon::parse($tamu->waktu_kunjungan)->isoFormat('D MMMM Y') . "\n"
                                    . "🏢 Satker: " . $tamu->nama_satker . "\n"
                                    . "👤 Nama Tamu: " . $tamu->nama_tamu . "\n"
                                    . "🤝 Bertemu: " . ($tamu->pegawaiKppn->nama ?? '-') . "\n"
                                    . "📍 Lokasi: " . $tamu->kantor . "\n"
                                    . "⏰ Waktu: " . \Carbon\Carbon::parse($tamu->waktu_kunjungan)->format('H:i') . " WITA";
                            @endphp
                            <a href="https://wa.me/?text={{ urlencode($pesan) }}"
                               target="_blank"
                               class="btn btn-icon btn-sm"
                               style="background:#25d366;color:#fff;border:none;"
                               title="Kirim WhatsApp">
                                <i class="bi bi-whatsapp"></i>
                            </a>
                            <a href="{{ route('admin.buku-tamu.edit', $tamu->id) }}"
                            class="btn btn-icon btn-sm btn-warning"
                            title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            {{-- Hapus --}}
                            <form action="{{ route('admin.buku-tamu.destroy', $tamu->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-icon btn-sm btn-danger" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="bi bi-journal-x fs-2 d-block mb-2"></i>
                        Belum ada data kunjungan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($bukuTamu->hasPages())
    <div class="p-3 border-top">
        {{ $bukuTamu->links() }}
    </div>
    @endif
</div>
@endsection