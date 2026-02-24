@extends('layouts.app')
@section('title', 'Tambah Seksi')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-diagram-3"></i> Tambah Seksi</h2>
        <a href="{{ route('admin.seksi.index') }}" class="btn btn-secondary">
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
            <form action="{{ route('admin.seksi.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Nama Seksi <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama_seksi" 
                               class="form-control @error('nama_seksi') is-invalid @enderror"
                               value="{{ old('nama_seksi') }}" 
                               required 
                               placeholder="Contoh: Seksi Kepegawaian">
                        @error('nama_seksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nama Kepala Seksi <span class="text-danger">*</span></label>
                        <input type="text" 
                               name="nama_kepala_seksi" 
                               class="form-control @error('nama_kepala_seksi') is-invalid @enderror"
                               value="{{ old('nama_kepala_seksi') }}" 
                               required 
                               placeholder="Masukkan nama lengkap kepala seksi">
                        @error('nama_kepala_seksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">NIP Kepala Seksi</label>
                        <input type="text" 
                               name="nip_kepala_seksi" 
                               class="form-control @error('nip_kepala_seksi') is-invalid @enderror"
                               value="{{ old('nip_kepala_seksi') }}" 
                               placeholder="18 digit NIP" 
                               maxlength="18">
                        @error('nip_kepala_seksi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Opsional</small>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.seksi.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan Seksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection