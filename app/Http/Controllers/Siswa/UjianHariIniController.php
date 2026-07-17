<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Ujian;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UjianHariIniController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DATA SISWA LOGIN & KELAS AKTIF
        |--------------------------------------------------------------------------
        */
        $siswa = Siswa::with(['kelasAktif.kelas.tingkat'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $kelasAktif = optional($siswa->kelasAktif)->kelas;
        $ujians = collect();

        if ($kelasAktif) {
            $now = Carbon::now();

            /*
            |--------------------------------------------------------------------------
            | AMBIL UJIAN HARI INI / SEDANG AKTIF
            |--------------------------------------------------------------------------
            */
            $ujians = Ujian::with([
                    'bankSoal.mataPelajaran',
                    'jenisUjian',
                    'tahunAjaran'
                ])
                ->whereHas('kelas', function($query) use ($kelasAktif) {
                    $query->where('kelas.id', $kelasAktif->id);
                })
                ->where(function($query) use ($now) {
                    // Skenario 1: Ujian yang mulai hari ini
                    $query->whereDate('waktu_mulai', $now->toDateString())
                    // Skenario 2: Atau ujian multi-hari yang saat ini sedang aktif rentang waktunya
                    ->orWhere(function($q) use ($now) {
                        $q->where('waktu_mulai', '<=', $now)
                        ->where('waktu_selesai', '>=', $now);
                    });
                })
                ->orderBy('waktu_mulai', 'asc')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | MAP STATUS REALTIME SECARA EFISIEN
            |--------------------------------------------------------------------------
            */
            $ujians->transform(function($ujian) use ($now) {
                $mulai = Carbon::parse($ujian->waktu_mulai);
                $selesai = Carbon::parse($ujian->waktu_selesai);

                if ($now->lt($mulai)) {
                    $ujian->status = 'belum';
                } elseif ($now->between($mulai, $selesai)) {
                    $ujian->status = 'berlangsung';
                } else {
                    $ujian->status = 'selesai';
                }

                return $ujian;
            });
        }

        return view('dashboard-siswa.ujian-hari-ini.index', [
            'siswa'      => $siswa,
            'kelasAktif' => $kelasAktif,
            'ujians'     => $ujians
        ]);
    }

}