require('dotenv').config();
const express    = require('express');
const blockchain = require('./services/blockchain');
const auth       = require('./middleware/auth');

const app = express();
app.use(express.json());
app.use(auth); // semua route wajib pakai secret key

// ─── ROUTES ──────────────────────────────────────────────

// Daftarkan pemilih ke blockchain
app.post('/pemilih/daftarkan', async (req, res) => {
    const { wallet_address } = req.body;

    if (!wallet_address) {
        return res.status(422).json({ error: 'wallet_address wajib diisi' });
    }

    const result = await blockchain.daftarkanPemilih(wallet_address);
    return res.json(result);
});

// Kirim suara ke blockchain
app.post('/vote', async (req, res) => {
    const { wallet_address, kandidat_id } = req.body;

    if (!wallet_address || !kandidat_id) {
        return res.status(422).json({ error: 'wallet_address dan kandidat_id wajib diisi' });
    }

    const result = await blockchain.castVote(wallet_address, Number(kandidat_id));
    return res.json(result);
});

// Ambil jumlah suara satu kandidat
app.get('/suara/:kandidatId', async (req, res) => {
    const count = await blockchain.getVoteCount(Number(req.params.kandidatId));
    return res.json({ kandidat_id: req.params.kandidatId, jumlah_suara: count });
});

// Ambil hasil semua kandidat
app.get('/hasil', async (req, res) => {
    try {
        const total = Number(req.query.total) || 0;

        // Kalau total 0, langsung return kosong
        if (total === 0) {
            return res.json({ hasil: [] });
        }

        const hasil = await blockchain.getHasilVoting(total);
        return res.json({ hasil });
    } catch (error) {
        return res.json({ hasil: [] });
    }
});

// Status voting (aktif/tidak + sisa waktu)
app.get('/status', async (req, res) => {
    const status = await blockchain.getStatusVoting();
    return res.json(status);
});

// Pemenang
app.get('/winner', async (req, res) => {
    const winner = await blockchain.getWinner();
    return res.json(winner);
});

// Tambah kandidat ke blockchain
app.post('/kandidat/tambah', async (req, res) => {
    const { nama, visi } = req.body;

    if (!nama || !visi) {
        return res.status(422).json({ error: 'nama dan visi wajib diisi' });
    }

    const result = await blockchain.tambahKandidat(nama, visi);
    return res.json(result);
});

// Cek apakah pemilih sudah voting
app.get('/pemilih/cek-voting', async (req, res) => {
    const { wallet_address } = req.query;

    if (!wallet_address) {
        return res.status(422).json({ error: 'wallet_address wajib diisi' });
    }

    try {
        const sudahVoting = await blockchain.sudahVoting(wallet_address);
        return res.json({ sudah_voting: sudahVoting });
    } catch (error) {
        return res.json({ sudah_voting: false });
    }
});

// Mulai voting
app.post('/voting/mulai', async (req, res) => {
    const { durasi_menit } = req.body;

    if (!durasi_menit) {
        return res.status(422).json({ error: 'durasi_menit wajib diisi' });
    }

    const result = await blockchain.mulaiVoting(Number(durasi_menit));
    return res.json(result);
});

// Akhiri voting
app.post('/voting/akhiri', async (req, res) => {
    const result = await blockchain.akhiriVoting();
    return res.json(result);
});

// ─── START ───────────────────────────────────────────────

const PORT = process.env.PORT || 3001;
app.listen(PORT, () => {
    console.log(`Signer service berjalan di port ${PORT}`);
});