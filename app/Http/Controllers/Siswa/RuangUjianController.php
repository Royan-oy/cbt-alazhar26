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
        // Memuat relasi dan menghitung jumlah soal di dalam bank soal terkait
        $ujian->load([
            'bankSoal.mataPelajaran',
            'jenisUjian',
        ])->loadCount(['bankSoal as total_soal' => function($query) {
            $query->withCount('soals');
        }]);

        // Namun, cara paling bersih dan aman di Laravel untuk relasi nested count adalah:
        $ujian->load([
            'bankSoal.mataPelajaran',
            'jenisUjian',
        ]);
        
        // Hitung jumlah soal secara spesifik lewat relation instance
        $totalSoal = $ujian->bankSoal ? $ujian->bankSoal->soals()->count() : 0;

        return view('dashboard-siswa.ruang-ujian.index', compact('ujian', 'totalSoal'));
    }

    /**
     * PROSES VALIDASI TOKEN SEBELUM MASUK UJIAN
     */
    public function prosesMasuk(Request $request, Ujian $ujian)
    {
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

            'bankSoal.mataPelajaran'

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

        return view(
            'dashboard-siswa.ruang-ujian.kerja',
            compact(
                'ujian',
                'soals',
                'nilai',
                'jawaban',
                'currentQuestion'
            )
        );
    }

    public function submit(Request $request, Ujian $ujian)
    {
        dd($request->all());
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
}