<?php

namespace App\Http\Controllers;

use App\Models\Jenjang;
use App\Models\Tingkat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TingkatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Tingkat::with('jenjang');

        // Super Admin bisa melihat semua jenjang
        if ($user->role == 'super_admin') {

            if ($request->filled('jenjang')) {
                $query->where('jenjang_id', $request->jenjang);
            }

            $jenjangs = Jenjang::orderBy('nama_jenjang')->get();
        }

        // Admin Jenjang hanya melihat jenjang miliknya
        else {

            $jenjangId = optional($user->admin)->jenjang_id;

            $query->where('jenjang_id', $jenjangId);

            $jenjangs = Jenjang::where('id', $jenjangId)->get();
        }

        // Search
        if ($request->filled('search')) {
            $query->where('nama_tingkat', 'like', '%' . $request->search . '%');
        }

        $tingkats = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('tingkat.index', compact(
            'tingkats',
            'jenjangs'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();

        if ($user->role == 'super_admin') {

            $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

        } elseif ($user->role == 'admin_jenjang') {

            $jenjangs = Jenjang::where(
                'id',
                optional($user->admin)->jenjang_id
            )->get();

        } else {

            abort(403);

        }

        return view('tingkat.create', compact('jenjangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role == 'super_admin') {

            $request->validate([
                'jenjang_id' => 'required|exists:jenjangs,id',
                'nama_tingkat' => [
                    'required',
                    'max:50',
                    Rule::unique('tingkats')->where(function ($q) use ($request) {
                        return $q->where('jenjang_id', $request->jenjang_id);
                    }),
                ],
            ]);

            $jenjangId = $request->jenjang_id;

        } elseif ($user->role == 'admin_jenjang') {

            $request->validate([
                'nama_tingkat' => [
                    'required',
                    'max:50',
                    Rule::unique('tingkats')->where(function ($q) use ($user) {
                        return $q->where('jenjang_id', optional($user->admin)->jenjang_id);
                    }),
                ],
            ]);

            $jenjangId = optional($user->admin)->jenjang_id;

        } else {

            abort(403);

        }

        Tingkat::create([
            'jenjang_id' => $jenjangId,
            'nama_tingkat' => $request->nama_tingkat,
        ]);

        return redirect()
            ->route('tingkat.index')
            ->with('success', 'Data tingkat berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tingkat $tingkat)
    {
        $user = Auth::user();

        // Admin jenjang hanya boleh mengubah data miliknya
        if (
            $user->role == 'admin_jenjang' &&
            $tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        if ($user->role == 'super_admin') {

            $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

        } elseif ($user->role == 'admin_jenjang') {

            $jenjangs = Jenjang::where(
                'id',
                optional($user->admin)->jenjang_id
            )->get();

        } else {

            abort(403);

        }

        return view('tingkat.edit', compact(
            'tingkat',
            'jenjangs'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tingkat $tingkat)
    {
        $user = Auth::user();

        // Admin jenjang hanya boleh mengubah tingkat miliknya
        if (
            $user->role == 'admin_jenjang' &&
            $tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        if ($user->role == 'super_admin') {

            $request->validate([
                'jenjang_id' => 'required|exists:jenjangs,id',
                'nama_tingkat' => [
                    'required',
                    'max:50',
                    Rule::unique('tingkats')
                        ->ignore($tingkat->id)
                        ->where(function ($q) use ($request) {
                            return $q->where('jenjang_id', $request->jenjang_id);
                        }),
                ],
            ]);

            $jenjangId = $request->jenjang_id;

        } elseif ($user->role == 'admin_jenjang') {

            $request->validate([
                'nama_tingkat' => [
                    'required',
                    'max:50',
                    Rule::unique('tingkats')
                        ->ignore($tingkat->id)
                        ->where(function ($q) use ($user) {
                            return $q->where(
                                'jenjang_id',
                                optional($user->admin)->jenjang_id
                            );
                        }),
                ],
            ]);

            // Admin tidak boleh mengganti jenjang
            $jenjangId = optional($user->admin)->jenjang_id;

        } else {

            abort(403);

        }

        $tingkat->update([
            'jenjang_id'   => $jenjangId,
            'nama_tingkat' => $request->nama_tingkat,
        ]);

        return redirect()
            ->route('tingkat.index')
            ->with('success', 'Data tingkat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tingkat $tingkat)
    {
        $user = Auth::user();

        if (
            $user->role == 'admin_jenjang' &&
            $tingkat->jenjang_id != optional($user->admin)->jenjang_id
        ) {
            abort(403);
        }

        // Jika nanti sudah ada tabel kelas
        if ($tingkat->kelas()->exists()) {

            return back()->with(
                'error',
                'Tingkat tidak dapat dihapus karena masih digunakan oleh data kelas.'
            );

        }

        $tingkat->delete();

        return back()->with(
            'success',
            'Data tingkat berhasil dihapus.'
        );
    }
}