@extends('layouts.app')

@section('title', 'Edit Pegawai')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-pencil-square"></i> Edit Pegawai</h2>
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
            <form action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama') is-invalid @enderror"
                               name="nama" 
                               value="{{ old('nama', $pegawai->nama) }}" 
                               required>
                        @error('nama')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">NIP <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nip') is-invalid @enderror"
                               name="nip" 
                               value="{{ old('nip', $pegawai->nip) }}" 
                               required
                               maxlength="18">
                        @error('nip')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pangkat / Golongan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('pangkat_gol') is-invalid @enderror"
                               name="pangkat_gol" 
                               value="{{ old('pangkat_gol', $pegawai->pangkat_gol) }}" 
                               required>
                        @error('pangkat_gol')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('jabatan') is-invalid @enderror"
                               name="jabatan" 
                               value="{{ old('jabatan', $pegawai->jabatan) }}" 
                               required>
                        @error('jabatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Unit Kerja <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('unit_kerja') is-invalid @enderror"
                               name="unit_kerja" 
                               value="{{ old('unit_kerja', $pegawai->unit_kerja) }}" 
                               required>
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
                                        {{ old('seksi_id', $pegawai->seksi_id) == $s->id ? 'selected' : '' }}>
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
                            @if($pegawai->seksi)
                                <span class="fw-semibold">{{ $pegawai->seksi->nama_kepala_seksi }}</span>
                                <small class="text-muted">NIP: {{ $pegawai->seksi->nip_kepala_seksi ?? '-' }}</small>
                            @else
                                <span class="text-muted fst-italic">Pilih seksi terlebih dahulu</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sisa Cuti Tahunan</label>
                        <input type="number" 
                               class="form-control @error('sisa_cuti_tahunan') is-invalid @enderror"
                               name="sisa_cuti_tahunan" 
                               value="{{ old('sisa_cuti_tahunan', $pegawai->sisa_cuti_tahunan) }}" 
                               min="0"
                               step="0.5">
                        @error('sisa_cuti_tahunan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sisa Cuti Tambahan</label>
                        <input type="number" 
                               class="form-control @error('sisa_cuti_tambahan') is-invalid @enderror"
                               name="sisa_cuti_tambahan" 
                               value="{{ old('sisa_cuti_tambahan', $pegawai->sisa_cuti_tambahan) }}" 
                               min="0"
                               step="0.5">
                        @error('sisa_cuti_tambahan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.pegawai.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Pegawai
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
</script>
@endpush

@endsection