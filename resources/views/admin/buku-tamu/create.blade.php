@extends('layouts.app')

@section('title', 'Input Buku Tamu')
@section('page-title', 'Buku Tamu')
@section('page-subtitle', 'Input Kunjungan')

@push('styles')
<style>
    #video-container { position: relative; }
    #videoEl { width: 100%; border-radius: 10px; background: #000; max-height: 300px; object-fit: cover; }
    #canvasEl { display: none; }
    #preview-img { width: 100%; border-radius: 10px; display: none; max-height: 300px; object-fit: cover; }
    .camera-btn { font-size: 13px; font-weight: 600; border-radius: 9px; padding: 8px 18px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h4>Input Buku Tamu</h4>
    <p>Catat data kunjungan satker ke KPPN</p>
</div>

<div class="card p-4" style="max-width:680px;">
    <form action="{{ route('admin.buku-tamu.store') }}" method="POST" id="formBukuTamu">
        @csrf

        {{-- FOTO KAMERA --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Foto Tamu <span class="text-danger">*</span></label>
            <div id="video-container" class="mb-2">
                <video id="videoEl" autoplay playsinline></video>
                <canvas id="canvasEl"></canvas>
                <img id="preview-img" src="" alt="Preview Foto">
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-secondary camera-btn" id="btnStartCam">
                    <i class="bi bi-camera-fill me-1"></i> Buka Kamera
                </button>
                <button type="button" class="btn btn-primary camera-btn d-none" id="btnCapture">
                    <i class="bi bi-camera me-1"></i> Ambil Foto
                </button>
                <button type="button" class="btn btn-outline-primary camera-btn d-none" id="btnSwitchCam">
                    <i class="bi bi-arrow-repeat me-1"></i> Ganti Kamera
                </button>
                <button type="button" class="btn btn-outline-secondary camera-btn d-none" id="btnRetake">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Ulangi
                </button>
            </div>
            <input type="hidden" name="foto_base64" id="foto_base64">
            @error('foto_base64')
                <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- NAMA SATKER --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Satker <span class="text-danger">*</span></label>
            <input type="text" name="nama_satker" class="form-control @error('nama_satker') is-invalid @enderror"
                   value="{{ old('nama_satker') }}" placeholder="Contoh: Dinas Keuangan Kab. XYZ" required>
            @error('nama_satker')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- NAMA TAMU --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Tamu <span class="text-danger">*</span></label>
            <input type="text" name="nama_tamu" class="form-control @error('nama_tamu') is-invalid @enderror"
                   value="{{ old('nama_tamu') }}" placeholder="Nama lengkap tamu" required>
            @error('nama_tamu')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- BERTEMU PEGAWAI --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Bertemu Pegawai KPPN <span class="text-danger">*</span></label>
            <select name="pegawai_kppn_id" class="form-select @error('pegawai_kppn_id') is-invalid @enderror" required>
                <option value="">— Pilih Pegawai —</option>
                @foreach($pegawaiList as $p)
                    <option value="{{ $p->id }}" {{ old('pegawai_kppn_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama }} — {{ $p->jabatan }}
                    </option>
                @endforeach
            </select>
            @error('pegawai_kppn_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- KANTOR --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Lokasi / Kantor <span class="text-danger">*</span></label>
            <select name="kantor" class="form-select @error('kantor') is-invalid @enderror" required>
                <option value="">— Pilih Lokasi —</option>
                @foreach($kantorList as $k)
                    <option value="{{ $k }}" {{ old('kantor') == $k ? 'selected' : '' }}>{{ $k }}</option>
                @endforeach
            </select>
            @error('kantor')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- KETERANGAN --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Keterangan Konsultasi</label>
            <textarea name="keterangan" class="form-control" rows="3"
                      placeholder="Keperluan / catatan tambahan (opsional)">{{ old('keterangan') }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary" id="btnSimpan">
                <i class="bi bi-save me-1"></i> Simpan
            </button>
            <a href="{{ route('admin.buku-tamu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function() {
    let stream = null;
    let facingMode = 'user';
    const videoEl    = document.getElementById('videoEl');
    const canvasEl   = document.getElementById('canvasEl');
    const previewImg = document.getElementById('preview-img');
    const fotoInput  = document.getElementById('foto_base64');
    const btnStart   = document.getElementById('btnStartCam');
    const btnCapture = document.getElementById('btnCapture');
    const btnRetake  = document.getElementById('btnRetake');
    const btnSwitch  = document.getElementById('btnSwitchCam');

    async function startCamera() {
        if (stream) stream.getTracks().forEach(t => t.stop());
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode }, audio: false });
            videoEl.srcObject = stream;
            videoEl.style.display = 'block';
            previewImg.style.display = 'none';
            btnStart.classList.add('d-none');
            btnCapture.classList.remove('d-none');
            btnSwitch.classList.remove('d-none');
        } catch (e) {
            alert('Tidak dapat mengakses kamera: ' + e.message);
        }
    }

    btnStart.addEventListener('click', () => startCamera());

    btnSwitch.addEventListener('click', () => {
        facingMode = facingMode === 'user' ? 'environment' : 'user';
        startCamera();
    });

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
        btnSwitch.classList.add('d-none');
        btnRetake.classList.remove('d-none');
    });

    btnRetake.addEventListener('click', () => {
        fotoInput.value = '';
        previewImg.style.display = 'none';
        btnRetake.classList.add('d-none');
        btnSwitch.classList.add('d-none');
        btnStart.classList.remove('d-none');
    });

    document.getElementById('formBukuTamu').addEventListener('submit', function(e) {
        if (!fotoInput.value) {
            e.preventDefault();
            alert('Foto tamu wajib diambil sebelum menyimpan!');
        }
    });
})();
</script>
@endpush