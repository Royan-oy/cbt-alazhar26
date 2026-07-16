<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruBankSoalController extends Controller
{
    public function index()
    {
        // Pastikan guru hanya melihat bank soal yang ia miliki
        $bankSoals = BankSoal::where('guru_mapel_id', Auth::user()->guru_mapel_id)->get();
        return view('guru.bank-soal.index', compact('bankSoals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank_soal' => 'required|string|max:255',
            'mata_pelajaran_id' => 'required',
            'jenjang_id' => 'required',
        ]);

        BankSoal::create([
            'guru_mapel_id' => Auth::user()->guru_mapel_id,
            'nama_bank_soal' => $request->nama_bank_soal,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'jenjang_id' => $request->jenjang_id,
            'deskripsi' => $request->deskripsi,
            'is_publish' => false, // Default false
        ]);

        return redirect()->route('guru.bank-soal.index')->with('success', 'Bank Soal berhasil dibuat');
    }
}