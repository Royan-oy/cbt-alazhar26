<?php

namespace App\Http\Controllers;

use App\Models\Ujian;
use App\Models\BankSoal;
use App\Models\JenisUjian;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'tampilkan_nilai'       => 'nullable|boolean',
            'tampilkan_pembahasan'  => 'nullable|boolean',
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

        $ujian = Ujian::create([
            'bank_soal_id'         => $request->bank_soal_id,
            'jenis_ujian_id'       => $request->jenis_ujian_id,
            'tahun_ajaran_id'      => $request->tahun_ajaran_id,
            'nama_ujian'           => $request->nama_ujian,
            'waktu_mulai'          => $request->waktu_mulai,
            'waktu_selesai'        => $request->waktu_selesai,
            'durasi_minimal'       => $request->durasi_minimal,
            'token'                => Ujian::generateToken(),
            'token_aktif'          => false,
            'acak_soal'            => $request->boolean('acak_soal'),
            'acak_jawaban'         => $request->boolean('acak_jawaban'),
            'tampilkan_nilai'      => $request->boolean('tampilkan_nilai'),
            'tampilkan_pembahasan' => $request->boolean('tampilkan_pembahasan'),
        ]);

        $ujian->kelas()->sync($request->kelas_id);

        return redirect()->route('ujian.index')
            ->with('success', 'Jadwal ujian berhasil dibuat. Token: ' . $ujian->token);
    }

    public function show(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        // Sinkronkan status token berdasarkan waktu
        $this->updateStatusToken($ujian);

        // Ambil data terbaru setelah update
        $ujian->refresh();

        $ujian->load([
            'bankSoal.mataPelajaran',
            'bankSoal.jenjang',
            'jenisUjian',
            'tahunAjaran',
            'kelas.tingkat'
        ]);

        return view('ujian.show', compact('ujian'));
    }

    private function updateStatusToken(Ujian $ujian)
    {
        $now = now();

        // Jika sudah masuk waktu ujian dan token masih nonaktif
        if (
            !$ujian->token_aktif &&
            $now->greaterThanOrEqualTo($ujian->waktu_mulai) &&
            $now->lessThanOrEqualTo($ujian->waktu_selesai)
        ) {
            $ujian->update([
                'token_aktif' => true,
            ]);
        }

        // Jika waktu ujian sudah habis tetapi token masih aktif
        if (
            $ujian->token_aktif &&
            $now->greaterThan($ujian->waktu_selesai)
        ) {
            $ujian->update([
                'token_aktif' => false,
            ]);
        }
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
            'tampilkan_nilai'       => 'nullable|boolean',
            'tampilkan_pembahasan'  => 'nullable|boolean',
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
            'tampilkan_nilai'      => $request->boolean('tampilkan_nilai'),
            'tampilkan_pembahasan' => $request->boolean('tampilkan_pembahasan'),
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

    public function toggleToken(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        $ujian->update(['token_aktif' => !$ujian->token_aktif]);

        $pesan = $ujian->token_aktif
            ? 'Token diaktifkan, siswa sudah bisa masuk ujian.'
            : 'Token dinonaktifkan.';

        return back()->with('success', $pesan);
    }

    public function regenerateToken(Ujian $ujian)
    {
        $this->authorizeJenjang($ujian);

        if ($ujian->token_aktif) {
            return back()->with('error', 'Nonaktifkan token dulu sebelum membuat token baru.');
        }

        $ujian->update(['token' => Ujian::generateToken()]);

        return back()->with('success', 'Token baru berhasil dibuat: ' . $ujian->token);
    }

    /**
     * Data dropdown untuk form create/edit.
     */
    private function formData()
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;
        $isAdminJenjang = Auth::user()->role == 'admin_jenjang';

        $jenjangs = $isAdminJenjang
            ? Jenjang::where('id', $jenjangAdmin)->get()
            : Jenjang::orderBy('nama_jenjang', 'asc')->get();

        $bankSoals = BankSoal::with(['mataPelajaran', 'jenjang'])
            ->where('is_publish', true)
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->orderBy('nama_bank_soal', 'asc')
            ->get();

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

        return compact('jenjangs', 'bankSoals', 'kelasList', 'jenisUjians', 'tahunAjarans');
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
}