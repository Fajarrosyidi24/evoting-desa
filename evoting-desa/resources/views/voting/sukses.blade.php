<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voting Berhasil — E-Voting Desa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'DM Mono', monospace !important; }

        body {
            min-height: 100vh;
            background: #FAFAFA;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Confetti dots background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                radial-gradient(circle, rgba(220,38,38,0.06) 1px, transparent 1px),
                radial-gradient(circle, rgba(220,38,38,0.03) 1px, transparent 1px);
            background-size: 32px 32px, 16px 16px;
            background-position: 0 0, 8px 8px;
            pointer-events: none;
        }

        .glow-top {
            position: fixed;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(220,38,38,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .card {
            background: white;
            border-radius: 28px;
            padding: 48px 40px;
            max-width: 480px;
            width: 100%;
            box-shadow: 0 8px 48px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            z-index: 10;
            text-align: center;
        }

        /* Success icon ring animation */
        .success-ring {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, #FEE2E2, #FECACA);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            position: relative;
        }

        .success-ring::before {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 2px solid rgba(220,38,38,0.15);
            animation: ring-pulse 2s ease-in-out infinite;
        }

        .success-ring::after {
            content: '';
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            border: 2px solid rgba(220,38,38,0.07);
            animation: ring-pulse 2s ease-in-out infinite 0.3s;
        }

        @keyframes ring-pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.05); }
        }

        .success-check {
            font-size: 40px;
            line-height: 1;
        }

        /* Kandidat name pill */
        .kandidat-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
            border: 1.5px solid #FECACA;
            border-radius: 40px;
            padding: 8px 20px;
            margin: 16px 0;
        }

        .tx-box {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 14px;
            padding: 16px;
            text-align: left;
            position: relative;
        }

        .copy-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 600;
            color: #6B7280;
            cursor: pointer;
            transition: all 0.2s;
        }

        .copy-btn:hover {
            background: #F3F4F6;
            color: #374151;
        }

        .copy-btn.copied {
            background: #F0FDF4;
            border-color: #BBF7D0;
            color: #15803D;
        }

        .btn-primary {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #DC2626, #B91C1C);
            color: white;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            border-radius: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(220,38,38,0.25);
        }

        .btn-secondary {
            display: block;
            width: 100%;
            background: #F9FAFB;
            border: 1.5px solid #E5E7EB;
            color: #6B7280;
            font-weight: 600;
            font-size: 15px;
            padding: 14px;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }

        .btn-secondary:hover {
            background: #F3F4F6;
            color: #374151;
        }

        .divider {
            height: 1px;
            background: #F3F4F6;
            margin: 24px 0;
        }

        .step-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            text-align: left;
            padding: 8px 0;
        }

        .step-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #DC2626;
            flex-shrink: 0;
            margin-top: 6px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .fade-up { animation: fadeUp 0.5s ease forwards; }
        .d1 { animation-delay: 0.1s;  opacity: 0; }
        .d2 { animation-delay: 0.2s;  opacity: 0; }
        .d3 { animation-delay: 0.3s;  opacity: 0; }
        .d4 { animation-delay: 0.4s;  opacity: 0; }
        .d5 { animation-delay: 0.5s;  opacity: 0; }

        @keyframes bounceIn {
            0%   { opacity: 0; transform: scale(0.5); }
            60%  { transform: scale(1.1); }
            80%  { transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }

        .bounce-in { animation: bounceIn 0.6s cubic-bezier(0.4, 0, 0.2, 1) forwards; }
    </style>
</head>
<body>

<div class="glow-top"></div>

<div class="card">

    {{-- Success icon --}}
    <div class="bounce-in">
        <div class="success-ring">
            <span class="success-check">✅</span>
        </div>
    </div>

    {{-- Heading --}}
    <div class="fade-up d1">
        <h1 class="text-2xl font-extrabold text-gray-900 mb-1">Suara Berhasil Dikirim!</h1>
        <p class="text-gray-500 text-sm">Partisipasimu sangat berarti untuk desa kita</p>
    </div>

    {{-- Kandidat pilihan --}}
    <div class="fade-up d2">
        <p class="text-gray-400 text-xs mt-4 mb-1">Anda memilih</p>
        <div class="kandidat-pill">
            <span class="text-red-500 text-base">🏆</span>
            <span class="font-bold text-red-700 text-base">{{ $nama_kandidat }}</span>
        </div>
    </div>

    <div class="divider fade-up d2"></div>

    {{-- TX Hash --}}
    <div class="fade-up d3">
        <div class="tx-box">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                Transaction Hash
            </p>
            <p id="tx-hash" class="font-mono text-xs text-gray-600 break-all leading-relaxed pr-16">
                {{ $tx_hash }}
            </p>
            <button class="copy-btn" id="copyBtn" onclick="copyTxHash()">
                Salin
            </button>
        </div>
        <p class="text-xs text-gray-400 mt-2 text-left">
            Simpan hash ini sebagai bukti bahwa suaramu tercatat di blockchain
        </p>
    </div>

    <div class="divider fade-up d3"></div>

    {{-- Apa selanjutnya --}}
    <div class="fade-up d4 text-left mb-6">
        <p class="text-sm font-bold text-gray-700 mb-3">Apa yang bisa kamu lakukan sekarang?</p>
        <div class="space-y-1">
            <div class="step-item">
                <div class="step-dot"></div>
                <p class="text-sm text-gray-500">
                    Verifikasi suaramu di
                    <a href="{{ route('publik.transaksi') }}?tx_hash={{ $tx_hash }}"
                        target="_blank"
                        class="text-red-600 font-semibold hover:underline">
                        halaman transaksi blockchain
                    </a>
                </p>
            </div>
            <div class="step-item">
                <div class="step-dot" style="background:#1D4ED8"></div>
                <p class="text-sm text-gray-500">
                    Pantau hasil voting secara real-time di
                    <a href="{{ route('publik.hasil') }}"
                        target="_blank"
                        class="text-blue-600 font-semibold hover:underline">
                        halaman hasil publik
                    </a>
                </p>
            </div>
            <div class="step-item">
                <div class="step-dot" style="background:#15803D"></div>
                <p class="text-sm text-gray-500">
                    Ajak warga lain yang belum voting untuk berpartisipasi
                </p>
            </div>
        </div>
    </div>

    {{-- Buttons --}}
    <div class="flex flex-col gap-3 fade-up d5">
        <a href="{{ route('publik.hasil') }}" class="btn-primary">
            Lihat Hasil Voting →
        </a>
        <form method="POST" action="{{ route('pemilih.logout') }}">
            @csrf
            <button type="submit" class="btn-secondary">
                Selesai & Logout
            </button>
        </form>
    </div>

    {{-- Footer note --}}
    <div class="mt-6 fade-up d5">
        <div class="flex items-center justify-center gap-2">
            <div style="width:20px;height:13px;border-radius:3px;overflow:hidden;display:flex;flex-direction:column;">
                <div style="flex:1;background:#DC2626;"></div>
                <div style="flex:1;background:#ffffff;border:1px solid #e5e7eb;"></div>
            </div>
            <p class="text-xs text-gray-300">E-Voting Desa · Powered by Blockchain</p>
        </div>
    </div>

</div>

<script>
    function copyTxHash() {
        const hash = document.getElementById('tx-hash').textContent.trim();
        const btn  = document.getElementById('copyBtn');

        navigator.clipboard.writeText(hash).then(() => {
            btn.textContent = 'Tersalin!';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.textContent = 'Salin';
                btn.classList.remove('copied');
            }, 2000);
        });
    }
</script>

</body>
</html>