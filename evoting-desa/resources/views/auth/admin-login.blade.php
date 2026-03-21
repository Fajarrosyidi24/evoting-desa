<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — E-Voting Desa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            min-height: 100vh;
            background: #0f0f0f;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(220,38,38,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -200px;
            right: -200px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(153,27,27,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        .card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(20px);
        }

        .input-field {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 13px 16px;
            font-size: 14px;
            color: white;
            outline: none;
            transition: all 0.2s;
        }

        .input-field::placeholder { color: rgba(255,255,255,0.25); }

        .input-field:focus {
            border-color: rgba(220,38,38,0.6);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(220,38,38,0.4);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), transparent);
            border-radius: inherit;
        }

        .error-box {
            background: rgba(220,38,38,0.1);
            border: 1px solid rgba(220,38,38,0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: #FCA5A5;
        }

        .success-box {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.3);
            border-radius: 12px;
            padding: 12px 16px;
            color: #86EFAC;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }
        .d4 { animation-delay: 0.20s; opacity: 0; }
        .d5 { animation-delay: 0.25s; opacity: 0; }

        label { color: rgba(255,255,255,0.6); font-size: 13px; font-weight: 500; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(220,38,38,0.15);
            border: 1px solid rgba(220,38,38,0.3);
            color: #FCA5A5;
            font-size: 11px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.05em;
        }

        .divider {
            height: 1px;
            background: rgba(255,255,255,0.06);
            margin: 24px 0;
        }
    </style>
</head>
<body>

<div class="grid-bg"></div>

<div class="card">

    {{-- Header --}}
    <div class="fade-up d1 mb-8">
        <div class="flex items-center justify-between mb-6">
            {{-- Logo --}}
            <div class="flex items-center gap-2.5">
                <div style="width:32px;height:20px;border-radius:4px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 2px 8px rgba(0,0,0,0.4)">
                    <div style="flex:1;background:#DC2626;"></div>
                    <div style="flex:1;background:#ffffff;"></div>
                </div>
                <span class="text-white font-bold text-sm">E-Voting Desa</span>
            </div>
            <span class="badge">
                <span style="width:6px;height:6px;background:#EF4444;border-radius:50%;display:inline-block;"></span>
                ADMIN
            </span>
        </div>

        <h2 class="text-2xl font-extrabold text-white mb-1.5">Panel Panitia</h2>
        <p class="text-sm" style="color:rgba(255,255,255,0.4)">
            Login untuk mengelola kandidat, pemilih, dan voting
        </p>
    </div>

    {{-- Alerts --}}
    @if ($errors->any())
        <div class="error-box mb-5 fade-up d1">
            @foreach ($errors->all() as $error)
                <p class="text-sm flex items-center gap-2">
                    <span>⚠</span> {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <div class="success-box mb-5 fade-up d1">
            <p class="text-sm">{{ session('status') }}</p>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="mb-4 fade-up d2">
            <label class="block mb-2">Email</label>
            <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="admin@desa.id"
                class="input-field"
                autofocus>
        </div>

        <div class="mb-6 fade-up d3">
            <label class="block mb-2">Password</label>
            <input
                type="password"
                name="password"
                placeholder="••••••••••"
                class="input-field">
        </div>

        <div class="fade-up d4">
            <button type="submit" class="btn-login">
                Masuk ke Panel Admin
            </button>
        </div>
    </form>

    <div class="divider"></div>

    {{-- Footer links --}}
    <div class="flex justify-between items-center fade-up d5">
        <a href="{{ route('publik.hasil') }}"
            class="text-xs hover:text-red-400 transition"
            style="color:rgba(255,255,255,0.3)">
            ← Halaman publik
        </a>
        <a href="{{ route('pemilih.login') }}"
            class="text-xs hover:text-white transition"
            style="color:rgba(255,255,255,0.3)">
            Login pemilih
        </a>
    </div>

    {{-- Security note --}}
    <div class="mt-5 fade-up d5" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:12px 14px;">
        <p class="text-xs" style="color:rgba(255,255,255,0.25);">
            🔐 Akses terbatas untuk panitia resmi. Semua aktivitas admin dicatat dalam sistem.
        </p>
    </div>

</div>

</body>
</html>