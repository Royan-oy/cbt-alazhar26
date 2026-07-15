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

class WaliKelasController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

        $waliKelas = WaliKelas::with(['guru.jenjang', 'kelas.tingkat', 'tahunAjaran'])
            ->when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->whereHas('guru', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('guru', function ($guru) use ($request) {
                        $guru->where('nama', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('kelas', function ($kelas) use ($request) {
                        $kelas->where('nama_kelas', 'like', '%' . $request->search . '%');
                    });
                });
            })
            ->when($request->filled('jenjang') && Auth::user()->role != 'admin_jenjang', function ($query) use ($request) {
                $query->whereHas('guru', function ($q) use ($request) {
                    $q->where('jenjang_id', $request->jenjang);
                });
            })
            ->when($request->filled('tahun_ajaran'), function ($query) use ($request) {
                $query->where('tahun_ajaran_id', $request->tahun_ajaran);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalWaliKelas = WaliKelas::when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->whereHas('guru', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        return view('wali-kelas.index', compact('waliKelas', 'totalWaliKelas', 'jenjangs', 'tahunAjarans'));
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
}