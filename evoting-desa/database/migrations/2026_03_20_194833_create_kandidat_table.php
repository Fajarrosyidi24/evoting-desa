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
        Schema::create('kandidat', function (Blueprint $table) {
            $table->id();
             $table->unsignedTinyInteger('nomor_urut')->unique();
            $table->string('nama');
            $table->string('visi');
            $table->text('misi')->nullable();
            $table->string('foto_path')->nullable();
            $table->boolean('aktif')->default(true);
            $table->boolean('terdaftar_blockchain')->default(false); // sudah di-push ke smart contract?
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandidat');
    }
};
