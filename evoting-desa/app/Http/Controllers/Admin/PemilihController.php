<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilih;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PemilihController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function index()
    {
        $pemilih = Pemilih::orderBy('nama')->paginate(20);
        return view('admin.pemilih.index', compact('pemilih'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik'           => 'required|digits:16|unique:pemilih,nik',
            'nama'          => 'required|string|max:100',
            'alamat'        => 'nullable|string',
            'no_hp'         => 'nullable|string|max:15',
            'tanggal_lahir' => 'required|date',
        ], [
            'nik.unique'  => 'NIK sudah terdaftar',
            'nik.digits'  => 'NIK harus 16 digit',
        ]);

        // Generate wallet address unik untuk pemilih ini
        // Pakai account Hardhat berikutnya atau generate random
        $walletAddress = $this->generateWalletAddress();

        $pemilih = Pemilih::create([
            'nik'                  => $request->nik,
            'nama'                 => $request->nama,
            'alamat'               => $request->alamat,
            'no_hp'                => $request->no_hp,
            'tanggal_lahir'        => $request->tanggal_lahir,
            'wallet_address'       => $walletAddress,
            'terdaftar_blockchain' => false,
            'sudah_voting'         => false,
        ]);

        // Daftarkan wallet pemilih ke smart contract
        $result = $this->blockchain->daftarkanPemilih($walletAddress);

        if ($result['success'] ?? false) {
            $pemilih->update(['terdaftar_blockchain' => true]);
            return back()->with('success', "Pemilih {$pemilih->nama} berhasil didaftarkan!");
        }

        return back()->with('warning', "Pemilih tersimpan tapi gagal ke blockchain: " . ($result['error'] ?? 'Unknown error'));
    }

    public function daftarkanKeBlockchain(Pemilih $pemilih)
    {
        if ($pemilih->terdaftar_blockchain) {
            return back()->withErrors(['error' => 'Pemilih sudah terdaftar di blockchain']);
        }

        $result = $this->blockchain->daftarkanPemilih($pemilih->wallet_address);

        if ($result['success'] ?? false) {
            $pemilih->update(['terdaftar_blockchain' => true]);
            return back()->with('success', "{$pemilih->nama} berhasil didaftarkan ke blockchain!");
        }

        return back()->withErrors(['error' => $result['error'] ?? 'Gagal mendaftarkan ke blockchain']);
    }

    public function destroy(Pemilih $pemilih)
    {
        if ($pemilih->terdaftar_blockchain) {
            return back()->withErrors(['error' => 'Pemilih yang sudah terdaftar di blockchain tidak bisa dihapus']);
        }

        $pemilih->delete();
        return back()->with('success', 'Data pemilih berhasil dihapus');
    }

    private function generateWalletAddress(): string
    {
        // Generate address acak yang valid (untuk local development)
        // Di production, tiap pemilih sebaiknya punya wallet sendiri
        return '0x' . bin2hex(random_bytes(20));
    }
}