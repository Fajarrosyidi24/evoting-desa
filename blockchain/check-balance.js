import { ethers } from "ethers";
import dotenv from "dotenv";
dotenv.config();

const provider = new ethers.JsonRpcProvider("https://rpc-amoy.polygon.technology");
const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);
const balance  = await provider.getBalance(wallet.address);

console.log("Address :", wallet.address);
console.log("Saldo   :", ethers.formatEther(balance), "MATIC");