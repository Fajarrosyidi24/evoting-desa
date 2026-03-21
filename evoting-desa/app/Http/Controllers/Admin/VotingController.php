<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Models\VotingLog;
use App\Services\BlockchainService;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function index()
{
    $status   = $this->blockchain->getStatusVoting();

    $totalKandidat    = Kandidat::where('terdaftar_blockchain', true)->count();
    $totalPemilih     = Pemilih::where('terdaftar_blockchain', true)->count();
    $totalSudahVoting = Pemilih::where('sudah_voting', true)->count();
    $totalBelumVoting = $totalPemilih - $totalSudahVoting;

    // Ambil hasil suara dari blockchain
    $hasilVoting = [];
    $totalSuara  = 0;

    if ($totalKandidat > 0) {
        $kandidat = Kandidat::where('terdaftar_blockchain', true)
            ->orderBy('id')
            ->get();

        $hasilRaw = $this->blockchain->getHasilVoting($totalKandidat);

        foreach ($kandidat as $index => $k) {
            $jumlah = $hasilRaw[$index]['jumlah_suara'] ?? 0;
            $totalSuara += $jumlah;

            $hasilVoting[] = [
                'id'           => $k->id,
                'nama'         => $k->nama,
                'nomor_urut'   => $k->nomor_urut,
                'visi'         => $k->visi,
                'jumlah_suara' => $jumlah,
            ];
        }

        // Hitung persentase setelah total diketahui
        foreach ($hasilVoting as &$h) {
            $h['persentase'] = $totalSuara > 0
                ? round(($h['jumlah_suara'] / $totalSuara) * 100, 1)
                : 0;
        }
        unset($h);

        // Urutkan berdasarkan suara terbanyak
        usort($hasilVoting, fn($a, $b) => $b['jumlah_suara'] - $a['jumlah_suara']);
    }

    // Log voting terbaru
    $logTerbaru = VotingLog::with(['pemilih', 'kandidat'])
        ->orderByDesc('voted_at')
        ->limit(10)
        ->get();

    return view('admin.voting.index', compact(
        'status',
        'totalKandidat',
        'totalPemilih',
        'totalSudahVoting',
        'totalBelumVoting',
        'hasilVoting',
        'totalSuara',
        'logTerbaru',
    ));
}

    public function mulai(Request $request)
    {
        $request->validate([
            'durasi_menit' => 'required|integer|min:1|max:1440',
        ], [
            'durasi_menit.required' => 'Durasi voting wajib diisi',
            'durasi_menit.min'      => 'Durasi minimal 1 menit',
            'durasi_menit.max'      => 'Durasi maksimal 1440 menit (24 jam)',
        ]);

        // Validasi minimal 2 kandidat sudah terdaftar di blockchain
        $totalKandidat = Kandidat::where('terdaftar_blockchain', true)->count();
        if ($totalKandidat < 2) {
            return back()->withErrors([
                'error' => 'Minimal 2 kandidat harus terdaftar di blockchain sebelum voting dimulai'
            ]);
        }

        $result = $this->blockchain->mulaiVoting($request->durasi_menit);

        if ($result['success'] ?? false) {
            return back()->with('success', "Voting berhasil dimulai! Durasi: {$request->durasi_menit} menit");
        }

        return back()->withErrors([
            'error' => 'Gagal memulai voting: ' . ($result['error'] ?? 'Unknown error')
        ]);
    }

    public function akhiri()
    {
        $result = $this->blockchain->akhiriVoting();

        if ($result['success'] ?? false) {
            return back()->with('success', 'Voting berhasil diakhiri!');
        }

        return back()->withErrors([
            'error' => 'Gagal mengakhiri voting: ' . ($result['error'] ?? 'Unknown error')
        ]);
    }

    public function dashboard()
{
    $status           = $this->blockchain->getStatusVoting();
    $totalKandidat    = Kandidat::where('terdaftar_blockchain', true)->count();
    $totalPemilih     = Pemilih::where('terdaftar_blockchain', true)->count();
    $totalSudahVoting = Pemilih::where('sudah_voting', true)->count();
    $totalBelumVoting = $totalPemilih - $totalSudahVoting;

    $hasilVoting = [];
    $totalSuara  = 0;

    if ($totalKandidat > 0) {
        $kandidat = Kandidat::where('terdaftar_blockchain', true)->orderBy('id')->get();
        $hasilRaw = $this->blockchain->getHasilVoting($totalKandidat);

        foreach ($kandidat as $index => $k) {
            $jumlah       = $hasilRaw[$index]['jumlah_suara'] ?? 0;
            $totalSuara  += $jumlah;
            $hasilVoting[] = [
                'nama'         => $k->nama,
                'nomor_urut'   => $k->nomor_urut,
                'jumlah_suara' => $jumlah,
            ];
        }

        foreach ($hasilVoting as &$h) {
            $h['persentase'] = $totalSuara > 0
                ? round(($h['jumlah_suara'] / $totalSuara) * 100, 1)
                : 0;
        }
        unset($h);

        usort($hasilVoting, fn($a, $b) => $b['jumlah_suara'] - $a['jumlah_suara']);
    }

    $logTerbaru = \App\Models\VotingLog::with(['pemilih', 'kandidat'])
        ->orderByDesc('voted_at')
        ->limit(5)
        ->get();

    return view('admin.dashboard', compact(
        'status',
        'totalKandidat',
        'totalPemilih',
        'totalSudahVoting',
        'totalBelumVoting',
        'hasilVoting',
        'totalSuara',
        'logTerbaru',
    ));
}
}