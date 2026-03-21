<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kandidat extends Model
{
    protected $table = 'kandidat'; // ← tambahkan ini

    protected $fillable = [
        'nomor_urut', 'nama', 'visi', 'misi',
        'foto_path', 'aktif', 'terdaftar_blockchain',
    ];

    protected $casts = [
        'aktif'                => 'boolean',
        'terdaftar_blockchain' => 'boolean',
    ];

    public function votingLogs(): HasMany
    {
        return $this->hasMany(VotingLog::class);
    }
}