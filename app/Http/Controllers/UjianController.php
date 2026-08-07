<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\BankSoal;
use App\Models\JenisUjian;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jenjang;
use App\Models\GuruMapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UjianController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;
        $isAdminJenjang = Auth::user()->role == 'admin_jenjang';

        // Tahun ajaran aktif
        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        // Jika user memilih filter gunakan filter,
        // jika tidak gunakan tahun ajaran aktif
        $tahunAjaranId = $request->filled('tahun_ajaran')
            ? $request->tahun_ajaran
            : optional($tahunAktif)->id;

        $ujians = Ujian::with(['bankSoal.mataPelajaran', 'bankSoal.jenjang', 'jenisUjian', 'tahunAjaran', 'kelas'])
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('bankSoal', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when($request->filled('jenjang') && !$isAdminJenjang, function ($query) use ($request) {
                $query->whereHas('bankSoal', function ($q) use ($request) {
                    $q->where('jenjang_id', $request->jenjang);
                });
            })
            ->when($request->filled('jenis_ujian'), function ($query) use ($request) {
                $query->where('jenis_ujian_id', $request->jenis_ujian);
            })
            ->when($tahunAjaranId, function ($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama_ujian', 'like', '%' . $request->search . '%');
            })
            ->orderByDesc('waktu_mulai')
            ->paginate(10)
            ->withQueryString();

        $baseQuery = Ujian::query()
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('bankSoal', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when($request->filled('jenjang') && !$isAdminJenjang, function ($query) use ($request) {
                $query->whereHas('bankSoal', function ($q) use ($request) {
                    $q->where('jenjang_id', $request->jenjang);
                });
            })
            ->when($request->filled('jenis_ujian'), function ($query) use ($request) {
                $query->where('jenis_ujian_id', $request->jenis_ujian);
            })
            ->when($tahunAjaranId, function ($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            });

        $totalUjian = (clone $baseQuery)->count();

        $totalBerlangsung = (clone $baseQuery)
            ->where('waktu_mulai', '<=', now())
            ->where('waktu_selesai', '>=', now())
            ->count();

        $totalAkanDatang = (clone $baseQuery)
            ->where('waktu_mulai', '>', now())
            ->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();
        $jenisUjians = JenisUjian::where('aktif', true)->orderBy('nama', 'asc')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        return view('ujian.index', compact(
            'ujians', 'totalUjian', 'totalBerlangsung', 'totalAkanDatang', 'jenjangs', 'jenisUjians', 'tahunAjarans'
        ));
    }

    public function create()
    {
        $data = $this->formData();

        return view('ujian.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'bank_soal_id'          => 'required|exists:bank_soals,id',
            'jenis_ujian_id'        => 'required|exists:jenis_ujians,id',
            'tahun_ajaran_id'       => 'required|exists:tahun_ajarans,id',
            'nama_ujian'            => 'required|string|max:150',
            'waktu_mulai'           => 'required|date',
            'waktu_selesai'         => 'required|date|after:waktu_mulai',
            'durasi_minimal'        => 'required|integer|min:1',
            'kelas_id'              => 'required|array|min:1',
            'kelas_id.*'            => 'exists:kelas,id',
            'acak_soal'             => 'nullable|boolean',
            'acak_jawaban'          => 'nullable|boolean',
        ], [
            'bank_soal_id.required'   => 'Bank soal wajib dipilih.',
            'jenis_ujian_id.required' => 'Jenis ujian wajib dipilih.',
            'tahun_ajaran_id.required'=> 'Tahun ajaran wajib dipilih.',
            'nama_ujian.required'     => 'Nama ujian wajib diisi.',
            'waktu_mulai.required'    => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'  => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'durasi_minimal.required' => 'Durasi minimal pengerjaan wajib diisi.',
            'kelas_id.required'       => 'Minimal pilih 1 kelas.',
        ]);

        $bankSoal = BankSoal::find($request->bank_soal_id);

        if (!$bankSoal->is_publish) {
            return back()->withInput()->withErrors([
                'bank_soal_id' => 'Bank soal ini belum dipublikasikan, tidak bisa dipakai untuk ujian.',
            ]);
        }

        $this->authorizeJenjangBankSoal($bankSoal);
        $this->authorizeJenjangKelas($request->kelas_id, $bankSoal->jenjang_id);

        $this->authorizeKelasSesuaiGuruBankSoal($request->kelas_id, $bankSoal);

        $unassignedKelas = $this->checkKelasHasGuruMapel($request->kelas_id, $bankSoal, $request->tahun_ajaran_id);
        if (!empty($unassignedKelas)) {
            return back()->withInput()->withErrors([
                'kelas_id' => 'Gagal menyimpan jadwal ujian! Kelas berikut belum memiliki Guru Mata Pelajaran (' . optional($bankSoal->mataPelajaran)->nama_mapel . ') untuk Tahun Ajaran terpilih: ' . implode(', ', $unassignedKelas) . '. Silakan atur Guru Mapel terlebih dahulu di menu Pengguna > Guru Mapel.',
            ]);
        }

        $ujian = Ujian::create([
            'bank_soal_id'         => $request->bank_soal_id,
            'jenis_ujian_id'       => $request->jenis_ujian_id,
            'tahun_ajaran_id'      => $request->tahun_ajaran_id,
            'nama_ujian'           => $request->nama_ujian,
            'waktu_mulai'          => $request->waktu_mulai,
            'waktu_selesai'        => $request->waktu_selesai,
            'durasi_minimal'       => $request->durasi_minimal,
            'token'                => Ujian::generateToken(),
            'acak_soal'            => $request->boolean('acak_soal'),
            'acak_jawaban'         => $request->boolean('acak_jawaban'),
        ]);

        $ujian->kelas()->sync($request->kelas_id);

        return redirect()->route('ujian.index')
            ->with('success', 'Jadwal ujian berhasil dibuat. Token: ' . $ujian->token);
    }

    public function show(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $ujian->load([
            'bankSoal.mataPelajaran',
            'bankSoal.jenjang',
            'jenisUjian',
            'tahunAjaran',
            'kelas.tingkat'
        ]);

        return view('ujian.show', compact('ujian'));
    }

    public function edit(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $data = $this->formData();
        $data['ujian'] = $ujian;

        return view('ujian.edit', $data);
    }

    public function update(Request $request, Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $request->validate([
            'bank_soal_id'          => 'required|exists:bank_soals,id',
            'jenis_ujian_id'        => 'required|exists:jenis_ujians,id',
            'tahun_ajaran_id'       => 'required|exists:tahun_ajarans,id',
            'nama_ujian'            => 'required|string|max:150',
            'waktu_mulai'           => 'required|date',
            'waktu_selesai'         => 'required|date|after:waktu_mulai',
            'durasi_minimal'        => 'required|integer|min:1',
            'kelas_id'              => 'required|array|min:1',
            'kelas_id.*'            => 'exists:kelas,id',
            'acak_soal'             => 'nullable|boolean',
            'acak_jawaban'          => 'nullable|boolean',
        ], [
            'bank_soal_id.required'   => 'Bank soal wajib dipilih.',
            'jenis_ujian_id.required' => 'Jenis ujian wajib dipilih.',
            'tahun_ajaran_id.required'=> 'Tahun ajaran wajib dipilih.',
            'nama_ujian.required'     => 'Nama ujian wajib diisi.',
            'waktu_mulai.required'    => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required'  => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'durasi_minimal.required' => 'Durasi minimal pengerjaan wajib diisi.',
            'kelas_id.required'       => 'Minimal pilih 1 kelas.',
        ]);

        $bankSoal = BankSoal::find($request->bank_soal_id);

        if (!$bankSoal->is_publish) {
            return back()->withInput()->withErrors([
                'bank_soal_id' => 'Bank soal ini belum dipublikasikan, tidak bisa dipakai untuk ujian.',
            ]);
        }

        $this->authorizeJenjangBankSoal($bankSoal);
        $this->authorizeJenjangKelas($request->kelas_id, $bankSoal->jenjang_id);

        $this->authorizeKelasSesuaiGuruBankSoal($request->kelas_id, $bankSoal);

        $unassignedKelas = $this->checkKelasHasGuruMapel($request->kelas_id, $bankSoal, $request->tahun_ajaran_id);
        if (!empty($unassignedKelas)) {
            return back()->withInput()->withErrors([
                'kelas_id' => 'Gagal memperbarui jadwal ujian! Kelas berikut belum memiliki Guru Mata Pelajaran (' . optional($bankSoal->mataPelajaran)->nama_mapel . ') untuk Tahun Ajaran terpilih: ' . implode(', ', $unassignedKelas) . '. Silakan atur Guru Mapel terlebih dahulu di menu Pengguna > Guru Mapel.',
            ]);
        }

        if ($ujian->token_aktif) {
            return back()->withInput()->withErrors([
                'bank_soal_id' => 'Ujian ini tokennya sedang aktif (sedang berjalan), nonaktifkan token dulu sebelum mengubah jadwal.',
            ]);
        }

        $ujian->update([
            'bank_soal_id'         => $request->bank_soal_id,
            'jenis_ujian_id'       => $request->jenis_ujian_id,
            'tahun_ajaran_id'      => $request->tahun_ajaran_id,
            'nama_ujian'           => $request->nama_ujian,
            'waktu_mulai'          => $request->waktu_mulai,
            'waktu_selesai'        => $request->waktu_selesai,
            'durasi_minimal'       => $request->durasi_minimal,
            'acak_soal'            => $request->boolean('acak_soal'),
            'acak_jawaban'         => $request->boolean('acak_jawaban'),
        ]);

        $ujian->kelas()->sync($request->kelas_id);

        return redirect()->route('ujian.index')
            ->with('success', 'Jadwal ujian berhasil diperbarui.');
    }

    public function destroy(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        if ($ujian->token_aktif) {
            return back()->with('error', 'Tidak bisa menghapus ujian yang tokennya sedang aktif. Nonaktifkan dulu.');
        }

        $ujian->delete();

        return redirect()->route('ujian.index')
            ->with('success', 'Jadwal ujian berhasil dihapus.');
    }

    public function regenerateToken(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $ujian->update(['token' => Ujian::generateToken()]);

        return back()->with('success', 'Token baru berhasil dibuat: ' . $ujian->token);
    }

    /**
     * Data dropdown untuk form create/edit.
     */
    private function formData()
    {
        $user = Auth::user();
        $jenjangAdmin = optional($user->admin)->jenjang_id;
        $isAdminJenjang = $user->role == 'admin_jenjang';

        $jenjangs = $isAdminJenjang
            ? Jenjang::where('id', $jenjangAdmin)->get()
            : Jenjang::orderBy('nama_jenjang', 'asc')->get();

        $bankSoals = BankSoal::with([
                'mataPelajaran',
                'jenjang',
                'guruMapel.guru',
                'guruMapel.kelas.tingkat'
            ])
            ->where('is_publish', true)
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->orderBy('nama_bank_soal', 'asc')
            ->get();

        // daftar kelas mentah (masih dibatasi jenjang untuk admin_jenjang).
        // Filter presisi "kelas yang diajar guru pembuat bank soal" dilakukan
        // di JS lewat $bankSoalKelasMap begitu bank soal dipilih.
        $kelasList = Kelas::with('tingkat.jenjang')
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $jenisUjians = JenisUjian::where('aktif', true)->orderBy('nama', 'asc')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        // mapping bank_soal_id => [kelas_id, ...]
        // kelas diambil dari guru_mapel milik bank soal itu (guru_mapel_id),
        // yang sudah otomatis mengunci kombinasi guru + mapel + tahun ajaran.
        $bankSoalKelasMap = [];

        foreach ($bankSoals as $bs) {
            if ($bs->kategori === 'bersama') {
                // Bank Soal Ujian Bersama: tampilkan semua kelas di jenjang yang sama
                $bankSoalKelasMap[$bs->id] = $kelasList
                    ->filter(function($k) use ($bs) {
                        return optional($k->tingkat)->jenjang_id == $bs->jenjang_id;
                    })
                    ->pluck('id')
                    ->values()
                    ->toArray();
            } else {
                $bankSoalKelasMap[$bs->id] = $bs->guruMapel
                    ? $bs->guruMapel->kelas->pluck('id')->toArray()
                    : [];
            }
        }

        // mapping tambahan bankSoalKelasGuruMap[bsId][taId] => [kelas_id, ...]
        $guruMapelKelasData = DB::table('guru_mapel_kelas')
            ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
            ->select('guru_mapels.mata_pelajaran_id', 'guru_mapels.tahun_ajaran_id', 'guru_mapel_kelas.kelas_id')
            ->get();

        $mapelTahunKelasGuruMap = [];
        foreach ($guruMapelKelasData as $row) {
            $mapelTahunKelasGuruMap[$row->mata_pelajaran_id][$row->tahun_ajaran_id][] = $row->kelas_id;
        }

        $bankSoalKelasGuruMap = [];
        foreach ($bankSoals as $bs) {
            $mapelId = $bs->mata_pelajaran_id;
            foreach ($tahunAjarans as $ta) {
                $bankSoalKelasGuruMap[$bs->id][$ta->id] = array_values(array_unique($mapelTahunKelasGuruMap[$mapelId][$ta->id] ?? []));
            }
        }

        return compact(
            'jenjangs', 'bankSoals', 'kelasList', 'jenisUjians', 'tahunAjarans', 'bankSoalKelasMap', 'bankSoalKelasGuruMap'
        );
    }

    /**
     * Pastikan bank soal berada di jenjang admin_jenjang yang login.
     */
    private function authorizeJenjangBankSoal(BankSoal $bankSoal)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            if ($bankSoal->jenjang_id != $jenjangAdmin) {
                abort(403, 'Bank soal ini di luar jenjang Anda.');
            }
        }
    }

    /**
     * Pastikan semua kelas yang dipilih satu jenjang dengan bank soal.
     */
    private function authorizeJenjangKelas(array $kelasIds, $jenjangId)
    {
        $kelasList = Kelas::with('tingkat')->whereIn('id', $kelasIds)->get();

        foreach ($kelasList as $kelas) {
            if (optional($kelas->tingkat)->jenjang_id != $jenjangId) {
                abort(422, 'Ada kelas yang tidak sesuai dengan jenjang bank soal.');
            }
        }
    }

    /**
     * Pastikan kelas yang dipilih benar-benar ada di guru_mapel_kelas
     * milik guru_mapel dari bank soal ini. Berlaku untuk semua role
     * (super_admin, admin_jenjang, guru) karena ini aturan data, bukan
     * aturan hak akses per-role.
     */
    private function authorizeKelasSesuaiGuruBankSoal(array $kelasIds, BankSoal $bankSoal)
    {
        // Jika kategori 'bersama', bank soal boleh dipakai untuk semua kelas di jenjang tersebut
        if ($bankSoal->kategori === 'bersama') {
            return;
        }

        $allowedKelasIds = $bankSoal->guruMapel
            ? $bankSoal->guruMapel->kelas->pluck('id')->toArray()
            : [];

        foreach ($kelasIds as $kelasId) {
            if (!in_array($kelasId, $allowedKelasIds)) {
                abort(422, 'Bank soal ini berkategori Personal. Ada kelas yang tidak diajar oleh guru pembuat bank soal ini.');
            }
        }
    }

    /**
     * Memeriksa apakah setiap kelas yang dipilih memiliki Guru Mapel
     * untuk Mata Pelajaran bank soal & Tahun Ajaran terpilih.
     */
    private function checkKelasHasGuruMapel(array $kelasIds, BankSoal $bankSoal, $tahunAjaranId)
    {
        $mapelId = $bankSoal->mata_pelajaran_id;

        $kelasWithGuru = DB::table('guru_mapel_kelas')
            ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
            ->where('guru_mapels.mata_pelajaran_id', $mapelId)
            ->where('guru_mapels.tahun_ajaran_id', $tahunAjaranId)
            ->whereIn('guru_mapel_kelas.kelas_id', $kelasIds)
            ->pluck('guru_mapel_kelas.kelas_id')
            ->toArray();

        $unassignedIds = array_diff($kelasIds, $kelasWithGuru);

        if (empty($unassignedIds)) {
            return [];
        }

        return Kelas::whereIn('id', $unassignedIds)->pluck('nama_kelas')->toArray();
    }

    /**
     * Pastikan admin_jenjang tidak bisa mengakses ujian di luar jenjangnya.
     */
    private function authorizeJenjang(Ujian $ujian)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            $ujian->loadMissing('bankSoal');

            if (optional($ujian->bankSoal)->jenjang_id != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke jadwal ujian ini.');
            }
        }
    }

    /**
     * Publikasikan nilai ujian ke siswa.
     */
    public function publishNilai(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        if (now()->lt($ujian->waktu_selesai)) {
            return back()->with('error', 'Tidak dapat mempublikasikan nilai untuk ujian yang belum berakhir.');
        }

        $ujian->update([
            'publish_nilai' => true,
            'published_at'  => now(),
            'published_by'  => Auth::id(),
        ]);

        $menungguCount = \App\Models\Nilai::where('ujian_id', $ujian->id)
            ->where('status_penilaian', 'menunggu')
            ->count();

        $pesan = 'Nilai ujian "' . $ujian->nama_ujian . '" berhasil dipublikasikan ke siswa.';
        if ($menungguCount > 0) {
            $pesan .= ' (Catatan: Terdapat ' . $menungguCount . ' siswa yang jawaban essay-nya belum dikoreksi guru; nilai mereka akan otomatis dapat dilihat setelah selesai dikoreksi).';
        }

        return back()->with('success', $pesan);
    }

    /**
     * Tarik kembali (unpublish) nilai ujian dari siswa.
     */
    public function unpublishNilai(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $ujian->update([
            'publish_nilai' => false,
            'published_at'  => null,
            'published_by'  => null,
        ]);

        return back()->with('success', 'Publikasi nilai ujian "' . $ujian->nama_ujian . '" berhasil ditarik kembali.');
    }

    /**
     * Batch publish nilai ujian berdasarkan filter.
     */
    public function batchPublishNilai(Request $request)
    {
        $request->validate([
            'jenis_ujian_id'  => 'nullable|exists:jenis_ujians,id',
            'tahun_ajaran_id' => 'nullable|exists:tahun_ajarans,id',
        ]);

        $user = Auth::user();
        $isAdminJenjang = $user->role == 'admin_jenjang';
        $jenjangAdmin = optional($user->admin)->jenjang_id;

        $query = Ujian::where('waktu_selesai', '<=', now())
            ->where('publish_nilai', false);

        if ($isAdminJenjang) {
            $query->whereHas('bankSoal', function ($q) use ($jenjangAdmin) {
                $q->where('jenjang_id', $jenjangAdmin);
            });
        }

        if ($request->filled('jenis_ujian_id')) {
            $query->where('jenis_ujian_id', $request->jenis_ujian_id);
        }

        if ($request->filled('tahun_ajaran_id')) {
            $query->where('tahun_ajaran_id', $request->tahun_ajaran_id);
        }

        $ujians = $query->get();

        if ($ujians->isEmpty()) {
            return back()->with('warning', 'Tidak ada ujian yang sesuai kriteria untuk dipublikasikan (atau semua ujian sesuai filter sudah dipublikasikan / belum selesai).');
        }

        $publishedCount = 0;
        foreach ($ujians as $ujian) {
            $ujian->update([
                'publish_nilai' => true,
                'published_at'  => now(),
                'published_by'  => Auth::id(),
            ]);
            $publishedCount++;
        }

        return back()->with('success', "Berhasil mempublikasikan nilai untuk {$publishedCount} ujian.");
    }
}