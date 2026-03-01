@extends('layouts.app')

@section('title', 'Tambah Pegawai')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-plus"></i> Tambah Pegawai Baru</h2>
        <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pegawai.store') }}" method="POST">
                @csrf

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Informasi:</strong> Akun login akan dibuat otomatis dengan username = NIP dan password = <strong>123456</strong>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror"
                               name="nama" 
                               value="{{ old('nama') }}" 
                               required
                               placeholder="Masukkan nama lengkap">
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nip') is-invalid @enderror"
                               name="nip" 
                               value="{{ old('nip') }}" 
                               required
                               placeholder="18 digit NIP"
                               maxlength="18">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">NIP akan digunakan sebagai username login</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pangkat / Golongan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('pangkat_gol') is-invalid @enderror"
                               name="pangkat_gol" 
                               value="{{ old('pangkat_gol') }}" 
                               required
                               placeholder="Contoh: Penata Muda (III/a)">
                        @error('pangkat_gol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('jabatan') is-invalid @enderror"
                               name="jabatan" 
                               value="{{ old('jabatan') }}" 
                               required
                               placeholder="Masukkan jabatan">
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <select 
                            class="form-select @error('unit_kerja') is-invalid @enderror"
                            name="unit_kerja"
                            required>
                            <option value="">-- Pilih Unit Kerja --</option>
                            <option value="KPPN Kolaka" 
                                {{ old('unit_kerja') == 'KPPN Kolaka' ? 'selected' : '' }}>
                                KPPN Kolaka
                            </option>
                        </select>
                        @error('unit_kerja')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- SEKSI --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Seksi</label>
                        <select name="seksi_id"
                                id="seksiSelect"
                                class="form-select @error('seksi_id') is-invalid @enderror"
                                onchange="updateKepala()">
                            <option value="">-- Tidak Ada Seksi --</option>
                            @foreach($seksiList as $s)
                                <option value="{{ $s->id }}"
                                        data-kepala="{{ $s->nama_kepala_seksi }}"
                                        data-nip="{{ $s->nip_kepala_seksi ?? '-' }}"
                                        {{ old('seksi_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_seksi }}
                                </option>
                            @endforeach
                        </select>
                        @error('seksi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- INFO KEPALA SEKSI --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kepala Seksi</label>
                        <div class="form-control bg-light d-flex flex-column justify-content-center"
                             id="infoKepala"
                             style="min-height: 38px; cursor: default;">
                            @if(old('seksi_id'))
                                @php $oldSeksi = $seksiList->find(old('seksi_id')); @endphp
                                @if($oldSeksi)
                                    <span class="fw-semibold">{{ $oldSeksi->nama_kepala_seksi }}</span>
                                    <small class="text-muted">NIP: {{ $oldSeksi->nip_kepala_seksi ?? '-' }}</small>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Pilih seksi terlebih dahulu</span>
                            @endif
                        </div>
                    </div>

                    {{-- ATASAN --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Atasan Langsung</label>
                        <select name="atasan_id"
                                id="atasanSelect"
                                class="form-select @error('atasan_id') is-invalid @enderror"
                                onchange="updateInfoAtasan()">
                            <option value="">-- Tidak Ada Atasan / Kepala Kantor --</option>
                            @foreach($pegawaiList as $p)
                                <option value="{{ $p->id }}"
                                        data-nama="{{ $p->nama }}"
                                        data-nip="{{ $p->nip }}"
                                        data-jabatan="{{ $p->jabatan }}"
                                        {{ old('atasan_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama }} - {{ $p->jabatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('atasan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jika atasan adalah Kepala Kantor, biarkan kosong</small>
                    </div>

                    {{-- INFO ATASAN --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Info Atasan</label>
                        <div class="form-control bg-light d-flex flex-column justify-content-center"
                             id="infoAtasan"
                             style="min-height: 38px; cursor: default;">
                            @if(old('atasan_id'))
                                @php $oldAtasan = $pegawaiList->find(old('atasan_id')); @endphp
                                @if($oldAtasan)
                                    <span class="fw-semibold">{{ $oldAtasan->nama }}</span>
                                    <small class="text-muted">NIP: {{ $oldAtasan->nip }} | {{ $oldAtasan->jabatan }}</small>
                                @endif
                            @else
                                <span class="text-muted fst-italic">Pilih atasan terlebih dahulu</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sisa Cuti Tahunan</label>
                        <input type="number" 
                               class="form-control @error('sisa_cuti_tahunan') is-invalid @enderror"
                               name="sisa_cuti_tahunan" 
                               value="{{ old('sisa_cuti_tahunan', 12) }}" 
                               min="0"
                               step="0.5"
                               placeholder="0">
                        @error('sisa_cuti_tahunan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Default: 12 hari</small>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sisa Cuti Tambahan</label>
                        <input type="number" 
                               class="form-control @error('sisa_cuti_tambahan') is-invalid @enderror"
                               name="sisa_cuti_tambahan" 
                               value="{{ old('sisa_cuti_tambahan', 0) }}" 
                               min="0"
                               step="0.5"
                               placeholder="0">
                        @error('sisa_cuti_tambahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Default: 0 hari</small>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Pegawai
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateKepala() {
        const select = document.getElementById('seksiSelect');
        const info   = document.getElementById('infoKepala');
        const opt    = select.options[select.selectedIndex];

        if (!select.value) {
            info.innerHTML = '<span class="text-muted fst-italic">Pilih seksi terlebih dahulu</span>';
            return;
        }

        const kepala = opt.getAttribute('data-kepala');
        const nip    = opt.getAttribute('data-nip');
        info.innerHTML = `<span class="fw-semibold">${kepala}</span><small class="text-muted">NIP: ${nip}</small>`;
    }

    function updateInfoAtasan() {
        const select = document.getElementById('atasanSelect');
        const info   = document.getElementById('infoAtasan');
        const opt    = select.options[select.selectedIndex];

        if (!select.value) {
            info.innerHTML = '<span class="text-muted fst-italic">Pilih atasan terlebih dahulu</span>';
            return;
        }

        const nama    = opt.getAttribute('data-nama');
        const nip     = opt.getAttribute('data-nip');
        const jabatan = opt.getAttribute('data-jabatan');
        info.innerHTML = `<span class="fw-semibold">${nama}</span><small class="text-muted">NIP: ${nip} | ${jabatan}</small>`;
    }
</script>
@endpush

@endsection