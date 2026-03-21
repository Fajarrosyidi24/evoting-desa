import dotenv from "dotenv";
import { ethers } from "ethers";
dotenv.config();

const CONTRACT_ABI = [
    "function daftarkanPemilih(address _pemilih) external",
    "function tambahKandidat(string memory _nama, string memory _visi) external",
];

const provider = new ethers.JsonRpcProvider("http://127.0.0.1:8545");
const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);
const contract = new ethers.Contract(process.env.CONTRACT_ADDRESS, CONTRACT_ABI, wallet);

// ─── Data yang perlu disinkronisasi ───────────────────
const kandidat = [
    { nama: "H. Suryanto, S.Sos", visi: "Mewujudkan desa mandiri, sejahtera, dan berbudaya" },
    { nama: "Ir. Bambang Widodo",  visi: "Membangun desa yang transparan, inovatif, dan berdaya saing" },
    { nama: "Siti Rahayu, S.Pd",  visi: "Desa yang adil, merata, dan berwawasan lingkungan" },
];

const pemilih = [
    "0x70997970c51812dc3a010c7d01b50e0d17dc79c8",
    "0x3c44cdddb6a900fa2b585dd299e03d12fa4293bc",
    "0x90f79bf6eb2c4f870365e785982e1f101e93b906",
    "0x15d34aaf54267db7d7c367839aaf71a00a2c6a65",
    "0x9965507d1a55bcc2695c58ba16fb37d819b0a4dc",
];

async function main() {
    console.log("Mulai sinkronisasi...\n");

    // Daftarkan kandidat
    for (const k of kandidat) {
        try {
            const tx = await contract.tambahKandidat(k.nama, k.visi, { gasLimit: 300000 });
            await tx.wait(1);
            console.log(`✓ Kandidat: ${k.nama}`);
        } catch (e) {
            console.log(`✗ Kandidat ${k.nama}: ${e.reason || e.message}`);
        }
    }

    console.log('');

    // Daftarkan pemilih
    for (const addr of pemilih) {
        try {
            const tx = await contract.daftarkanPemilih(addr, { gasLimit: 100000 });
            await tx.wait(1);
            console.log(`✓ Pemilih: ${addr}`);
        } catch (e) {
            console.log(`✗ Pemilih ${addr}: ${e.reason || e.message}`);
        }
    }

    console.log('\nSinkronisasi selesai!');
}

main().catch(console.error);