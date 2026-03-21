<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Models\VotingLog;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublikController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function hasil()
    {
        $status           = $this->blockchain->getStatusVoting();
        $totalKandidat    = Kandidat::where('terdaftar_blockchain', true)->count();
        $totalPemilih     = Pemilih::where('terdaftar_blockchain', true)->count();
        $totalSudahVoting = Pemilih::where('sudah_voting', true)->count();

        $hasilVoting = [];
        $totalSuara  = 0;
        $pemenang    = null;

        if ($totalKandidat > 0) {
            $kandidat = Kandidat::where('terdaftar_blockchain', true)
                ->orderBy('id')
                ->get();

            $hasilRaw = $this->blockchain->getHasilVoting($totalKandidat);

            foreach ($kandidat as $index => $k) {
                $jumlah       = $hasilRaw[$index]['jumlah_suara'] ?? 0;
                $totalSuara  += $jumlah;
                $hasilVoting[] = [
                    'nama'         => $k->nama,
                    'nomor_urut'   => $k->nomor_urut,
                    'visi'         => $k->visi,
                    'jumlah_suara' => $jumlah,
                    'foto_path'    => $k->foto_path,
                ];
            }

            foreach ($hasilVoting as &$h) {
                $h['persentase'] = $totalSuara > 0
                    ? round(($h['jumlah_suara'] / $totalSuara) * 100, 1)
                    : 0;
            }
            unset($h);

            usort($hasilVoting, fn($a, $b) => $b['jumlah_suara'] - $a['jumlah_suara']);

            if (!$status['aktif'] && $totalSuara > 0) {
                $pemenang = $hasilVoting[0];
            }
        }

        $logTerbaru = VotingLog::with(['kandidat'])
            ->orderByDesc('voted_at')
            ->limit(10)
            ->get();

        $namaVoting = \App\Models\Setting::get('nama_voting', 'E-Voting Desa');
        $namaDesa   = \App\Models\Setting::get('nama_desa', 'Desa');

        return view('publik.hasil', compact(
            'status',
            'totalKandidat',
            'totalPemilih',
            'totalSudahVoting',
            'hasilVoting',
            'totalSuara',
            'pemenang',
            'logTerbaru',
            'namaVoting',
            'namaDesa',
        ));
    }

    public function transaksi(Request $request)
{
    $namaVoting = \App\Models\Setting::get('nama_voting', 'E-Voting Desa');
    $namaDesa   = \App\Models\Setting::get('nama_desa', 'Desa');

    // Ambil semua log dengan relasi
    $logs = VotingLog::with(['kandidat'])
        ->orderByDesc('voted_at')
        ->paginate(20);

    // Statistik
    $totalTransaksi   = VotingLog::count();
    $totalKonfirmasi  = VotingLog::where('status', 'confirmed')->count();
    $totalGagal       = VotingLog::where('status', 'failed')->count();

    // Cek apakah ada tx_hash yang dicari
    $cariTxHash = $request->query('tx_hash');
    $hasilCari  = null;

    if ($cariTxHash) {
        $hasilCari = VotingLog::with(['pemilih', 'kandidat'])
            ->where('tx_hash', $cariTxHash)
            ->first();
    }

    return view('publik.transaksi', compact(
        'logs',
        'totalTransaksi',
        'totalKonfirmasi',
        'totalGagal',
        'namaVoting',
        'namaDesa',
        'cariTxHash',
        'hasilCari',
    ));
}
}