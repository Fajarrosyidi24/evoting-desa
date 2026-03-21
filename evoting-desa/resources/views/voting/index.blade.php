<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting — E-Voting Desa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'DM Mono', monospace !important; }

        body {
            min-height: 100vh;
            background: #FAFAFA;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #F3F4F6;
            padding: 14px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 1px 12px rgba(0,0,0,0.04);
        }

        .user-badge {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 40px;
            padding: 6px 14px 6px 8px;
        }

        .user-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .countdown-bar {
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            color: white;
            text-align: center;
            padding: 12px 24px;
        }

        .card-kandidat {
            background: white;
            border: 2px solid #F3F4F6;
            border-radius: 20px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .card-kandidat::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #DC2626, #EF4444);
            opacity: 0;
            transition: opacity 0.2s;
        }

        .card-kandidat:hover {
            border-color: #FECACA;
            box-shadow: 0 4px 20px rgba(220,38,38,0.08);
            transform: translateY(-2px);
        }

        .card-kandidat:has(input:checked) {
            border-color: #DC2626;
            background: #FFF5F5;
            box-shadow: 0 4px 24px rgba(220,38,38,0.12);
            transform: translateY(-2px);
        }

        .card-kandidat:has(input:checked)::before {
            opacity: 1;
        }

        .nomor-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: #F3F4F6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            color: #6B7280;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .card-kandidat:has(input:checked) .nomor-badge {
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
        }

        .check-circle {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #D1D5DB;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .card-kandidat:has(input:checked) .check-circle {
            background: #DC2626;
            border-color: #DC2626;
        }

        .check-icon {
            width: 12px;
            height: 12px;
            color: white;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .card-kandidat:has(input:checked) .check-icon {
            opacity: 1;
        }

        .btn-vote {
            width: 100%;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            font-size: 16px;
            padding: 16px;
            border-radius: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }

        .btn-vote:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(220,38,38,0.3);
        }

        .btn-vote:active { transform: translateY(0); }

        .btn-vote::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.5s;
        }

        .btn-vote:hover::after { left: 100%; }

        .info-box {
            background: #F0FDF4;
            border: 1px solid #BBF7D0;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .warning-box {
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .error-box {
            background: #FEF2F2;
            border: 1.5px solid #FECACA;
            border-radius: 14px;
            padding: 14px 16px;
        }

        .sudah-voting-card {
            background: white;
            border-radius: 24px;
            padding: 48px 32px;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            border: 1px solid #F3F4F6;
        }

        .tx-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 14px 16px;
        }

        .voting-inactive-card {
            background: white;
            border-radius: 24px;
            padding: 48px 32px;
            text-align: center;
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            border: 1px solid #F3F4F6;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.4s ease forwards; }
        .d1 { animation-delay: 0.05s; opacity: 0; }
        .d2 { animation-delay: 0.10s; opacity: 0; }
        .d3 { animation-delay: 0.15s; opacity: 0; }
        .d4 { animation-delay: 0.20s; opacity: 0; }

        /* Modal overlay */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .modal-overlay.show { display: flex; }

        .modal-card {
            background: white;
            border-radius: 24px;
            padding: 32px;
            max-width: 420px;
            width: 100%;
            box-shadow: 0 24px 64px rgba(0,0,0,0.15);
        }

        .btn-confirm {
            width: 100%;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
        }

        .btn-confirm:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            box-shadow: 0 4px 16px rgba(220,38,38,0.3);
        }

        .btn-cancel {
            width: 100%;
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            color: #6B7280;
            font-weight: 600;
            padding: 14px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 15px;
        }

        .btn-cancel:hover { background: #F3F4F6; color: #374151; }
    </style>
</head>
<body>

{{-- Topbar --}}
<div class="topbar">
    <div class="flex items-center gap-3">
        <div style="width:32px;height:20px;border-radius:4px;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 1px 4px rgba(0,0,0,0.15)">
            <div style="flex:1;background:#DC2626;"></div>
            <div style="flex:1;background:#ffffff;border-top:1px solid #e5e7eb;"></div>
        </div>
        <span class="font-bold text-gray-900 text-sm">E-Voting Desa</span>
    </div>

    <div class="flex items-center gap-3">
        <div class="user-badge">
            <div class="user-avatar">
                {{ strtoupper(substr($pemilih->nama, 0, 1)) }}
            </div>
            <span class="text-sm font-semibold text-gray-700">{{ $pemilih->nama }}</span>
        </div>
        <form method="POST" action="{{ route('pemilih.logout') }}">
            @csrf
            <button type="submit"
                class="text-sm font-medium text-gray-400 hover:text-red-600 transition px-3 py-1.5 rounded-lg hover:bg-red-50">
                Logout
            </button>
        </form>
    </div>
</div>

{{-- Countdown bar --}}
@if ($status['aktif'] && $status['sisa_detik'] > 0 && !$sudahVoting)
    <div class="countdown-bar">
        <div class="flex items-center justify-center gap-3">
            <span class="text-red-200 text-sm">Sisa waktu voting</span>
            <span id="countdown" class="font-mono text-xl font-bold tracking-widest">--:--:--</span>
        </div>
    </div>
@endif

<div class="max-w-2xl mx-auto px-4 py-10">

    {{-- Error --}}
    @if ($errors->any())
        <div class="error-box mb-6 fade-up d1">
            @foreach ($errors->all() as $error)
                <p class="text-sm text-red-700 flex items-center gap-2">
                    <span>⚠</span> {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    {{-- Sudah voting --}}
    @if ($sudahVoting)
        <div class="sudah-voting-card fade-up d1">
            <div class="text-6xl mb-5">✅</div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Suara Anda Sudah Tercatat</h2>
            <p class="text-gray-500 text-sm mb-6">
                Terima kasih, <strong>{{ $pemilih->nama }}</strong>! Suaramu sudah tersimpan
                permanen di blockchain dan tidak dapat diubah.
            </p>
            @if ($pemilih->votingLog)
                <div class="tx-box text-left mb-4">
                    <p class="text-xs text-gray-400 font-medium mb-1">Transaction Hash (bukti suara)</p>
                    <p class="font-mono text-xs text-gray-600 break-all">{{ $pemilih->votingLog->tx_hash }}</p>
                </div>
                <a href="{{ route('publik.transaksi') }}?tx_hash={{ $pemilih->votingLog->tx_hash }}"
                    target="_blank"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 hover:text-red-700 transition">
                    Verifikasi di blockchain →
                </a>
            @endif
            <div class="mt-6 pt-6 border-t border-gray-100">
                <a href="{{ route('publik.hasil') }}"
                    class="text-sm text-gray-400 hover:text-gray-600 transition">
                    Lihat hasil voting publik →
                </a>
            </div>
        </div>

    {{-- Voting tidak aktif --}}
    @elseif (!$status['aktif'])
        <div class="voting-inactive-card fade-up d1">
            <div class="text-6xl mb-5">⏳</div>
            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Voting Belum Dimulai</h2>
            <p class="text-gray-500 text-sm mb-6">
                Halo, <strong>{{ $pemilih->nama }}</strong>! Voting belum dimulai.
                Tunggu pengumuman resmi dari panitia desa.
            </p>
            <a href="{{ route('publik.hasil') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                Pantau halaman publik →
            </a>
        </div>

    {{-- Form voting --}}
    @else
        {{-- Header --}}
        <div class="mb-8 fade-up d1">
            <p class="text-red-600 text-xs font-bold uppercase tracking-widest mb-2">Pilihan Anda Menentukan</p>
            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Pilih Kandidat</h1>
            <p class="text-gray-500 text-sm">
                Pilih satu kandidat kepala desa pilihanmu. Pilihan bersifat rahasia dan
                hanya bisa dilakukan sekali.
            </p>
        </div>

        {{-- Info box --}}
        <div class="info-box mb-6 fade-up d2">
            <div class="flex items-start gap-3">
                <span class="text-green-600 text-base flex-shrink-0 mt-0.5">ℹ</span>
                <p class="text-sm text-green-700 leading-relaxed">
                    Klik kartu kandidat untuk memilih, lalu klik tombol
                    <strong>"Kirim Suara"</strong> untuk mengkonfirmasi. Suaramu akan
                    tercatat permanen di blockchain.
                </p>
            </div>
        </div>

        {{-- Kandidat cards --}}
        <form id="formVoting" method="POST" action="{{ route('voting.kirim') }}">
            @csrf
            <div class="space-y-4 mb-8 fade-up d3">
                @forelse ($kandidat as $k)
                    <label class="card-kandidat block">
                        <input type="radio" name="kandidat_id" value="{{ $k->id }}" class="hidden">

                        <div class="flex items-center gap-4">
                            {{-- Nomor urut --}}
                            <div class="nomor-badge">{{ $k->nomor_urut }}</div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-lg leading-tight">{{ $k->nama }}</p>
                                <p class="text-gray-500 text-sm mt-1 leading-relaxed">{{ $k->visi }}</p>
                                @if ($k->misi)
                                    <p class="text-gray-400 text-xs mt-1.5 leading-relaxed">
                                        {{ Str::limit($k->misi, 100) }}
                                    </p>
                                @endif
                            </div>

                            {{-- Check --}}
                            <div class="check-circle flex-shrink-0">
                                <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="warning-box">
                        <p class="text-sm text-yellow-700">Belum ada kandidat yang terdaftar.</p>
                    </div>
                @endforelse
            </div>

            {{-- Warning --}}
            <div class="warning-box mb-6 fade-up d3">
                <div class="flex items-start gap-3">
                    <span class="text-yellow-600 flex-shrink-0 mt-0.5">⚠</span>
                    <p class="text-sm text-yellow-700 leading-relaxed">
                        <strong>Perhatian:</strong> Pilihan yang sudah dikonfirmasi
                        <strong>tidak dapat diubah</strong>. Pastikan pilihanmu sudah benar sebelum mengirim suara.
                    </p>
                </div>
            </div>

            {{-- Submit button --}}
            <div class="fade-up d4">
                <button type="button" onclick="showModal()" class="btn-vote">
                    Kirim Suara Saya →
                </button>
            </div>
        </form>
    @endif

</div>

{{-- Confirmation Modal --}}
<div id="modal" class="modal-overlay">
    <div class="modal-card">
        <div class="text-center mb-6">
            <div class="text-5xl mb-4">🗳️</div>
            <h3 class="text-xl font-extrabold text-gray-900 mb-2">Konfirmasi Pilihan</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                Anda akan memilih:
            </p>
            <div class="mt-3 px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
                <p id="modal-kandidat-name" class="font-bold text-red-700 text-lg">—</p>
            </div>
            <p class="text-gray-400 text-xs mt-3">
                Pilihan ini akan dicatat permanen di blockchain dan tidak dapat diubah.
            </p>
        </div>

        <div class="flex flex-col gap-3">
            <button type="button" onclick="submitVote()" class="btn-confirm">
                Ya, Kirim Suara Saya
            </button>
            <button type="button" onclick="hideModal()" class="btn-cancel">
                Batal, Pilih Lagi
            </button>
        </div>
    </div>
</div>

{{-- Scripts --}}
@if ($status['aktif'] && $status['sisa_detik'] > 0 && !$sudahVoting)
<script>
    let sisa = {{ $status['sisa_detik'] }};
    const el = document.getElementById('countdown');
    function tick() {
        if (sisa <= 0) { el.textContent = 'Habis'; return; }
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

<script>
    function showModal() {
        const selected = document.querySelector('input[name="kandidat_id"]:checked');
        if (!selected) {
            alert('Pilih salah satu kandidat terlebih dahulu!');
            return;
        }

        // Ambil nama kandidat dari card yang dipilih
        const card = selected.closest('label');
        const nama = card.querySelector('p.font-bold').textContent.trim();
        document.getElementById('modal-kandidat-name').textContent = nama;
        document.getElementById('modal').classList.add('show');
    }

    function hideModal() {
        document.getElementById('modal').classList.remove('show');
    }

    function submitVote() {
        document.getElementById('formVoting').submit();
    }

    // Tutup modal kalau klik di luar
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) hideModal();
    });
</script>

</body>
</html>