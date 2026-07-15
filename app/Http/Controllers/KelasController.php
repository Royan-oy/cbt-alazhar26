<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Tingkat;
use App\Models\TahunAjaran;
use App\Models\Jenjang;
use App\Models\SiswaKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Kelas::with([
            'tingkat.jenjang'
        ]);

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */

        if ($user->role == 'admin_jenjang') {

            $jenjangId = optional($user->admin)->jenjang_id;

            $query->whereHas('tingkat', function ($q) use ($jenjangId) {
                $q->where('jenjang_id', $jenjangId);
            });

            // Admin Jenjang tidak memilih jenjang
            $jenjangs = collect();

            $tingkats = Tingkat::where('jenjang_id', $jenjangId)
                ->orderBy('nama_tingkat')
                ->get();

            $totalKelas = Kelas::whereHas('tingkat', function ($q) use ($jenjangId) {
                $q->where('jenjang_id', $jenjangId);
            })->count();

            $totalTingkat = Tingkat::where('jenjang_id', $jenjangId)->count();

        } else {

            // Super Admin
            $jenjangs = \App\Models\Jenjang::orderBy('nama_jenjang')->get();

            $tingkats = Tingkat::with('jenjang')
                ->orderBy('jenjang_id')
                ->orderBy('nama_tingkat')
                ->get();

            $totalKelas = Kelas::count();

            $totalTingkat = Tingkat::count();
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $query->where('nama_kelas', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('jenjang')) {

            $query->whereHas('tingkat', function ($q) use ($request) {
                $q->where('jenjang_id', $request->jenjang);
            });
        }

        if ($request->filled('tingkat')) {

            $query->where('tingkat_id', $request->tingkat);
        }

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $kelas = $query
            ->join('tingkats', 'kelas.tingkat_id', '=', 'tingkats.id')
            ->select('kelas.*')
            ->orderBy('tingkats.jenjang_id')
            ->orderBy('tingkats.nama_tingkat')
            ->orderBy('kelas.nama_kelas')
            ->paginate(10)
            ->withQueryString();

        return view('kelas.index', compact(
            'kelas',
            'jenjangs',
            'tingkats',
            'totalKelas',
            'totalTingkat'
        ));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role == 'admin_jenjang') {

            $tingkats = Tingkat::where(
                'jenjang_id',
                optional($user->admin)->jenjang_id
            )
            ->orderBy('nama_tingkat')
            ->get();

        } else {

            $tingkats = Tingkat::with('jenjang')
                ->orderBy('nama_tingkat')
                ->get();

        }

        return view(
            'kelas.create',
            compact('tingkats')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'tingkat_id' => 'required|exists:tingkats,id',
            'nama_kelas' => 'required|max:20'
        ]);

        $tingkat = Tingkat::findOrFail($request->tingkat_id);

        if (
            Auth::user()->role == 'admin_jenjang' &&
            $tingkat->jenjang_id != optional(Auth::user()->admin)->jenjang_id
        ) {
            abort(403);
        }

        Kelas::create([

            'tingkat_id' => $request->tingkat_id,

            // sementara isi otomatis
            'tahun_ajaran_id' => TahunAjaran::where('is_aktif',1)->value('id'),

            'nama_kelas' => strtoupper($request->nama_kelas)

        ]);

        return redirect()
            ->route('kelas.index')
            ->with(
                'success',
                'Data kelas berhasil ditambahkan.'
            );
    }

    public function show(Kelas $kelas)
    {
        $kelas->load('tingkat.jenjang');

        $tahunAjaranAktif = TahunAjaran::where('is_aktif', true)->first();

        $siswaKelas = SiswaKelas::with('siswa')
            ->where('kelas_id', $kelas->id)
            ->when($tahunAjaranAktif, function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            })
            ->when(request('search'), function ($query) {
                $query->whereHas('siswa', function ($q) {
                    $q->where('nama', 'like', '%' . request('search') . '%');
                });
            })
            ->paginate(10)
            ->withQueryString();

        $totalSiswa = SiswaKelas::where('kelas_id', $kelas->id)
            ->when($tahunAjaranAktif, function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            })
            ->count();

        return view('kelas.show', compact('kelas', 'siswaKelas', 'totalSiswa', 'tahunAjaranAktif'));
    }

    public function edit(Kelas $kelas)
    {
        $user = Auth::user();

        // Admin jenjang hanya boleh mengedit kelas milik jenjangnya
        if (
            $user->role == 'admin_jenjang' &&
            $kelas->tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        if ($user->role == 'super_admin') {

            $tingkats = Tingkat::with('jenjang')
                ->orderBy('nama_tingkat')
                ->get();

        } else {

            $tingkats = Tingkat::where(
                    'jenjang_id',
                    optional($user->admin)->jenjang_id
                )
                ->orderBy('nama_tingkat')
                ->get();

        }

        return view('kelas.edit', compact(
            'kelas',
            'tingkats'
        ));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $user = Auth::user();

        if (
            $user->role == 'admin_jenjang' &&
            $kelas->tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        $request->validate([
            'tingkat_id' => 'required|exists:tingkats,id',
            'nama_kelas' => 'required|string|max:20',
        ]);

        $kelas->update([
            'tingkat_id' => $request->tingkat_id,
            'nama_kelas' => strtoupper($request->nama_kelas),
        ]);

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $user = Auth::user();

        if (
            $user->role == 'admin_jenjang' &&
            $kelas->tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        $kelas->delete();

        return redirect()
            ->route('kelas.index')
            ->with('success', 'Data kelas berhasil dihapus.');
    }
}