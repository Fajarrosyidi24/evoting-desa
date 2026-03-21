<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pemilih;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PemilihLoginController extends Controller
{
    public function showLoginForm()
    {
        // Kalau sudah login, redirect ke halaman voting
        if (Auth::guard('pemilih')->check()) {
            return redirect()->route('voting.index');
        }

        return view('auth.pemilih-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'nik'            => 'required|digits:16',
            'tanggal_lahir'  => 'required|date',
        ], [
            'nik.required'           => 'NIK wajib diisi',
            'nik.digits'             => 'NIK harus 16 digit',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
        ]);

        // Cari pemilih berdasarkan NIK
        $pemilih = Pemilih::where('nik', $request->nik)->first();

        // Validasi NIK & tanggal lahir
        if (!$pemilih) {
            return back()->withErrors([
                'nik' => 'NIK tidak terdaftar sebagai pemilih',
            ]);
        }

        if (!$pemilih->terdaftar_blockchain) {
            return back()->withErrors([
                'nik' => 'Akun Anda belum diaktifkan oleh panitia',
            ]);
        }

        // Cek tanggal lahir sebagai "password"
        $tanggalLahirInput = $request->tanggal_lahir;
        $tanggalLahirDb    = Carbon::parse($pemilih->tanggal_lahir)->format('Y-m-d');


        if ($tanggalLahirInput !== $tanggalLahirDb) {
            return back()->withErrors([
                'tanggal_lahir' => 'Tanggal lahir tidak sesuai',
            ]);
        }

        // Login berhasil
        Auth::guard('pemilih')->login($pemilih, false);

        return redirect()->route('voting.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('pemilih')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('pemilih.login');
    }
}
