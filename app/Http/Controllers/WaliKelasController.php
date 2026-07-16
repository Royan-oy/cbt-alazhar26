<?php

namespace App\Http\Controllers;

use App\Models\WaliKelas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Exports\WaliKelasExport;
use App\Imports\WaliKelasImport;
use Maatwebsite\Excel\Facades\Excel;

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $jenjangAdmin = optional($user->admin)->jenjang_id;

        /*
        |--------------------------------------------------------------------------
        | Tahun Ajaran Aktif
        |--------------------------------------------------------------------------
        */

        $tahunAktif = TahunAjaran::where('is_aktif', 1)->first();

        /*
        |--------------------------------------------------------------------------
        | Query
        |--------------------------------------------------------------------------
        */

        $query = WaliKelas::with([
            'guru.jenjang',
            'kelas.tingkat',
            'tahunAjaran'
        ]);

        /*
        |--------------------------------------------------------------------------
        | Role Admin Jenjang
        |--------------------------------------------------------------------------
        */

        if ($user->role == 'admin_jenjang') {

            $query->whereHas('guru', function ($q) use ($jenjangAdmin) {
                $q->where('jenjang_id', $jenjangAdmin);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('guru', function ($guru) use ($search) {
                    $guru->where('nama', 'like', "%{$search}%");
                })

                ->orWhereHas('kelas', function ($kelas) use ($search) {
                    $kelas->where('nama_kelas', 'like', "%{$search}%");
                });

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Jenjang
        |--------------------------------------------------------------------------
        */

        if ($request->filled('jenjang') && $user->role != 'admin_jenjang') {

            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('jenjang_id', $request->jenjang);
            });

        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahun Ajaran
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun_ajaran')) {

            $query->where('tahun_ajaran_id', $request->tahun_ajaran);

        } elseif ($tahunAktif) {

            // Default tampilkan tahun ajaran aktif
            $query->where('tahun_ajaran_id', $tahunAktif->id);

        }

        /*
        |--------------------------------------------------------------------------
        | Data
        |--------------------------------------------------------------------------
        */

        $waliKelas = $query
            ->orderByDesc('tahun_ajaran_id')
            ->orderBy('kelas_id')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $totalWaliKelas = (clone $query)->count();

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')
            ->orderByDesc('nama_tahun')
            ->get();

        return view('wali-kelas.index', compact(
            'waliKelas',
            'totalWaliKelas',
            'jenjangs',
            'tahunAjarans',
            'tahunAktif'
        ));
    }

    public function create()
    {
        $data = $this->formData();

        return view('wali-kelas.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'         => 'required|exists:gurus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ], [
            'guru_id.required'         => 'Guru wajib dipilih.',
            'kelas_id.required'        => 'Kelas wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $this->validateJenjangMatch($request);
        $this->validateUniqueAssignment($request);

        WaliKelas::create([
            'guru_id'         => $request->guru_id,
            'kelas_id'        => $request->kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()->route('wali-kelas.index')
            ->with('success', 'Wali kelas berhasil ditambahkan.');
    }

    public function edit(WaliKelas $wali_kelas)
    {
        $this->authorizeJenjang($wali_kelas);

        $data = $this->formData();
        $data['waliKelas'] = $wali_kelas;

        return view('wali-kelas.edit', $data);
    }

    public function update(Request $request, WaliKelas $wali_kelas)
    {
        $this->authorizeJenjang($wali_kelas);

        $request->validate([
            'guru_id'         => 'required|exists:gurus,id',
            'kelas_id'        => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ], [
            'guru_id.required'         => 'Guru wajib dipilih.',
            'kelas_id.required'        => 'Kelas wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $this->validateJenjangMatch($request);
        $this->validateUniqueAssignment($request, $wali_kelas->id);

        $wali_kelas->update([
            'guru_id'         => $request->guru_id,
            'kelas_id'        => $request->kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()->route('wali-kelas.index')
            ->with('success', 'Wali kelas berhasil diperbarui.');
    }

    public function destroy(WaliKelas $wali_kelas)
    {
        $this->authorizeJenjang($wali_kelas);

        $wali_kelas->delete();

        return redirect()->route('wali-kelas.index')
            ->with('success', 'Wali kelas berhasil dihapus.');
    }

    /**
     * Data dropdown untuk form create/edit, sudah discope sesuai jenjang admin_jenjang.
     */
    private function formData()
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

        $jenjangs = Auth::user()->role == 'admin_jenjang'
            ? Jenjang::where('id', $jenjangAdmin)->get()
            : Jenjang::orderBy('nama_jenjang', 'asc')->get();

        $gurus = Guru::with('jenjang')
            ->when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->orderBy('nama', 'asc')
            ->get();

        $kelasList = Kelas::with('tingkat.jenjang')
            ->when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->whereHas('tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        return compact('jenjangs', 'gurus', 'kelasList', 'tahunAjarans');
    }

    /**
     * Pastikan kelas yang dipilih berada di jenjang yang sama dengan guru.
     */
    private function validateJenjangMatch(Request $request)
    {
        $guru = Guru::find($request->guru_id);
        $kelas = Kelas::with('tingkat')->find($request->kelas_id);

        if ($guru && $kelas && optional($kelas->tingkat)->jenjang_id != $guru->jenjang_id) {
            throw ValidationException::withMessages([
                'kelas_id' => 'Kelas yang dipilih tidak sesuai dengan jenjang guru ini.',
            ]);
        }
    }

    /**
     * Pastikan maksimal 2 wali kelas per kelas per tahun ajaran,
     * dan 1 guru hanya wali 1 kelas per tahun ajaran (tidak dobel kelas).
     */
    private function validateUniqueAssignment(Request $request, $ignoreId = null)
    {
        $jumlahWaliKelasIni = WaliKelas::where('kelas_id', $request->kelas_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->count();

        if ($jumlahWaliKelasIni >= 2) {
            throw ValidationException::withMessages([
                'kelas_id' => 'Kelas ini sudah memiliki 2 wali kelas pada tahun ajaran tersebut (maksimal 2 wali per kelas).',
            ]);
        }

        $guruSudahJadiWaliKelasIni = WaliKelas::where('kelas_id', $request->kelas_id)
            ->where('guru_id', $request->guru_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        if ($guruSudahJadiWaliKelasIni) {
            throw ValidationException::withMessages([
                'guru_id' => 'Guru ini sudah menjadi wali di kelas yang sama pada tahun ajaran tersebut.',
            ]);
        }

        $guruSudahJadiWaliKelasLain = WaliKelas::where('guru_id', $request->guru_id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->where('kelas_id', '!=', $request->kelas_id)
            ->when($ignoreId, function ($query) use ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            })
            ->exists();

        if ($guruSudahJadiWaliKelasLain) {
            throw ValidationException::withMessages([
                'guru_id' => 'Guru ini sudah menjadi wali kelas lain pada tahun ajaran tersebut.',
            ]);
        }
    }

    /**
     * Pastikan admin_jenjang tidak bisa mengelola wali kelas di luar jenjangnya.
     */
    private function authorizeJenjang(WaliKelas $waliKelas)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            if (optional($waliKelas->guru)->jenjang_id != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke data ini.');
            }
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'jenjang',
            'tahun_ajaran',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Jenjang
        |--------------------------------------------------------------------------
        */

        if (Auth::user()->role == 'admin_jenjang') {

            $namaJenjang = optional(optional(Auth::user()->admin)->jenjang)->nama_jenjang ?? 'Jenjang';

        } elseif ($request->filled('jenjang')) {

            $jenjang = Jenjang::find($request->jenjang);

            $namaJenjang = $jenjang
                ? $jenjang->nama_jenjang
                : 'Semua_Jenjang';

        } else {

            $namaJenjang = 'Semua_Jenjang';

        }

        /*
        |--------------------------------------------------------------------------
        | Tahun Ajaran
        |--------------------------------------------------------------------------
        */

        if ($request->filled('tahun_ajaran')) {

            $tahun = TahunAjaran::find($request->tahun_ajaran);

        } else {

            // Default mengikuti tahun ajaran aktif
            $tahun = TahunAjaran::where('is_aktif', 1)->first();

        }

        if ($tahun) {

            $namaTahun = str_replace('/', '-', $tahun->nama_tahun)
                .'_'
                .ucfirst($tahun->semester);

        } else {

            $namaTahun = 'Semua_Tahun';

        }

        /*
        |--------------------------------------------------------------------------
        | Bersihkan karakter nama file
        |--------------------------------------------------------------------------
        */

        $namaJenjang = str_replace([' ', '/'], ['_', '-'], $namaJenjang);

        /*
        |--------------------------------------------------------------------------
        | Nama File
        |--------------------------------------------------------------------------
        */

        $namaFile = sprintf(
            'wali_kelas_%s_%s_%s.xlsx',
            $namaJenjang,
            $namaTahun,
            now()->format('Ymd_His')
        );

        return Excel::download(
            new WaliKelasExport($filters),
            $namaFile
        );
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ], [
            'file.required' => 'Silakan pilih file Excel.',
            'file.mimes' => 'File harus berupa Excel (.xlsx, .xls, .csv).',
        ]);

        $import = new WaliKelasImport();

        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('wali-kelas.index')
            ->with([
                'success' => "Import selesai. {$import->success} data berhasil diimport.",
                'import_success' => $import->success,
                'import_skipped' => $import->skipped,
                'import_failed' => count($import->failed),
                'import_errors' => $import->failed,
            ]);
    }
}