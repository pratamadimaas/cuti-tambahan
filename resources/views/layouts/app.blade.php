<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Kepegawaian')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary: #3b5bdb;
            --primary-light: #eef2ff;
            --primary-dark: #2f4ac0;
            --accent: #f03e3e;
            --accent-soft: #fff0f0;
            --success: #0ca678;
            --warning: #f59f00;
            --danger: #f03e3e;
            --bg: #f0f2f8;
            --sidebar-bg: #0f1b3d;
            --sidebar-text: #a8b4d0;
            --sidebar-active: #3b5bdb;
            --card-bg: #ffffff;
            --text: #1a1f36;
            --text-muted: #6b7a99;
            --border: #e4e8f0;
            --shadow: 0 2px 16px rgba(15,27,61,0.08);
            --shadow-md: 0 8px 32px rgba(15,27,61,0.12);
            --radius: 14px;
            --radius-sm: 8px;
            --sidebar-width: 240px;
            --topbar-height: 60px;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }

        /* ──────────── SIDEBAR ──────────── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1030;
            transition: transform .3s ease;
            overflow-y: auto;
            scrollbar-width: none;
        }
        .sidebar::-webkit-scrollbar { display: none; }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .sidebar-brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar-brand-text {
            font-size: 13.5px;
            font-weight: 700;
            color: #fff;
            line-height: 1.25;
            letter-spacing: 0.01em;
        }
        .sidebar-brand-sub {
            font-size: 10px;
            color: var(--sidebar-text);
            font-weight: 500;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .sidebar-section {
            padding: 20px 14px 6px;
        }
        .sidebar-section-label {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: rgba(168,180,208,0.45);
            padding: 0 8px;
            margin-bottom: 4px;
        }

        .sidebar-nav .nav-item { margin-bottom: 2px; }

        .sidebar-nav .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 9px;
            color: var(--sidebar-text);
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            transition: all .18s ease;
            position: relative;
        }
        .sidebar-nav .nav-link .nav-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
            background: rgba(255,255,255,0.04);
            transition: all .18s ease;
        }
        .sidebar-nav .nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,0.06);
        }
        .sidebar-nav .nav-link:hover .nav-icon {
            background: rgba(59,91,219,0.25);
            color: #7e9dff;
        }
        .sidebar-nav .nav-link.active {
            color: #fff;
            background: var(--primary);
        }
        .sidebar-nav .nav-link.active .nav-icon {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .nav-badge {
            margin-left: auto;
            font-size: 10px;
            padding: 2px 7px;
            border-radius: 20px;
            background: rgba(59,91,219,0.35);
            color: #a0b4ff;
            font-weight: 600;
        }
        .nav-link.active .nav-badge {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 14px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
        }
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .user-name {
            font-size: 12.5px;
            font-weight: 600;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-role {
            font-size: 10.5px;
            color: var(--sidebar-text);
        }

        /* ──────────── TOPBAR ──────────── */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: rgba(240,242,248,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 28px;
            z-index: 1020;
            gap: 14px;
        }
        .topbar-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            flex: 1;
        }
        .topbar-title span {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 13px;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .topbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            border: 1px solid var(--border);
            background: var(--card-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 16px;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
            position: relative;
        }
        .topbar-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }
        .topbar-notif-dot {
            position: absolute;
            top: 6px;
            right: 7px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent);
            border: 2px solid var(--bg);
        }

        /* ──────────── MAIN CONTENT ──────────── */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            padding-top: var(--topbar-height);
            min-height: 100vh;
        }
        .main-content {
            padding: 28px;
            animation: fadeUp .3s ease;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ──────────── CARDS ──────────── */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            background: var(--card-bg);
            overflow: hidden;
        }
        .card-header-clean {
            padding: 18px 22px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-title-sm {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        /* ──────────── TABLES ──────────── */
        .table-modern {
            width: 100%;
            border-collapse: collapse;
            font-size: 13.5px;
        }
        .table-modern thead th {
            background: #f6f8ff;
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 12px 18px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }
        .table-modern tbody tr {
            border-bottom: 1px solid #f0f2f8;
            transition: background .15s;
        }
        .table-modern tbody tr:last-child { border-bottom: none; }
        .table-modern tbody tr:hover { background: #f7f9ff; }
        .table-modern tbody td {
            padding: 13px 18px;
            color: var(--text);
            vertical-align: middle;
        }

        /* ──────────── BADGES ──────────── */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .badge-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.7;
        }
        .badge-menunggu { background: #fff8e1; color: #e65100; }
        .badge-disetujui { background: #e8f5e9; color: #1b5e20; }
        .badge-ditolak { background: #ffebee; color: #b71c1c; }

        /* ──────────── ALERTS ──────────── */
        .alert-modern {
            border: none;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 500;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-modern.alert-success { background: #ecfdf5; color: #065f46; }
        .alert-modern.alert-danger  { background: #fef2f2; color: #991b1b; }

        /* ──────────── BUTTONS ──────────── */
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            font-size: 13.5px;
            border-radius: 9px;
            padding: 8px 18px;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            font-size: 14px;
        }

        /* ──────────── STAT CARD ──────────── */
        .stat-card {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: box-shadow .2s, transform .2s;
        }
        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stat-icon.blue  { background: #eef2ff; color: var(--primary); }
        .stat-icon.green { background: #ecfdf5; color: var(--success); }
        .stat-icon.red   { background: #fef2f2; color: var(--danger); }
        .stat-icon.amber { background: #fffbeb; color: var(--warning); }
        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--text);
            line-height: 1;
        }
        .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 2px;
        }

        /* ──────────── PAGE HEADER ──────────── */
        .page-header {
            margin-bottom: 24px;
        }
        .page-header h4 {
            font-size: 20px;
            font-weight: 800;
            color: var(--text);
            margin-bottom: 2px;
        }
        .page-header p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        /* ──────────── MOBILE TOGGLE ──────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1025;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .sidebar-overlay.open { display: block; }
            .topbar { left: 0; }
            .main-wrapper { margin-left: 0; }
        }

        /* ──────────── MONO FONT ──────────── */
        .font-mono {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon"><i class="bi bi-building-fill"></i></div>
        <div>
            <div class="sidebar-brand-text">SMART-KPPN Kolaka</div>
            <div class="sidebar-brand-sub">Sistem Monitoring Administrasi & Registrasi Tamu</div>
        </div>
    </div>

    @if(auth()->user()->isAdmin())
    {{-- SIDEBAR ADMIN --}}
    <div class="sidebar-section">
        <div class="sidebar-section-label">Menu Utama</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                    Dashboard
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Kepegawaian</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.pegawai.index') }}"
                   class="nav-link {{ request()->routeIs('admin.pegawai.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-people"></i></span>
                    Data Pegawai
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.seksi.index') }}"
                   class="nav-link {{ request()->routeIs('admin.seksi.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-diagram-3"></i></span>
                    Data Seksi
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('admin.persetujuan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.persetujuan.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-check-circle"></i></span>
                    Persetujuan Cuti
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Laporan</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.rekap.sisa-cuti') }}"
                   class="nav-link {{ request()->routeIs('admin.rekap.sisa-cuti') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-pie-chart"></i></span>
                    Rekap Sisa Cuti
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Tamu</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.buku-tamu.index') }}"
                   class="nav-link {{ request()->routeIs('admin.buku-tamu.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
                    Buku Tamu
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Pengaturan</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.kepala-kantor.index') }}"
                   class="nav-link {{ request()->routeIs('admin.kepala-kantor.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-gear"></i></span>
                    Kepala Kantor
                </a>
            </div>
        </nav>
    </div>

    @elseif(auth()->user()->role === 'sekre')
    {{-- SIDEBAR SEKRE --}}
    <div class="sidebar-section">
        <div class="sidebar-section-label">Menu</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.buku-tamu.index') }}"
                   class="nav-link {{ request()->routeIs('admin.buku-tamu.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
                    Buku Tamu
                </a>
            </div>
        </nav>
    </div>

    @else
    {{-- SIDEBAR PEGAWAI --}}
    <div class="sidebar-section">
        <div class="sidebar-section-label">Menu Utama</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
                    Dashboard
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Cuti Saya</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('pegawai.cuti-tambahan.index') }}"
                   class="nav-link {{ request()->routeIs('pegawai.cuti-tambahan.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-calendar-plus"></i></span>
                    Ajukan Cuti
                </a>
            </div>
        </nav>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-label">Tamu</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('admin.buku-tamu.index') }}"
                   class="nav-link {{ request()->routeIs('admin.buku-tamu.*') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-journal-text"></i></span>
                    Buku Tamu
                </a>
            </div>
        </nav>
    </div>

    @endif

    {{-- PROFIL — tampil untuk SEMUA user --}}
    <div class="sidebar-section">
        <div class="sidebar-section-label">Akun</div>
        <nav class="sidebar-nav">
            <div class="nav-item">
                <a href="{{ route('profil') }}"
                   class="nav-link {{ request()->routeIs('profil') ? 'active' : '' }}">
                    <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
                    Profil & Password
                </a>
            </div>
        </nav>
    </div>

    {{-- SIDEBAR FOOTER: user info + tombol logout --}}
    <div class="sidebar-footer">
        <div class="user-card mb-2">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
            </div>
            <div style="overflow:hidden;">
                <div class="user-name">
                    @if(auth()->user()->isAdmin())
                        {{ auth()->user()->username }}
                    @elseif(auth()->user()->role === 'sekre')
                        {{ auth()->user()->username }}
                    @else
                        {{ auth()->user()->pegawai->nama ?? auth()->user()->username }}
                    @endif
                </div>
                <div class="user-role">
                    @if(auth()->user()->isAdmin())
                        Administrator
                    @elseif(auth()->user()->role === 'sekre')
                        Sekretariat
                    @else
                        Pegawai
                    @endif
                </div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100"
                    style="font-size:13px; border-radius:9px; padding:9px; font-weight:600;">
                <i class="bi bi-box-arrow-right me-1"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- TOPBAR -->
<div class="topbar">
    <button class="topbar-btn d-md-none" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <div class="topbar-title">
        @yield('page-title', 'Dashboard')
        @hasSection('page-subtitle')
        <span>/ @yield('page-subtitle')</span>
        @endif
    </div>

    <div class="topbar-actions">
        @if(auth()->user()->isAdmin())
        <a href="{{ route('admin.persetujuan.index') }}" class="topbar-btn" title="Persetujuan">
            <i class="bi bi-bell"></i>
            <span class="topbar-notif-dot"></span>
        </a>
        <a href="{{ route('dashboard') }}" class="topbar-btn" title="Dashboard">
            <i class="bi bi-house"></i>
        </a>
        @elseif(auth()->user()->role === 'sekre')
        <a href="{{ route('admin.buku-tamu.index') }}" class="topbar-btn" title="Buku Tamu">
            <i class="bi bi-journal-text"></i>
        </a>
        @else
        <a href="{{ route('dashboard') }}" class="topbar-btn" title="Dashboard">
            <i class="bi bi-house"></i>
        </a>
        @endif
    </div>
</div>

<!-- MAIN -->
<div class="main-wrapper">
    <div class="main-content">

        @if (session('success'))
        <div class="alert alert-modern alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill fs-5"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:11px;"></button>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-modern alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" style="font-size:11px;"></button>
        </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('sidebarOverlay').classList.toggle('open');
    }
</script>
@stack('scripts')
</body>
</html>