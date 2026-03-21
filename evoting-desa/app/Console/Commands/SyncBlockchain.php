<?php

namespace App\Console\Commands;

use App\Models\Kandidat;
use App\Models\Pemilih;
use App\Services\BlockchainService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncBlockchain extends Command
{
    protected $signature   = 'blockchain:sync';
    protected $description = 'Sync ulang semua data ke blockchain (pakai setelah Hardhat node restart)';

    public function __construct(protected BlockchainService $blockchain)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Memulai sync blockchain...');
        $this->newLine();

        // Sync kandidat
        $this->info('--- Sync Kandidat ---');
        $kandidat = Kandidat::where('aktif', true)->orderBy('id')->get();

        foreach ($kandidat as $k) {
            $result = $this->blockchain->tambahKandidat($k->nama, $k->visi);

            if ($result['success'] ?? false) {
                DB::table('kandidat')->where('id', $k->id)->update(['terdaftar_blockchain' => true]);
                $this->info("✓ {$k->nama}");
            } else {
                $this->error("✗ {$k->nama}: " . ($result['error'] ?? 'Unknown'));
            }
        }

        $this->newLine();

        // Sync pemilih
        $this->info('--- Sync Pemilih ---');
        $pemilih = Pemilih::whereNotNull('wallet_address')->orderBy('id')->get();

        foreach ($pemilih as $p) {
            $result = $this->blockchain->daftarkanPemilih($p->wallet_address);

            if ($result['success'] ?? false) {
                DB::table('pemilih')->where('id', $p->id)->update(['terdaftar_blockchain' => true]);
                $this->info("✓ {$p->nama} ({$p->wallet_address})");
            } else {
                $this->error("✗ {$p->nama}: " . ($result['error'] ?? 'Unknown'));
            }
        }

        $this->newLine();
        $this->info('Sync selesai! Jangan lupa mulai voting lagi dari admin panel.');
    }
}