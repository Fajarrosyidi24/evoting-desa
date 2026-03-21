<?php

return [
    'rpc_url'          => env('BLOCKCHAIN_RPC_URL'),
    'chain_id'         => env('BLOCKCHAIN_CHAIN_ID', 80001),
    'admin_address'    => env('ADMIN_WALLET_ADDRESS'),
    'admin_private_key'=> env('ADMIN_PRIVATE_KEY'),
    'contract_address' => env('CONTRACT_ADDRESS'),
];