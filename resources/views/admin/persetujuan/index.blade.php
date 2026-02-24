@extends('layouts.app')

@section('title', 'Persetujuan Cuti Tambahan')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clipboard-check"></i> Persetujuan Cuti Tambahan</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <ul class="nav nav-tabs mb-3" id="statusTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button">
                <i class="bi bi-list"></i> Semua
                <span class="badge bg-secondary ms-1">{{ $permohonan->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="menunggu-tab" data-bs-toggle="tab" data-bs-target="#menunggu" type="button">
                <i class="bi bi-clock"></i> Menunggu
                <span class="badge bg-warning ms-1">{{ $permohonan->where('status', 'menunggu')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="disetujui-tab" data-bs-toggle="tab" data-bs-target="#disetujui" type="button">
                <i class="bi bi-check-circle"></i> Disetujui
                <span class="badge bg-success ms-1">{{ $permohonan->where('status', 'disetujui')->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak" type="button">
                <i class="bi bi-x-circle"></i> Ditolak
                <span class="badge bg-danger ms-1">{{ $permohonan->where('status', 'ditolak')->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="statusTabContent">
        <!-- Semua Permohonan -->
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            @include('admin.persetujuan.partials.table', ['data' => $permohonan])
        </div>

        <!-- Menunggu -->
        <div class="tab-pane fade" id="menunggu" role="tabpanel">
            @include('admin.persetujuan.partials.table', ['data' => $permohonan->where('status', 'menunggu')])
        </div>

        <!-- Disetujui -->
        <div class="tab-pane fade" id="disetujui" role="tabpanel">
            @include('admin.persetujuan.partials.table', ['data' => $permohonan->where('status', 'disetujui')])
        </div>

        <!-- Ditolak -->
        <div class="tab-pane fade" id="ditolak" role="tabpanel">
            @include('admin.persetujuan.partials.table', ['data' => $permohonan->where('status', 'ditolak')])
        </div>
    </div>
</div>
@endsection