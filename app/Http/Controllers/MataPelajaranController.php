<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MataPelajaranController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

        $mapels = MataPelajaran::with('jenjang')
            ->when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->when($request->filled('jenjang') && Auth::user()->role != 'admin_jenjang', function ($query) use ($request) {
                $query->where('jenjang_id', $request->jenjang);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('nama_mapel', 'like', '%' . $request->search . '%');
            })
            ->orderBy('nama_mapel', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalMapel = MataPelajaran::when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('mata-pelajaran.index', compact('mapels', 'totalMapel', 'jenjangs'));
    }

    public function create()
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('mata-pelajaran.create', compact('jenjangs'));
    }

    public function store(Request $request)
    {
        $jenjangId = Auth::user()->role == 'admin_jenjang'
            ? optional(Auth::user()->admin)->jenjang_id
            : $request->jenjang_id;

        $request->validate([
            'jenjang_id' => Auth::user()->role != 'admin_jenjang' ? 'required|exists:jenjangs,id' : 'nullable',
            'nama_mapel' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('mata_pelajarans')
                    ->where(function ($query) use ($jenjangId) {
                        return $query->where('jenjang_id', $jenjangId);
                    }),
            ],
        ], [
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique'   => 'Mata pelajaran ini sudah terdaftar di jenjang tersebut.',
        ]);

        MataPelajaran::create([
            'jenjang_id' => $jenjangId,
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran)
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('mata-pelajaran.edit', compact('mataPelajaran', 'jenjangs'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran)
    {
        $jenjangId = Auth::user()->role == 'admin_jenjang'
            ? optional(Auth::user()->admin)->jenjang_id
            : $request->jenjang_id;

        $request->validate([
            'jenjang_id' => Auth::user()->role != 'admin_jenjang' ? 'required|exists:jenjangs,id' : 'nullable',
            'nama_mapel' => [
                'required',
                'string',
                'max:100',
                \Illuminate\Validation\Rule::unique('mata_pelajarans')
                    ->where(function ($query) use ($jenjangId) {
                        return $query->where('jenjang_id', $jenjangId);
                    })
                    ->ignore($mataPelajaran->id),
            ],
        ], [
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique'   => 'Mata pelajaran ini sudah terdaftar di jenjang tersebut.',
        ]);

        $mataPelajaran->update([
            'jenjang_id' => $jenjangId,
            'nama_mapel' => $request->nama_mapel,
        ]);

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran)
    {
        $mataPelajaran->delete();

        return redirect()->route('mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}