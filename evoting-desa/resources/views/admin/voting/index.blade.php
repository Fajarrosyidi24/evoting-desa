<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Voting — Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'DM Mono', monospace !important; }

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

        .stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 20px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }

        .stat-blue::before   { background: linear-gradient(90deg,#1D4ED8,#3B82F6); }
        .stat-purple::before { background: linear-gradient(90deg,#7C3AED,#A78BFA); }
        .stat-green::before  { background: linear-gradient(90deg,#15803D,#22C55E); }
        .stat-orange::before { background: linear-gradient(90deg,#C2410C,#F97316); }

        .status-panel {
            border-radius: 20px;
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .status-aktif {
            background: linear-gradient(135deg, rgba(220,38,38,0.1), rgba(153,27,27,0.06));
            border: 1px solid rgba(220,38,38,0.2);
        }

        .status-nonaktif {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.07);
        }

        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(220,38,38,0.15);
            border: 1px solid rgba(220,38,38,0.3);
            color: #FCA5A5;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .offline-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4);
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .input-durasi {
            background: rgba(255,255,255,0.06);
            border: 1.5px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 11px 14px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            width: 120px;
        }

        .input-durasi:focus {
            border-color: rgba(220,38,38,0.5);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }

        .btn-mulai {
            background: linear-gradient(135deg, #16A34A, #15803D);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 11px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-mulai:hover {
            background: linear-gradient(135deg, #22C55E, #16A34A);
            box-shadow: 0 4px 16px rgba(22,163,74,0.3);
            transform: translateY(-1px);
        }

        .btn-akhiri {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            font-size: 14px;
            padding: 11px 24px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-akhiri:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            box-shadow: 0 4px 16px rgba(220,38,38,0.3);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            font-weight: 500;
            padding: 8px 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-ghost:hover {
            background: rgba(255,255,255,0.08);
            color: white;
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

        .progress-bar {
            height: 8px;
            background: rgba(255,255,255,0.06);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill-red {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #DC2626, #EF4444);
            transition: width 1s ease;
        }

        .rank-item {
            padding: 16px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .rank-item:last-child { border-bottom: none; }

        .log-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .log-row:last-child { border-bottom: none; }
        .log-row:hover { background: rgba(255,255,255,0.02); }

        .winner-banner {
            background: linear-gradient(135deg, rgba(251,191,36,0.12), rgba(245,158,11,0.06));
            border: 1px solid rgba(251,191,36,0.25);
            border-radius: 16px;
            padding: 20px 24px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }
        .d4 { animation-delay: 0.20s; opacity: 0; }
        .d5 { animation-delay: 0.25s; opacity: 0; }

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
        <a href="{{ route('admin.voting.index') }}" class="nav-item active">
            <div class="nav-icon">🗳️</div>Kelola Voting
        </a>
        <p class="nav-section">Data</p>
        <a href="{{ route('admin.kandidat.index') }}" class="nav-item">
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
            <h1 class="text-lg font-bold text-white">Kelola Voting</h1>
            <p class="text-xs" style="color:rgba(255,255,255,0.35)">Kontrol & pantau voting secara real-time</p>
        </div>
        <div class="flex items-center gap-3">
            @if ($status['aktif'])
                <div class="live-badge">
                    <span class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse"></span>
                    Live
                </div>
            @else
                <div class="offline-badge">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:rgba(255,255,255,0.3)"></span>
                    Offline
                </div>
            @endif
            <button onclick="location.reload()" class="btn-ghost">Refresh</button>
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

        {{-- Status panel --}}
        <div class="status-panel {{ $status['aktif'] ? 'status-aktif' : 'status-nonaktif' }} mb-6 fade-up d1">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div>
                    @if ($status['aktif'])
                        <div class="live-badge mb-3">
                            <span class="w-1.5 h-1.5 bg-red-400 rounded-full animate-pulse"></span>
                            Voting Sedang Berlangsung
                        </div>
                        <p class="text-xs mb-1" style="color:rgba(255,255,255,0.4)">Sisa waktu</p>
                        <p id="countdown" class="font-mono text-5xl font-extrabold text-white tracking-widest">
                            --:--:--
                        </p>
                    @else
                        <div class="offline-badge mb-3">
                            <span class="w-1.5 h-1.5 rounded-full" style="background:rgba(255,255,255,0.3)"></span>
                            Voting Tidak Aktif
                        </div>
                        <p class="text-white font-semibold">Voting belum dimulai atau sudah selesai</p>
                        <p class="text-sm mt-1" style="color:rgba(255,255,255,0.35)">
                            Pastikan minimal 2 kandidat sudah terdaftar di blockchain sebelum memulai
                        </p>
                    @endif
                </div>

                {{-- Control --}}
                @if (!$status['aktif'])
                    <form method="POST" action="{{ route('admin.voting.mulai') }}"
                        class="flex items-end gap-3"
                        onsubmit="return confirm('Mulai voting sekarang? Pastikan semua persiapan sudah selesai.')">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold mb-2"
                                style="color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.05em">
                                Durasi (menit)
                            </label>
                            <input type="number" name="durasi_menit" value="60" min="1" max="1440"
                                class="input-durasi">
                        </div>
                        <button type="submit" class="btn-mulai">
                            <span>▶</span> Mulai Voting
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.voting.akhiri') }}"
                        onsubmit="return confirm('Akhiri voting sekarang? Tindakan ini TIDAK dapat dibatalkan.')">
                        @csrf
                        <button type="submit" class="btn-akhiri">
                            <span>■</span> Akhiri Voting
                        </button>
                    </form>
                @endif
            </div>

            {{-- Progress partisipasi --}}
            @php $partisipasi = $totalPemilih > 0 ? round(($totalSudahVoting / $totalPemilih) * 100, 1) : 0; @endphp
            <div>
                <div class="flex justify-between items-center mb-2">
                    <p class="text-xs font-semibold" style="color:rgba(255,255,255,0.45)">Tingkat Partisipasi</p>
                    <p class="text-sm font-bold text-white">{{ $partisipasi }}%</p>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill-red" style="width:{{ $partisipasi }}%"></div>
                </div>
                <p class="text-xs mt-1.5" style="color:rgba(255,255,255,0.3)">
                    {{ $totalSudahVoting }} dari {{ $totalPemilih }} pemilih
                </p>
            </div>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 fade-up d2">
            <div class="stat-card stat-blue">
                <p class="text-3xl font-extrabold text-white">{{ $totalKandidat }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Kandidat</p>
            </div>
            <div class="stat-card stat-purple">
                <p class="text-3xl font-extrabold text-white">{{ $totalPemilih }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Total Pemilih</p>
            </div>
            <div class="stat-card stat-green">
                <p class="text-3xl font-extrabold text-white">{{ $totalSudahVoting }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Sudah Voting</p>
            </div>
            <div class="stat-card stat-orange">
                <p class="text-3xl font-extrabold text-white">{{ $totalBelumVoting }}</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,0.35)">Belum Voting</p>
            </div>
        </div>

        {{-- Pemenang banner --}}
        @if (!$status['aktif'] && count($hasilVoting) > 0 && $totalSuara > 0)
            <div class="winner-banner mb-6 fade-up d2 flex items-center gap-4">
                <div class="text-4xl">🏆</div>
                <div>
                    <p class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-1">Pemenang</p>
                    <p class="text-xl font-extrabold text-white">{{ $hasilVoting[0]['nama'] }}</p>
                    <p class="text-sm text-amber-300 mt-0.5">
                        {{ $hasilVoting[0]['jumlah_suara'] }} suara · {{ $hasilVoting[0]['persentase'] }}%
                    </p>
                </div>
            </div>
        @endif

        {{-- Charts + Ranking --}}
        @if (count($hasilVoting) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                {{-- Bar chart --}}
                <div class="card fade-up d3">
                    <div class="p-5 pb-0" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <div class="flex justify-between items-center pb-4">
                            <h2 class="font-bold text-white">Grafik Suara</h2>
                            @if ($status['aktif'])
                                <span class="text-xs px-2 py-0.5 rounded-full"
                                    style="background:rgba(220,38,38,0.12);color:#FCA5A5;border:1px solid rgba(220,38,38,0.2)">
                                    Live
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="p-5">
                        @if ($totalSuara > 0)
                            <div class="relative h-52">
                                <canvas id="barChart"></canvas>
                            </div>
                        @else
                            <div class="h-52 flex flex-col items-center justify-center"
                                style="color:rgba(255,255,255,0.2)">
                                <p class="text-3xl mb-2">📊</p>
                                <p class="text-sm">Belum ada suara</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Donut chart --}}
                <div class="card fade-up d3">
                    <div class="p-5" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                        <h2 class="font-bold text-white">Distribusi Suara</h2>
                    </div>
                    <div class="p-5">
                        @if ($totalSuara > 0)
                            <div class="relative h-52">
                                <canvas id="pieChart"></canvas>
                            </div>
                        @else
                            <div class="h-52 flex flex-col items-center justify-center"
                                style="color:rgba(255,255,255,0.2)">
                                <p class="text-3xl mb-2">🥧</p>
                                <p class="text-sm">Belum ada suara</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Ranking table --}}
            <div class="card mb-6 fade-up d4">
                <div class="flex justify-between items-center px-6 py-4"
                    style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <h2 class="font-bold text-white">Perolehan Suara</h2>
                    <span class="text-xs" style="color:rgba(255,255,255,0.3)">Total {{ $totalSuara }} suara</span>
                </div>

                @php $rankColors = ['#DC2626','#1D4ED8','#15803D','#D97706','#7C3AED']; @endphp

                @foreach ($hasilVoting as $index => $hasil)
                    <div class="rank-item">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold text-white flex-shrink-0"
                                    style="background:{{ $rankColors[$index] ?? '#6b7280' }}">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <p class="font-semibold text-white text-sm">{{ $hasil['nama'] }}</p>
                                    <p class="text-xs" style="color:rgba(255,255,255,0.3)">
                                        Nomor urut {{ $hasil['nomor_urut'] }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-white">{{ $hasil['jumlah_suara'] }}</p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">{{ $hasil['persentase'] }}%</p>
                            </div>
                        </div>
                        <div class="progress-bar">
                            <div class="h-full rounded-full transition-all duration-700"
                                style="width:{{ $hasil['persentase'] }}%;background:{{ $rankColors[$index] ?? '#6b7280' }}">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Log terbaru --}}
        @if ($logTerbaru->count() > 0)
            <div class="card fade-up d5">
                <div class="flex justify-between items-center px-6 py-4"
                    style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <h2 class="font-bold text-white">Aktivitas Voting Terbaru</h2>
                    <a href="{{ route('publik.transaksi') }}" target="_blank"
                        class="text-xs" style="color:rgba(255,255,255,0.3)"
                        onmouseover="this.style.color='white'"
                        onmouseout="this.style.color='rgba(255,255,255,0.3)'">
                        Lihat blockchain →
                    </a>
                </div>
                @foreach ($logTerbaru as $log)
                    <div class="log-row">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $log->pemilih?->nama ?? '-' }}</p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">
                                    memilih
                                    <span style="color:rgba(255,255,255,0.6)">{{ $log->kandidat?->nama ?? '-' }}</span>
                                    ·
                                    <span class="font-mono" style="color:rgba(255,255,255,0.25)">
                                        {{ Str::limit($log->tx_hash, 20) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <p class="font-mono text-xs flex-shrink-0" style="color:rgba(255,255,255,0.25)">
                            {{ $log->voted_at?->format('H:i:s') }}
                        </p>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

{{-- Scripts --}}
@if ($status['aktif'] && $status['sisa_detik'] > 0)
<script>
    let sisa = {{ $status['sisa_detik'] }};
    const el = document.getElementById('countdown');
    function tick() {
        if (sisa <= 0) { el.textContent = 'Selesai'; return; }
        const j = Math.floor(sisa / 3600);
        const m = Math.floor((sisa % 3600) / 60);
        const d = sisa % 60;
        el.textContent =
            String(j).padStart(2,'0') + ':' +
            String(m).padStart(2,'0') + ':' +
            String(d).padStart(2,'0');
        sisa--;
    }
    tick();
    setInterval(tick, 1000);
</script>
@endif

@if (count($hasilVoting) > 0 && $totalSuara > 0)
<script>
    const labels = @json(collect($hasilVoting)->pluck('nama'));
    const data   = @json(collect($hasilVoting)->pluck('jumlah_suara'));
    const colors = ['#DC2626','#1D4ED8','#15803D','#D97706','#7C3AED'];

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
                borderRadius   : 10,
                borderWidth    : 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: 'rgba(255,255,255,0.3)', font: { size: 11 } },
                    grid: { color: 'rgba(255,255,255,0.04)' },
                    border: { color: 'rgba(255,255,255,0.06)' }
                },
                x: {
                    ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 11 } },
                    grid: { display: false },
                    border: { color: 'rgba(255,255,255,0.06)' }
                }
            }
        }
    });

    new Chart(document.getElementById('pieChart'), {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data,
                backgroundColor: colors,
                borderWidth    : 3,
                borderColor    : '#0a0a0a',
                hoverOffset    : 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color  : 'rgba(255,255,255,0.5)',
                        font   : { size: 11 },
                        padding: 16,
                    }
                }
            }
        }
    });
</script>
@endif

</body>
</html>