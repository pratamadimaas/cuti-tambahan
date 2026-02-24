@extends('layouts.app')

@section('title', 'Edit Permohonan Cuti Tambahan')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-pencil-square text-primary fs-5"></i>
                            <span class="card-title-sm">Edit Permohonan Cuti Tambahan</span>
                        </div>
                        <a href="{{ route('pegawai.cuti-tambahan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>

                    <div class="p-4">
                        @if($errors->any())
                            <div class="mb-4" style="background:#fef2f2; color:#991b1b; border-radius:8px; padding:12px 16px; font-size:13px; display:flex; gap:10px; align-items:flex-start;">
                                <i class="bi bi-exclamation-circle-fill fs-5 mt-1"></i>
                                <ul class="mb-0 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('pegawai.cuti-tambahan.update', $cutiTambahan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- Nomor ND readonly --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Nomor Nota Dinas</label>
                                <input type="text" class="form-control bg-light font-mono"
                                       value="{{ $cutiTambahan->nomor_nota_dinas }}" disabled>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Nota Dinas <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_nota_dinas"
                                       class="form-control @error('tanggal_nota_dinas') is-invalid @enderror"
                                       value="{{ old('tanggal_nota_dinas', \Carbon\Carbon::parse($cutiTambahan->tanggal_nota_dinas)->format('Y-m-d')) }}"
                                       required>
                                @error('tanggal_nota_dinas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- Tanggal Cuti Dinamis --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    Tanggal Cuti <span class="text-danger">*</span>
                                    <small class="text-muted fw-normal ms-1">— Bisa pilih lebih dari satu, tidak harus berurutan</small>
                                </label>

                                <div id="tanggal-container">
                                    @php
                                        $tanggalList = old('tanggal_cuti', $cutiTambahan->tanggal_cuti ?? []);
                                    @endphp
                                    @foreach($tanggalList as $tgl)
                                        <div class="tanggal-row d-flex gap-2 mb-2">
                                            <input type="date" name="tanggal_cuti[]"
                                                   class="form-control"
                                                   value="{{ $tgl }}" required>
                                            <button type="button"
                                                    class="btn btn-danger btn-icon remove-tanggal"
                                                    title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endforeach

                                    {{-- Fallback jika tanggal_cuti kosong --}}
                                    @if(empty($tanggalList))
                                        <div class="tanggal-row d-flex gap-2 mb-2">
                                            <input type="date" name="tanggal_cuti[]"
                                                   class="form-control" required>
                                            <button type="button"
                                                    class="btn btn-danger btn-icon remove-tanggal"
                                                    title="Hapus" disabled>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                <button type="button" id="tambah-tanggal"
                                        class="btn btn-sm mt-1"
                                        style="background:#eef2ff; color:#3b5bdb; font-weight:600; border-radius:8px; border:none;">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Tanggal
                                </button>

                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="bi bi-info-circle"></i>
                                        Total: <strong id="jumlah-text">{{ count($tanggalList) ?: 1 }}</strong> hari dipilih
                                    </small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alasan Cuti <span class="text-danger">*</span></label>
                                <textarea name="alasan_cuti" rows="3"
                                          class="form-control @error('alasan_cuti') is-invalid @enderror"
                                          required
                                          placeholder="Jelaskan alasan pengajuan cuti tambahan">{{ old('alasan_cuti', $cutiTambahan->alasan_cuti) }}</textarea>
                                @error('alasan_cuti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat Selama Cuti <span class="text-danger">*</span></label>
                                <textarea name="alamat_cuti" rows="2"
                                          class="form-control @error('alamat_cuti') is-invalid @enderror"
                                          required
                                          placeholder="Alamat lengkap selama menjalani cuti">{{ old('alamat_cuti', $cutiTambahan->alamat_cuti) }}</textarea>
                                @error('alamat_cuti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pegawai.cuti-tambahan.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function updateJumlah() {
        const rows = document.querySelectorAll('.tanggal-row');
        document.getElementById('jumlah-text').textContent = rows.length;
        rows.forEach(row => {
            row.querySelector('.remove-tanggal').disabled = (rows.length === 1);
        });
    }

    document.getElementById('tambah-tanggal').addEventListener('click', function () {
        const container = document.getElementById('tanggal-container');
        const div = document.createElement('div');
        div.className = 'tanggal-row d-flex gap-2 mb-2';
        div.innerHTML = `
            <input type="date" name="tanggal_cuti[]" class="form-control" required>
            <button type="button" class="btn btn-danger btn-icon remove-tanggal" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        div.querySelector('.remove-tanggal').addEventListener('click', function () {
            div.remove();
            updateJumlah();
        });
        updateJumlah();
    });

    document.querySelectorAll('.remove-tanggal').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.tanggal-row').remove();
            updateJumlah();
        });
    });

    updateJumlah();
</script>
@endpush

@endsection