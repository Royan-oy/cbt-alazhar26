<?php

namespace App\Http\Controllers;

use App\Models\JenisUjian;
use Illuminate\Http\Request;

class JenisUjianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $jenisUjians = JenisUjian::when($request->search, function ($query) use ($request) {
                $query->where('kode', 'like', '%' . $request->search . '%')
                    ->orWhere('nama', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('jenis_ujian.index', compact('jenisUjians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('jenis_ujian.create');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:jenis_ujians,kode',
            'nama' => 'required|string|max:100|unique:jenis_ujians,nama',
            'deskripsi' => 'nullable|string',
        ],[
            'kode.required' => 'Kode jenis ujian wajib diisi.',
            'kode.unique'   => 'Kode jenis ujian sudah digunakan.',

            'nama.required' => 'Nama jenis ujian wajib diisi.',
            'nama.unique'   => 'Nama jenis ujian sudah tersedia.',
        ]);

        JenisUjian::create([
            'kode'       => strtoupper($request->kode),
            'nama'       => $request->nama,
            'deskripsi'  => $request->deskripsi,
            'aktif'      => false, // Default tidak aktif
        ]);

        return redirect()
            ->route('jenis-ujian.index')
            ->with('success','Jenis ujian berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisUjian $jenisUjian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JenisUjian $jenisUjian)
    {
        return view('jenis_ujian.edit', compact('jenisUjian'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, JenisUjian $jenisUjian)
    {
        $request->validate([
            'kode' => 'required|max:20|unique:jenis_ujians,kode,' . $jenisUjian->id,
            'nama' => 'required|max:100|unique:jenis_ujians,nama,' . $jenisUjian->id,
            'deskripsi' => 'nullable|max:1000',
        ], [
            'kode.required' => 'Kode jenis ujian wajib diisi.',
            'kode.unique' => 'Kode sudah digunakan.',
            'nama.required' => 'Nama jenis ujian wajib diisi.',
            'nama.unique' => 'Nama jenis ujian sudah digunakan.',
        ]);

        $jenisUjian->update([
            'kode'       => strtoupper($request->kode),
            'nama'       => $request->nama,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()
            ->route('jenis-ujian.index')
            ->with('success', 'Jenis ujian berhasil diperbarui.');
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(JenisUjian $jenisUjian)
    {
        if ($jenisUjian->aktif) {

            return redirect()
                ->route('jenis-ujian.index')
                ->with(
                    'error',
                    'Jenis ujian yang masih aktif tidak dapat dihapus. Nonaktifkan terlebih dahulu.'
                );
        }

        try {

            $jenisUjian->delete();

            return redirect()
                ->route('jenis-ujian.index')
                ->with(
                    'success',
                    'Jenis ujian berhasil dihapus.'
                );

        } catch (\Exception $e) {

            return redirect()
                ->route('jenis-ujian.index')
                ->with(
                    'error',
                    'Jenis ujian tidak dapat dihapus karena masih digunakan oleh data lain.'
                );

        }
    }

    /**
     * Aktifkan jenis ujian.
     */
    public function aktifkan(JenisUjian $jenisUjian)
    {
        $jenisUjian->update([
            'aktif' => true,
        ]);

        return back()->with(
            'success',
            'Jenis ujian berhasil diaktifkan.'
        );
    }

    /**
     * Nonaktifkan jenis ujian.
     */
    public function nonaktifkan(JenisUjian $jenisUjian)
    {
        $jenisUjian->update([
            'aktif' => false,
        ]);

        return back()->with(
            'success',
            'Jenis ujian berhasil dinonaktifkan.'
        );
    }
}