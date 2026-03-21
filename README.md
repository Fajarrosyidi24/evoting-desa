# E-Voting Desa — Sistem Pemilihan Kepala Desa Berbasis Blockchain

Sistem e-voting transparan untuk pemilihan kepala desa menggunakan teknologi blockchain Ethereum. Setiap suara tercatat permanen di blockchain dan dapat diverifikasi publik.

## Arsitektur Sistem
```
evoting-desa/
├── evoting-desa/      → Backend & Frontend (Laravel)
├── evoting-signer/    → Blockchain Signer Service (Node.js)
└── blockchain/        → Smart Contract (Hardhat + Solidity)
```

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Smart Contract | Solidity 0.8.19 + Hardhat |
| Blockchain | Ethereum (local: Hardhat Network) |
| Signer Service | Node.js + Express + ethers.js |
| Backend | Laravel 11 (PHP 8.3) |
| Frontend | Blade + Tailwind CSS |
| Database | SQLite (development) |

---

## Prasyarat

Pastikan sudah terinstall:

- **PHP** >= 8.2
- **Composer**
- **Node.js** v22 LTS (bukan v25)
- **npm**
- **Git**

Cek versi:
```bash
php --version
composer --version
node --version   # harus v22.x.x
npm --version
```

---

## Cara Menjalankan Project (Development)

> Butuh **4 terminal** yang berjalan bersamaan

### Terminal 1 — Jalankan Hardhat Local Blockchain
```bash
cd blockchain
npm install
npx hardhat node
```

Biarkan terminal ini tetap berjalan. Hardhat akan membuat 20 wallet virtual dengan masing-masing 10.000 ETH untuk testing.

---

### Terminal 2 — Deploy Smart Contract

Buka terminal baru:
```bash
cd blockchain

# Salin .env.example ke .env
cp .env.example .env
```

Edit `.env`, isi dengan private key dari Account #0 yang muncul di Terminal 1:
```env
ADMIN_PRIVATE_KEY=0xac0974bec39a17e36ba4a6b4d238ff944bacb478cbed5efcae784d7bf4f2ff80
```

Deploy contract:
```bash
npx hardhat run scripts/deploy.js --network localhost
```

Salin `CONTRACT_ADDRESS` dari output. Contoh:
```
Contract address: 0x5FbDB2315678afecb367f032d93F642f64180aa3
```

---

### Terminal 3 — Jalankan Signer Service (Node.js)
```bash
cd evoting-signer
npm install

# Salin .env.example ke .env
cp .env.example .env
```

Edit `evoting-signer/.env`:
```env
PORT=3001
RPC_URL=http://127.0.0.1:8545
CHAIN_ID=31337
ADMIN_PRIVATE_KEY=0xac0974bec39a17e36ba4a6b4d238ff944bacb478cbed5efcae784d7bf4f2ff80
ADMIN_WALLET_ADDRESS=0xf39Fd6e51aad88F6F4ce6aB8827279cffFb92266
CONTRACT_ADDRESS=0x5FbDB2315678afecb367f032d93F642f64180aa3
INTERNAL_SECRET=ganti_dengan_string_rahasia_panjang
```

Jalankan:
```bash
node index.js
```

Test koneksi:
```bash
curl http://localhost:3001/status -H "x-internal-secret: ganti_dengan_string_rahasia_panjang"
# Expected: {"aktif":false,"sisa_detik":0}
```

---

### Terminal 4 — Jalankan Laravel
```bash
cd evoting-desa
composer install
npm install

# Salin .env.example ke .env
cp .env.example .env

# Generate app key
php artisan key:generate
```

Edit `evoting-desa/.env`:
```env
APP_NAME="E-Voting Desa"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite

BLOCKCHAIN_RPC_URL=http://127.0.0.1:8545
BLOCKCHAIN_CHAIN_ID=31337
CONTRACT_ADDRESS=0x5FbDB2315678afecb367f032d93F642f64180aa3
ADMIN_WALLET_ADDRESS=0xf39Fd6e51aad88F6F4ce6aB8827279cffFb92266
ADMIN_PRIVATE_KEY=0xac0974bec39a17e36ba4a6b4d238ff944bacb478cbed5efcae784d7bf4f2ff80

SIGNER_URL=http://localhost:3001
INTERNAL_SECRET=ganti_dengan_string_rahasia_panjang
```

Setup database & seed data:
```bash
# Buat file database SQLite
touch database/database.sqlite

# Jalankan migration
php artisan migrate

# Seed data dummy + sync ke blockchain
php artisan db:seed
```

Jalankan server:
```bash
php artisan serve
```

---

## Akses Aplikasi

| Halaman | URL |
|---------|-----|
| Hasil Voting Publik | http://localhost:8000 |
| Login Warga | http://localhost:8000/login |
| Login Admin | http://localhost:8000/admin/login |
| Riwayat Transaksi | http://localhost:8000/transaksi |

---

## Akun Default (setelah seeding)

### Admin / Panitia
| Field | Value |
|-------|-------|
| Email | admin@desa.id |
| Password | password123 |

### Warga (contoh)
| Field | Value |
|-------|-------|
| NIK | 3578012345678901 |
| Tanggal Lahir | 1990-05-15 |

---

## Alur Penggunaan Sistem
```
1. Admin login → Dashboard
2. Admin tambah kandidat → Daftarkan ke blockchain
3. Admin tambah pemilih → Daftarkan ke blockchain
4. Admin mulai voting (set durasi)
5. Warga login dengan NIK + tanggal lahir
6. Warga pilih kandidat → Konfirmasi → Suara tercatat di blockchain
7. Publik bisa pantau hasil di halaman publik (real-time)
8. Admin akhiri voting → Hasil final tampil
9. Siapapun bisa verifikasi suara via TX hash
```

---

## Perintah Berguna

### Reset total (migrate fresh + seed ulang)
```bash
# Pastikan Hardhat node berjalan & signer aktif
php artisan migrate:fresh --seed
```

### Sync ulang data ke blockchain (setelah Hardhat node restart)
```bash
php artisan blockchain:sync
```

### Cek status blockchain
```bash
curl http://localhost:3001/status -H "x-internal-secret: YOUR_SECRET"
```

---

## Struktur Folder Penting
```
evoting-desa/                    ← Laravel
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/               ← Controller admin
│   │   ├── Auth/                ← Controller autentikasi
│   │   ├── PublikController.php ← Halaman publik
│   │   └── VotingController.php ← Halaman voting warga
│   ├── Models/                  ← Eloquent models
│   └── Services/
│       └── BlockchainService.php ← Jembatan ke signer
├── database/
│   └── seeders/
│       └── DatabaseSeeder.php   ← Seed + sync blockchain
└── resources/views/             ← Blade templates

evoting-signer/                  ← Node.js
├── services/
│   └── blockchain.js            ← Logic ethers.js
├── middleware/
│   └── auth.js                  ← Validasi secret key
└── index.js                     ← Express server

blockchain/                      ← Hardhat
├── contracts/
│   └── Voting.sol               ← Smart contract
└── scripts/
    └── deploy.js                ← Script deploy
```

---

## Catatan Penting

> **Hardhat local network reset setiap kali dimatikan.** Setelah Hardhat node di-restart, jalankan ulang deploy dan seed:
> ```bash
> # Terminal 2
> npx hardhat run scripts/deploy.js --network localhost
> # Update CONTRACT_ADDRESS di .env signer & laravel
>
> # Terminal 4
> php artisan migrate:fresh --seed
> ```

> **Private key di `.env` adalah kunci Hardhat bawaan** yang bersifat publik dan hanya untuk development. Jangan gunakan di mainnet.

---

## Lisensi

MIT License — bebas digunakan dan dimodifikasi untuk keperluan desa.