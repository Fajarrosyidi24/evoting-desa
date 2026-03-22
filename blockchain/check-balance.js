// import { ethers } from "ethers";
// import dotenv from "dotenv";
// dotenv.config();

// const provider = new ethers.JsonRpcProvider("https://rpc-amoy.polygon.technology");
// const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);
// const balance  = await provider.getBalance(wallet.address);

// console.log("Address :", wallet.address);
// console.log("Saldo   :", ethers.formatEther(balance), "MATIC");

import dotenv from "dotenv";
import { ethers } from "ethers";
dotenv.config();

const provider = new ethers.JsonRpcProvider("https://ethereum-sepolia-rpc.publicnode.com");
const wallet   = new ethers.Wallet(process.env.ADMIN_PRIVATE_KEY, provider);
const balance  = await provider.getBalance(wallet.address);

console.log("Address :", wallet.address);
console.log("Network : Ethereum Sepolia Testnet");
console.log("Saldo   :", ethers.formatEther(balance), "ETH");