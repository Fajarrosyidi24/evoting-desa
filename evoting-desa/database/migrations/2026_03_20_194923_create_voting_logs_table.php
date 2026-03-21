<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('voting_logs', function (Blueprint $table) {
          $table->id();
            $table->foreignId('pemilih_id')->constrained('pemilih')->cascadeOnDelete();
            $table->foreignId('kandidat_id')->constrained('kandidat')->cascadeOnDelete();
            $table->string('tx_hash', 66)->nullable();   // hash transaksi blockchain (0x + 64 hex)
            $table->enum('status', [
                'pending',    // transaksi baru dikirim, belum dikonfirmasi
                'confirmed',  // sudah masuk block
                'failed',     // gagal di blockchain
            ])->default('pending');
            $table->string('block_number')->nullable();  // di block mana transaksi dikonfirmasi
            $table->timestamp('voted_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voting_logs');
    }
};
