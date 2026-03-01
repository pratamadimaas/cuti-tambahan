@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil')
@section('page-subtitle', 'Informasi Akun')

@section('content')
<div class="row g-4">

    {{-- Info Akun --}}
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#3b5bdb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;margin:0 auto 12px;">
                        {{ strtoupper(substr($user->username, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $pegawai->nama ?? $user->username }}</h5>
                    <span class="badge bg-primary-subtle text-primary rounded-pill px-3" style="font-size:12px;">
                        @if($user->isAdmin()) Administrator
                        @elseif($user->role === 'sekre') Sekretariat
                        @else Pegawai
                        @endif
                    </span>
                </div>

                <hr>

                <table class="w-100" style="font-size:13.5px;">
                    <tr>
                        <td class="text-muted py-2" style="width:40%">Username</td>
                        <td class="fw-600">{{ $user->username }}</td>
                    </tr>
                    @if($pegawai)
                    <tr>
                        <td class="text-muted py-2">NIP</td>
                        <td class="fw-600 font-mono">{{ $pegawai->nip }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-2">Jabatan</td>
                        <td class="fw-600">{{ $pegawai->jabatan }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-2">Unit Kerja</td>
                        <td class="fw-600">{{ $pegawai->unit_kerja }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted py-2">Pangkat/Gol</td>
                        <td class="fw-600">{{ $pegawai->pangkat_gol }}</td>
                    </tr>
                    @if($pegawai->seksi)
                    <tr>
                        <td class="text-muted py-2">Seksi</td>
                        <td class="fw-600">{{ $pegawai->seksi->nama_seksi }}</td>
                    </tr>
                    @endif
                    @if($pegawai->atasan)
                    <tr>
                        <td class="text-muted py-2">Atasan</td>
                        <td class="fw-600">{{ $pegawai->atasan->nama }}</td>
                    </tr>
                    @endif
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Ubah Password --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-1"><i class="bi bi-shield-lock me-2 text-primary"></i>Ubah Password</h6>
                <p class="text-muted mb-4" style="font-size:13px;">Pastikan password baru minimal 6 karakter.</p>

                <form action="{{ route('profil.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13.5px;">Password Lama</label>
                        <div class="input-group">
                            <input type="password" name="password_lama" id="passLama"
                                   class="form-control @error('password_lama') is-invalid @enderror"
                                   placeholder="Masukkan password lama" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passLama', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_lama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13.5px;">Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru" id="passBaru"
                                   class="form-control @error('password_baru') is-invalid @enderror"
                                   placeholder="Minimal 6 karakter" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passBaru', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_baru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:13.5px;">Konfirmasi Password Baru</label>
                        <div class="input-group">
                            <input type="password" name="password_baru_confirmation" id="passKonfirm"
                                   class="form-control"
                                   placeholder="Ulangi password baru" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePass('passKonfirm', this)">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endpush