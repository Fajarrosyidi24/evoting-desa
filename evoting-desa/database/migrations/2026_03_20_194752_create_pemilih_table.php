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
        Schema::create('pemilih', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();        // NIK KTP warga
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('wallet_address', 42)->unique()->nullable(); // address Ethereum
            $table->boolean('terdaftar_blockchain')->default(false);    // sudah didaftarkan ke smart contract?
            $table->boolean('sudah_voting')->default(false);            // cache lokal, sumber kebenarannya tetap blockchain
            $table->string('foto_ktp_path')->nullable();                // disimpan lokal, bukan di blockchain
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemilih');
    }
};
