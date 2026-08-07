<?php

namespace App\Http\Controllers;

use App\Models\JawabanSiswa;
use App\Models\Nilai;
use App\Models\Ujian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapUjianExport;

class GuruNilaiSiswaController extends Controller
{
    /**
     * Memastikan bahwa user saat ini adalah guru yang memiliki wewenang
     */
    /**
     * Memastikan bahwa user saat ini adalah guru dan mengambil seluruh konteks mengajar guru (mapel & kelas)
     */
    private function getGuruContext()
    {
        $guru = Auth::user()->guru;
        if (!$guru) {
            abort(403, 'Akses ditolak. Anda bukan Guru.');
        }

        $guruMapels = DB::table('guru_mapels')
            ->where('guru_id', $guru->id)
            ->get();

        if ($guruMapels->isEmpty()) {
            abort(403, 'Akses ditolak. Anda belum ditetapkan sebagai Guru Mata Pelajaran.');
        }

        $guruMapelIds = $guruMapels->pluck('id')->toArray();
        $guruMapelMapelIds = $guruMapels->pluck('mata_pelajaran_id')->toArray();

        // Ambil semua kelas_id yang diajar oleh guru ini
        $guruKelasIds = DB::table('guru_mapel_kelas')
            ->whereIn('guru_mapel_id', $guruMapelIds)
            ->pluck('kelas_id')
            ->toArray();

        return [
            'guru'              => $guru,
            'guruMapelIds'      => $guruMapelIds,
            'guruMapelMapelIds' => $guruMapelMapelIds,
            'guruKelasIds'      => $guruKelasIds,
        ];
    }

    /**
     * Memeriksa dan mengambil data Ujian jika guru berhak mengaksesnya
     */
    private function getAccessibleUjian($id, array $context)
    {
        $guruId = $context['guru']->id;

        return DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->leftJoin('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->leftJoin('tahun_ajarans', 'ujians.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->where('ujians.id', $id)
            ->whereExists(function($sub) use ($guruId) {
                $sub->select(DB::raw(1))
                    ->from('ujian_kelas')
                    ->join('guru_mapel_kelas', 'ujian_kelas.kelas_id', '=', 'guru_mapel_kelas.kelas_id')
                    ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
                    ->whereColumn('ujian_kelas.ujian_id', 'ujians.id')
                    ->whereColumn('guru_mapels.mata_pelajaran_id', 'bank_soals.mata_pelajaran_id')
                    ->whereColumn('guru_mapels.tahun_ajaran_id', 'ujians.tahun_ajaran_id')
                    ->where('guru_mapels.guru_id', $guruId);
            })
            ->select(
                'ujians.*',
                'bank_soals.mata_pelajaran_id',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian',
                'tahun_ajarans.nama_tahun',
                'bank_soals.id as bank_soal_id',
                'bank_soals.kkm'
            )
            ->first();
    }

    /**
     * Mengambil daftar ID kelas yang diajar oleh guru ini khusus untuk Mapel dan Tahun Ajaran dari Ujian tertentu
     */
    private function getGuruKelasForUjian($guruId, $mataPelajaranId, $tahunAjaranId)
    {
        return DB::table('guru_mapel_kelas')
            ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
            ->where('guru_mapels.guru_id', $guruId)
            ->where('guru_mapels.mata_pelajaran_id', $mataPelajaranId)
            ->where('guru_mapels.tahun_ajaran_id', $tahunAjaranId)
            ->pluck('guru_mapel_kelas.kelas_id')
            ->toArray();
    }

    /**
     * Menampilkan daftar Ujian yang dapat diakses oleh Guru
     */
    public function index()
    {
        $context = $this->getGuruContext();
        $guruId  = $context['guru']->id;

        // Ambil ujian yang setidaknya memiliki 1 kelas yang diajar oleh guru ini untuk mapel & tahun ajaran tsb
        $ujians = DB::table('ujians')
            ->join('bank_soals', 'ujians.bank_soal_id', '=', 'bank_soals.id')
            ->join('mata_pelajarans', 'bank_soals.mata_pelajaran_id', '=', 'mata_pelajarans.id')
            ->join('jenis_ujians', 'ujians.jenis_ujian_id', '=', 'jenis_ujians.id')
            ->join('tahun_ajarans', 'ujians.tahun_ajaran_id', '=', 'tahun_ajarans.id')
            ->whereExists(function($sub) use ($guruId) {
                $sub->select(DB::raw(1))
                    ->from('ujian_kelas')
                    ->join('guru_mapel_kelas', 'ujian_kelas.kelas_id', '=', 'guru_mapel_kelas.kelas_id')
                    ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
                    ->whereColumn('ujian_kelas.ujian_id', 'ujians.id')
                    ->whereColumn('guru_mapels.mata_pelajaran_id', 'bank_soals.mata_pelajaran_id')
                    ->whereColumn('guru_mapels.tahun_ajaran_id', 'ujians.tahun_ajaran_id')
                    ->where('guru_mapels.guru_id', $guruId);
            })
            ->select(
                'ujians.id',
                'ujians.nama_ujian',
                'ujians.waktu_mulai',
                'ujians.waktu_selesai',
                'ujians.tahun_ajaran_id',
                'bank_soals.mata_pelajaran_id',
                'mata_pelajarans.nama_mapel',
                'jenis_ujians.nama as nama_jenis_ujian',
                'tahun_ajarans.nama_tahun'
            )
            ->orderBy('ujians.created_at', 'desc')
            ->paginate(15);

        // Ambil info tambahan untuk setiap ujian: jumlah peserta khusus dari kelas yang diajar guru ini untuk mapel & tahun tsb
        foreach ($ujians as $ujian) {
            $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

            $ujian->peserta_count = DB::table('nilais')
                ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
                ->join('siswa_kelas', function($join) use ($ujian) {
                    $join->on('siswas.id', '=', 'siswa_kelas.siswa_id')
                         ->where('siswa_kelas.tahun_ajaran_id', '=', $ujian->tahun_ajaran_id);
                })
                ->where('nilais.ujian_id', $ujian->id)
                ->whereIn('siswa_kelas.kelas_id', $allowedKelasIds)
                ->count();
        }

        return view('guru.nilai-siswa.index', compact('ujians'));
    }

    /**
     * Menampilkan daftar peserta (siswa) dan nilainya untuk satu Ujian tertentu (Khusus Kelas Pengajar)
     */
    public function show($id)
    {
        $context = $this->getGuruContext();

        // Validasi kepemilikan / hak akses ujian
        $ujian = $this->getAccessibleUjian($id, $context);

        if (!$ujian) {
            abort(404, 'Data ujian tidak ditemukan atau Anda tidak memiliki akses.');
        }

        // Ambil daftar kelas yang diajar guru ini khusus untuk Mapel dan Tahun Ajaran ujian ini
        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        $kelasList = DB::table('ujian_kelas')
            ->join('kelas', 'ujian_kelas.kelas_id', '=', 'kelas.id')
            ->where('ujian_kelas.ujian_id', $id)
            ->whereIn('kelas.id', $allowedKelasIds)
            ->select('kelas.id', 'kelas.nama_kelas')
            ->orderBy('kelas.nama_kelas', 'asc')
            ->get();

        $pesertas = DB::table('siswas')
            ->join('siswa_kelas', function($join) use ($ujian) {
                $join->on('siswas.id', '=', 'siswa_kelas.siswa_id')
                     ->where('siswa_kelas.tahun_ajaran_id', '=', $ujian->tahun_ajaran_id);
            })
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
            ->leftJoin('nilais', function($join) use ($id) {
                $join->on('nilais.siswa_id', '=', 'siswas.id')
                     ->where('nilais.ujian_id', '=', $id);
            })
            ->whereIn('kelas.id', $allowedKelasIds)
            ->select(
                'siswas.id as siswa_id',
                'siswas.nama as nama_siswa',
                'siswas.nis',
                'kelas.id as kelas_id',
                'kelas.nama_kelas',
                'nilais.id as nilai_id',
                'nilais.status',
                'nilais.waktu_mulai_kerja',
                'nilais.waktu_kumpul',
                'nilais.violation_count',
                'nilais.nilai_akhir'
            )
            ->orderByRaw("
                CASE 
                    WHEN nilais.status = 'selesai' THEN 1
                    WHEN nilais.status = 'mengerjakan' THEN 2
                    ELSE 3
                END ASC
            ")
            ->orderBy('kelas.nama_kelas', 'asc')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        // Cek berapa banyak jawaban essay per siswa yang belum dinilai (is_benar is null)
        $unscoredAnswers = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->whereIn('jawaban_siswas.nilai_id', $pesertas->pluck('nilai_id')->filter()->toArray())
            ->whereIn('soals.jenis_soal', ['essay', 'isian'])
            ->whereNull('jawaban_siswas.is_benar')
            ->select('jawaban_siswas.nilai_id', DB::raw('count(*) as count'))
            ->groupBy('jawaban_siswas.nilai_id')
            ->pluck('count', 'nilai_id');

        foreach ($pesertas as $p) {
            $p->status = $p->status ?? 'belum';
            $p->belum_dikoreksi = $p->nilai_id ? $unscoredAnswers->get($p->nilai_id, 0) : 0;
        }

        return view('guru.nilai-siswa.show', compact('ujian', 'pesertas', 'kelasList'));
    }

    /**
     * Halaman Koreksi Jawaban Essay/Isian untuk satu siswa
     */
    public function koreksi($ujian_id, $siswa_id)
    {
        $context = $this->getGuruContext();

        // Validasi
        $ujian = $this->getAccessibleUjian($ujian_id, $context);

        if (!$ujian) abort(404, 'Ujian tidak ditemukan atau Anda tidak memiliki akses.');

        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        $siswa = DB::table('siswas')->where('id', $siswa_id)->first();
        if (!$siswa) abort(404, 'Siswa tidak ditemukan.');

        // Pastikan siswa ini berada di kelas yang diajar oleh guru ini
        $isSiswaInGuruKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $ujian->tahun_ajaran_id)
            ->whereIn('kelas_id', $allowedKelasIds)
            ->exists();

        if (!$isSiswaInGuruKelas) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengoreksi siswa dari kelas ini.');
        }

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

        // Ambil soal Pilihan Ganda, PG Kompleks, Benar/Salah, & Mencocokkan (Koreksi Otomatis)
        $jawabans_pg = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->where('jawaban_siswas.nilai_id', $nilai->id)
            ->whereIn('soals.jenis_soal', ['pilihan_ganda', 'pilihan_ganda_kompleks', 'benar_salah', 'mencocokkan'])
            ->select(
                'jawaban_siswas.id as jawaban_id',
                'jawaban_siswas.pilihan_jawaban_id',
                'jawaban_siswas.jawaban_text',
                'jawaban_siswas.jawaban_json',
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

        // Decode JSON jawaban_json
        foreach ($jawabans_pg as $jp) {
            if ($jp->jawaban_json) {
                $jp->jawaban_json = is_array($jp->jawaban_json) ? $jp->jawaban_json : json_decode($jp->jawaban_json, true);
            }
        }

        // Ambil opsi pilihan_jawabans untuk semua soal koreksi otomatis
        $soal_pg_ids = $jawabans_pg->pluck('soal_id');
        $opsi_pg = DB::table('pilihan_jawabans')
            ->whereIn('soal_id', $soal_pg_ids)
            ->orderBy('urutan', 'asc')
            ->get()
            ->groupBy('soal_id');

        // --- REKALKULASI SKOR PG KOMPLEKS (Proporsional Tanpa Penalti) ---
        // Memastikan data lama yang dihitung dengan formula sebelumnya akan otomatis diperbarui.
        $adaPerubahanSkor = false;
        foreach ($jawabans_pg as $jp) {
            if ($jp->jenis_soal === 'pilihan_ganda_kompleks') {
                $siswaChoiceIds = is_array($jp->jawaban_json) ? array_map('intval', $jp->jawaban_json) : [];
                $kunciBenarIds = [];

                if (isset($opsi_pg[$jp->soal_id])) {
                    foreach ($opsi_pg[$jp->soal_id] as $opsi) {
                        if ($opsi->is_benar) {
                            $kunciBenarIds[] = (int) $opsi->id;
                        }
                    }
                }

                // Batasi pilihan siswa tidak boleh melebihi jumlah kunci benar
                $maxAllowed = count($kunciBenarIds);
                if ($maxAllowed > 0 && count($siswaChoiceIds) > $maxAllowed) {
                    $siswaChoiceIds = array_slice($siswaChoiceIds, 0, $maxAllowed);
                }

                // Hitung skor proporsional tanpa penalti
                if (count($kunciBenarIds) > 0 && count($siswaChoiceIds) > 0) {
                    $benarHit = count(array_intersect($siswaChoiceIds, $kunciBenarIds));
                    $netRatio = $benarHit / count($kunciBenarIds);
                    $skorBaru = round($netRatio * $jp->bobot, 2);
                    $isBenarBaru = ($netRatio >= 1.0);
                } else {
                    $skorBaru = 0;
                    $isBenarBaru = false;
                }

                // Update DB dan in-memory jika skor berubah
                if ((float) $jp->nilai_jawaban !== (float) $skorBaru || (bool) $jp->is_benar !== $isBenarBaru) {
                    DB::table('jawaban_siswas')
                        ->where('id', $jp->jawaban_id)
                        ->update(['nilai' => $skorBaru, 'is_benar' => $isBenarBaru]);

                    $jp->nilai_jawaban = $skorBaru;
                    $jp->is_benar = $isBenarBaru ? 1 : 0;
                    $adaPerubahanSkor = true;
                }
            }
        }

        // Hitung total skor otomatis dan jumlah benar PG/Otomatis
        $skor_pg = $jawabans_pg->sum('nilai_jawaban');
        $benar_pg = $jawabans_pg->where('is_benar', true)->count();
        $total_soal_pg = $jawabans_pg->count();

        // Hitung akumulasi bobot dan total skor siswa saat ini (PG + Essay yang sudah dinilai)
        $totalBobot = DB::table('soals')
            ->where('bank_soal_id', $ujian->bank_soal_id)
            ->sum('bobot');

        $totalSkorSiswa = DB::table('jawaban_siswas')
            ->where('nilai_id', $nilai->id)
            ->sum('nilai');

        $potongan = (float) ($nilai->potongan_pelanggaran ?? 0);
        $nilai_sementara = 0;
        if ($totalBobot > 0) {
            $nilaiKotor = ($totalSkorSiswa / $totalBobot) * 100;
            $nilai_sementara = max(0, round($nilaiKotor - $potongan, 2));
        }

        // Sinkronkan nilai_akhir di database jika ada perubahan skor
        if ($adaPerubahanSkor || ($nilai->nilai_akhir == 0 && ($nilai_sementara > 0 || $potongan > 0))) {
            DB::table('nilais')->where('id', $nilai->id)->update(['nilai_akhir' => $nilai_sementara]);
            $nilai->nilai_akhir = $nilai_sementara;
        }

        return view('guru.nilai-siswa.koreksi', compact(
            'ujian', 'siswa', 'nilai', 'jawabans', 'jawabans_pg', 'opsi_pg', 'skor_pg', 'benar_pg', 'total_soal_pg', 'nilai_sementara'
        ));
    }

    /**
     * Menyimpan hasil koreksi (nilai) dari Guru Mapel
     */
    public function storeKoreksi(Request $request, $ujian_id, $siswa_id)
    {
        $context = $this->getGuruContext();

        // Security check
        $ujian = $this->getAccessibleUjian($ujian_id, $context);

        if (!$ujian) abort(403, 'Akses ditolak.');

        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        // Pastikan siswa ini berada di kelas yang diajar oleh guru ini
        $isSiswaInGuruKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $ujian->tahun_ajaran_id)
            ->whereIn('kelas_id', $allowedKelasIds)
            ->exists();

        if (!$isSiswaInGuruKelas) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengoreksi siswa dari kelas ini.');
        }

        $nilai = Nilai::where('ujian_id', $ujian_id)->where('siswa_id', $siswa_id)->firstOrFail();

        // Simpan potongan nilai pelanggaran jika dikirimkan
        if ($request->has('potongan_pelanggaran')) {
            $nilai->update([
                'potongan_pelanggaran' => max(0, (float) $request->input('potongan_pelanggaran'))
            ]);
        }

        $koreksiData = $request->input('koreksi', []); // format: [jawaban_id => ['nilai' => X, 'is_benar' => 1/0]]

        DB::beginTransaction();
        try {
            foreach ($koreksiData as $jawaban_id => $data) {
                // Ambil info soal terkait untuk memvalidasi bobot nilai
                $jawabanSiswa = JawabanSiswa::where('id', $jawaban_id)
                    ->where('nilai_id', $nilai->id)
                    ->first();
                
                if ($jawabanSiswa) {
                    $soal = DB::table('soals')->where('id', $jawabanSiswa->soal_id)->first();
                    $inputNilai = (float) ($data['nilai'] ?? 0);
                    
                    // Validasi backend: nilai tidak boleh kurang dari 0 atau lebih dari bobot soal
                    if ($soal && ($inputNilai < 0 || $inputNilai > $soal->bobot)) {
                        throw new \Exception("Nilai untuk soal nomor {$soal->urutan} melebihi bobot maksimal ({$soal->bobot}).");
                    }
                    
                    $jawabanSiswa->update([
                        'nilai' => $inputNilai,
                        'is_benar' => isset($data['is_benar']) ? (bool) $data['is_benar'] : null
                    ]);
                }
            }

            // --- KALKULASI ULANG NILAI AKHIR ---
            
            // 1. Dapatkan total bobot semua soal di ujian ini
            $totalBobot = DB::table('soals')
                ->where('bank_soal_id', $ujian->bank_soal_id)
                ->sum('bobot');

            // 2. Dapatkan total nilai keseluruhan yang dicapai siswa (PG + Essay)
            $totalSkorSiswa = DB::table('jawaban_siswas')
                ->where('nilai_id', $nilai->id)
                ->sum('nilai');

            // 3. Dapatkan total nilai pilihan ganda / otomatis saja untuk kolom 'nilai_pg'
            $totalSkorPG = DB::table('jawaban_siswas')
                ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
                ->where('jawaban_siswas.nilai_id', $nilai->id)
                ->whereIn('soals.jenis_soal', ['pilihan_ganda', 'pilihan_ganda_kompleks', 'benar_salah', 'mencocokkan'])
                ->sum('jawaban_siswas.nilai');

            // 4. Hitung akumulasi nilai essay & isian untuk mengisi kolom 'nilai_essay'
            $totalSkorEssay = DB::table('jawaban_siswas')
                ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
                ->where('jawaban_siswas.nilai_id', $nilai->id)
                ->whereIn('soals.jenis_soal', ['essay', 'isian'])
                ->sum('jawaban_siswas.nilai');

            $potongan = (float) ($nilai->potongan_pelanggaran ?? 0);
            $nilaiAkhir = 0;
            if ($totalBobot > 0) {
                $nilaiKotor = ($totalSkorSiswa / $totalBobot) * 100;
                $nilaiAkhir = max(0, round($nilaiKotor - $potongan, 2));
            }

            // Cek apakah masih ada jawaban essay/isian yang belum dinilai (is_benar null)
            $masihAdaUnscored = DB::table('jawaban_siswas')
                ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
                ->where('jawaban_siswas.nilai_id', $nilai->id)
                ->whereIn('soals.jenis_soal', ['essay', 'isian'])
                ->whereNull('jawaban_siswas.is_benar')
                ->exists();

            $statusPenilaian = $masihAdaUnscored ? 'menunggu' : 'selesai';

            // Update nilai akhir, nilai pg, nilai essay, dan status penilaian
            $nilai->update([
                'nilai_pg'         => round($totalSkorPG, 2),
                'nilai_essay'      => round($totalSkorEssay, 2),
                'nilai_akhir'      => round($nilaiAkhir, 2),
                'status_penilaian' => $statusPenilaian
            ]);


            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Koreksi jawaban berhasil disimpan.',
                    'nilai_akhir' => round($nilaiAkhir, 2)
                ]);
            }

            return redirect()->route('dashboard-guru.nilai-siswa.show', $ujian_id)
                ->with('success', 'Koreksi jawaban berhasil disimpan dan nilai akhir telah diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat menyimpan koreksi: ' . $e->getMessage());
        }
    }

    /**
     * Export PDF Rekap Ujian Per Ujian (Mendukung Filter Kelas dan Search Query)
     */
    public function exportPdf(Request $request, $id)
    {
        $context = $this->getGuruContext();

        $ujian = $this->getAccessibleUjian($id, $context);

        if (!$ujian) {
            abort(404, 'Data ujian tidak ditemukan.');
        }

        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        $query = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('siswa_kelas', function($join) use ($ujian) {
                $join->on('siswas.id', '=', 'siswa_kelas.siswa_id')
                     ->where('siswa_kelas.tahun_ajaran_id', '=', $ujian->tahun_ajaran_id);
            })
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
            ->where('nilais.ujian_id', $id)
            ->whereIn('kelas.id', $allowedKelasIds)
            ->select(
                'siswas.id as siswa_id',
                'siswas.nama as nama_siswa',
                'siswas.nis',
                'kelas.id as kelas_id',
                'kelas.nama_kelas',
                'nilais.id as nilai_id',
                'nilais.status',
                'nilais.nilai_akhir'
            );

        $kelasFilterName = null;
        if ($request->filled('kelas_id') && $request->kelas_id !== 'all') {
            $query->where('kelas.id', $request->kelas_id);
            $kelasFilterName = DB::table('kelas')->where('id', $request->kelas_id)->value('nama_kelas');
        }

        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('siswas.nama', 'like', "%{$searchQuery}%")
                  ->orWhere('siswas.nis', 'like', "%{$searchQuery}%")
                  ->orWhere('kelas.nama_kelas', 'like', "%{$searchQuery}%");
            });
        }

        $pesertas = $query->orderBy('kelas.nama_kelas', 'asc')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        $scores = $pesertas->pluck('nilai_akhir');
        $avgScore = $scores->isNotEmpty() ? $scores->avg() : 0;
        $maxScore = $scores->isNotEmpty() ? $scores->max() : 0;
        $minScore = $scores->isNotEmpty() ? $scores->min() : 0;

        $pdf = PDF::loadView('pdf.rekap-ujian', compact(
            'ujian', 'pesertas', 'kelasFilterName', 'searchQuery',
            'avgScore', 'maxScore', 'minScore'
        ))->setPaper('a4', 'portrait');

        $fileName = 'Hasil_Ujian_' . Str::slug($ujian->nama_ujian) . '.pdf';
        return $pdf->stream($fileName);
    }

    /**
     * Export Excel Rekap Ujian Per Ujian (Mendukung Filter Kelas dan Search Query)
     */
    public function exportExcel(Request $request, $id)
    {
        $context = $this->getGuruContext();

        $ujian = $this->getAccessibleUjian($id, $context);

        if (!$ujian) {
            abort(404, 'Data ujian tidak ditemukan.');
        }

        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        $query = DB::table('nilais')
            ->join('siswas', 'nilais.siswa_id', '=', 'siswas.id')
            ->join('siswa_kelas', function($join) use ($ujian) {
                $join->on('siswas.id', '=', 'siswa_kelas.siswa_id')
                     ->where('siswa_kelas.tahun_ajaran_id', '=', $ujian->tahun_ajaran_id);
            })
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
            ->where('nilais.ujian_id', $id)
            ->whereIn('kelas.id', $allowedKelasIds)
            ->select(
                'siswas.id as siswa_id',
                'siswas.nama as nama_siswa',
                'siswas.nis',
                'kelas.id as kelas_id',
                'kelas.nama_kelas',
                'nilais.id as nilai_id',
                'nilais.status',
                'nilais.nilai_akhir'
            );

        $kelasFilterName = null;
        if ($request->filled('kelas_id') && $request->kelas_id !== 'all') {
            $query->where('kelas.id', $request->kelas_id);
            $kelasFilterName = DB::table('kelas')->where('id', $request->kelas_id)->value('nama_kelas');
        }

        $searchQuery = $request->input('search');
        if ($searchQuery) {
            $query->where(function($q) use ($searchQuery) {
                $q->where('siswas.nama', 'like', "%{$searchQuery}%")
                  ->orWhere('siswas.nis', 'like', "%{$searchQuery}%")
                  ->orWhere('kelas.nama_kelas', 'like', "%{$searchQuery}%");
            });
        }

        $pesertas = $query->orderBy('kelas.nama_kelas', 'asc')
            ->orderBy('siswas.nama', 'asc')
            ->get();

        $scores = $pesertas->pluck('nilai_akhir');
        $avgScore = $scores->isNotEmpty() ? $scores->avg() : 0;
        $maxScore = $scores->isNotEmpty() ? $scores->max() : 0;
        $minScore = $scores->isNotEmpty() ? $scores->min() : 0;

        $fileName = 'Hasil_Ujian_' . Str::slug($ujian->nama_ujian) . '.xlsx';

        return Excel::download(new RekapUjianExport(
            $ujian, $pesertas, $kelasFilterName, $searchQuery,
            $avgScore, $maxScore, $minScore
        ), $fileName);
    }

    /**
     * Export PDF Transkrip Hasil Ujian Individual Siswa
     */
    public function exportSiswaPdf($ujian_id, $siswa_id)
    {
        $context = $this->getGuruContext();

        $ujian = $this->getAccessibleUjian($ujian_id, $context);

        if (!$ujian) abort(404, 'Ujian tidak ditemukan.');

        $allowedKelasIds = $this->getGuruKelasForUjian($context['guru']->id, $ujian->mata_pelajaran_id, $ujian->tahun_ajaran_id);

        $siswa = DB::table('siswas')->where('id', $siswa_id)->first();
        if (!$siswa) abort(404, 'Siswa tidak ditemukan.');

        // Pastikan siswa ini berada di kelas yang diajar oleh guru ini
        $isSiswaInGuruKelas = DB::table('siswa_kelas')
            ->where('siswa_id', $siswa_id)
            ->where('tahun_ajaran_id', $ujian->tahun_ajaran_id)
            ->whereIn('kelas_id', $allowedKelasIds)
            ->exists();

        if (!$isSiswaInGuruKelas) {
            abort(403, 'Anda tidak memiliki wewenang untuk melihat transkrip siswa dari kelas ini.');
        }

        $nilai = DB::table('nilais')
            ->where('ujian_id', $ujian_id)
            ->where('siswa_id', $siswa_id)
            ->first();

        if (!$nilai) abort(404, 'Siswa belum mengikuti ujian ini.');

        $jawabans = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->where('jawaban_siswas.nilai_id', $nilai->id)
            ->whereIn('soals.jenis_soal', ['essay', 'isian'])
            ->select(
                'jawaban_siswas.jawaban_text',
                'jawaban_siswas.nilai as nilai_jawaban',
                'jawaban_siswas.is_benar',
                'soals.teks_soal',
                'soals.bobot',
                'soals.urutan',
                'soals.jenis_soal'
            )
            ->orderBy('soals.urutan', 'asc')
            ->get();

        $jawabans_pg = DB::table('jawaban_siswas')
            ->join('soals', 'jawaban_siswas.soal_id', '=', 'soals.id')
            ->where('jawaban_siswas.nilai_id', $nilai->id)
            ->where('soals.jenis_soal', 'pilihan_ganda')
            ->select('jawaban_siswas.nilai as nilai_jawaban', 'jawaban_siswas.is_benar')
            ->get();

        $skor_pg = $jawabans_pg->sum('nilai_jawaban');
        $benar_pg = $jawabans_pg->where('is_benar', true)->count();
        $total_soal_pg = $jawabans_pg->count();

        $pdf = PDF::loadView('pdf.transkrip-siswa', compact(
            'ujian', 'siswa', 'nilai', 'jawabans', 'skor_pg', 'benar_pg', 'total_soal_pg'
        ))->setPaper('a4', 'portrait');

        $fileName = 'Transkrip_' . Str::slug($siswa->nama) . '_' . Str::slug($ujian->nama_ujian) . '.pdf';
        return $pdf->download($fileName);
    }
}
