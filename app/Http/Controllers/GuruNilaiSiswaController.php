<?php

namespace App\Http\Controllers;

use App\Models\JawabanSiswa;
use App\Models\Nilai;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruNilaiSiswaController extends Controller
{
    /**
     * Memastikan bahwa user saat ini adalah guru yang memiliki wewenang
     */
    private function checkGuruMapel()
    {
        $guru = Auth::user()->guru;
        if (!$guru) {
            abort(403, 'Akses ditolak. Anda bukan Guru.');
        }

        $guruMapelIds = DB::table('guru_mapels')
            ->where('guru_id', $guru->id)
            ->pluck('id');
            
        if ($guruMapelIds->isEmpty()) {
            abort(403, 'Akses ditolak. Anda belum ditetapkan sebagai Guru Mata Pelajaran.');
        }

        return $guruMapelIds;
    }

    /**
     * Menampilkan daftar Ujian yang dibuat oleh Guru yang sedang login
     */
    public function index()
    {
        $guruMapelIds = $this->checkGuruMapel();

        // Ambil ujian berdasarkan bank soal milik guru ini
        $ujians = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->join('tahun_ajarans', 'ujians.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->whereIn('bank_soals.guru_mapel_id', $guruMapelIds)
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'ujians.waktu_mulai',
                'ujians.waktu_selesai',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian',
                'tahun_ajarans.nama_tahun'
            )
            ->orderBy('ujians.created_at', 'desc')
            ->paginate(15);

        // Ambil info tambahan untuk setiap ujian: jumlah peserta
        foreach ($ujians as $ujian) {
            $ujian->peserta_count = DB::table('nilais')->where('ujian_id', $ujian->id)->count();
        }

        return view('guru.nilai-siswa.index', compact('ujians'));
    }

    /**
     * Menampilkan daftar peserta (siswa) dan nilainya untuk satu Ujian tertentu
     */
    public function show($id)
    {
        $guruMapelIds = $this->checkGuruMapel();

        // Validasi kepemilikan ujian
        $ujian = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->where('ujians.id', $id)
            ->whereIn('bank_soals.guru_mapel_id', $guruMapelIds)
            ->select('ujians.*', 'mata_pelajarans.nama_mapel', 'bank_soals.id as bank_soal_id')
            ->first();

        if (!$ujian) {
            abort(404, 'Data ujian tidak ditemukan atau bukan milik Anda.');
        }

        $pesertas = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->where('nilais.ujian_id', $id)
            ->select(
                'siswas.id as siswa_id',
                'siswas.nama as nama_siswa',
                'siswas.nis',
                'nilais.id as nilai_id',
                'nilais.status',
                'nilais.waktu_mulai_kerja',
                'nilais.waktu_kumpul',
                'nilais.nilai_akhir'
            )
            ->orderBy('siswas.nama', 'asc')
            ->get();

        // Cek berapa banyak jawaban essay per siswa yang belum dinilai (is_benar is null)
        $unscoredAnswers = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->whereIn('jawaban_siswas.nilai_id', $pesertas->pluck('nilai_id'))
            ->whereIn('soals.jenis_soal', ['essay', 'isian'])
            ->whereNull('jawaban_siswas.is_benar')
            ->select('jawaban_siswas.nilai_id', DB::raw('count(*) as count'))
            ->groupBy('jawaban_siswas.nilai_id')
            ->pluck('count', 'nilai_id');

        foreach ($pesertas as $p) {
            $p->belum_dikoreksi = $unscoredAnswers->get($p->nilai_id, 0);
        }

        return view('guru.nilai-siswa.show', compact('ujian', 'pesertas'));
    }

    /**
     * Halaman Koreksi Jawaban Essay/Isian untuk satu siswa
     */
    public function koreksi($ujian_id, $siswa_id)
    {
        $guruMapelIds = $this->checkGuruMapel();

        // Validasi
        $ujian = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->where('ujians.id', $ujian_id)
            ->whereIn('bank_soals.guru_mapel_id', $guruMapelIds)
            ->select('ujians.*', 'mata_pelajarans.nama_mapel', 'bank_soals.id as bank_soal_id')
            ->first();

        if (!$ujian) abort(404, 'Ujian tidak ditemukan.');

        $siswa = DB::table('siswas')->where('id', $siswa_id)->first();
        if (!$siswa) abort(404, 'Siswa tidak ditemukan.');

        $nilai = DB::table('nilais')
            ->where('ujian_id', $ujian_id)
            ->where('siswa_id', $siswa_id)
            ->first();

        if (!$nilai) abort(404, 'Siswa belum mengikuti ujian ini.');

        // Ambil soal yang berjenis essay atau isian dan jawaban siswa
        $jawabans = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->where('jawaban_siswas.nilai_id', $nilai->id)
            ->whereIn('soals.jenis_soal', ['essay', 'isian'])
            ->select(
                'jawaban_siswas.id as jawaban_id',
                'jawaban_siswas.jawaban_text',
                'jawaban_siswas.nilai as nilai_jawaban',
                'jawaban_siswas.is_benar',
                'soals.id as soal_id',
                'soals.teks_soal',
                'soals.gambar',
                'soals.bobot',
                'soals.urutan',
                'soals.jenis_soal'
            )
            ->orderBy('soals.urutan', 'asc')
            ->get();

        return view('guru.nilai-siswa.koreksi', compact('ujian', 'siswa', 'nilai', 'jawabans'));
    }

    /**
     * Menyimpan hasil koreksi (nilai) dari Guru Mapel
     */
    public function storeKoreksi(Request $request, $ujian_id, $siswa_id)
    {
        $guruMapelIds = $this->checkGuruMapel();

        // Security check
        $ujian = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->where('ujians.id', $ujian_id)
            ->whereIn('bank_soals.guru_mapel_id', $guruMapelIds)
            ->first();

        if (!$ujian) abort(403);

        $nilai = Nilai::where('ujian_id', $ujian_id)->where('siswa_id', $siswa_id)->firstOrFail();

        $koreksiData = $request->input('koreksi', []); // format: [jawaban_id => ['nilai' => X, 'is_benar' => 1/0]]

        DB::beginTransaction();
        try {
            foreach ($koreksiData as $jawaban_id => $data) {
                // Update nilai dan is_benar di table jawaban_siswas
                JawabanSiswa::where('id', $jawaban_id)
                    ->where('nilai_id', $nilai->id)
                    ->update([
                        'nilai' => $data['nilai'] ?? 0,
                        'is_benar' => isset($data['is_benar']) ? (bool) $data['is_benar'] : null
                    ]);
            }

            // --- KALKULASI ULANG NILAI AKHIR ---
            
            // 1. Dapatkan total bobot semua soal di ujian ini
            $totalBobot = DB::table('soals')
                ->where('bank_soal_id', $ujian->bank_soal_id)
                ->sum('bobot');

            // 2. Dapatkan total nilai yang dicapai siswa (dari jawaban PG maupun Essay yang benar)
            // Untuk PG: jika is_benar = true, maka nilainya adalah bobot soal.
            // Untuk Essay: nilainya adalah nilai yang diinput guru (jawaban_siswas.nilai).
            // Saat RuangUjianController submit nanti, PG harus set jawaban_siswas.nilai = bobot_soal jika benar.
            // Di sini kita asumsikan jawaban_siswas.nilai sudah merepresentasikan poin yang didapat.
            
            $totalSkorSiswa = DB::table('jawaban_siswas')
                ->where('nilai_id', $nilai->id)
                ->sum('nilai');

            $nilaiAkhir = 0;
            if ($totalBobot > 0) {
                $nilaiAkhir = ($totalSkorSiswa / $totalBobot) * 100;
            }

            $nilai->update(['nilai_akhir' => round($nilaiAkhir, 2)]);

            DB::commit();

            return redirect()->route('dashboard-guru.nilai-siswa.show', $ujian_id)
                ->with('success', 'Koreksi jawaban berhasil disimpan dan nilai akhir telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat menyimpan koreksi: ' . $e->getMessage());
        }
    }
}
