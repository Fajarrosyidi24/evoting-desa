<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pemilih extends Authenticatable
{
    protected $table = 'pemilih';

    protected $fillable = [
        'nik', 'nama', 'alamat', 'no_hp',
        'wallet_address', 'terdaftar_blockchain',
        'sudah_voting', 'foto_ktp_path',
        'tanggal_lahir', 'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
    'terdaftar_blockchain' => 'boolean',
    'sudah_voting'         => 'boolean',
    'tanggal_lahir'        => 'date:Y-m-d',  // ← pastikan ini ada
];

    public function votingLog(): HasOne
    {
        return $this->hasOne(VotingLog::class);
    }
}