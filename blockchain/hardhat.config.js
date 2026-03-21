import dotenv from "dotenv";
dotenv.config();

const config = {
    solidity: "0.8.19",
    networks: {
        localhost: {
            type    : "http",
            url     : "http://127.0.0.1:8545",
            chainId : 31337,
            accounts: [process.env.ADMIN_PRIVATE_KEY],
        },
        amoy: {
            type    : "http",
            url     : "https://rpc-amoy.polygon.technology",
            accounts: [process.env.ADMIN_PRIVATE_KEY],
            chainId : 80002,
        },
    },
};

export default config;