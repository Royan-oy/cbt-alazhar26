<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;

class GuruJadwalUjianController extends Controller
{
    public function index(Request $request)
{
    // Menggunakan with agar relasi bertingkat dimuat sejak awal
    $query = Ujian::query()->with('bankSoal.mataPelajaran');

    // 1. Fitur Pencarian berdasarkan nama ujian
    if ($request->filled('search')) {
        $query->where('nama_ujian', 'like', '%' . $request->search . '%');
    }

    // 2. Fitur Filter berdasarkan tanggal ujian
    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal', $request->tanggal);
    }

    // Mengambil data dengan pagination
    $jadwalUjian = $query->latest()->paginate(9); 
    $jadwalUjian->appends($request->all());
    
    return view('guru.jadwal-ujian.index', compact('jadwalUjian'));
}
    // TAMBAHKAN METHOD INI
    public function show($id)
    {
        // Mengambil data ujian berdasarkan ID. 
        // findOrFail digunakan agar jika ID tidak ada, otomatis memunculkan halaman 404 (Tidak Ditemukan) tanpa error system.
$ujian = Ujian::with('bankSoal.mataPelajaran')->findOrFail($id);

    return view('guru.jadwal-ujian.show', compact('ujian'));
    }
}