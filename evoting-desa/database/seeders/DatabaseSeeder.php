<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pemilih;
use App\Models\Kandidat;
use App\Models\Setting;
use App\Services\BlockchainService;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $blockchain = app(BlockchainService::class);

        // ─── ADMIN ─────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@desa.id'],
            [
                'name'     => 'Admin Panitia',
                'email'    => 'admin@desa.id',
                'password' => Hash::make('password123'),
            ]
        );
        $this->command->info('✓ Admin seeded');

        // ─── SETTINGS ──────────────────────────────────────────
        $settings = [
            ['key' => 'nama_voting',      'value' => 'Pilkades Desa Sukamaju 2025'],
            ['key' => 'nama_desa',        'value' => 'Desa Sukamaju'],
            ['key' => 'voting_aktif',     'value' => 'false'],
            ['key' => 'contract_address', 'value' => env('CONTRACT_ADDRESS', '')],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], $s);
        }
        $this->command->info('✓ Settings seeded');

        // ─── AKHIRI VOTING JIKA MASIH AKTIF ───────────────────
    $this->command->info('--- Cek Status Voting ---');
    $status = $blockchain->getStatusVoting();

    if ($status['aktif']) {
        $this->command->warn('  Voting masih aktif, mengakhiri dulu...');
        sleep(2);
        $result = $blockchain->akhiriVoting();
        if ($result['success'] ?? false) {
            $this->command->info('  ✓ Voting berhasil diakhiri');
        } else {
            $this->command->error('  ✗ Gagal akhiri voting: ' . ($result['error'] ?? 'Unknown'));
            $this->command->error('  Hentikan proses — akhiri voting manual dulu dari admin panel');
            return;
        }
        sleep(2);
    } else {
        $this->command->info('  ✓ Voting tidak aktif, lanjut...');
    }

        // ─── KANDIDAT + SYNC BLOCKCHAIN ────────────────────────
        $this->command->info('--- Sync Kandidat ke Blockchain ---');

        $kandidatData = [
            [
                'nomor_urut' => 1,
                'nama'       => 'H. Suryanto, S.Sos',
                'visi'       => 'Mewujudkan desa mandiri, sejahtera, dan berbudaya',
                'misi'       => "1. Meningkatkan infrastruktur desa\n2. Mengembangkan potensi UMKM\n3. Meningkatkan kualitas pendidikan",
                'aktif'      => true,
            ],
            [
                'nomor_urut' => 2,
                'nama'       => 'Ir. Bambang Widodo',
                'visi'       => 'Membangun desa yang transparan, inovatif, dan berdaya saing',
                'misi'       => "1. Digitalisasi pelayanan desa\n2. Pemberdayaan pemuda\n3. Pengelolaan dana desa yang transparan",
                'aktif'      => true,
            ],
            [
                'nomor_urut' => 3,
                'nama'       => 'Siti Rahayu, S.Pd',
                'visi'       => 'Desa yang adil, merata, dan berwawasan lingkungan',
                'misi'       => "1. Peningkatan kesehatan masyarakat\n2. Pelestarian lingkungan hidup\n3. Pemberdayaan perempuan dan anak",
                'aktif'      => true,
            ],
        ];

        foreach ($kandidatData as $k) {
            $kandidat = Kandidat::updateOrCreate(
                ['nomor_urut' => $k['nomor_urut']],
                array_merge($k, ['terdaftar_blockchain' => false])
            );

            // Tunggu 2 detik sebelum kirim transaksi
            // supaya nonce tidak bentrok
            sleep(2);

            $result = $blockchain->tambahKandidat($kandidat->nama, $kandidat->visi);

            if ($result['success'] ?? false) {
                DB::table('kandidat')
                    ->where('id', $kandidat->id)
                    ->update(['terdaftar_blockchain' => true]);
                $this->command->info("  ✓ {$kandidat->nama}");
            } else {
                $this->command->warn("  ✗ {$kandidat->nama}: " . ($result['error'] ?? 'Unknown'));

                // Retry sekali kalau gagal
                $this->command->warn("  Retrying {$kandidat->nama}...");
                sleep(3);
                $retry = $blockchain->tambahKandidat($kandidat->nama, $kandidat->visi);
                if ($retry['success'] ?? false) {
                    DB::table('kandidat')
                        ->where('id', $kandidat->id)
                        ->update(['terdaftar_blockchain' => true]);
                    $this->command->info("  ✓ {$kandidat->nama} (retry berhasil)");
                } else {
                    $this->command->error("  ✗ {$kandidat->nama} gagal setelah retry: " . ($retry['error'] ?? 'Unknown'));
                }
            }
        }

        // ─── PEMILIH + SYNC BLOCKCHAIN ─────────────────────────
        $this->command->newLine();
        $this->command->info('--- Sync Pemilih ke Blockchain ---');

        $pemilihData = [
            [
                'nik'            => '3578012345678901',
                'nama'           => 'Budi Santoso',
                'alamat'         => 'Jl. Mawar No. 1, RT 01/RW 02',
                'no_hp'          => '081234567890',
                'tanggal_lahir'  => '1990-05-15',
                'wallet_address' => '0x70997970c51812dc3a010c7d01b50e0d17dc79c8',
            ],
            [
                'nik'            => '3578012345678902',
                'nama'           => 'Siti Aminah',
                'alamat'         => 'Jl. Melati No. 5, RT 02/RW 01',
                'no_hp'          => '082345678901',
                'tanggal_lahir'  => '1985-08-20',
                'wallet_address' => '0x3c44cdddb6a900fa2b585dd299e03d12fa4293bc',
            ],
            [
                'nik'            => '3578012345678903',
                'nama'           => 'Ahmad Fauzi',
                'alamat'         => 'Jl. Kenanga No. 12, RT 03/RW 03',
                'no_hp'          => '083456789012',
                'tanggal_lahir'  => '1995-12-10',
                'wallet_address' => '0x90f79bf6eb2c4f870365e785982e1f101e93b906',
            ],
            [
                'nik'            => '3578012345678904',
                'nama'           => 'Dewi Ratnasari',
                'alamat'         => 'Jl. Anggrek No. 8, RT 01/RW 04',
                'no_hp'          => '084567890123',
                'tanggal_lahir'  => '1992-03-25',
                'wallet_address' => '0x15d34aaf54267db7d7c367839aaf71a00a2c6a65',
            ],
            [
                'nik'            => '3578012345678905',
                'nama'           => 'Hendra Gunawan',
                'alamat'         => 'Jl. Dahlia No. 3, RT 04/RW 02',
                'no_hp'          => '085678901234',
                'tanggal_lahir'  => '1988-07-07',
                'wallet_address' => '0x9965507d1a55bcc2695c58ba16fb37d819b0a4dc',
            ],
        ];

        foreach ($pemilihData as $p) {
            $pemilih = Pemilih::updateOrCreate(
                ['nik' => $p['nik']],
                array_merge($p, [
                    'terdaftar_blockchain' => false,
                    'sudah_voting'         => false,
                ])
            );

            // Tunggu 2 detik
            sleep(2);

            $result = $blockchain->daftarkanPemilih($pemilih->wallet_address);

            if ($result['success'] ?? false) {
                DB::table('pemilih')
                    ->where('id', $pemilih->id)
                    ->update(['terdaftar_blockchain' => true]);
                $this->command->info("  ✓ {$pemilih->nama}");
            } else {
                $this->command->warn("  ✗ {$pemilih->nama}: " . ($result['error'] ?? 'Unknown'));

                // Retry sekali
                $this->command->warn("  Retrying {$pemilih->nama}...");
                sleep(3);
                $retry = $blockchain->daftarkanPemilih($pemilih->wallet_address);
                if ($retry['success'] ?? false) {
                    DB::table('pemilih')
                        ->where('id', $pemilih->id)
                        ->update(['terdaftar_blockchain' => true]);
                    $this->command->info("  ✓ {$pemilih->nama} (retry berhasil)");
                } else {
                    $this->command->error("  ✗ {$pemilih->nama} gagal setelah retry: " . ($retry['error'] ?? 'Unknown'));
                }
            }
        }

        // ─── SUMMARY ───────────────────────────────────────────
        $this->command->newLine();
        $this->command->info('======================================');
        $this->command->info('Seeding & sync blockchain selesai!');
        $this->command->info('======================================');
        $this->command->newLine();

        $totalKandidat = Kandidat::where('terdaftar_blockchain', true)->count();
        $totalPemilih  = Pemilih::where('terdaftar_blockchain', true)->count();

        $this->command->info("Kandidat terdaftar : {$totalKandidat} / " . count($kandidatData));
        $this->command->info("Pemilih terdaftar  : {$totalPemilih} / " . count($pemilihData));
        $this->command->newLine();
        $this->command->info('Login Admin  : admin@desa.id / password123');
        $this->command->info('Login Warga  : NIK 3578012345678901 / tgl lahir 1990-05-15');
        $this->command->newLine();
        $this->command->warn('INGAT: Mulai voting dulu dari admin panel!');
        $this->command->warn('http://localhost:8000/admin/voting');
    }
}