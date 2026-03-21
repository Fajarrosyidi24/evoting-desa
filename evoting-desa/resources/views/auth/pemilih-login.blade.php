<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pemilih — E-Voting Desa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            min-height: 100vh;
            background: #FAFAFA;
            display: flex;
        }

        .left-panel {
            background: linear-gradient(160deg, #DC2626 0%, #991B1B 50%, #1a0a0a 100%);
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.06) 0%, transparent 70%);
            border-radius: 50%;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(220,38,38,0.4) 0%, transparent 70%);
            border-radius: 50%;
        }

        .bendera {
            display: flex;
            flex-direction: column;
            width: 56px;
            height: 36px;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .input-field {
            width: 100%;
            border: 1.5px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 15px;
            transition: all 0.2s;
            outline: none;
            background: white;
            color: #111827;
        }

        .input-field:focus {
            border-color: #DC2626;
            box-shadow: 0 0 0 3px rgba(220,38,38,0.08);
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
            background: linear-gradient(135deg, #B91C1C, #991B1B);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(220,38,38,0.3);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::after { left: 100%; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.1s;  opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }
        .d4 { animation-delay: 0.2s;  opacity: 0; }
        .d5 { animation-delay: 0.25s; opacity: 0; }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .feature-item:last-child { border-bottom: none; }

        .feature-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 16px;
        }

        .error-box {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            border-radius: 12px;
            padding: 12px 16px;
            color: #DC2626;
        }
    </style>
</head>
<body>

{{-- Left Panel --}}
<div class="left-panel hidden md:flex flex-col justify-between w-5/12 p-12 relative z-10">
    <div>
        {{-- Logo --}}
        <div class="flex items-center gap-3 mb-16">
            <div class="bendera">
                <div style="flex:1; background:#DC2626;"></div>
                <div style="flex:1; background:#ffffff;"></div>
            </div>
            <div>
                <p class="text-white font-bold text-lg leading-none">E-Voting Desa</p>
                <p class="text-red-200 text-xs leading-none mt-1">Berbasis Blockchain</p>
            </div>
        </div>

        {{-- Headline --}}
        <div class="mb-12">
            <h1 class="text-4xl font-extrabold text-white leading-tight mb-4">
                Suaramu<br>
                <span class="text-red-300">Menentukan</span><br>
                Masa Depan Desa
            </h1>
            <p class="text-red-200 text-sm leading-relaxed">
                Sistem voting elektronik yang transparan, aman, dan tercatat
                permanen di blockchain — tidak dapat dimanipulasi oleh siapapun.
            </p>
        </div>

        {{-- Features --}}
        <div>
            <div class="feature-item">
                <div class="feature-icon">🔒</div>
                <div>
                    <p class="text-white font-semibold text-sm">Aman & Terenkripsi</p>
                    <p class="text-red-300 text-xs mt-0.5">Identitas terlindungi, suara rahasia</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">⛓️</div>
                <div>
                    <p class="text-white font-semibold text-sm">Tercatat di Blockchain</p>
                    <p class="text-red-300 text-xs mt-0.5">Permanen, transparan, tidak bisa diubah</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon">✅</div>
                <div>
                    <p class="text-white font-semibold text-sm">Satu Orang Satu Suara</p>
                    <p class="text-red-300 text-xs mt-0.5">Sistem mencegah double voting otomatis</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom --}}
    <div>
        <p class="text-red-400 text-xs">
            © 2025 E-Voting Desa · Powered by Blockchain
        </p>
    </div>
</div>

{{-- Right Panel --}}
<div class="flex-1 flex items-center justify-center p-6 md:p-12">
    <div class="w-full max-w-md">

        {{-- Mobile logo --}}
        <div class="flex md:hidden items-center gap-3 mb-8">
            <div class="bendera">
                <div style="flex:1; background:#DC2626;"></div>
                <div style="flex:1; background:#ffffff; border:1px solid #e5e7eb;"></div>
            </div>
            <p class="font-bold text-gray-900">E-Voting Desa</p>
        </div>

        {{-- Form header --}}
        <div class="mb-8 fade-up d1">
            <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Masuk sebagai Pemilih</h2>
            <p class="text-gray-400 text-sm">
                Gunakan NIK dan tanggal lahir sesuai KTP untuk login
            </p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="error-box mb-6 fade-up d1">
                @foreach ($errors->all() as $error)
                    <p class="text-sm flex items-center gap-2">
                        <span>⚠</span> {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('pemilih.login') }}">
            @csrf

            <div class="mb-5 fade-up d2">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    NIK (Nomor Induk Kependudukan)
                </label>
                <input
                    type="text"
                    name="nik"
                    value="{{ old('nik') }}"
                    maxlength="16"
                    placeholder="Masukkan 16 digit NIK KTP"
                    class="input-field font-mono"
                    autofocus>
                <p class="text-xs text-gray-400 mt-1.5">Contoh: 3578xxxxxxxx0001</p>
            </div>

            <div class="mb-8 fade-up d3">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tanggal Lahir
                </label>
                <input
                    type="date"
                    name="tanggal_lahir"
                    value="{{ old('tanggal_lahir') }}"
                    class="input-field">
                <p class="text-xs text-gray-400 mt-1.5">Sesuai tanggal lahir di KTP</p>
            </div>

            <div class="fade-up d4">
                <button type="submit" class="btn-login">
                    Masuk & Mulai Voting
                </button>
            </div>
        </form>

        {{-- Divider --}}
        <div class="flex items-center gap-3 my-6 fade-up d5">
            <div class="flex-1 h-px bg-gray-200"></div>
            <span class="text-gray-400 text-xs">atau</span>
            <div class="flex-1 h-px bg-gray-200"></div>
        </div>

        {{-- Links --}}
        <div class="flex justify-between items-center fade-up d5">
            <a href="{{ route('publik.hasil') }}"
                class="text-sm text-gray-500 hover:text-red-600 transition font-medium">
                ← Lihat hasil voting
            </a>
            <a href="{{ route('admin.login') }}"
                class="text-sm text-gray-400 hover:text-gray-600 transition">
                Login panitia
            </a>
        </div>

        {{-- Info box --}}
        <div class="mt-8 bg-blue-50 border border-blue-100 rounded-xl p-4 fade-up d5">
            <p class="text-xs text-blue-700 font-semibold mb-1">ℹ Belum bisa login?</p>
            <p class="text-xs text-blue-600 leading-relaxed">
                Pastikan NIK kamu sudah terdaftar oleh panitia. Hubungi panitia desa
                jika mengalami kesulitan login.
            </p>
        </div>

    </div>
</div>

</body>
</html>