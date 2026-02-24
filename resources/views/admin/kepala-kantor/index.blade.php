@extends('layouts.app')

@section('title', 'Pengaturan Kepala Kantor')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Data Kepala Kantor</h5>
                </div>
                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.kepala-kantor.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="nama"
                                   id="nama"
                                   class="form-control @error('nama') is-invalid @enderror"
                                   value="{{ old('nama', $kepalaKantor->nama ?? '') }}"
                                   required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text"
                                   name="nip"
                                   id="nip"
                                   class="form-control @error('nip') is-invalid @enderror"
                                   value="{{ old('nip', $kepalaKantor->nip ?? '') }}"
                                   maxlength="18">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="pangkat_gol" class="form-label">Pangkat / Golongan</label>
                            <input type="text"
                                   name="pangkat_gol"
                                   id="pangkat_gol"
                                   class="form-control @error('pangkat_gol') is-invalid @enderror"
                                   value="{{ old('pangkat_gol', $kepalaKantor->pangkat_gol ?? '') }}">
                            @error('pangkat_gol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection