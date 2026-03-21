<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>{{ $namaVoting }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'DM Mono', monospace; }

        :root {
            --merah: #DC2626;
            --merah-muda: #EF4444;
            --merah-gelap: #991B1B;
            --putih: #FFFFFF;
            --abu: #F8F8F8;
        }

        body { background: #FAFAFA; }

        .hero-bg {
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 40%, #1a1a2e 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(220,38,38,0.3) 0%, transparent 70%);
            border-radius: 50%;
        }

        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .badge-live {
            background: linear-gradient(135deg, #DC2626, #EF4444);
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(220,38,38,0.4); }
            50% { box-shadow: 0 0 0 8px rgba(220,38,38,0); }
        }

        .bar-merah {
            background: linear-gradient(90deg, #DC2626, #EF4444);
        }
        .bar-biru {
            background: linear-gradient(90deg, #1D4ED8, #3B82F6);
        }
        .bar-hijau {
            background: linear-gradient(90deg, #15803D, #22C55E);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .slide-up { animation: slideUp 0.6s ease forwards; }
        .delay-1  { animation-delay: 0.1s; opacity: 0; }
        .delay-2  { animation-delay: 0.2s; opacity: 0; }
        .delay-3  { animation-delay: 0.3s; opacity: 0; }
        .delay-4  { animation-delay: 0.4s; opacity: 0; }

        .winner-card {
            background: linear-gradient(135deg, #FEF3C7, #FDE68A, #FCD34D);
            border: 2px solid #F59E0B;
        }

        .garuda-pattern {
            background-image: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(220,38,38,0.03) 10px,
                rgba(220,38,38,0.03) 20px
            );
        }

        .nav-glass {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(220,38,38,0.1);
        }

        .stat-card {
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }

        .stat-merah::before { background: linear-gradient(90deg, #DC2626, #EF4444); }
        .stat-biru::before  { background: linear-gradient(90deg, #1D4ED8, #3B82F6); }
        .stat-hijau::before { background: linear-gradient(90deg, #15803D, #22C55E); }
    </style>
</head>
<body>

{{-- Navbar --}}
<nav class="nav-glass sticky top-0 z-50 py-3 px-4">
    <div class="max-w-6xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            {{-- Bendera mini --}}
            <div class="w-8 h-5 rounded overflow-hidden flex flex-col">
                <div class="flex-1 bg-red-600"></div>
                <div class="flex-1 bg-white border-t border-gray-200"></div>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm leading-none">{{ $namaDesa }}</p>
                <p class="text-xs text-gray-400 leading-none mt-0.5">E-Voting Blockchain</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if ($status['aktif'])
                <span class="badge-live text-white text-xs font-semibold px-3 py-1.5 rounded-full flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                    LIVE
                </span>
            @endif
            <a href="{{ route('publik.transaksi') }}"
                class="text-sm text-gray-600 hover:text-red-600 px-3 py-1.5 rounded-lg transition font-medium">
                Transaksi
            </a>
            <a href="{{ route('pemilih.login') }}"
                class="text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-lg transition font-semibold">
                Login Voting
            </a>
        </div>
    </div>
</nav>

{{-- Hero Section --}}
<div class="hero-bg py-16 px-4 relative z-10">
    <div class="max-w-6xl mx-auto text-center relative z-10">
        <div class="slide-up">
            <p class="text-red-200 text-sm font-medium tracking-widest uppercase mb-3">Pemilihan Kepala Desa</p>
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-4 leading-tight">
                {{ $namaVoting }}
            </h1>
            <p class="text-red-200 text-base mb-8 max-w-xl mx-auto">
                Hasil voting transparan berbasis teknologi blockchain —
                setiap suara tercatat permanen dan tidak dapat dimanipulasi
            </p>
        </div>

        {{-- Countdown --}}
        @if ($status['aktif'] && $status['sisa_detik'] > 0)
            <div class="slide-up delay-1 inline-block bg-white/10 backdrop-blur border border-white/20 rounded-2xl px-8 py-5 mb-6">
                <p class="text-red-200 text-xs font-medium uppercase tracking-widest mb-2">Sisa Waktu Voting</p>
                <p id="countdown" class="font-mono text-5xl font-bold text-white tracking-widest">--:--:--</p>
            </div>
        @endif

        {{-- Stats --}}
        <div class="slide-up delay-2 grid grid-cols-3 gap-4 max-w-lg mx-auto">
            <div class="bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/20">
                <p class="text-3xl font-extrabold text-white">{{ $totalKandidat }}</p>
                <p class="text-red-200 text-xs mt-1">Kandidat</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/20">
                <p class="text-3xl font-extrabold text-white">{{ $totalPemilih }}</p>
                <p class="text-red-200 text-xs mt-1">Pemilih</p>
            </div>
            <div class="bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/20">
                <p class="text-3xl font-extrabold text-white">{{ $totalSudahVoting }}</p>
                <p class="text-red-200 text-xs mt-1">Suara Masuk</p>
            </div>
        </div>
    </div>
</div>

{{-- Wave separator --}}
<div class="relative -mt-1">
    <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H1380C1320 60 1200 60 1080 60C960 60 840 60 720 60C600 60 480 60 360 60C240 60 120 60 60 60H0Z" fill="#FAFAFA"/>
    </svg>
</div>

<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Pemenang Banner --}}
    @if ($pemenang)
        <div class="winner-card rounded-2xl p-6 mb-8 slide-up flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
            <div class="text-5xl">🏆</div>
            <div>
                <p class="text-amber-700 text-sm font-semibold uppercase tracking-wider">Pemenang Pilkades</p>
                <p class="text-2xl font-extrabold text-amber-900">{{ $pemenang['nama'] }}</p>
                <p class="text-amber-700 text-sm mt-1">
                    {{ $pemenang['jumlah_suara'] }} suara · {{ $pemenang['persentase'] }}% dari total suara
                </p>
            </div>
        </div>
    @endif

    {{-- Hasil Kandidat --}}
    @if (count($hasilVoting) > 0)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-xl font-bold text-gray-900">Perolehan Suara</h2>
                <span class="text-sm text-gray-400">{{ $totalSuara }} suara masuk</span>
            </div>

            <div class="space-y-4">
                @php $barColors = ['bar-merah', 'bar-biru', 'bar-hijau']; @endphp

                @foreach ($hasilVoting as $index => $hasil)
                    <div class="card p-5 slide-up delay-{{ $index + 1 }}">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl font-extrabold text-white
                                    {{ $index === 0 ? 'bg-gradient-to-br from-red-500 to-red-700' :
                                       ($index === 1 ? 'bg-gradient-to-br from-blue-500 to-blue-700' :
                                        'bg-gradient-to-br from-green-500 to-green-700') }}">
                                    {{ $hasil['nomor_urut'] }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ $hasil['nama'] }}</p>
                                    <p class="text-gray-400 text-sm">{{ Str::limit($hasil['visi'], 70) }}</p>
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0 ml-4">
                                <p class="text-2xl font-extrabold
                                    {{ $index === 0 ? 'text-red-600' : ($index === 1 ? 'text-blue-600' : 'text-green-600') }}">
                                    {{ $hasil['jumlah_suara'] }}
                                </p>
                                <p class="text-gray-400 text-sm">{{ $hasil['persentase'] }}%</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $barColors[$index] ?? 'bar-merah' }} h-2.5 rounded-full transition-all duration-1000"
                                style="width: {{ $hasil['persentase'] }}%">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Charts --}}
        @if ($totalSuara > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Grafik Perbandingan</h3>
                    <div class="relative h-56">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
                <div class="card p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Distribusi Suara</h3>
                    <div class="relative h-56">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- Partisipasi --}}
    @if ($totalPemilih > 0)
        @php $partisipasi = round(($totalSudahVoting / $totalPemilih) * 100, 1); @endphp
        <div class="card p-6 mb-8">
            <div class="flex justify-between items-center mb-3">
                <h3 class="font-bold text-gray-900">Tingkat Partisipasi</h3>
                <span class="text-2xl font-extrabold text-red-600">{{ $partisipasi }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                <div class="bar-merah h-4 rounded-full transition-all duration-1000"
                    style="width: {{ $partisipasi }}%"></div>
            </div>
            <p class="text-gray-400 text-sm mt-2">
                {{ $totalSudahVoting }} dari {{ $totalPemilih }} pemilih telah memberikan suara
            </p>
        </div>
    @endif

    {{-- Transaksi Terbaru --}}
    @if ($logTerbaru->count() > 0)
        <div class="card p-6 mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-900">Transaksi Blockchain Terbaru</h3>
                <a href="{{ route('publik.transaksi') }}"
                    class="text-sm text-red-600 hover:text-red-700 font-medium">
                    Lihat semua →
                </a>
            </div>
            <div class="space-y-3">
                @foreach ($logTerbaru as $log)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full flex-shrink-0"></div>
                            <div>
                                <p class="text-sm text-gray-700">
                                    Suara untuk
                                    <span class="font-semibold text-gray-900">{{ $log->kandidat?->nama ?? '-' }}</span>
                                </p>
                                <p class="font-mono text-xs text-gray-400">
                                    {{ Str::limit($log->tx_hash, 40) }}
                                </p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 flex-shrink-0 ml-3">
                            {{ $log->voted_at?->format('H:i:s') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="text-center py-6 border-t border-gray-100">
        <div class="flex justify-center items-center gap-2 mb-2">
            <div class="w-6 h-4 rounded overflow-hidden flex flex-col">
                <div class="flex-1 bg-red-600"></div>
                <div class="flex-1 bg-white border border-gray-200"></div>
            </div>
            <p class="text-sm font-semibold text-gray-700">{{ $namaDesa }}</p>
        </div>
        <p class="text-xs text-gray-400">
            Data langsung dari blockchain · Transparan · Tidak dapat dimanipulasi
        </p>
        <p class="text-xs text-gray-300 mt-1">Auto refresh setiap 30 detik</p>
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
    const colors = ['#DC2626', '#1D4ED8', '#15803D', '#D97706', '#7C3AED'];
    const light  = ['#FEE2E2', '#DBEAFE', '#DCFCE7', '#FEF3C7', '#EDE9FE'];

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
                y: { beginAtZero: true, ticks: { stepSize: 1, color: '#9ca3af' }, grid: { color: '#f3f4f6' } },
                x: { ticks: { color: '#6b7280' }, grid: { display: false } }
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
                borderColor    : '#ffffff',
                hoverOffset    : 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels  : { color: '#6b7280', font: { size: 12 }, padding: 16 }
                }
            }
        }
    });
</script>
@endif

</body>
</html>