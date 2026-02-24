<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Kepegawaian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f1b3d;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* Subtle background blobs */
        body::before, body::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            pointer-events: none;
        }
        body::before {
            width: 500px;
            height: 500px;
            background: #3b5bdb;
            top: -100px;
            right: -100px;
        }
        body::after {
            width: 400px;
            height: 400px;
            background: #1c7ed6;
            bottom: -100px;
            left: -80px;
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
            padding: 16px;
            position: relative;
            z-index: 1;
        }

        /* Brand above card */
        .login-brand {
            text-align: center;
            margin-bottom: 24px;
        }
        .login-brand-icon {
            width: 52px;
            height: 52px;
            background: #3b5bdb;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #fff;
            margin-bottom: 12px;
            box-shadow: 0 8px 24px rgba(59,91,219,0.4);
        }
        .login-brand h1 {
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 4px;
            letter-spacing: 0.01em;
        }
        .login-brand p {
            font-size: 12.5px;
            color: #a8b4d0;
            margin: 0;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Card */
        .login-card {
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
            padding: 32px 36px;
        }

        .login-card h2 {
            font-size: 17px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 6px;
        }
        .login-card .subtitle {
            font-size: 13px;
            color: #6b7a99;
            margin-bottom: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 6px;
        }

        .input-group-text {
            background: #f0f2f8;
            border: 1px solid #e4e8f0;
            border-right: none;
            color: #6b7a99;
        }

        .form-control {
            border: 1px solid #e4e8f0;
            border-left: none;
            font-size: 13.5px;
            color: #1a1f36;
            padding: 10px 14px;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            border-color: #3b5bdb;
            box-shadow: 0 0 0 3px rgba(59,91,219,0.1);
        }
        .input-group:focus-within .input-group-text {
            border-color: #3b5bdb;
        }

        .form-check-input:checked {
            background-color: #3b5bdb;
            border-color: #3b5bdb;
        }
        .form-check-label {
            font-size: 13px;
            color: #6b7a99;
        }

        .btn-login {
            background: #3b5bdb;
            border: none;
            color: #fff;
            font-weight: 700;
            font-size: 14px;
            border-radius: 10px;
            padding: 11px;
            width: 100%;
            transition: background .2s, transform .15s, box-shadow .2s;
            box-shadow: 0 4px 14px rgba(59,91,219,0.35);
        }
        .btn-login:hover {
            background: #2f4ac0;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59,91,219,0.45);
        }
        .btn-login:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 22px 0;
            color: #c8cfe0;
            font-size: 12px;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e4e8f0;
        }

        .hint-box {
            background: #f6f8ff;
            border: 1px solid #e4e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 12px;
            color: #6b7a99;
            line-height: 1.8;
        }
        .hint-box strong {
            color: #1a1f36;
        }
        .hint-box code {
            background: #eef2ff;
            color: #3b5bdb;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 11.5px;
        }

        .alert {
            font-size: 13px;
            border-radius: 10px;
            border: none;
            padding: 10px 14px;
        }
        .alert-danger  { background: #fef2f2; color: #991b1b; }
        .alert-success { background: #ecfdf5; color: #065f46; }
    </style>
</head>
<body>
    <div class="login-wrap">

        {{-- Brand --}}
        <div class="login-brand">
            <div class="login-brand-icon"><i class="bi bi-building-fill"></i></div>
            <h1>SMART-KPPN Kolaka</h1>
            <p>Sistem Monitoring Administrasi & Registrasi Tamu</p>
        </div>

        {{-- Card --}}
        <div class="login-card">
            <h2>Welcome</h2>

            @if (session('error'))
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Username / NIP</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text"
                               class="form-control @error('username') is-invalid @enderror"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Masukkan username atau NIP"
                               required autofocus>
                    </div>
                    @error('username')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Masukkan password"
                               required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>