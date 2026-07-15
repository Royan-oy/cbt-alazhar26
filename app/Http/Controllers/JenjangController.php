<?php

namespace App\Http\Controllers;

use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class JenjangController extends Controller
{
    public function index(Request $request)
    {
        $query = Jenjang::query();

        if ($request->filled('search')) {
            $query->where('nama_jenjang', 'like', '%' . $request->search . '%');
        }

        $jenjangs = $query->latest()->paginate(10);

        // Agar keyword tetap ada saat pindah halaman
        $jenjangs->appends($request->all());

        return view('jenjang.index', compact('jenjangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_jenjang' => 'required|max:100|unique:jenjangs,nama_jenjang'
        ]);

        Jenjang::create([
            'nama_jenjang' => $request->nama_jenjang,
            'slug' => Str::slug($request->nama_jenjang)
        ]);

        return redirect()
            ->route('jenjang.index')
            ->with('success', 'Jenjang berhasil ditambahkan.');
    }

    public function edit(Jenjang $jenjang)
    {
        return view('jenjang.edit', compact('jenjang'));
    }

    public function update(Request $request, Jenjang $jenjang)
    {
        $request->validate([
            'nama_jenjang' => 'required|max:100|unique:jenjangs,nama_jenjang,' . $jenjang->id,
        ]);

        $jenjang->update([
            'nama_jenjang' => $request->nama_jenjang,
            'slug' => Str::slug($request->nama_jenjang),
        ]);

        return redirect()
            ->route('jenjang.index')
            ->with('success', 'Jenjang berhasil diperbarui.');
    }

    public function destroy(Jenjang $jenjang)
    {
        $jenjang->delete();

        return redirect()
            ->route('jenjang.index')
            ->with('success', 'Jenjang berhasil dihapus.');
    }
}