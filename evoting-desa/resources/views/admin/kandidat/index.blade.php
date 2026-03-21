<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kandidat — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        body { background: #0a0a0a; color: white; min-height: 100vh; }

        .grid-bg {
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: rgba(255,255,255,0.02);
            border-right: 1px solid rgba(255,255,255,0.06);
            position: fixed;
            left: 0; top: 0; bottom: 0;
            z-index: 40;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            position: relative;
            z-index: 1;
        }

        .topbar {
            background: rgba(10,10,10,0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.85);
        }

        .nav-item.active {
            background: rgba(220,38,38,0.12);
            color: #FCA5A5;
            border: 1px solid rgba(220,38,38,0.2);
        }

        .nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .nav-item.active .nav-icon { background: rgba(220,38,38,0.2); }
        .nav-item:not(.active) .nav-icon { background: rgba(255,255,255,0.05); }

        .nav-section {
            padding: 6px 20px 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.2);
            text-transform: uppercase;
            margin-top: 8px;
        }

        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            overflow: hidden;
        }

        .input-field {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 14px;
            color: white;
            outline: none;
            transition: all 0.2s;
        }

        .input-field::placeholder { color: rgba(255,255,255,0.2); }

        .input-field:focus {
            border-color: rgba(220,38,38,0.5);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }

        textarea.input-field { resize: vertical; min-height: 80px; }

        label.field-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.45);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-primary {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 11px 20px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            justify-content: center;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            box-shadow: 0 4px 16px rgba(220,38,38,0.3);
            transform: translateY(-1px);
        }

        .btn-blockchain {
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.25);
            color: #93C5FD;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .btn-blockchain:hover {
            background: rgba(59,130,246,0.2);
            color: #BFDBFE;
        }

        .btn-hapus {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            color: #FCA5A5;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-hapus:hover {
            background: rgba(239,68,68,0.15);
            color: #FEE2E2;
        }

        .badge-blockchain {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-pending {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(234,179,8,0.1);
            border: 1px solid rgba(234,179,8,0.2);
            color: #FDE047;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .alert-success {
            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 14px;
            padding: 12px 16px;
            color: #86EFAC;
            font-size: 14px;
        }

        .alert-warning {
            background: rgba(234,179,8,0.08);
            border: 1px solid rgba(234,179,8,0.2);
            border-radius: 14px;
            padding: 12px 16px;
            color: #FDE047;
            font-size: 14px;
        }

        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 14px;
            padding: 12px 16px;
            color: #FCA5A5;
            font-size: 14px;
        }

        .kandidat-row {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: background 0.15s;
        }

        .kandidat-row:last-child { border-bottom: none; }
        .kandidat-row:hover { background: rgba(255,255,255,0.02); }

        .nomor-badge {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 800;
            flex-shrink: 0;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.6);
        }

        .nomor-badge.blockchain {
            background: rgba(220,38,38,0.15);
            color: #FCA5A5;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="grid-bg"></div>

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="px-6 mb-8">
        <div class="flex items-center gap-3">
            <div style="width:36px;height:22px;border-radius:5px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 2px 8px rgba(0,0,0,0.5);flex-shrink:0">
                <div style="flex:1;background:#DC2626;"></div>
                <div style="flex:1;background:#ffffff;"></div>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-none">E-Voting Desa</p>
                <p class="text-xs leading-none mt-1" style="color:rgba(255,255,255,0.3)">Panel Admin</p>
            </div>
        </div>
    </div>

    <div class="flex-1">
        <p class="nav-section">Menu Utama</p>
        <a href="{{ route('admin.dashboard') }}" class="nav-item">
            <div class="nav-icon">📊</div>Dashboard
        </a>
        <a href="{{ route('admin.voting.index') }}" class="nav-item">
            <div class="nav-icon">🗳️</div>Kelola Voting
        </a>
        <p class="nav-section">Data</p>
        <a href="{{ route('admin.kandidat.index') }}" class="nav-item active">
            <div class="nav-icon">🏆</div>Kandidat
        </a>
        <a href="{{ route('admin.pemilih.index') }}" class="nav-item">
            <div class="nav-icon">👥</div>Pemilih
        </a>
        <p class="nav-section">Publik</p>
        <a href="{{ route('publik.hasil') }}" target="_blank" class="nav-item">
            <div class="nav-icon">🌐</div>Halaman Publik
        </a>
        <a href="{{ route('publik.transaksi') }}" target="_blank" class="nav-item">
            <div class="nav-icon">⛓️</div>Transaksi
        </a>
    </div>

    <div class="px-4 pt-4" style="border-top:1px solid rgba(255,255,255,0.06)">
        <div class="flex items-center gap-3 p-3 rounded-xl" style="background:rgba(255,255,255,0.03)">
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                style="background:linear-gradient(135deg,#DC2626,#B91C1C)">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs truncate" style="color:rgba(255,255,255,0.35)">Administrator</p>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="text-xs hover:text-red-400 transition" style="color:rgba(255,255,255,0.25)">
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Main --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <h1 class="text-lg font-bold text-white">Kelola Kandidat</h1>
            <p class="text-xs" style="color:rgba(255,255,255,0.35)">Tambah & daftarkan kandidat ke blockchain</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
            class="text-sm px-4 py-2 rounded-lg transition font-medium"
            style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.5)"
            onmouseover="this.style.color='white'"
            onmouseout="this.style.color='rgba(255,255,255,0.5)'">
            ← Dashboard
        </a>
    </div>

    <div class="p-8">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert-success mb-5 fade-up d1 flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="alert-warning mb-5 fade-up d1 flex items-center gap-2">
                <span>⚠</span> {{ session('warning') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert-error mb-5 fade-up d1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2"><span>✗</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            {{-- Form tambah --}}
            <div class="md:col-span-2 fade-up d1">
                <div class="card p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#DC2626,#EF4444)"></div>
                        <h2 class="font-bold text-white">Tambah Kandidat</h2>
                    </div>

                    <form method="POST" action="{{ route('admin.kandidat.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="field-label">Nomor Urut</label>
                            <input type="number" name="nomor_urut" value="{{ old('nomor_urut') }}"
                                placeholder="1" min="1"
                                class="input-field">
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Nama kandidat"
                                class="input-field">
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Visi</label>
                            <input type="text" name="visi" value="{{ old('visi') }}"
                                placeholder="Visi singkat kandidat"
                                class="input-field">
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Misi (opsional)</label>
                            <textarea name="misi" placeholder="Poin-poin misi..."
                                class="input-field">{{ old('misi') }}</textarea>
                        </div>

                        <div class="mb-6">
                            <label class="field-label">Foto (opsional)</label>
                            <input type="file" name="foto" accept="image/*"
                                class="input-field text-xs" style="padding:8px 14px;">
                            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.25)">Maks 2MB · JPG, PNG</p>
                        </div>

                        <button type="submit" class="btn-primary">
                            <span>+</span> Tambah & Daftarkan ke Blockchain
                        </button>
                    </form>
                </div>

                {{-- Info box --}}
                <div class="mt-4 p-4 rounded-xl fade-up d2"
                    style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15)">
                    <p class="text-xs font-semibold text-blue-400 mb-1">ℹ Catatan penting</p>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.35)">
                        Kandidat hanya bisa ditambahkan saat voting belum aktif.
                        Setelah terdaftar di blockchain, data tidak dapat dihapus.
                    </p>
                </div>
            </div>

            {{-- Daftar kandidat --}}
            <div class="md:col-span-3 fade-up d2">
                <div class="card">
                    <div class="flex justify-between items-center px-6 py-4"
                        style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <div>
                            <h2 class="font-bold text-white">Daftar Kandidat</h2>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">
                                {{ $kandidat->count() }} kandidat ·
                                {{ $kandidat->where('terdaftar_blockchain', true)->count() }} di blockchain
                            </p>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full font-semibold"
                            style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.4)">
                            {{ $kandidat->count() }} total
                        </span>
                    </div>

                    @forelse ($kandidat as $k)
                        <div class="kandidat-row">
                            {{-- Nomor --}}
                            <div class="nomor-badge {{ $k->terdaftar_blockchain ? 'blockchain' : '' }}">
                                {{ $k->nomor_urut }}
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-white text-sm">{{ $k->nama }}</p>
                                <p class="text-xs mt-0.5 truncate" style="color:rgba(255,255,255,0.35)">
                                    {{ $k->visi }}
                                </p>
                                <div class="mt-2">
                                    @if ($k->terdaftar_blockchain)
                                        <span class="badge-blockchain">
                                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                                            Terdaftar blockchain
                                        </span>
                                    @else
                                        <span class="badge-pending">
                                            <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full"></span>
                                            Belum di blockchain
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col gap-2 flex-shrink-0">
                                @if (!$k->terdaftar_blockchain)
                                    <form method="POST" action="{{ route('admin.kandidat.daftarkan', $k) }}">
                                        @csrf
                                        <button type="submit" class="btn-blockchain"
                                            onclick="return confirm('Daftarkan {{ $k->nama }} ke blockchain?')">
                                            Daftarkan
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.kandidat.destroy', $k) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-hapus"
                                            onclick="return confirm('Hapus kandidat ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center">
                            <p class="text-4xl mb-3">🏆</p>
                            <p class="font-semibold" style="color:rgba(255,255,255,0.35)">Belum ada kandidat</p>
                            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.2)">
                                Tambahkan kandidat menggunakan form di samping
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>