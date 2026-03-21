<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BlockchainService
{
    private string $baseUrl;
    private array  $headers;

    public function __construct()
    {
        $this->baseUrl = env('SIGNER_URL', 'http://localhost:3001');
        $this->headers = [
            'x-internal-secret' => env('INTERNAL_SECRET'),
            'Content-Type'      => 'application/json',
        ];
    }

    // ─── WRITE ─────────────────────────────────────────────

    public function daftarkanPemilih(string $walletAddress): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(60) // transaksi blockchain bisa makan waktu
                ->post("{$this->baseUrl}/pemilih/daftarkan", [
                    'wallet_address' => $walletAddress,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('BlockchainService::daftarkanPemilih error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Gagal terhubung ke blockchain service'];
        }
    }

    public function castVote(string $walletAddress, int $kandidatId): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(60)
                ->post("{$this->baseUrl}/vote", [
                    'wallet_address' => $walletAddress,
                    'kandidat_id'    => $kandidatId,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('BlockchainService::castVote error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Gagal terhubung ke blockchain service'];
        }
    }

    // ─── READ ──────────────────────────────────────────────

    public function getVoteCount(int $kandidatId): int
    {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/suara/{$kandidatId}");

        return $response->json('jumlah_suara', 0);
    }

    public function getHasilVoting(int $totalKandidat): array
    {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/hasil", ['total' => $totalKandidat]);

        return $response->json('hasil', []);
    }

    public function getStatusVoting(): array
    {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/status");

        return $response->json();
    }

    public function getWinner(): array
    {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/winner");

        return $response->json();
    }

    public function tambahKandidat(string $nama, string $visi): array
    {
        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(60)
                ->post("{$this->baseUrl}/kandidat/tambah", [
                    'nama' => $nama,
                    'visi' => $visi,
                ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('BlockchainService::tambahKandidat error', ['error' => $e->getMessage()]);
            return ['success' => false, 'error' => 'Gagal terhubung ke blockchain service'];
        }
    }

   public function cekSudahVoting(string $walletAddress): bool
{
    try {
        $response = Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/pemilih/cek-voting", [
                'wallet_address' => $walletAddress,
            ]);

        return $response->json('sudah_voting', false);
    } catch (\Exception $e) {
        Log::error('BlockchainService::cekSudahVoting error', ['error' => $e->getMessage()]);
        return false;
    }
}

public function mulaiVoting(int $durasiMenit): array
{
    try {
        $response = Http::withHeaders($this->headers)
            ->timeout(60)
            ->post("{$this->baseUrl}/voting/mulai", [
                'durasi_menit' => $durasiMenit,
            ]);

        return $response->json();
    } catch (\Exception $e) {
        Log::error('BlockchainService::mulaiVoting error', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => 'Gagal terhubung ke blockchain service'];
    }
}

public function akhiriVoting(): array
{
    try {
        $response = Http::withHeaders($this->headers)
            ->timeout(60)
            ->post("{$this->baseUrl}/voting/akhiri");

        return $response->json();
    } catch (\Exception $e) {
        Log::error('BlockchainService::akhiriVoting error', ['error' => $e->getMessage()]);
        return ['success' => false, 'error' => 'Gagal terhubung ke blockchain service'];
    }
}
}
