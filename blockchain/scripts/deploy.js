import dotenv from "dotenv";
import { ethers } from "ethers";
import { readFileSync } from "fs";
import { join, dirname } from "path";
import { fileURLToPath } from "url";

dotenv.config();

const __dirname = dirname(fileURLToPath(import.meta.url));

async function main() {
    const artifactPath = join(__dirname, "../artifacts/contracts/Voting.sol/Voting.json");
    const artifact = JSON.parse(readFileSync(artifactPath, "utf8"));

    // Pakai localhost, bukan RPC dari .env
    const provider = new ethers.JsonRpcProvider("http://127.0.0.1:8545");
    const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);

    console.log("Deploy dari wallet :", wallet.address);

    const balance = await provider.getBalance(wallet.address);
    console.log("Saldo wallet       :", ethers.formatEther(balance), "ETH");

    if (balance === 0n) {
        console.error("ERROR: Saldo 0!");
        process.exit(1);
    }

    console.log("Deploying contract...");

    const factory  = new ethers.ContractFactory(artifact.abi, artifact.bytecode, wallet);
    const contract = await factory.deploy("Pilkades Desa Sukamaju 2025");

    await contract.waitForDeployment();

    const address = await contract.getAddress();

    console.log("======================================");
    console.log("Contract berhasil deploy!");
    console.log("Contract address:", address);
    console.log("======================================");
    console.log("CONTRACT_ADDRESS=" + address);
}

main().catch((error) => {
    console.error(error);
    process.exit(1);
});