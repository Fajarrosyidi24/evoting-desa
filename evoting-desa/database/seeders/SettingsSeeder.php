<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $settings = [
        ['key' => 'nama_voting',   'value' => 'Pilkades Desa Sukamaju 2025'],
        ['key' => 'nama_desa',     'value' => 'Desa Sukamaju'],
        ['key' => 'voting_aktif',  'value' => 'false'],
        ['key' => 'contract_address', 'value' => env('CONTRACT_ADDRESS')],
    ];

    foreach ($settings as $s) {
        Setting::updateOrCreate(['key' => $s['key']], $s);
    }
}
}
