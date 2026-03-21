<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kandidat;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KandidatController extends Controller
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function index()
    {
        $kandidat = Kandidat::orderBy('nomor_urut')->get();
        return view('admin.kandidat.index', compact('kandidat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_urut' => 'required|integer|unique:kandidat,nomor_urut',
            'nama'       => 'required|string|max:100',
            'visi'       => 'required|string|max:255',
            'misi'       => 'nullable|string',
            'foto'       => 'nullable|image|max:2048',
        ], [
            'nomor_urut.unique' => 'Nomor urut sudah dipakai kandidat lain',
            'foto.image'        => 'File harus berupa gambar',
            'foto.max'          => 'Ukuran foto maksimal 2MB',
        ]);

        // Cek status voting dari blockchain sebelum simpan apapun
        $status = $this->blockchain->getStatusVoting();
        if ($status['aktif']) {
            return back()
                ->withInput()
                ->withErrors(['error' => 'Tidak bisa menambah kandidat saat voting sedang berlangsung. Akhiri voting terlebih dahulu.']);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('kandidat', 'public');
        }

        $kandidat = Kandidat::create([
            'nomor_urut'           => $request->nomor_urut,
            'nama'                 => $request->nama,
            'visi'                 => $request->visi,
            'misi'                 => $request->misi,
            'foto_path'            => $fotoPath,
            'aktif'                => true,
            'terdaftar_blockchain' => false,
        ]);

        $result = $this->blockchain->tambahKandidat($kandidat->nama, $kandidat->visi);

        if ($result['success'] ?? false) {
            DB::table('kandidat')
                ->where('id', $kandidat->id)
                ->update(['terdaftar_blockchain' => true]);
            return back()->with('success', "Kandidat {$kandidat->nama} berhasil ditambahkan ke blockchain!");
        }

        // Kandidat tersimpan di DB tapi gagal ke blockchain
        return back()->with('warning', "Kandidat tersimpan di database tapi gagal ke blockchain: " . ($result['error'] ?? 'Unknown error'));
    }

    public function daftarkanKeBlockchain(Kandidat $kandidat)
    {
        if ($kandidat->terdaftar_blockchain) {
            return back()->withErrors(['error' => 'Kandidat sudah terdaftar di blockchain']);
        }

        // Cek voting aktif
        $status = $this->blockchain->getStatusVoting();
        if ($status['aktif']) {
            return back()->withErrors([
                'error' => 'Tidak bisa mendaftarkan kandidat saat voting sedang berlangsung. Akhiri voting terlebih dahulu.'
            ]);
        }

        $result = $this->blockchain->tambahKandidat($kandidat->nama, $kandidat->visi);

        if ($result['success'] ?? false) {
            DB::table('kandidat')
                ->where('id', $kandidat->id)
                ->update(['terdaftar_blockchain' => true]);
            return back()->with('success', "{$kandidat->nama} berhasil didaftarkan ke blockchain!");
        }

        return back()->withErrors([
            'error' => 'Gagal mendaftarkan ke blockchain: ' . ($result['error'] ?? 'Unknown error')
        ]);
    }

    public function destroy(Kandidat $kandidat)
    {
        if ($kandidat->terdaftar_blockchain) {
            return back()->withErrors(['error' => 'Kandidat yang sudah terdaftar di blockchain tidak bisa dihapus']);
        }

        $kandidat->delete();
        return back()->with('success', 'Kandidat berhasil dihapus');
    }
}
