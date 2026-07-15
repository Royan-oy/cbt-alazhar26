<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    public function index()
    {
        $tahunAjaran = TahunAjaran::latest()->paginate(10);

        return view('tahun_ajaran.index', compact('tahunAjaran'));
    }

    public function create()
    {
        return view('tahun_ajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahun' => 'required|max:20',
            'semester'   => 'required|in:ganjil,genap',
        ]);

        $cek = TahunAjaran::where('nama_tahun', $request->nama_tahun)
            ->where('semester', $request->semester)
            ->exists();

        if ($cek) {
            return back()
                ->withInput()
                ->withErrors([
                    'semester' => 'Semester pada tahun ajaran tersebut sudah tersedia.'
                ]);
        }

        TahunAjaran::create([
            'nama_tahun' => $request->nama_tahun,
            'semester'   => $request->semester,
            'is_aktif'   => false,
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(TahunAjaran $tahun_ajaran)
    {
        return view('tahun_ajaran.edit', compact('tahun_ajaran'));
    }

    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        $request->validate([
            'nama_tahun' => 'required|string|max:20',
            'semester'   => 'required|in:ganjil,genap',
        ]);

        $tahunAjaran->update([
            'nama_tahun' => $request->nama_tahun,
            'semester'   => $request->semester,
        ]);

        return redirect()
            ->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran)
    {
        if ($tahunAjaran->is_aktif) {
            return back()->with(
                'error',
                'Tahun ajaran yang sedang aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.'
            );
        }

        $tahunAjaran->delete();

        return back()->with(
            'success',
            'Tahun ajaran berhasil dihapus.'
        );
    }

    public function aktifkan(TahunAjaran $tahunAjaran)
    {
        $masihAktif = TahunAjaran::where('is_aktif', true)
            ->where('id', '!=', $tahunAjaran->id)
            ->exists();

        if ($masihAktif) {
            return back()->with(
                'error',
                'Masih ada tahun ajaran yang aktif. Silakan nonaktifkan terlebih dahulu.'
            );
        }

        $tahunAjaran->update([
            'is_aktif' => true
        ]);

        return back()->with(
            'success',
            'Tahun ajaran berhasil diaktifkan.'
        );
    }

    public function nonaktifkan(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->update([
            'is_aktif' => false
        ]);

        return back()->with(
            'success',
            'Tahun ajaran berhasil dinonaktifkan.'
        );
    }
}