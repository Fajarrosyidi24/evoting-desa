<?php

namespace App\Http\Controllers;

use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Models\VotingLog;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function index()
    {
        // Ambil data pemilih yang sedang login
        /** @var Pemilih $pemilih */
        $pemilih = Auth::guard('pemilih')->user();

        // Ambil status voting dari blockchain
        $status = $this->blockchain->getStatusVoting();

        // Ambil semua kandidat
        $kandidat = Kandidat::where('aktif', true)
            ->where('terdaftar_blockchain', true)
            ->orderBy('nomor_urut')
            ->get();

        // Cek apakah pemilih sudah voting (cek dari blockchain langsung)
        $sudahVoting = false;
        if ($pemilih->wallet_address) {
            $sudahVoting = $this->blockchain->cekSudahVoting($pemilih->wallet_address);
        }

        return view('voting.index', compact(
            'pemilih',
            'kandidat',
            'status',
            'sudahVoting',
        ));
    }

    public function kirimSuara(Request $request)
    {
        $request->validate([
            'kandidat_id' => 'required|integer|exists:kandidat,id',
        ], [
            'kandidat_id.required' => 'Pilih kandidat terlebih dahulu',
            'kandidat_id.exists'   => 'Kandidat tidak valid',
        ]);

        /** @var Pemilih $pemilih */
        $pemilih = Auth::guard('pemilih')->user();

        // Validasi pemilih terdaftar di blockchain
        if (!$pemilih->terdaftar_blockchain) {
            return back()->withErrors(['error' => 'Akun Anda belum terdaftar di blockchain']);
        }

        // Validasi belum voting (dari DB dulu, lebih cepat)
        if ($pemilih->sudah_voting) {
            return back()->withErrors(['error' => 'Anda sudah memberikan suara']);
        }

        // Double check dari blockchain
        $sudahVotingBlockchain = $this->blockchain->cekSudahVoting($pemilih->wallet_address);
        if ($sudahVotingBlockchain) {
            // Sinkronisasi status DB
            DB::table('pemilih')
                ->where('id', $pemilih->id)
                ->update(['sudah_voting' => true]);

            return back()->withErrors(['error' => 'Anda sudah memberikan suara (terdeteksi di blockchain)']);
        }

        $kandidat = Kandidat::findOrFail($request->kandidat_id);

        // Kirim suara ke blockchain via signer service
        $result = $this->blockchain->castVote(
            $pemilih->wallet_address,
            $kandidat->id
        );

        if (!($result['success'] ?? false)) {
            return back()->withErrors([
                'error' => 'Gagal mengirim suara: ' . ($result['error'] ?? 'Unknown error')
            ]);
        }

        // Simpan log ke database
        VotingLog::create([
            'pemilih_id'  => $pemilih->id,
            'kandidat_id' => $kandidat->id,
            'tx_hash'     => $result['tx_hash'],
            'status'      => 'confirmed',
            'block_number'=> $result['block_number'],
            'voted_at'    => now(),
            'confirmed_at'=> now(),
        ]);

        // Update status pemilih di DB (pakai DB facade untuk hindari issue undefined method)
        DB::table('pemilih')
            ->where('id', $pemilih->id)
            ->update(['sudah_voting' => true]);

        return redirect()->route('voting.sukses', [
            'tx_hash'      => $result['tx_hash'],
            'nama_kandidat'=> $kandidat->nama,
        ]);
    }

    public function sukses(Request $request)
    {
        // Pastikan ada tx_hash, kalau tidak redirect ke voting
        if (!$request->has('tx_hash')) {
            return redirect()->route('voting.index');
        }

        return view('voting.sukses', [
            'tx_hash'       => $request->tx_hash,
            'nama_kandidat' => $request->nama_kandidat,
        ]);
    }
}