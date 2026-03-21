<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi — {{ $namaVoting }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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

        .glow-red {
            position: fixed;
            top: -200px;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(220,38,38,0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .nav-glass {
            background: rgba(10,10,10,0.8);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            position: relative;
            z-index: 1;
        }

        .card-red {
            background: rgba(220,38,38,0.08);
            border: 1px solid rgba(220,38,38,0.2);
            border-radius: 20px;
        }

        .stat-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
        }

        .stat-blue::before  { background: linear-gradient(90deg, #1D4ED8, #3B82F6); }
        .stat-green::before { background: linear-gradient(90deg, #15803D, #22C55E); }
        .stat-red::before   { background: linear-gradient(90deg, #DC2626, #EF4444); }

        .input-search {
            background: rgba(255,255,255,0.05);
            border: 1.5px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }

        .input-search::placeholder { color: rgba(255,255,255,0.2); font-family: 'DM Mono', monospace; }

        .input-search:focus {
            border-color: rgba(220,38,38,0.5);
            background: rgba(220,38,38,0.05);
            box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }

        .btn-search {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 20px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-search:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            box-shadow: 0 4px 16px rgba(220,38,38,0.3);
        }

        .btn-reset {
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.5);
            font-size: 14px;
            padding: 12px 16px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-reset:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }

        .tx-row {
            padding: 16px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }

        .tx-row:last-child { border-bottom: none; }
        .tx-row:hover { background: rgba(255,255,255,0.02); margin: 0 -24px; padding: 16px 24px; border-radius: 12px; }

        .status-confirmed {
            background: rgba(34,197,94,0.1);
            border: 1px solid rgba(34,197,94,0.2);
            color: #86EFAC;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .status-pending {
            background: rgba(234,179,8,0.1);
            border: 1px solid rgba(234,179,8,0.2);
            color: #FDE047;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .status-failed {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.2);
            color: #FCA5A5;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .result-found {
            background: rgba(34,197,94,0.07);
            border: 1px solid rgba(34,197,94,0.2);
            border-radius: 16px;
            padding: 20px;
        }

        .result-notfound {
            background: rgba(239,68,68,0.07);
            border: 1px solid rgba(239,68,68,0.2);
            border-radius: 16px;
            padding: 20px;
        }

        .detail-row {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .detail-row:last-child { border-bottom: none; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }
        .d4 { animation-delay: 0.20s; opacity: 0; }

        /* Pagination dark override */
        nav[role="navigation"] span,
        nav[role="navigation"] a {
            background: rgba(255,255,255,0.04) !important;
            border-color: rgba(255,255,255,0.08) !important;
            color: rgba(255,255,255,0.6) !important;
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
    </style>
</head>
<body>

<div class="grid-bg"></div>
<div class="glow-red"></div>

{{-- Navbar --}}
<nav class="nav-glass py-4 px-4">
    <div class="max-w-5xl mx-auto flex justify-between items-center relative z-10">
        <div class="flex items-center gap-3">
            <div style="width:32px;height:20px;border-radius:4px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 2px 8px rgba(0,0,0,0.5)">
                <div style="flex:1;background:#DC2626;"></div>
                <div style="flex:1;background:#ffffff;"></div>
            </div>
            <div>
                <p class="text-white font-bold text-sm leading-none">{{ $namaDesa }}</p>
                <p class="text-xs leading-none mt-0.5" style="color:rgba(255,255,255,0.3)">E-Voting Blockchain</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('publik.hasil') }}"
                class="text-sm px-4 py-1.5 rounded-lg transition font-medium"
                style="color:rgba(255,255,255,0.5);background:rgba(255,255,255,0.05);"
                onmouseover="this.style.color='white'"
                onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                ← Hasil Voting
            </a>
            <a href="{{ route('pemilih.login') }}"
                class="text-sm font-semibold px-4 py-1.5 rounded-lg transition"
                style="background:linear-gradient(135deg,#DC2626,#B91C1C);color:white;">
                Login Voting
            </a>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-10 relative z-10">

    {{-- Page header --}}
    <div class="mb-8 fade-up d1">
        <div class="flex items-center gap-2 mb-3">
            <div class="w-1 h-6 rounded-full" style="background:linear-gradient(180deg,#DC2626,#EF4444)"></div>
            <p class="text-xs font-semibold tracking-widest uppercase" style="color:rgba(220,38,38,0.8)">Blockchain Explorer</p>
        </div>
        <h1 class="text-3xl font-extrabold text-white mb-2">Riwayat Transaksi</h1>
        <p class="text-sm" style="color:rgba(255,255,255,0.4)">
            {{ $namaVoting }} · Semua transaksi tercatat permanen di blockchain
        </p>
    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-3 gap-4 mb-8 fade-up d2">
        <div class="stat-card stat-blue">
            <p class="text-2xl font-extrabold text-blue-400">{{ $totalTransaksi }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.4)">Total Transaksi</p>
        </div>
        <div class="stat-card stat-green">
            <p class="text-2xl font-extrabold text-green-400">{{ $totalKonfirmasi }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.4)">Dikonfirmasi</p>
        </div>
        <div class="stat-card stat-red">
            <p class="text-2xl font-extrabold" style="color:#FCA5A5">{{ $totalGagal }}</p>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.4)">Gagal</p>
        </div>
    </div>

    {{-- Verifikasi TX Hash --}}
    <div class="card p-6 mb-8 fade-up d3">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-lg">🔍</span>
            <h2 class="text-base font-bold text-white">Verifikasi Suara</h2>
        </div>
        <p class="text-xs mb-4" style="color:rgba(255,255,255,0.35)">
            Masukkan TX hash yang kamu terima setelah voting untuk membuktikan suaramu tercatat di blockchain
        </p>

        <form method="GET" action="{{ route('publik.transaksi') }}">
            <div class="flex gap-2">
                <input
                    type="text"
                    name="tx_hash"
                    value="{{ $cariTxHash }}"
                    placeholder="0xabc123def456..."
                    class="input-search font-mono">
                <button type="submit" class="btn-search">Verifikasi</button>
                @if ($cariTxHash)
                    <a href="{{ route('publik.transaksi') }}" class="btn-reset">Reset</a>
                @endif
            </div>
        </form>

        {{-- Hasil verifikasi --}}
        @if ($cariTxHash)
            <div class="mt-4">
                @if ($hasilCari)
                    <div class="result-found">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0">✓</div>
                            <p class="font-bold text-green-400">Transaksi Valid & Ditemukan</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
                            <div class="detail-row">
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">Kandidat dipilih</p>
                                <p class="text-white font-semibold">{{ $hasilCari->kandidat?->nama ?? '-' }}</p>
                            </div>
                            <div class="detail-row">
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">Waktu voting</p>
                                <p class="text-white">{{ $hasilCari->voted_at?->format('d/m/Y H:i:s') }}</p>
                            </div>
                            <div class="detail-row">
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">Status</p>
                                <div class="mt-1">
                                    @if ($hasilCari->status === 'confirmed')
                                        <span class="status-confirmed">Confirmed</span>
                                    @else
                                        <span class="status-pending">{{ ucfirst($hasilCari->status) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="detail-row">
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">Block number</p>
                                <p class="font-mono text-white">#{{ $hasilCari->block_number ?? '-' }}</p>
                            </div>
                            <div class="detail-row md:col-span-2">
                                <p class="text-xs" style="color:rgba(255,255,255,0.35)">Transaction Hash</p>
                                <p class="font-mono text-xs text-green-400 break-all mt-1">{{ $hasilCari->tx_hash }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="result-notfound">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0"
                                style="background:rgba(239,68,68,0.2)">
                                <span class="text-red-400 font-bold">✗</span>
                            </div>
                            <div>
                                <p class="font-bold text-red-400">Transaksi Tidak Ditemukan</p>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">
                                    Hash <span class="font-mono">{{ Str::limit($cariTxHash, 30) }}...</span>
                                    tidak ada dalam sistem
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Tabel Transaksi --}}
    <div class="card p-6 fade-up d4">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-base font-bold text-white">Semua Transaksi</h2>
                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35)">{{ $logs->total() }} transaksi tercatat</p>
            </div>
            {{-- Live indicator --}}
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full"
                style="background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.15)">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                <span class="text-xs text-green-400 font-medium">On-chain</span>
            </div>
        </div>

        @forelse ($logs as $log)
            <div class="tx-row">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-start gap-3 flex-1 min-w-0">

                        {{-- Status dot --}}
                        <div class="mt-1.5 flex-shrink-0">
                            @if ($log->status === 'confirmed')
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            @elseif ($log->status === 'pending')
                                <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse"></div>
                            @else
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            {{-- TX Hash --}}
                            <p class="font-mono text-sm break-all leading-relaxed"
                                style="color:rgba(96,165,250,0.9)">
                                {{ $log->tx_hash }}
                            </p>

                            {{-- Meta info --}}
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5">
                                <span class="text-xs" style="color:rgba(255,255,255,0.4)">
                                    Kandidat:
                                    <span style="color:rgba(255,255,255,0.7)">{{ $log->kandidat?->nama ?? '-' }}</span>
                                </span>
                                @if ($log->block_number)
                                    <span class="font-mono text-xs" style="color:rgba(255,255,255,0.3)">
                                        Block #{{ $log->block_number }}
                                    </span>
                                @endif
                                @if ($log->status === 'confirmed')
                                    <span class="status-confirmed">Confirmed</span>
                                @elseif ($log->status === 'pending')
                                    <span class="status-pending">Pending</span>
                                @else
                                    <span class="status-failed">Failed</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Timestamp --}}
                    <div class="text-right flex-shrink-0 pl-5">
                        <p class="text-xs font-medium" style="color:rgba(255,255,255,0.5)">
                            {{ $log->voted_at?->format('d/m/Y') }}
                        </p>
                        <p class="font-mono text-xs" style="color:rgba(255,255,255,0.25)">
                            {{ $log->voted_at?->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center">
                <p class="text-4xl mb-3">📭</p>
                <p class="font-semibold" style="color:rgba(255,255,255,0.4)">Belum ada transaksi</p>
                <p class="text-sm mt-1" style="color:rgba(255,255,255,0.2)">Transaksi akan muncul setelah voting dimulai</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($logs->hasPages())
            <div class="mt-6 pt-4" style="border-top:1px solid rgba(255,255,255,0.05)">
                {{ $logs->links() }}
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="text-center mt-8 py-4">
        <p class="text-xs" style="color:rgba(255,255,255,0.2)">
            Semua transaksi tercatat permanen di blockchain dan dapat diverifikasi publik
        </p>
    </div>

</div>

</body>
</html>