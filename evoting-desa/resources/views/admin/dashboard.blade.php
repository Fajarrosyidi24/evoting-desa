<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin — E-Voting Desa</title>
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
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }

        .stat-red::after    { background: linear-gradient(90deg, #DC2626, #EF4444); }
        .stat-blue::after   { background: linear-gradient(90deg, #1D4ED8, #3B82F6); }
        .stat-green::after  { background: linear-gradient(90deg, #15803D, #22C55E); }
        .stat-orange::after { background: linear-gradient(90deg, #C2410C, #F97316); }

        .status-live {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .status-offline {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.4);
            font-size: 12px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 600;
            font-size: 13px;
            padding: 8px 16px;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary:hover {
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

        .progress-bar {
            height: 6px;
            background: rgba(255,255,255,0.07);
            border-radius: 999px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #DC2626, #EF4444);
        }

        .log-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }

        .log-row:last-child { border-bottom: none; }
        .log-row:hover { background: rgba(255,255,255,0.02); }

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

        .nav-section {
            padding: 6px 20px 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.2);
            text-transform: uppercase;
            margin-top: 8px;
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
    {{-- Logo --}}
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

    {{-- Nav items --}}
    <div class="flex-1">
        <p class="nav-section">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}" class="nav-item active">
            <div class="nav-icon">📊</div>
            Dashboard
        </a>

        <a href="{{ route('admin.voting.index') }}" class="nav-item">
            <div class="nav-icon">🗳️</div>
            Kelola Voting
        </a>

        <p class="nav-section">Data</p>

        <a href="{{ route('admin.kandidat.index') }}" class="nav-item">
            <div class="nav-icon">🏆</div>
            Kandidat
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.35)">{{ $totalKandidat }}</span>
        </a>

        <a href="{{ route('admin.pemilih.index') }}" class="nav-item">
            <div class="nav-icon">👥</div>
            Pemilih
            <span class="ml-auto text-xs px-2 py-0.5 rounded-full" style="background:rgba(255,255,255,0.07);color:rgba(255,255,255,0.35)">{{ $totalPemilih }}</span>
        </a>

        <p class="nav-section">Publik</p>

        <a href="{{ route('publik.hasil') }}" target="_blank" class="nav-item">
            <div class="nav-icon">🌐</div>
            Halaman Publik
        </a>

        <a href="{{ route('publik.transaksi') }}" target="_blank" class="nav-item">
            <div class="nav-icon">⛓️</div>
            Transaksi
        </a>
    </div>

    {{-- User info --}}
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

{{-- Main Content --}}
<div class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div>
            <h1 class="text-lg font-bold text-white">Dashboard</h1>
            <p class="text-xs" style="color:rgba(255,255,255,0.35)">
                {{ now()->format('l, d F Y') }}
            </p>
        </div>
        <div class="flex items-center gap-3">
            @if ($status['aktif'])
                <div class="status-live">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span>
                    Voting Berlangsung
                </div>
            @else
                <div class="status-offline">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:rgba(255,255,255,0.3)"></span>
                    Voting Tidak Aktif
                </div>
            @endif
            <button onclick="location.reload()" class="btn-ghost">Refresh</button>
        </div>
    </div>

    <div class="p-8">

        {{-- Countdown banner --}}
        @if ($status['aktif'] && $status['sisa_detik'] > 0)
            <div class="mb-6 fade-up d1 rounded-2xl p-5 flex items-center justify-between"
                style="background:linear-gradient(135deg,rgba(220,38,38,0.12),rgba(153,27,27,0.08));border:1px solid rgba(220,38,38,0.2)">
                <div>
                    <p class="text-xs font-semibold text-red-400 uppercase tracking-wider mb-1">Voting Sedang Berlangsung</p>
                    <p class="font-mono text-3xl font-bold text-white" id="countdown">--:--:--</p>
                </div>
                <a href="{{ route('admin.voting.index') }}" class="btn-primary">
                    Pantau Live →
                </a>
            </div>
        @elseif (!$status['aktif'])
            <div class="mb-6 fade-up d1 rounded-2xl p-5 flex items-center justify-between"
                style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.06)">
                <div>
                    <p class="text-sm font-semibold text-white mb-1">Voting belum dimulai</p>
                    <p class="text-xs" style="color:rgba(255,255,255,0.35)">Pastikan kandidat & pemilih sudah terdaftar di blockchain sebelum memulai</p>
                </div>
                <a href="{{ route('admin.voting.index') }}" class="btn-primary">
                    Mulai Voting →
                </a>
            </div>
        @endif

        {{-- Stat cards --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8 fade-up d2">
            <div class="stat-card stat-blue">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:rgba(96,165,250,0.7)">Kandidat</p>
                <p class="text-4xl font-extrabold text-white">{{ $totalKandidat }}</p>
                <p class="text-xs mt-2" style="color:rgba(255,255,255,0.3)">Terdaftar blockchain</p>
            </div>
            <div class="stat-card stat-red">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:rgba(252,165,165,0.7)">Total Pemilih</p>
                <p class="text-4xl font-extrabold text-white">{{ $totalPemilih }}</p>
                <p class="text-xs mt-2" style="color:rgba(255,255,255,0.3)">DPT terdaftar</p>
            </div>
            <div class="stat-card stat-green">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:rgba(134,239,172,0.7)">Sudah Voting</p>
                <p class="text-4xl font-extrabold text-white">{{ $totalSudahVoting }}</p>
                <p class="text-xs mt-2" style="color:rgba(255,255,255,0.3)">Suara masuk</p>
            </div>
            <div class="stat-card stat-orange">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:rgba(249,115,22,0.7)">Belum Voting</p>
                <p class="text-4xl font-extrabold text-white">{{ $totalBelumVoting }}</p>
                <p class="text-xs mt-2" style="color:rgba(255,255,255,0.3)">Belum memilih</p>
            </div>
        </div>

        {{-- Main grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

            {{-- Chart --}}
            <div class="md:col-span-2 card fade-up d3">
                <div class="flex justify-between items-center p-6 pb-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <div>
                        <h2 class="font-bold text-white">Hasil Suara</h2>
                        <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">
                            {{ $totalSuara }} suara masuk · {{ $status['aktif'] ? 'Live' : 'Final' }}
                        </p>
                    </div>
                    @if ($status['aktif'])
                        <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                            style="background:rgba(220,38,38,0.12);color:#FCA5A5;border:1px solid rgba(220,38,38,0.2)">
                            Live
                        </span>
                    @endif
                </div>
                <div class="p-6">
                    @if ($totalSuara > 0)
                        <div class="relative h-48">
                            <canvas id="barChart"></canvas>
                        </div>
                    @else
                        <div class="h-48 flex flex-col items-center justify-center"
                            style="color:rgba(255,255,255,0.2)">
                            <p class="text-4xl mb-2">📊</p>
                            <p class="text-sm">Belum ada suara masuk</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Partisipasi & Quick actions --}}
            <div class="flex flex-col gap-4 fade-up d4">

                {{-- Partisipasi --}}
                <div class="card p-5">
                    <h3 class="font-bold text-white text-sm mb-4">Tingkat Partisipasi</h3>
                    @php $partisipasi = $totalPemilih > 0 ? round(($totalSudahVoting / $totalPemilih) * 100, 1) : 0; @endphp
                    <div class="text-center mb-4">
                        <p class="text-4xl font-extrabold" style="color:#EF4444">{{ $partisipasi }}%</p>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.3)">
                            {{ $totalSudahVoting }} / {{ $totalPemilih }} pemilih
                        </p>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width:{{ $partisipasi }}%"></div>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="card p-5">
                    <h3 class="font-bold text-white text-sm mb-3">Aksi Cepat</h3>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.kandidat.index') }}"
                            class="flex items-center gap-3 p-3 rounded-xl transition"
                            style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06)"
                            onmouseover="this.style.background='rgba(255,255,255,0.06)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                            <span class="text-base">🏆</span>
                            <span class="text-sm text-white font-medium">Kelola Kandidat</span>
                        </a>
                        <a href="{{ route('admin.pemilih.index') }}"
                            class="flex items-center gap-3 p-3 rounded-xl transition"
                            style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06)"
                            onmouseover="this.style.background='rgba(255,255,255,0.06)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.03)'">
                            <span class="text-base">👥</span>
                            <span class="text-sm text-white font-medium">Kelola Pemilih</span>
                        </a>
                        <a href="{{ route('admin.voting.index') }}"
                            class="flex items-center gap-3 p-3 rounded-xl transition"
                            style="background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.15)"
                            onmouseover="this.style.background='rgba(220,38,38,0.15)'"
                            onmouseout="this.style.background='rgba(220,38,38,0.08)'">
                            <span class="text-base">🗳️</span>
                            <span class="text-sm font-medium" style="color:#FCA5A5">Kelola Voting</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ranking kandidat --}}
        @if (count($hasilVoting) > 0 && $totalSuara > 0)
            <div class="card mb-6 fade-up d4">
                <div class="p-6 pb-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <h2 class="font-bold text-white">Ranking Perolehan Suara</h2>
                </div>
                <div class="p-6 space-y-4">
                    @php $rankColors = ['#DC2626','#1D4ED8','#15803D']; @endphp
                    @foreach ($hasilVoting as $index => $h)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-full text-xs font-bold flex items-center justify-center text-white flex-shrink-0"
                                        style="background:{{ $rankColors[$index] ?? '#6b7280' }}">
                                        {{ $index + 1 }}
                                    </span>
                                    <span class="text-sm font-medium text-white">{{ $h['nama'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-bold text-white">{{ $h['jumlah_suara'] }}</span>
                                    <span class="text-xs ml-1" style="color:rgba(255,255,255,0.35)">{{ $h['persentase'] }}%</span>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="h-full rounded-full transition-all duration-700"
                                    style="width:{{ $h['persentase'] }}%;background:{{ $rankColors[$index] ?? '#6b7280' }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Log terbaru --}}
        @if ($logTerbaru->count() > 0)
            <div class="card fade-up d5">
                <div class="flex justify-between items-center p-6 pb-4" style="border-bottom:1px solid rgba(255,255,255,0.05)">
                    <h2 class="font-bold text-white">Aktivitas Terbaru</h2>
                    <a href="{{ route('admin.voting.index') }}" class="btn-ghost text-xs">Lihat semua</a>
                </div>
                @foreach ($logTerbaru as $log)
                    <div class="log-row">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs"
                                style="background:rgba(34,197,94,0.1);color:#86EFAC">✓</div>
                            <div>
                                <p class="text-sm font-medium text-white">{{ $log->pemilih?->nama ?? '-' }}</p>
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">
                                    memilih <span style="color:rgba(255,255,255,0.6)">{{ $log->kandidat?->nama ?? '-' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-mono text-xs" style="color:rgba(255,255,255,0.3)">
                                {{ $log->voted_at?->format('H:i:s') }}
                            </p>
                        </div>
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
        el.textContent = String(j).padStart(2,'0') + ':' + String(m).padStart(2,'0') + ':' + String(d).padStart(2,'0');
        sisa--;
    }
    tick();
    setInterval(tick, 1000);
</script>
@endif

@if ($totalSuara > 0)
<script>
    const labels = @json(collect($hasilVoting)->pluck('nama'));
    const data   = @json(collect($hasilVoting)->pluck('jumlah_suara'));
    const colors = ['#DC2626','#1D4ED8','#15803D','#C2410C','#7C3AED'];

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
</script>
@endif

</body>
</html>