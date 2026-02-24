@extends('layouts.app')

@section('title', 'Rekap Sisa Cuti Pegawai')

@push('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        body { font-size: 12px; }
        .print-header { display: block !important; }
        h4 { font-size: 14px; }
    }
    .print-header { display: none; }
</style>
@endpush

@section('content')
<div class="container-fluid">

    {{-- Header print --}}
    <div class="print-header text-center mb-3">
        <h5 class="fw-bold">REKAP SISA CUTI PEGAWAI</h5>
        <p class="mb-0">Kantor Pelayanan Perbendaharaan Negara Kolaka</p>
        <p>Tahun {{ date('Y') }}</p>
        <hr>
    </div>

    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center no-print">
            <h4>Rekap Sisa Cuti Pegawai (Tahun {{ date('Y') }})</h4>
            <div class="d-flex gap-2">
                <button onclick="printRekap()" class="btn btn-secondary btn-sm">
                    <i class="bi bi-printer"></i> Print PDF
                </button>
                <a href="{{ route('admin.rekap.export-sisa-cuti') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-file-excel"></i> Export Excel
                </a>
            </div>
        </div>
        <div class="col-12 no-print mt-2">
            <small class="text-muted">Total: {{ $pegawai->count() }} pegawai</small>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-2" style="display:none!important" id="print-meta">
        <small>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</small>
    </div>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th rowspan="2" class="align-middle text-center">No</th>
                        <th rowspan="2" class="align-middle">NIP</th>
                        <th rowspan="2" class="align-middle">Nama Pegawai</th>
                        <th rowspan="2" class="align-middle">Jabatan</th>
                        <th colspan="3" class="text-center border-start">Cuti Tahunan</th>
                        <th colspan="3" class="text-center border-start">Cuti Tambahan</th>
                    </tr>
                    <tr>
                        <th class="text-center border-start" style="font-size:11px;">Kuota</th>
                        <th class="text-center" style="font-size:11px;">Terpakai</th>
                        <th class="text-center" style="font-size:11px;">Sisa</th>
                        <th class="text-center border-start" style="font-size:11px;">Kuota</th>
                        <th class="text-center" style="font-size:11px;">Terpakai</th>
                        <th class="text-center" style="font-size:11px;">Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pegawai as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="font-mono" style="font-size:12px;">{{ $p->nip ?? '-' }}</td>
                        <td>{{ $p->nama }}</td>
                        <td style="font-size:12.5px;">{{ $p->jabatan ?? '-' }}</td>

                        {{-- Cuti Tahunan --}}
                        <td class="text-center border-start">{{ $p->kuota_tahunan }}</td>
                        <td class="text-center">{{ $p->terpakai_tahunan }}</td>
                        <td class="text-center">
                            <span class="badge {{ $p->sisa_cuti_tahunan > 5 ? 'bg-success' : ($p->sisa_tahunan > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $p->sisa_cuti_tahunan }} hari
                            </span>
                        </td>

                        {{-- Cuti Tambahan --}}
                        <td class="text-center border-start">{{ $p->kuota_tambahan }}</td>
                        <td class="text-center">{{ $p->terpakai_tambahan }}</td>
                        <td class="text-center">
                            <span class="badge {{ $p->sisa_cuti_tambahan > 5 ? 'bg-success' : ($p->sisa_cuti_tambahan > 0 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                {{ $p->sisa_cuti_tambahan }} hari
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">Tidak ada data pegawai.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function printRekap() {
        document.getElementById('print-meta').style.display = 'flex';
        window.print();
        setTimeout(() => {
            document.getElementById('print-meta').style.display = 'none';
        }, 1000);
    }
</script>

<style>
    @media print {
        nav, .navbar, .sidebar, footer, .breadcrumb,
        .no-print { display: none !important; }
        .print-header { display: block !important; }
        #print-meta { display: flex !important; }
        .badge {
            border: 1px solid #333 !important;
            color: #000 !important;
            background: transparent !important;
            -webkit-print-color-adjust: exact;
        }
        table { page-break-inside: auto; }
        tr { page-break-inside: avoid; }
        @page {
            margin: 1.5cm;
            size: A4 landscape;
        }
        body { font-size: 10pt; }
        .container-fluid { padding: 0 !important; }
        .card { border: none !important; }
        .card-body { padding: 0 !important; }
    }
</style>
@endpush