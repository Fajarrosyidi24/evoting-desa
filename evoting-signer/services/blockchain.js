const { ethers } = require('ethers');

const CONTRACT_ABI = [
    "function daftarkanPemilih(address _pemilih) external",
    "function tambahKandidat(string memory _nama, string memory _visi) external",
    "function mulaiVoting(uint256 _durasiMenit) external",
    "function akhiriVoting() external",
    "function vote(address _pemilihAddr, uint256 _kandidatId) external",
    "function getVoteCount(uint256 _id) external view returns (uint256)",
    "function getTotalKandidat() external view returns (uint256)",
    "function sudahVoting(address _addr) external view returns (bool)",
    "function getWinner() external view returns (uint256, string, uint256)",
    "function sisaWaktu() external view returns (uint256)",
    "function votingAktif() external view returns (bool)",
    "function pemilih(address) external view returns (bool, bool, uint256, uint256)",
    "event SuaraDiberikan(address indexed pemilihAddr, uint256 indexed kandidatId, uint256 waktu)",
];

class BlockchainService {
    constructor() {
        this.provider = new ethers.JsonRpcProvider(process.env.RPC_URL);
        this.wallet = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, this.provider);
        this.contract = new ethers.Contract(
            process.env.CONTRACT_ADDRESS,
            CONTRACT_ABI,
            this.wallet
        );
    }

    // Selalu ambil nonce terbaru dari blockchain — fix nonce error
    async getNonce() {
        return await this.provider.getTransactionCount(this.wallet.address, 'pending');
    }

    // ─── WRITE FUNCTIONS ───────────────────────────────────

    async tambahKandidat(nama, visi) {
    try {
        // Cek voting aktif dulu sebelum kirim transaksi
        const votingAktif = await this.contract.votingAktif();
        if (votingAktif) {
            return {
                success: false,
                error  : 'Tidak bisa tambah kandidat saat voting sedang berlangsung',
            };
        }

        const nonce = await this.getNonce();
        const tx    = await this.contract.tambahKandidat(nama, visi, {
            gasLimit: 300000,
            nonce,
        });

        const receipt = await tx.wait(1);

        return {
            success     : true,
            tx_hash     : receipt.hash,
            block_number: receipt.blockNumber.toString(),
        };
    } catch (error) {
        const pesan = this._parseError(error);

        // Kalau sudah terdaftar, anggap sukses
        if (pesan.includes('sudah') || pesan.includes('already')) {
            return { success: true, tx_hash: 'already_registered', block_number: '0' };
        }

        return { success: false, error: pesan };
    }
}

    async daftarkanPemilih(walletAddress) {
        try {
            // Cek dulu apakah sudah terdaftar
            const pemilihData = await this.contract.pemilih(walletAddress);
            if (pemilihData[0]) {
                // Sudah terdaftar — anggap sukses, tidak perlu daftar lagi
                return {
                    success: true,
                    tx_hash: 'already_registered',
                    block_number: '0',
                };
            }

            const nonce = await this.getNonce();
            const tx = await this.contract.daftarkanPemilih(walletAddress, {
                gasLimit: 100000,
                nonce,
            });

            const receipt = await tx.wait(1);

            return {
                success: true,
                tx_hash: receipt.hash,
                block_number: receipt.blockNumber.toString(),
            };
        } catch (error) {
            return {
                success: false,
                error: this._parseError(error),
            };
        }
    }

    async castVote(walletAddress, kandidatId) {
        try {
            // Cek sudah voting
            const alreadyVoted = await this.contract.sudahVoting(walletAddress);
            if (alreadyVoted) {
                return { success: false, error: 'Pemilih sudah memberikan suara' };
            }

            // Cek voting aktif
            const aktif = await this.contract.votingAktif();
            if (!aktif) {
                return { success: false, error: 'Voting tidak aktif' };
            }

            // Cek kandidat valid
            const totalKandidat = await this.contract.getTotalKandidat();
            if (kandidatId > Number(totalKandidat)) {
                return { success: false, error: `Kandidat ID ${kandidatId} tidak valid` };
            }

            // Cek pemilih terdaftar
            const pemilihData = await this.contract.pemilih(walletAddress);
            if (!pemilihData[0]) {
                return { success: false, error: `Wallet ${walletAddress} tidak terdaftar di blockchain` };
            }

            console.log(`Casting vote: wallet=${walletAddress}, kandidatId=${kandidatId}`);

            const nonce = await this.getNonce();
            const tx = await this.contract.vote(walletAddress, kandidatId, {
                gasLimit: 150000,
                nonce,
            });

            const receipt = await tx.wait(1);

            return {
                success: true,
                tx_hash: receipt.hash,
                block_number: receipt.blockNumber.toString(),
            };
        } catch (error) {
            console.error('castVote error:', error);
            return {
                success: false,
                error: this._parseError(error),
            };
        }
    }

    async mulaiVoting(durasiMenit) {
        try {
            const nonce = await this.getNonce();
            const tx = await this.contract.mulaiVoting(durasiMenit, {
                gasLimit: 150000,
                nonce,
            });

            const receipt = await tx.wait(1);

            return {
                success: true,
                tx_hash: receipt.hash,
                block_number: receipt.blockNumber.toString(),
            };
        } catch (error) {
            return {
                success: false,
                error: this._parseError(error),
            };
        }
    }

    async akhiriVoting() {
        try {
            const nonce = await this.getNonce();
            const tx = await this.contract.akhiriVoting({
                gasLimit: 100000,
                nonce,
            });

            const receipt = await tx.wait(1);

            return {
                success: true,
                tx_hash: receipt.hash,
                block_number: receipt.blockNumber.toString(),
            };
        } catch (error) {
            return {
                success: false,
                error: this._parseError(error),
            };
        }
    }

    // ─── READ FUNCTIONS ────────────────────────────────────

    async getVoteCount(kandidatId) {
        const count = await this.contract.getVoteCount(kandidatId);
        return Number(count);
    }

    async getStatusVoting() {
        const [aktif, sisaWaktu] = await Promise.all([
            this.contract.votingAktif(),
            this.contract.sisaWaktu(),
        ]);

        return {
            aktif: aktif,
            sisa_detik: Number(sisaWaktu),
        };
    }

    async getHasilVoting(totalKandidat) {
        const hasil = [];

        for (let i = 1; i <= totalKandidat; i++) {
            const count = await this.contract.getVoteCount(i);
            hasil.push({ kandidat_id: i, jumlah_suara: Number(count) });
        }

        return hasil;
    }

    async getWinner() {
        const [id, nama, suara] = await this.contract.getWinner();
        return {
            kandidat_id: Number(id),
            nama: nama,
            jumlah_suara: Number(suara),
        };
    }

    async sudahVoting(walletAddress) {
        return await this.contract.sudahVoting(walletAddress);
    }

    // ─── HELPER ────────────────────────────────────────────

   _parseError(error) {
    // Tangkap reason string dari revert — ini yang paling akurat
    if (error?.reason) return error.reason;

    // Cek di error info (Hardhat local node)
    if (error?.info?.error?.data?.message) return error.info.error.data.message;
    if (error?.info?.error?.message) return error.info.error.message;

    // Cek shortMessage dari ethers
    if (error?.shortMessage) return error.shortMessage;

    // Fallback cek message string
    const msg = error?.message || '';
    if (msg.includes('Tidak bisa tambah kandidat saat voting aktif')) return 'Tidak bisa tambah kandidat saat voting aktif';
    if (msg.includes('Pemilih sudah terdaftar')) return 'Pemilih sudah terdaftar';
    if (msg.includes('Pemilih sudah')) return 'Pemilih sudah memberikan suara';
    if (msg.includes('Voting tidak aktif')) return 'Voting tidak aktif';
    if (msg.includes('already been used')) return 'Nonce error - coba lagi';
    if (msg.includes('Nonce too low')) return 'Nonce error - coba lagi';

    return msg || 'Terjadi kesalahan pada blockchain';
}
}

module.exports = new BlockchainService();