<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Nilai;
use App\Models\JawabanSiswa;
use Illuminate\Support\Facades\Auth;

class RuangUjianController extends Controller
{
    public function mulai(Ujian $ujian)
    {
        /*
        |--------------------------------------------------------------------------
        | CEK SISWA LOGIN
        |--------------------------------------------------------------------------
        */

        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan.');
        }


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH SUDAH PERNAH MENGERJAKAN
        |--------------------------------------------------------------------------
        */

        $nilai = Nilai::where('ujian_id', $ujian->id)
            ->where('siswa_id', $siswa->id)
            ->first();


            
        if ($nilai && $nilai->status == 'selesai') {

            return redirect()
                ->route('dashboard-siswa.ujian-hari-ini')
                ->with('error', 'Ujian sudah dikumpulkan.');

        }


        /*
        |--------------------------------------------------------------------------
        | CEK WAKTU UJIAN
        |--------------------------------------------------------------------------
        */

        $now = Carbon::now();

        if (
            $now->lt(Carbon::parse($ujian->waktu_mulai)) ||
            $now->gt(Carbon::parse($ujian->waktu_selesai))
        ) {

            return redirect()
                ->route('dashboard-siswa.ujian-hari-ini')
                ->with(
                    'error',
                    'Ujian belum dibuka atau waktu ujian sudah berakhir.'
                );

        }

        /*
        |--------------------------------------------------------------------------
        | LOAD DATA UJIAN
        |--------------------------------------------------------------------------
        */

        $ujian->load([
            'bankSoal.mataPelajaran',
            'jenisUjian',
        ]);

        /*
        |--------------------------------------------------------------------------
        | HITUNG JUMLAH SOAL
        |--------------------------------------------------------------------------
        */

        $totalSoal = 0;


        if ($ujian->bankSoal) {

            $totalSoal = $ujian->bankSoal
                ->soals()
                ->count();

        }

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN TOKEN / KONFIRMASI
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard-siswa.ruang-ujian.index',
            compact(
                'ujian',
                'totalSoal'
            )
        );
    }

    /**
     * PROSES VALIDASI TOKEN SEBELUM MASUK UJIAN
     */
    public function prosesMasuk(Request $request, Ujian $ujian)
    {

        $siswa = Auth::user()->siswa;

        $nilai = Nilai::where('ujian_id',$ujian->id)
            ->where('siswa_id',$siswa->id)
            ->first();

        if($nilai && $nilai->status == 'selesai'){

            return redirect()
            ->route('dashboard-siswa.ujian-hari-ini')
            ->with(
                'error',
                'Ujian sudah pernah dikerjakan.'
            );

        }

        // 1. Cek apakah status ujian saat ini valid (rentang waktu sesuai)
        $now = Carbon::now();
        $mulai = Carbon::parse($ujian->waktu_mulai);
        $selesai = Carbon::parse($ujian->waktu_selesai);

        if ($now->lt($mulai) || $now->gt($selesai)) {
            return redirect()->route('dashboard-siswa.ujian-hari-ini')
                ->with('error', 'Waktu ujian tidak valid atau sesi telah berakhir.');
        }

        // 2. Jika di tabel ujians field `token_aktif` bernilai true/1, lakukan validasi input token
        if ($ujian->token_aktif) {
            $request->validate([
                'token' => 'required|string|size:6',
            ], [
                'token.required' => 'Token ujian wajib diisi!',
                'token.size' => 'Token harus berjumlah 6 karakter.'
            ]);

            // Validasi string token (menggunakan strtolower/strtoupper jika ingin case-insensitive)
            if (strtoupper($request->token) !== strtoupper($ujian->token)) {
                return redirect()->back()
                    ->withErrors(['token' => 'Token yang Anda masukkan salah atau sudah tidak aktif!'])
                    ->withInput();
            }
        }

        // 3. Jika token benar (atau jika ujian tidak pakai token), buat session penanda
        session(['ujian_terverifikasi_' . $ujian->id => true]);

        // 4. Arahkan siswa ke lembar kerja utama
        return redirect()->route('dashboard-siswa.ujian.kerja', $ujian->id);
    }

    /**
     * HALAMAN LEMBAR KERJA UJIAN
     */
    public function kerja(Ujian $ujian)
    {
        // Pengaman: Jika siswa mencoba bypass URL tanpa submit token lewat form
        if (!session('ujian_terverifikasi_' . $ujian->id)) {
            return redirect()
                ->route('dashboard-siswa.ujian.mulai', $ujian->id)
                ->with('error', 'Silakan masukkan token terlebih dahulu untuk mengakses ujian.');
        }

        // Ambil data siswa yang login
        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            abort(403, 'Data siswa tidak ditemukan.');
        }

        // Buat atau ambil data pengerjaan ujian
        $nilai = Nilai::firstOrCreate(
            [
                'ujian_id' => $ujian->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'waktu_mulai_kerja' => now(),
                'status' => 'mengerjakan',
            ]
        );

        if ($nilai->status == 'selesai') {

            return redirect()
                ->route('dashboard-siswa.ujian-hari-ini')
                ->with('error', 'Ujian sudah selesai.');
        }

        // Load soal
        $ujian->load([
            'bankSoal.soals' => function ($query) use ($ujian) {
                if ($ujian->acak_soal) {
                    $query->orderBy('urutan');
                } else {
                    $query->orderBy('urutan');
                }
            },
            'bankSoal.soals.pilihanJawabans',
            'bankSoal.mataPelajaran',
            'jenisUjian',      // <-- tambahkan
            'tahunAjaran',     // <-- tambahkan
        ]);

        // Pastikan bank soal ada
        if (!$ujian->bankSoal) {
            return back()->with('error', 'Bank soal tidak ditemukan.');
        }

        $soals = $ujian->bankSoal->soals;

        // Ambil jawaban yang sudah tersimpan
        $jawaban = $nilai->jawabanSiswas()
            ->with('pilihanJawaban')
            ->get()
            ->keyBy('soal_id');

        // Nomor soal terakhir
        $currentQuestion = $nilai->current_question;
        $violationCount = $nilai->violation_count;

        // Waktu paling cepat siswa boleh menekan tombol "Selesaikan Ujian"
        // = waktu_mulai ujian + durasi_minimal (asumsi dalam MENIT, sesuaikan jika satuan beda)
        $minSelesai = Carbon::parse($ujian->waktu_mulai)
            ->addMinutes((int) $ujian->durasi_minimal);
        

        return view(
            'dashboard-siswa.ruang-ujian.kerja',
            compact(
                'ujian',
                'soals',
                'nilai',
                'jawaban',
                'currentQuestion',
                'violationCount',
                'minSelesai'
            )
        );
    }

    public function submit(Request $request, Ujian $ujian)
    {
        $siswa = Auth::user()->siswa;

        if(!$siswa){
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil nilai siswa
        |--------------------------------------------------------------------------
        */

        $nilai = Nilai::where('ujian_id',$ujian->id)
            ->where('siswa_id',$siswa->id)
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Cegah submit ulang
        |--------------------------------------------------------------------------
        */

        $isAutoSubmit = $request->boolean('auto_submit');

        if ($nilai->status == 'selesai' && !$isAutoSubmit) {

            return redirect()
                ->route('dashboard-siswa.ujian-hari-ini')
                ->with('error', 'Ujian sudah dikumpulkan.');

        }

        /*
        |--------------------------------------------------------------------------
        | Sinkronisasi Jawaban dari Request Form (Mengatasi Race Condition AutoSave)
        |--------------------------------------------------------------------------
        */
        $inputJawabans = $request->input('jawaban', []);
        foreach ($inputJawabans as $soal_id => $isi_jawaban) {
            $jawaban = \App\Models\JawabanSiswa::firstOrNew([
                'nilai_id' => $nilai->id,
                'soal_id'  => $soal_id,
            ]);

            // Jika $isi_jawaban is numeric, asumsi itu Pilihan Ganda (berisi ID pilihan_jawaban)
            if (is_numeric($isi_jawaban)) {
                $jawaban->pilihan_jawaban_id = $isi_jawaban;
            } else {
                // Jika tidak, asumsi essay/isian
                $jawaban->jawaban_text = $isi_jawaban;
            }
            $jawaban->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil semua soal
        |--------------------------------------------------------------------------
        */

        $soals = $ujian->bankSoal
            ->soals()
            ->with('pilihanJawabans')
            ->orderBy('urutan')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Jawaban siswa
        |--------------------------------------------------------------------------
        */

        $jawabanSiswas = $nilai->jawabanSiswas()
            ->get()
            ->keyBy('soal_id');

        /*
        |--------------------------------------------------------------------------
        | Variabel nilai
        |--------------------------------------------------------------------------
        */

        $nilaiPG = 0;

        $adaEssay = false;

        /*
        |--------------------------------------------------------------------------
        | Koreksi jawaban
        |--------------------------------------------------------------------------
        */

        foreach($soals as $soal){

            $jawaban = $jawabanSiswas->get($soal->id);

            // Jika siswa sama sekali tidak menjawab (tidak ada di request maupun autosave)
            // Buat record kosong agar soal tetap muncul di halaman koreksi guru
            if (!$jawaban) {
                $jawaban = \App\Models\JawabanSiswa::create([
                    'nilai_id' => $nilai->id,
                    'soal_id'  => $soal->id,
                    'pilihan_jawaban_id' => null,
                    'jawaban_text' => null,
                    'is_benar' => false,
                    'nilai' => 0,
                ]);
            }
            /*
            |--------------------------------------------------------------------------
            | PILIHAN GANDA
            |--------------------------------------------------------------------------
            */

            if($soal->jenis_soal == 'pilihan_ganda'){

                $pilihanBenar = $soal->pilihanJawabans
                    ->where('is_benar',true)
                    ->first();

                if(
                    $jawaban &&
                    $pilihanBenar &&
                    $jawaban->pilihan_jawaban_id == $pilihanBenar->id
                ){

                    /*
                    Jawaban benar
                    Nilai sesuai bobot soal
                    */

                    $jawaban->update([
                        'is_benar'=>true,
                        'nilai'=>$soal->bobot
                    ]);

                    $nilaiPG += $soal->bobot;

                }else{

                    if($jawaban){
                        $jawaban->update([
                            'is_benar'=>false,
                            'nilai'=>0
                        ]);
                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | ESSAY / ISIAN
            |--------------------------------------------------------------------------
            */

            if(
                $soal->jenis_soal == 'essay'
                ||
                $soal->jenis_soal == 'isian'
            ){

                $adaEssay = true;

                /*
                Essay menunggu guru
                */
                if($jawaban){
                    $jawaban->update([
                        'is_benar'=>null,
                        'nilai'=>0
                    ]);
                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Simpan nilai
        |--------------------------------------------------------------------------
        */


        if($adaEssay){


            /*
            Ada essay
            Tunggu koreksi guru
            */


            $nilai->update([

                'nilai_pg'=> $nilaiPG,

                'nilai_akhir'=>0,

                'status'=>'selesai',

                'status_penilaian'=>'menunggu',

                'waktu_kumpul'=>now(),

            ]);



        }else{


            /*
            Semua pilihan ganda
            Nilai langsung selesai
            */


            $nilai->update([

                'nilai_pg'=>$nilaiPG,

                'nilai_akhir'=>$nilaiPG,

                'status'=>'selesai',

                'status_penilaian'=>'selesai',

                'waktu_kumpul'=>now(),

        ]);


        }




        /*
        |--------------------------------------------------------------------------
        | Hapus session token
        |--------------------------------------------------------------------------
        */

            session()->forget(
                'ujian_terverifikasi_'.$ujian->id
            );

            $message = $request->get('auto_submit')
                ? 'Ujian dikumpulkan otomatis karena Anda melakukan pelanggaran sebanyak 2 kali.'
                : 'Ujian berhasil dikumpulkan.';

            return redirect()
                ->route('dashboard-siswa.ujian-hari-ini')
                ->with('success', $message)
                ->with('auto_submit', $request->boolean('auto_submit'));

    }

    public function autoSave(Request $request)
    {
        $request->validate([
            'ujian_id'           => 'required|exists:ujians,id',
            'soal_id'            => 'required|exists:soals,id',
            'pilihan_jawaban_id' => 'nullable|exists:pilihan_jawabans,id',
            'jawaban_text'       => 'nullable|string',
            'is_ragu_ragu'       => 'nullable|boolean',
        ]);

        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data siswa tidak ditemukan.'
            ], 403);
        }

        $nilai = Nilai::where('ujian_id', $request->ujian_id)
            ->where('siswa_id', $siswa->id)
            ->first();

        if (!$nilai) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengerjaan ujian tidak ditemukan.'
            ], 404);
        }

        // Ambil record lama dulu (kalau ada), supaya field yang tidak
        // dikirim di request ini TIDAK ikut tertimpa null.
        $jawaban = JawabanSiswa::firstOrNew([
            'nilai_id' => $nilai->id,
            'soal_id'  => $request->soal_id,
        ]);

        // Hanya update kolom yang memang dikirim oleh request ini
        if ($request->has('pilihan_jawaban_id')) {
            $jawaban->pilihan_jawaban_id = $request->pilihan_jawaban_id;
        }

        if ($request->has('jawaban_text')) {
            $jawaban->jawaban_text = $request->jawaban_text;
        }

        if ($request->has('is_ragu_ragu')) {
            $jawaban->is_ragu_ragu = $request->boolean('is_ragu_ragu');
        }

        $jawaban->save();

        $nilai->update(['last_autosave' => now()]);

        return response()->json([
            'success'    => true,
            'message'    => 'Jawaban berhasil disimpan.',
            'jawaban_id' => $jawaban->id,
            'saved_at'   => now()->format('H:i:s'),
        ]);
    }

    public function violation(Request $request)
    {
        $request->validate([
            'ujian_id' => 'required|exists:ujians,id'
        ]);

        $siswa = Auth::user()->siswa;

        if (!$siswa) {
            return response()->json([
                'success' => false
            ],403);
        }

        $nilai = Nilai::where('ujian_id',$request->ujian_id)
            ->where('siswa_id',$siswa->id)
            ->first();

        if(!$nilai){
            return response()->json([
                'success'=>false
            ],404);
        }

        $nilai->increment('violation_count');

        $nilai->refresh();

        if($nilai->violation_count >= 2){

            return response()->json([
                'success' => true,
                'submit'  => true,
                'count'   => $nilai->violation_count
            ]);
        }

        return response()->json([
            'success'=>true,
            'submit'=>false,
            'count'=>$nilai->violation_count
        ]);
    }
}