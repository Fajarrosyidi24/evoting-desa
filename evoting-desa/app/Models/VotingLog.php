<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VotingLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'pemilih_id', 'kandidat_id',
        'tx_hash', 'status', 'block_number',
        'voted_at', 'confirmed_at',
    ];

    protected $casts = [
        'voted_at'     => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    public function pemilih(): BelongsTo
    {
        return $this->belongsTo(Pemilih::class);
    }

    public function kandidat(): BelongsTo
    {
        return $this->belongsTo(Kandidat::class);
    }
}