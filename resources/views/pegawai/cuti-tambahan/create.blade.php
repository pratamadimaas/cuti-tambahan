@extends('layouts.app')

@section('title', 'Ajukan Cuti')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-plus text-primary fs-5"></i>
                            <span class="card-title-sm">Ajukan Permohonan Cuti</span>
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
                                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('pegawai.cuti-tambahan.store') }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Tanggal Nota Dinas <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_nota_dinas"
                                       class="form-control @error('tanggal_nota_dinas') is-invalid @enderror"
                                       value="{{ old('tanggal_nota_dinas') }}" required>
                                @error('tanggal_nota_dinas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            {{-- ══════ CUTI TAMBAHAN ══════ --}}
                            <div class="mb-2">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge" style="background:#eef2ff; color:#3b5bdb; font-size:13px; padding:6px 12px; border-radius:8px;">
                                        <i class="bi bi-calendar-plus me-1"></i> Cuti Tambahan
                                    </span>
                                    <small class="text-muted">Sisa: <strong style="color:#3b5bdb;">{{ $infoCutiTambahan['sisa'] }} hari</strong></small>
                                </div>

                                <label class="form-label fw-semibold">
                                    Tanggal Cuti Tambahan
                                    <small class="text-muted fw-normal ms-1">— Bisa pilih lebih dari satu, tidak harus berurutan (opsional)</small>
                                </label>

                                <div id="tanggal-tambahan-container">
                                    <div class="tanggal-tambahan-row d-flex gap-2 mb-2">
                                        <input type="date" name="tanggal_cuti_tambahan[]" class="form-control">
                                        <button type="button" class="btn btn-danger btn-icon remove-tambahan" title="Hapus" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" id="tambah-tanggal-tambahan"
                                        class="btn btn-sm mt-1"
                                        style="background:#eef2ff; color:#3b5bdb; font-weight:600; border-radius:8px; border:none;">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Tanggal
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Total: <strong id="jumlah-tambahan">0</strong> hari dipilih</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            {{-- ══════ CUTI TAHUNAN ══════ --}}
                            <div class="mb-4">
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge" style="background:#ecfdf5; color:#065f46; font-size:13px; padding:6px 12px; border-radius:8px;">
                                        <i class="bi bi-calendar2-check me-1"></i> Cuti Tahunan
                                    </span>
                                    <small class="text-muted">Sisa: <strong style="color:#0ca678;">{{ $infoCutiTahunan['sisa'] }} hari</strong></small>
                                </div>

                                <label class="form-label fw-semibold">
                                    Tanggal Cuti Tahunan
                                    <small class="text-muted fw-normal ms-1">— Senin–Jumat saja, tidak harus berurutan (opsional)</small>
                                </label>

                                <div id="tanggal-tahunan-container">
                                    <div class="tanggal-tahunan-row d-flex gap-2 mb-2 align-items-center">
                                        <input type="date" name="tanggal_cuti_tahunan[0][tanggal]"
                                               class="form-control tanggal-tahunan-input">
                                        <select name="tanggal_cuti_tahunan[0][sesi]"
                                                class="form-select sesi-tahunan-select" style="max-width:150px;">
                                            <option value="penuh">Penuh (1 hr)</option>
                                            <option value="pagi">½ Pagi (0.5 hr)</option>
                                            <option value="siang">½ Siang (0.5 hr)</option>
                                        </select>
                                        <button type="button" class="btn btn-danger btn-icon remove-tahunan" title="Hapus" disabled>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="tambah-tanggal-tahunan"
                                        class="btn btn-sm mt-1"
                                        style="background:#ecfdf5; color:#065f46; font-weight:600; border-radius:8px; border:none;">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Tanggal
                                </button>
                                <div class="mt-2">
                                    <small class="text-muted"><i class="bi bi-info-circle"></i> Total: <strong id="jumlah-tahunan">0</strong> hari dipilih</small>
                                </div>
                                <div id="weekend-warning" class="mt-2" style="display:none;">
                                    <small class="text-danger"><i class="bi bi-exclamation-triangle"></i> Cuti tahunan tidak dapat diajukan pada hari Sabtu atau Minggu.</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alasan Cuti <span class="text-danger">*</span></label>
                                <textarea name="alasan_cuti" rows="3"
                                          class="form-control @error('alasan_cuti') is-invalid @enderror"
                                          required placeholder="Jelaskan alasan pengajuan cuti">{{ old('alasan_cuti') }}</textarea>
                                @error('alasan_cuti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat Selama Cuti <span class="text-danger">*</span></label>
                                <textarea name="alamat_cuti" rows="2"
                                          class="form-control @error('alamat_cuti') is-invalid @enderror"
                                          required placeholder="Alamat lengkap selama menjalani cuti">{{ old('alamat_cuti') }}</textarea>
                                @error('alamat_cuti')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('pegawai.cuti-tambahan.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary" id="btn-submit">
                                    <i class="bi bi-send"></i> Ajukan Permohonan
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
    // ── Cuti Tambahan ──
    function updateJumlahTambahan() {
        const rows = document.querySelectorAll('.tanggal-tambahan-row');
        let filled = 0;
        rows.forEach(row => {
            if (row.querySelector('input').value) filled++;
            row.querySelector('.remove-tambahan').disabled = (rows.length === 1);
        });
        document.getElementById('jumlah-tambahan').textContent = filled;
    }

    document.getElementById('tambah-tanggal-tambahan').addEventListener('click', function () {
        const container = document.getElementById('tanggal-tambahan-container');
        const div = document.createElement('div');
        div.className = 'tanggal-tambahan-row d-flex gap-2 mb-2';
        div.innerHTML = `
            <input type="date" name="tanggal_cuti_tambahan[]" class="form-control">
            <button type="button" class="btn btn-danger btn-icon remove-tambahan" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        div.querySelector('input').addEventListener('change', updateJumlahTambahan);
        div.querySelector('.remove-tambahan').addEventListener('click', function () {
            div.remove();
            updateJumlahTambahan();
        });
        updateJumlahTambahan();
    });

    document.querySelectorAll('.remove-tambahan').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.tanggal-tambahan-row').remove();
            updateJumlahTambahan();
        });
    });

    document.querySelectorAll('.tanggal-tambahan-row input').forEach(input => {
        input.addEventListener('change', updateJumlahTambahan);
    });

    // ── Cuti Tahunan ──
    let tahunanIndex = 1;

    function isWeekend(dateStr) {
        if (!dateStr) return false;
        const day = new Date(dateStr).getDay();
        return day === 0 || day === 6;
    }

    function hitungSesi(sesi) {
        return sesi === 'penuh' ? 1 : 0.5;
    }

    function formatJumlah(val) {
        return val % 1 === 0 ? val : val.toFixed(1);
    }

    function updateJumlahTahunan() {
        const rows = document.querySelectorAll('.tanggal-tahunan-row');
        let total = 0;
        let hasWeekend = false;

        rows.forEach(row => {
            const input  = row.querySelector('.tanggal-tahunan-input');
            const select = row.querySelector('.sesi-tahunan-select');
            row.querySelector('.remove-tahunan').disabled = (rows.length === 1);

            if (input.value) {
                if (isWeekend(input.value)) {
                    hasWeekend = true;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                    total += hitungSesi(select.value);
                }
            }
        });

        document.getElementById('jumlah-tahunan').textContent = formatJumlah(total);
        document.getElementById('weekend-warning').style.display = hasWeekend ? 'block' : 'none';
        document.getElementById('btn-submit').disabled = hasWeekend;
    }

    document.getElementById('tambah-tanggal-tahunan').addEventListener('click', function () {
        const container = document.getElementById('tanggal-tahunan-container');
        const idx = tahunanIndex++;
        const div = document.createElement('div');
        div.className = 'tanggal-tahunan-row d-flex gap-2 mb-2 align-items-center';
        div.innerHTML = `
            <input type="date" name="tanggal_cuti_tahunan[${idx}][tanggal]"
                   class="form-control tanggal-tahunan-input">
            <select name="tanggal_cuti_tahunan[${idx}][sesi]"
                    class="form-select sesi-tahunan-select" style="max-width:150px;">
                <option value="penuh">Penuh (1 hr)</option>
                <option value="pagi">½ Pagi (0.5 hr)</option>
                <option value="siang">½ Siang (0.5 hr)</option>
            </select>
            <button type="button" class="btn btn-danger btn-icon remove-tahunan" title="Hapus">
                <i class="bi bi-trash"></i>
            </button>
        `;
        container.appendChild(div);
        div.querySelector('.tanggal-tahunan-input').addEventListener('change', updateJumlahTahunan);
        div.querySelector('.sesi-tahunan-select').addEventListener('change', updateJumlahTahunan);
        div.querySelector('.remove-tahunan').addEventListener('click', function () {
            div.remove();
            updateJumlahTahunan();
        });
        updateJumlahTahunan();
    });

    document.querySelectorAll('.remove-tahunan').forEach(btn => {
        btn.addEventListener('click', function () {
            this.closest('.tanggal-tahunan-row').remove();
            updateJumlahTahunan();
        });
    });

    // Listener untuk row pertama yang sudah ada di DOM
    document.querySelectorAll('.tanggal-tahunan-input').forEach(input => {
        input.addEventListener('change', updateJumlahTahunan);
    });
    document.querySelectorAll('.sesi-tahunan-select').forEach(select => {
        select.addEventListener('change', updateJumlahTahunan);
    });
</script>
@endpush

@endsection