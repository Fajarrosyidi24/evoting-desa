import dotenv from "dotenv";
import { ethers } from "ethers";
import { readFileSync } from "fs";
import { join, dirname } from "path";
import { fileURLToPath } from "url";

dotenv.config();

const __dirname = dirname(fileURLToPath(import.meta.url));

async function main() {
    const artifactPath = join(__dirname, "../artifacts/contracts/Voting.sol/Voting.json");
    const artifact     = JSON.parse(readFileSync(artifactPath, "utf8"));

    // Ambil RPC dari .env — tidak hardcode localhost lagi
    const rpcUrl   = process.env.RPC_URL;
    const provider = new ethers.JsonRpcProvider(rpcUrl);
    const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);

    console.log("======================================");
    console.log("Deploy dari wallet :", wallet.address);

    const balance = await provider.getBalance(wallet.address);
    console.log("Saldo wallet       :", ethers.formatEther(balance), "ETH");
    console.log("Network            :", rpcUrl);
    console.log("======================================");

    if (balance === 0n) {
        console.error("ERROR: Saldo 0!");
        process.exit(1);
    }

    console.log("Deploying contract...");

    const factory  = new ethers.ContractFactory(artifact.abi, artifact.bytecode, wallet);
    const contract = await factory.deploy("Pilkades Desa Sukamaju 2025");

    console.log("Menunggu konfirmasi blockchain... (bisa 15-30 detik)");
    await contract.waitForDeployment();

    const address = await contract.getAddress();

    console.log("======================================");
    console.log("Contract berhasil deploy!");
    console.log("Contract address:", address);
    console.log("======================================");
    console.log("Update .env kamu:");
    console.log("CONTRACT_ADDRESS=" + address);
    console.log("");
    console.log("Cek di Sepolia explorer:");
    console.log("https://sepolia.etherscan.io/address/" + address);
}

main().catch((error) => {
    console.error(error);
    process.exit(1);
});