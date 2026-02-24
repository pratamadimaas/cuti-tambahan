@extends('layouts.app')

@section('title', 'Edit Buku Tamu')
@section('page-title', 'Buku Tamu')
@section('page-subtitle', 'Edit Data Kunjungan')

@push('styles')
<style>
    #videoEl { width: 100%; border-radius: 10px; background: #000; max-height: 300px; object-fit: cover; }
    #canvasEl { display: none; }
    #preview-img { width: 100%; border-radius: 10px; max-height: 300px; object-fit: cover; }
    .camera-btn { font-size: 13px; font-weight: 600; border-radius: 9px; padding: 8px 18px; }
    .foto-current { width: 100%; max-height: 300px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); }
</style>
@endpush

@section('content')
<div class="page-header">
    <h4>Edit Data Kunjungan</h4>
    <p>Perbarui data kunjungan satker ke KPPN</p>
</div>

<div class="card p-4" style="max-width:680px;">
    <form action="{{ route('admin.buku-tamu.update', $bukuTamu->id) }}" method="POST" id="formBukuTamu">
        @csrf
        @method('PUT')

        {{-- FOTO --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Foto Tamu</label>

            {{-- Foto saat ini --}}
            <div id="foto-current-wrapper" class="mb-2">
                <img id="foto-current"
                     src="{{ Storage::url($bukuTamu->foto_path) }}"
                     alt="Foto Tamu"
                     class="foto-current">
                <div class="mt-2">
                    <small class="text-muted">Foto saat ini. Klik tombol di bawah jika ingin menggantinya.</small>
                </div>
            </div>

            {{-- Area kamera (tersembunyi awal) --}}
            <div id="camera-wrapper" style="display:none;">
                <video id="videoEl" autoplay playsinline class="mb-2"></video>
                <canvas id="canvasEl"></canvas>
                <img id="preview-img" src="" alt="Preview" style="display:none;" class="mb-2">
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-outline-primary camera-btn" id="btnGantiFoto">
                    <i class="bi bi-camera-fill me-1"></i> Ganti Foto
                </button>
                <button type="button" class="btn btn-primary camera-btn d-none" id="btnCapture">
                    <i class="bi bi-camera me-1"></i> Ambil Foto
                </button>
                <button type="button" class="btn btn-outline-secondary camera-btn d-none" id="btnRetake">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Ulangi
                </button>
                <button type="button" class="btn btn-outline-danger camera-btn d-none" id="btnBatalGanti">
                    <i class="bi bi-x me-1"></i> Batal Ganti
                </button>
            </div>

            {{-- Jika foto baru diambil, isi hidden ini; jika tidak, kosong = pakai foto lama --}}
            <input type="hidden" name="foto_base64" id="foto_base64" value="">

            @error('foto_base64')
                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- NAMA SATKER --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Satker <span class="text-danger">*</span></label>
            <input type="text"
                   name="nama_satker"
                   class="form-control @error('nama_satker') is-invalid @enderror"
                   value="{{ old('nama_satker', $bukuTamu->nama_satker) }}"
                   placeholder="Contoh: Dinas Keuangan Kab. XYZ"
                   required>
            @error('nama_satker')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- NAMA TAMU --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
            <input type="text"
                   name="nama_tamu"
                   class="form-control @error('nama_tamu') is-invalid @enderror"
                   value="{{ old('nama_tamu', $bukuTamu->nama_tamu) }}"
                   placeholder="Nama lengkap tamu"
                   required>
            @error('nama_tamu')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- BERTEMU PEGAWAI --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Bertemu Pegawai KPPN <span class="text-danger">*</span></label>
            <select name="pegawai_kppn_id"
                    class="form-select @error('pegawai_kppn_id') is-invalid @enderror"
                    required>
                <option value="">— Pilih Pegawai —</option>
                @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}"
                        {{ old('pegawai_kppn_id', $bukuTamu->pegawai_kppn_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} — {{ $p->jabatan }}
                    </option>
                @endforeach
            </select>
            @error('pegawai_kppn_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- KANTOR --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Lokasi / Kantor <span class="text-danger">*</span></label>
            <select name="kantor"
                    class="form-select @error('kantor') is-invalid @enderror"
                    required>
                <option value="">— Pilih Lokasi —</option>
                @foreach($kantorList as $k)
                    <option value="{{ $k }}"
                        {{ old('kantor', $bukuTamu->kantor) == $k ? 'selected' : '' }}>
                        {{ $k }}
                    </option>
                @endforeach
            </select>
            @error('kantor')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- WAKTU KUNJUNGAN (read-only) --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Waktu Kunjungan</label>
            <input type="text"
                   class="form-control bg-light"
                   value="{{ \Carbon\Carbon::parse($bukuTamu->waktu_kunjungan)->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WITA"
                   readonly>
            <small class="text-muted">Waktu kunjungan tidak dapat diubah.</small>
        </div>

        {{-- KETERANGAN --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Keterangan Konsultasi</label>
            <textarea name="keterangan"
                      class="form-control"
                      rows="3"
                      placeholder="Keperluan / catatan tambahan (opsional)">{{ old('keterangan', $bukuTamu->keterangan) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.buku-tamu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    let stream      = null;
    let modeGanti   = false;

    const videoEl       = document.getElementById('videoEl');
    const canvasEl      = document.getElementById('canvasEl');
    const previewImg    = document.getElementById('preview-img');
    const fotoInput     = document.getElementById('foto_base64');
    const fotoCurrent   = document.getElementById('foto-current-wrapper');
    const cameraWrapper = document.getElementById('camera-wrapper');

    const btnGanti      = document.getElementById('btnGantiFoto');
    const btnCapture    = document.getElementById('btnCapture');
    const btnRetake     = document.getElementById('btnRetake');
    const btnBatal      = document.getElementById('btnBatalGanti');

    // Klik "Ganti Foto" → buka kamera
    btnGanti.addEventListener('click', async () => {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
            videoEl.srcObject = stream;
            videoEl.style.display  = 'block';
            previewImg.style.display = 'none';
            cameraWrapper.style.display = 'block';
            fotoCurrent.style.display   = 'none';

            btnGanti.classList.add('d-none');
            btnCapture.classList.remove('d-none');
            btnBatal.classList.remove('d-none');
            modeGanti = true;
        } catch (e) {
            alert('Tidak dapat mengakses kamera: ' + e.message);
        }
    });

    // Ambil foto
    btnCapture.addEventListener('click', () => {
        canvasEl.width  = videoEl.videoWidth;
        canvasEl.height = videoEl.videoHeight;
        canvasEl.getContext('2d').drawImage(videoEl, 0, 0);
        const dataUrl = canvasEl.toDataURL('image/jpeg', 0.85);
        fotoInput.value = dataUrl;

        previewImg.src = dataUrl;
        previewImg.style.display = 'block';
        videoEl.style.display    = 'none';

        if (stream) stream.getTracks().forEach(t => t.stop());

        btnCapture.classList.add('d-none');
        btnRetake.classList.remove('d-none');
    });

    // Ulangi → buka kamera lagi
    btnRetake.addEventListener('click', async () => {
        fotoInput.value = '';
        previewImg.style.display = 'none';
        btnRetake.classList.add('d-none');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
            videoEl.srcObject = stream;
            videoEl.style.display = 'block';
            btnCapture.classList.remove('d-none');
        } catch (e) {
            alert('Tidak dapat mengakses kamera: ' + e.message);
        }
    });

    // Batal ganti → kembali ke foto lama
    btnBatal.addEventListener('click', () => {
        fotoInput.value = '';
        if (stream) stream.getTracks().forEach(t => t.stop());

        cameraWrapper.style.display = 'none';
        fotoCurrent.style.display   = 'block';
        previewImg.style.display    = 'none';
        videoEl.style.display       = 'none';

        btnGanti.classList.remove('d-none');
        btnCapture.classList.add('d-none');
        btnRetake.classList.add('d-none');
        btnBatal.classList.add('d-none');
        modeGanti = false;
    });
})();
</script>
@endpush