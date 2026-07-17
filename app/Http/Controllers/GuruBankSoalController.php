<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruBankSoalController extends Controller
{
    public function index()
    {
        $guru = Auth::user()->guru;

        // Satu guru bisa punya BEBERAPA guru_mapel (lintas mapel/tahun ajaran),
        // jadi ambil SEMUA guru_mapel_id miliknya, bukan satu id tunggal
        // (sebelumnya: Auth::user()->guru_mapel_id, yang juga salah karena
        // guru_mapel_id tidak ada di tabel users maupun gurus).
        $guruMapelIds = $guru->guruMapels()->pluck('id');

        $bankSoals = BankSoal::whereIn('guru_mapel_id', $guruMapelIds)->get();

        return view('guru.bank-soal.index', compact('bankSoals'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;
        // dd(Auth::user()->role, Auth::user()->guru);

        // Hanya mapel yang benar-benar diampu guru ini yang boleh dipilih,
        // bukan MataPelajaran::all() (sebelumnya guru bisa pilih mapel siapa saja).
        // Asumsi: relasi GuruMapel::mataPelajaran() sudah/akan didefinisikan.
        $guruMapels = $guru->guruMapels()->with('mataPelajaran')->get();
        return view('guru.bank-soal.create', [
            'guruMapels' => $guruMapels,
            'jenjang' => $guru->jenjang, // hanya untuk ditampilkan, TIDAK dikirim lewat form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank_soal' => 'required|string|max:255',
            'guru_mapel_id' => 'required|exists:guru_mapels,id',
            'deskripsi' => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;

        // Pastikan guru_mapel yang dipilih benar-benar milik guru yang login
        // (mencegah guru A mengirim guru_mapel_id milik guru B lewat DevTools).
        $guruMapel = $guru->guruMapels()->findOrFail($request->guru_mapel_id);

        BankSoal::create([
            'guru_mapel_id' => $guruMapel->id,
            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id, // diturunkan, bukan dari input form
            'jenjang_id' => $guru->jenjang_id, // diturunkan dari data guru, bukan dari input "SD" hardcoded
            'nama_bank_soal' => $request->nama_bank_soal,
            'deskripsi' => $request->deskripsi,
            'is_publish' => false, // Default false
        ]);

        return redirect()
            ->route('dashboard-guru.bank-soal.index') // diperbaiki: sebelumnya 'guru.bank-soal.index' (route ini tidak ada di web.php, akan error)
            ->with('success', 'Bank Soal berhasil dibuat');
    }
}