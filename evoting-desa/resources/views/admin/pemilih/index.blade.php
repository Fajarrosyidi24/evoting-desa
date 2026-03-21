<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemilih — Admin</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
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
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
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
            font-size: 11px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-hapus:hover {
            background: rgba(239,68,68,0.15);
        }

        .badge-blockchain {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .badge-pending {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(234,179,8,0.1);
            border: 1px solid rgba(234,179,8,0.2);
            color: #FDE047;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .badge-voted {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(59,130,246,0.1);
            border: 1px solid rgba(59,130,246,0.2);
            color: #93C5FD;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 20px;
        }

        .badge-not-voted {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 600;
            padding: 2px 8px;
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

        .alert-error {
            background: rgba(239,68,68,0.08);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 14px;
            padding: 12px 16px;
            color: #FCA5A5;
            font-size: 14px;
        }

        .pemilih-row {
            padding: 14px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: background 0.15s;
        }

        .pemilih-row:last-child { border-bottom: none; }
        .pemilih-row:hover { background: rgba(255,255,255,0.02); }

        .avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 800;
            color: white;
            flex-shrink: 0;
            background: linear-gradient(135deg, #374151, #1F2937);
        }

        .search-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 9px 14px;
            color: white;
            font-size: 13px;
            outline: none;
            transition: all 0.2s;
            width: 200px;
        }

        .search-box::placeholder { color: rgba(255,255,255,0.2); }

        .search-box:focus {
            border-color: rgba(220,38,38,0.4);
            background: rgba(220,38,38,0.04);
            width: 240px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }

        /* Pagination dark override */
        nav[role="navigation"] span,
        nav[role="navigation"] a {
            background: rgba(255,255,255,0.04) !important;
            border-color: rgba(255,255,255,0.08) !important;
            color: rgba(255,255,255,0.5) !important;
            border-radius: 8px !important;
        }

        nav[role="navigation"] a:hover {
            background: rgba(220,38,38,0.15) !important;
            color: white !important;
        }

        nav[role="navigation"] [aria-current="page"] span {
            background: rgba(220,38,38,0.3) !important;
            color: white !important;
        }

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
        <a href="{{ route('admin.kandidat.index') }}" class="nav-item">
            <div class="nav-icon">🏆</div>Kandidat
        </a>
        <a href="{{ route('admin.pemilih.index') }}" class="nav-item active">
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
            <h1 class="text-lg font-bold text-white">Kelola Pemilih</h1>
            <p class="text-xs" style="color:rgba(255,255,255,0.35)">{{ $pemilih->total() }} pemilih terdaftar</p>
        </div>
        <div class="flex items-center gap-3">
            <input type="text" placeholder="Cari nama atau NIK..."
                class="search-box"
                oninput="filterPemilih(this.value)">
            <a href="{{ route('admin.dashboard') }}"
                class="text-sm px-4 py-2 rounded-lg transition font-medium"
                style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.5)"
                onmouseover="this.style.color='white'"
                onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                ← Dashboard
            </a>
        </div>
    </div>

    <div class="p-8">

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert-success mb-5 fade-up d1 flex items-center gap-2">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert-error mb-5 fade-up d1">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2"><span>✗</span> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Stat mini --}}
        <div class="grid grid-cols-3 gap-4 mb-6 fade-up d1">
            <div class="p-4 rounded-xl text-center" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06)">
                <p class="text-2xl font-extrabold text-white">{{ $pemilih->total() }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Total Pemilih</p>
            </div>
            <div class="p-4 rounded-xl text-center" style="background:rgba(34,197,94,0.06);border:1px solid rgba(34,197,94,0.15)">
                <p class="text-2xl font-extrabold text-green-400">
                    {{ \App\Models\Pemilih::where('terdaftar_blockchain', true)->count() }}
                </p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Di Blockchain</p>
            </div>
            <div class="p-4 rounded-xl text-center" style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15)">
                <p class="text-2xl font-extrabold text-blue-400">
                    {{ \App\Models\Pemilih::where('sudah_voting', true)->count() }}
                </p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Sudah Voting</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">

            {{-- Form tambah --}}
            <div class="md:col-span-2 fade-up d1">
                <div class="card p-6">
                    <div class="flex items-center gap-2 mb-6">
                        <div class="w-1 h-5 rounded-full" style="background:linear-gradient(180deg,#DC2626,#EF4444)"></div>
                        <h2 class="font-bold text-white">Tambah Pemilih</h2>
                    </div>

                    <form method="POST" action="{{ route('admin.pemilih.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="field-label">NIK (16 digit)</label>
                            <input type="text" name="nik" value="{{ old('nik') }}"
                                placeholder="3578xxxxxxxx0001" maxlength="16"
                                class="input-field" style="font-family:'DM Mono',monospace">
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Nama sesuai KTP"
                                class="input-field">
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                                class="input-field">
                            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.2)">Digunakan sebagai password login</p>
                        </div>

                        <div class="mb-4">
                            <label class="field-label">Alamat (opsional)</label>
                            <input type="text" name="alamat" value="{{ old('alamat') }}"
                                placeholder="Alamat lengkap"
                                class="input-field">
                        </div>

                        <div class="mb-6">
                            <label class="field-label">No HP (opsional)</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                                placeholder="08xxxxxxxxxx"
                                class="input-field">
                        </div>

                        <button type="submit" class="btn-primary">
                            <span>+</span> Tambah & Daftarkan ke Blockchain
                        </button>
                    </form>
                </div>

                <div class="mt-4 p-4 rounded-xl fade-up d2"
                    style="background:rgba(59,130,246,0.06);border:1px solid rgba(59,130,246,0.15)">
                    <p class="text-xs font-semibold text-blue-400 mb-1">ℹ Catatan</p>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.35)">
                        Setiap pemilih akan otomatis didaftarkan ke blockchain saat ditambahkan.
                        Tanggal lahir digunakan sebagai password login warga.
                    </p>
                </div>
            </div>

            {{-- Daftar pemilih --}}
            <div class="md:col-span-3 fade-up d2">
                <div class="card">
                    <div class="px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <h2 class="font-bold text-white">Daftar Pemilih</h2>
                        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">
                            Halaman {{ $pemilih->currentPage() }} dari {{ $pemilih->lastPage() }}
                        </p>
                    </div>

                    <div id="pemilih-list">
                        @forelse ($pemilih as $p)
                            <div class="pemilih-row pemilih-item"
                                data-nama="{{ strtolower($p->nama) }}"
                                data-nik="{{ $p->nik }}">

                                {{-- Avatar --}}
                                <div class="avatar">
                                    {{ strtoupper(substr($p->nama, 0, 1)) }}
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-white text-sm">{{ $p->nama }}</p>
                                    <p class="font-mono text-xs mt-0.5" style="color:rgba(255,255,255,0.3)">
                                        {{ $p->nik }}
                                    </p>
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @if ($p->terdaftar_blockchain)
                                            <span class="badge-blockchain">
                                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                                                Blockchain
                                            </span>
                                        @else
                                            <span class="badge-pending">
                                                <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full"></span>
                                                Belum chain
                                            </span>
                                        @endif

                                        @if ($p->sudah_voting)
                                            <span class="badge-voted">
                                                <span class="w-1.5 h-1.5 bg-blue-400 rounded-full"></span>
                                                Sudah voting
                                            </span>
                                        @else
                                            <span class="badge-not-voted">Belum voting</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="flex flex-col gap-1.5 flex-shrink-0">
                                    @if (!$p->terdaftar_blockchain)
                                        <form method="POST" action="{{ route('admin.pemilih.daftarkan', $p) }}">
                                            @csrf
                                            <button type="submit" class="btn-blockchain"
                                                onclick="return confirm('Daftarkan {{ $p->nama }} ke blockchain?')">
                                                Daftarkan
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.pemilih.destroy', $p) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-hapus"
                                                onclick="return confirm('Hapus pemilih ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="py-16 text-center">
                                <p class="text-4xl mb-3">👥</p>
                                <p class="font-semibold" style="color:rgba(255,255,255,0.35)">Belum ada pemilih</p>
                                <p class="text-sm mt-1" style="color:rgba(255,255,255,0.2)">
                                    Tambahkan pemilih menggunakan form di samping
                                </p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if ($pemilih->hasPages())
                        <div class="px-6 py-4" style="border-top:1px solid rgba(255,255,255,0.05)">
                            {{ $pemilih->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function filterPemilih(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('.pemilih-item').forEach(row => {
            const nama = row.dataset.nama || '';
            const nik  = row.dataset.nik  || '';
            row.style.display = (nama.includes(q) || nik.includes(q)) ? '' : 'none';
        });
    }
</script>

</body>
</html>