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
                    'tahunAjaran',
                    'nilais' => function($query) use ($siswa){
                        $query->where('siswa_id',$siswa->id);
                    }
                ])
                ->whereHas('kelas', function($query) use ($kelasAktif) {
                    $query->where('kelas.id', $kelasAktif->id);
                })
                ->orderBy('waktu_mulai', 'asc')
                ->get();

            /*
            |--------------------------------------------------------------------------
            | MAP STATUS REALTIME SECARA EFISIEN
            |--------------------------------------------------------------------------
            */
            $ujians->transform(function($ujian) use ($now){

                $mulai = Carbon::parse($ujian->waktu_mulai);
                $selesai = Carbon::parse($ujian->waktu_selesai);


                /*
                |--------------------------------------------------------------------------
                | CEK STATUS PENGERJAAN SISWA
                |--------------------------------------------------------------------------
                */

                $nilai = $ujian->nilais->first();


                if($nilai && $nilai->status == 'selesai'){

                    $ujian->status_siswa = 'selesai';

                }else{

                    $ujian->status_siswa = 'belum_mengerjakan';

                }



                /*
                |--------------------------------------------------------------------------
                | STATUS WAKTU UJIAN
                |--------------------------------------------------------------------------
                */


                if ($now->lt($mulai)) {
                    $ujian->status = 'belum';
                } elseif ($now->between($mulai,$selesai)) {
                    $ujian->status = 'berlangsung';
                } else {
                    $ujian->status = 'selesai';
                }

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
        }

        return view('dashboard-siswa.ujian-hari-ini.index', [
            'siswa'      => $siswa,
            'kelasAktif' => $kelasAktif,
            'ujians'     => $ujians
        ]);
    }

}