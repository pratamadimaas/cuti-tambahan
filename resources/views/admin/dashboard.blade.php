@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')


@section('content')

<div class="page-header">
    <h4>Dashboard Kepegawaian</h4>
    <p>Selamat datang kembali, ringkasan data hari ini.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-value">{{ $totalPegawai }}</div>
                <div class="stat-label">Total Pegawai</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-file-earmark-text"></i></div>
            <div>
                <div class="stat-value">{{ $totalPermohonan }}</div>
                <div class="stat-label">Total Permohonan</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon amber"><i class="bi bi-clock-history"></i></div>
            <div>
                <div class="stat-value">{{ $permohonanMenunggu }}</div>
                <div class="stat-label">Menunggu</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div>
                <div class="stat-value">{{ $permohonanDisetujui }}</div>
                <div class="stat-label">Disetujui</div>
            </div>
        </div>
    </div>
</div>

{{-- Chart + Quick Menu --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-bar-chart me-1 text-primary"></i> Statistik Status</span>
            </div>
            <div class="card-body pt-0">
                <canvas id="statusChart" height="220"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header-clean mb-3">
                <span class="card-title-sm"><i class="bi bi-lightning me-1 text-primary"></i> Menu Cepat</span>
            </div>
            <div class="card-body pt-0 d-flex flex-column gap-2">
                <a href="{{ route('admin.pegawai.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-person-plus"></i> Tambah Pegawai Baru
                </a>
                <a href="{{ route('admin.persetujuan.index') }}" class="btn d-flex align-items-center gap-2"
                   style="background:#fffbeb; color:#92400e; border:1px solid #fde68a; font-weight:600; border-radius:9px;">
                    <i class="bi bi-check-circle"></i>
                    Persetujuan Cuti
                    @if($permohonanMenunggu > 0)
                    <span class="badge ms-auto" style="background:#f59f00; color:#fff;">{{ $permohonanMenunggu }}</span>
                    @endif
                </a>
                
                {{-- UPDATED: 2 Menu Rekap Terpisah --}}
                <a href="{{ route('admin.rekap.sisa-cuti') }}" class="btn d-flex align-items-center gap-2"
                   style="background:#ecfdf5; color:#065f46; border:1px solid #a7f3d0; font-weight:600; border-radius:9px;">
                    <i class="bi bi-pie-chart"></i> Rekap Sisa Cuti
                </a>
                
                <a href="{{ route('admin.kepala-kantor.index') }}" class="btn d-flex align-items-center gap-2"
                   style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0; font-weight:600; border-radius:9px;">
                    <i class="bi bi-gear"></i> Pengaturan Kepala Kantor
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Info Box --}}
<div class="card" style="border-left: 4px solid #f59f00;">
    <div class="card-body d-flex gap-3" style="padding:18px 22px;">
        <div style="color:#f59f00; font-size:22px; flex-shrink:0; padding-top:2px;">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div>
            <div style="font-size:13.5px; font-weight:700; color:#92400e; margin-bottom:8px;">Informasi Sistem</div>
            <ul class="mb-0" style="font-size:13px; color:#78350f; padding-left:18px; line-height:1.8;">
                <li><strong>Sistem mencatat CUTI TAMBAHAN dan TAHUNAN</strong></li>
                <li>Saat permohonan disetujui, sisa cuti tahunan tambahan pegawai akan otomatis berkurang</li>
                <li>Permohonan yang sudah disetujui hanya dapat dibatalkan oleh admin</li>
                <li>Semua dokumen di-generate dalam format <strong>Microsoft Word (.docx)</strong></li>
            </ul>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('statusChart');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Menunggu', 'Disetujui', 'Ditolak'],
        datasets: [{
            data: [{{ $permohonanMenunggu }}, {{ $permohonanDisetujui }}, {{ $permohonanDitolak }}],
            backgroundColor: ['#f59f00', '#0ca678', '#f03e3e'],
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: { family: 'Plus Jakarta Sans', size: 12 },
                    boxWidth: 10,
                    boxHeight: 10,
                    borderRadius: 3,
                    padding: 16,
                }
            }
        }
    }
});
</script>
@endpush