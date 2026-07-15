<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Jenjang;
use App\Imports\GuruImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;


class GuruController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

        $gurus = Guru::with(['user', 'jenjang'])
            ->when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->when($request->filled('jenjang') && Auth::user()->role != 'admin_jenjang', function ($query) use ($request) {
                $query->where('jenjang_id', $request->jenjang);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%')
                      ->orWhere('nip', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalGuru = Guru::when(Auth::user()->role == 'admin_jenjang', function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('guru.index', compact('gurus', 'totalGuru', 'jenjangs'));
    }

    public function create()
    {
        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('guru.create', compact('jenjangs'));
    }

    public function show(Guru $guru)
    {
        $this->authorizeJenjang($guru);

        $guru->load([
            'user',
            'jenjang',
            'guruMapels.mataPelajaran',
            'guruMapels.kelas.tingkat',
            'guruMapels.tahunAjaran',
            'waliKelas.kelas.tingkat',
            'waliKelas.tahunAjaran',
        ]);

        return view('guru.show', compact('guru'));
    }

    public function store(Request $request)
    {
        $jenjangIdForUnique = Auth::user()->role == 'admin_jenjang'
            ? optional(Auth::user()->admin)->jenjang_id
            : $request->jenjang_id;

        $request->validate([
            'nama'       => 'required|string|max:150',
            'nip'        => 'required|string|max:50|unique:gurus,nip',
            'no_hp'      => 'nullable|string|max:20',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:6|confirmed',
            'jenjang_id' => Auth::user()->role != 'admin_jenjang' ? 'required|exists:jenjangs,id' : 'nullable',
        ], [
            'nama.required'       => 'Nama wajib diisi.',
            'nip.required'        => 'NIP wajib diisi.',
            'nip.unique'          => 'NIP ini sudah terdaftar.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.mimes'          => 'Format gambar harus jpg, jpeg, atau png.',
            'foto.max'            => 'Ukuran gambar maksimal 2MB.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar.',
            'password.required'   => 'Password wajib diisi.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
        ]);

        $jenjangId = Auth::user()->role == 'admin_jenjang'
            ? optional(Auth::user()->admin)->jenjang_id
            : $request->jenjang_id;

        DB::transaction(function () use ($request, $jenjangId) {
            $user = new User();
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->role     = 'guru';
            $user->save();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('guru', 'public');
            }

            Guru::create([
                'user_id'    => $user->id,
                'jenjang_id' => $jenjangId,
                'nama'       => $request->nama,
                'nip'        => $request->nip,
                'no_hp'      => $request->no_hp,
                'foto'       => $fotoPath,
            ]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $this->authorizeJenjang($guru);

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        return view('guru.edit', compact('guru', 'jenjangs'));
    }

    public function update(Request $request, Guru $guru)
    {
        $this->authorizeJenjang($guru);

        $request->validate([
            'nama'       => 'required|string|max:150',
            'nip'        => 'required|string|max:50|unique:gurus,nip,' . $guru->id,
            'no_hp'      => 'nullable|string|max:20',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'email'      => 'required|email|unique:users,email,' . $guru->user_id,
            'password'   => 'nullable|string|min:6|confirmed',
            'jenjang_id' => Auth::user()->role != 'admin_jenjang' ? 'required|exists:jenjangs,id' : 'nullable',
        ], [
            'nama.required'       => 'Nama wajib diisi.',
            'nip.required'        => 'NIP wajib diisi.',
            'nip.unique'          => 'NIP ini sudah terdaftar.',
            'foto.image'          => 'File harus berupa gambar.',
            'foto.mimes'          => 'Format gambar harus jpg, jpeg, atau png.',
            'foto.max'            => 'Ukuran gambar maksimal 2MB.',
            'email.required'      => 'Email wajib diisi.',
            'email.email'         => 'Format email tidak valid.',
            'email.unique'        => 'Email ini sudah terdaftar.',
            'password.min'        => 'Password minimal 6 karakter.',
            'password.confirmed'  => 'Konfirmasi password tidak cocok.',
            'jenjang_id.required' => 'Jenjang wajib dipilih.',
        ]);

        $jenjangId = Auth::user()->role == 'admin_jenjang'
            ? $guru->jenjang_id
            : $request->jenjang_id;

        DB::transaction(function () use ($request, $guru, $jenjangId) {
            $userData = [
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $guru->user()->update($userData);

            $fotoPath = $guru->foto;
            if ($request->hasFile('foto')) {
                if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                    Storage::disk('public')->delete($guru->foto);
                }
                $fotoPath = $request->file('foto')->store('guru', 'public');
            }

            $guru->update([
                'jenjang_id' => $jenjangId,
                'nama'       => $request->nama,
                'nip'        => $request->nip,
                'no_hp'      => $request->no_hp,
                'foto'       => $fotoPath,
            ]);
        });

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $this->authorizeJenjang($guru);

        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        // Menghapus user akan otomatis menghapus data guru (cascadeOnDelete)
        $guru->user()->delete();

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes'    => 'File harus berformat xlsx, xls atau csv.',
            'file.max'      => 'Ukuran file maksimal 5 MB.',
        ]);

        try {

            $import = new GuruImport();

            Excel::import($import, $request->file('file'));

            $pesan = "{$import->berhasil} data guru berhasil diimport.";

            if (count($import->failures()) > 0) {
                $pesan .= " ".count($import->failures())." baris gagal divalidasi.";
            }

            if (count($import->gagalDuplikat) > 0) {
                $pesan .= " ".count($import->gagalDuplikat())." data dilewati karena NIP atau Email sudah ada.";
            }

            return redirect()
                ->route('guru.index')
                ->with('success', $pesan)
                ->with('import_failures', $import->failures())
                ->with('import_duplikat', $import->gagalDuplikat);

        } catch (\Exception $e) {

            Log::error($e);

            return back()->withInput()->with(
                'error',
                'Import gagal. '.$e->getMessage()
            );
        }
    }


    public function downloadTemplate()
    {
        $path = public_path('storage/app/templates/template_import_guru.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download(
            $path,
            'template_import_guru.xlsx'
        );
    }

    /**
     * Pastikan admin_jenjang tidak bisa mengakses guru di luar jenjangnya.
     */
    private function authorizeJenjang(Guru $guru)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            if ($guru->jenjang_id != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke data guru ini.');
            }
        }
    }
}