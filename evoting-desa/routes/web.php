<?php

use App\Http\Controllers\Admin\KandidatController;
use App\Http\Controllers\Admin\PemilihController;
use App\Http\Controllers\Admin\VotingController as AdminVotingController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\PemilihLoginController;
use App\Http\Controllers\PublikController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublikController::class, 'hasil'])->name('publik.hasil');
Route::get('/transaksi', [PublikController::class, 'transaksi'])->name('publik.transaksi');
Route::get('/hasil', [PublikController::class, 'hasil'])->name('publik.hasil.alt');

Route::prefix('')->name('pemilih.')->group(function () {
    Route::get('/login', [PemilihLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [PemilihLoginController::class, 'login']);
    Route::post('/logout', [PemilihLoginController::class, 'logout'])->name('logout');
});

Route::middleware('pemilih.auth')->group(function () {
    Route::get('/voting', [VotingController::class, 'index'])->name('voting.index');
    Route::post('/voting/kirim', [VotingController::class, 'kirimSuara'])->name('voting.kirim');
    Route::get('/voting/sukses', [VotingController::class, 'sukses'])->name('voting.sukses');
});

// ─── ADMIN AUTH ────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

    // Halaman admin (wajib login sebagai admin)
    Route::middleware('admin.auth')->group(function () {
      Route::get('/dashboard', [AdminVotingController::class, 'dashboard'])->name('dashboard');
         Route::get('/kandidat', [KandidatController::class, 'index'])->name('kandidat.index');
    Route::post('/kandidat', [KandidatController::class, 'store'])->name('kandidat.store');
    Route::delete('/kandidat/{kandidat}', [KandidatController::class, 'destroy'])->name('kandidat.destroy');

    // Pemilih
    Route::get('/pemilih', [PemilihController::class, 'index'])->name('pemilih.index');
    Route::post('/pemilih', [PemilihController::class, 'store'])->name('pemilih.store');
    Route::post('/pemilih/{pemilih}/daftarkan', [PemilihController::class, 'daftarkanKeBlockchain'])->name('pemilih.daftarkan');
    Route::delete('/pemilih/{pemilih}', [PemilihController::class, 'destroy'])->name('pemilih.destroy');

    // Voting
    Route::get('/voting', [AdminVotingController::class, 'index'])->name('voting.index');
    Route::post('/voting/mulai', [AdminVotingController::class, 'mulai'])->name('voting.mulai');
    Route::post('/voting/akhiri', [AdminVotingController::class, 'akhiri'])->name('voting.akhiri');

    Route::post('/kandidat/{kandidat}/daftarkan', [KandidatController::class, 'daftarkanKeBlockchain'])->name('kandidat.daftarkan');
    });
});