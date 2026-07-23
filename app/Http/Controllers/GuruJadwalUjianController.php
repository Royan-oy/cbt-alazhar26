<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GuruJadwalUjianController extends Controller
{
    public function index(Request $request)
    {
        $query = Ujian::query()->with(['bankSoal.mataPelajaran', 'jenisUjian']);

        // 1. Fitur Pencarian berdasarkan nama ujian
        if ($request->filled('search')) {
            $query->where('nama_ujian', 'like', '%' . $request->search . '%');
        }

        // 2. Fitur Filter berdasarkan tanggal ujian
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_mulai', $request->tanggal);
        }

        $now = Carbon::now();

        // Ambil data ujian secara komprehensif
        $ujians = $query->orderBy('waktu_mulai', 'desc')->get();

        // Transformasi status & kategori filter per-ujian
        $ujians->transform(function($ujian) use ($now) {
            $mulai = Carbon::parse($ujian->waktu_mulai);
            $selesai = Carbon::parse($ujian->waktu_selesai);

            // Status Waktu (Realtime Badge)
            if ($now->between($mulai, $selesai)) {
                $ujian->status_waktu = 'berlangsung';
                $ujian->status_label = 'Berlangsung';
            } elseif ($now->lt($mulai)) {
                $ujian->status_waktu = 'belum';
                $ujian->status_label = 'Belum Mulai';
            } else {
                $ujian->status_waktu = 'selesai';
                $ujian->status_label = 'Selesai';
            }

            // Kategori Filter (samakan persis dengan logika siswa)
            $isHariIni = $mulai->isSameDay($now) || $selesai->isSameDay($now) || $now->between($mulai, $selesai);

            if ($isHariIni) {
                $ujian->filter_category = 'hari_ini';
            } elseif ($mulai->isFuture()) {
                $ujian->filter_category = 'akan_datang';
            } else {
                $ujian->filter_category = 'riwayat';
            }

            return $ujian;
        });

        // Hitung total per kategori
        $counts = [
            'semua'       => $ujians->count(),
            'hari_ini'    => $ujians->where('filter_category', 'hari_ini')->count(),
            'akan_datang' => $ujians->where('filter_category', 'akan_datang')->count(),
            'riwayat'     => $ujians->where('filter_category', 'riwayat')->count(),
        ];

        return view('guru.jadwal-ujian.index', compact('ujians', 'counts'));
    }

    public function show($id)
    {
        $ujian = Ujian::with(['bankSoal.mataPelajaran', 'jenisUjian'])->findOrFail($id);
        return view('guru.jadwal-ujian.show', compact('ujian'));
    }
}
