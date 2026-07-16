<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SiswaKelas;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Jenjang;
use App\Models\TahunAjaran;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;
        $isAdminJenjang = Auth::user()->role == 'admin_jenjang';

        $siswas = Siswa::with(['user', 'kelasAktif.kelas.tingkat.jenjang'])
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('kelasAktif.kelas.tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->when($request->filled('jenjang') && !$isAdminJenjang, function ($query) use ($request) {
                $query->whereHas('kelasAktif.kelas.tingkat', function ($q) use ($request) {
                    $q->where('jenjang_id', $request->jenjang);
                });
            })
            ->when($request->filled('kelas'), function ($query) use ($request) {
                $query->whereHas('kelasAktif', function ($q) use ($request) {
                    $q->where('kelas_id', $request->kelas);
                });
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama', 'like', '%' . $request->search . '%')
                      ->orWhere('nis', 'like', '%' . $request->search . '%')
                      ->orWhere('nisn', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        $totalSiswa = Siswa::when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('kelasAktif.kelas.tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        $kelasList = Kelas::with('tingkat')
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        return view('siswa.index', compact('siswas', 'totalSiswa', 'jenjangs', 'kelasList'));
    }

    public function create()
    {
        $data = $this->formData();

        return view('siswa.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:150',
            'nis'             => 'required|string|max:50|unique:siswas,nis|unique:users,nis',
            'nisn'            => 'nullable|string|max:50|unique:siswas,nisn',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'        => 'required|string|min:6|confirmed',
            'kelas_id'        => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ], [
            'nama.required'            => 'Nama wajib diisi.',
            'nis.required'             => 'NIS wajib diisi.',
            'nis.unique'               => 'NIS ini sudah terdaftar.',
            'nisn.unique'              => 'NISN ini sudah terdaftar.',
            'foto.image'               => 'File harus berupa gambar.',
            'foto.mimes'               => 'Format gambar harus jpg, jpeg, atau png.',
            'foto.max'                 => 'Ukuran gambar maksimal 2MB.',
            'password.required'        => 'Password wajib diisi.',
            'password.min'             => 'Password minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
            'kelas_id.required'        => 'Kelas wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $this->authorizeJenjangKelas($request->kelas_id);

        DB::transaction(function () use ($request) {
            $user = new User();
            $user->nis      = $request->nis;
            $user->password = Hash::make($request->password);
            $user->role     = 'siswa';
            $user->save();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('siswa', 'public');
            }

            $siswa = Siswa::create([
                'user_id' => $user->id,
                'nama'    => $request->nama,
                'nis'     => $request->nis,
                'nisn'    => $request->nisn,
                'foto'    => $fotoPath,
            ]);

            SiswaKelas::create([
                'siswa_id'        => $siswa->id,
                'kelas_id'        => $request->kelas_id,
                'tahun_ajaran_id' => $request->tahun_ajaran_id,
            ]);
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        $this->authorizeJenjang($siswa);

        $siswa->load([
            'user',
            'siswaKelas.kelas.tingkat.jenjang',
            'siswaKelas.tahunAjaran',
        ]);

        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $this->authorizeJenjang($siswa);

        $data = $this->formData();
        $data['siswa'] = $siswa;

        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();

        $data['siswaKelasAktif'] = SiswaKelas::where('siswa_id', $siswa->id)
            ->where('tahun_ajaran_id', optional($tahunAktif)->id)
            ->first();

        return view('siswa.edit', $data);
    }

    public function update(Request $request, Siswa $siswa)
    {
        $this->authorizeJenjang($siswa);

        $request->validate([
            'nama'            => 'required|string|max:150',
            'nis'             => 'required|string|max:50|unique:siswas,nis,' . $siswa->id . '|unique:users,nis,' . $siswa->user_id,
            'nisn'            => 'nullable|string|max:50|unique:siswas,nisn,' . $siswa->id,
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'        => 'nullable|string|min:6|confirmed',
            'kelas_id'        => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
        ], [
            'nama.required'            => 'Nama wajib diisi.',
            'nis.required'             => 'NIS wajib diisi.',
            'nis.unique'               => 'NIS ini sudah terdaftar.',
            'nisn.unique'              => 'NISN ini sudah terdaftar.',
            'foto.image'               => 'File harus berupa gambar.',
            'foto.mimes'               => 'Format gambar harus jpg, jpeg, atau png.',
            'foto.max'                 => 'Ukuran gambar maksimal 2MB.',
            'password.min'             => 'Password minimal 6 karakter.',
            'password.confirmed'       => 'Konfirmasi password tidak cocok.',
            'kelas_id.required'        => 'Kelas wajib dipilih.',
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib dipilih.',
        ]);

        $this->authorizeJenjangKelas($request->kelas_id);

        DB::transaction(function () use ($request, $siswa) {
            $userData = [
                'nis' => $request->nis,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $siswa->user()->update($userData);

            $fotoPath = $siswa->foto;
            if ($request->hasFile('foto')) {
                if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                    Storage::disk('public')->delete($siswa->foto);
                }
                $fotoPath = $request->file('foto')->store('siswa', 'public');
            }

            $siswa->update([
                'nama' => $request->nama,
                'nis'  => $request->nis,
                'nisn' => $request->nisn,
                'foto' => $fotoPath,
            ]);

            SiswaKelas::updateOrCreate(
                [
                    'siswa_id'        => $siswa->id,
                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                ],
                [
                    'kelas_id' => $request->kelas_id,
                ]
            );
        });

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $this->authorizeJenjang($siswa);

        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        // Menghapus user otomatis menghapus siswa & siswa_kelas (cascadeOnDelete)
        $siswa->user()->delete();

        return redirect()->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file.required' => 'File Excel wajib diunggah.',
            'file.mimes'    => 'File harus berformat xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new SiswaImport();

        Excel::import($import, $request->file('file'));

        $pesan = $import->berhasil . ' siswa baru berhasil ditambahkan, ' . $import->diperbarui . ' siswa berhasil dipindahkan kelasnya.';

        if (count($import->failures()) > 0) {
            $pesan .= ' ' . count($import->failures()) . ' baris gagal validasi.';
        }

        if (count($import->gagalLainnya) > 0) {
            $pesan .= ' ' . count($import->gagalLainnya) . ' baris dilewati (lihat detail di bawah).';
        }

        return redirect()->route('siswa.index')
            ->with('success', $pesan)
            ->with('import_failures', $import->failures())
            ->with('import_gagal', $import->gagalLainnya);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['jenjang', 'kelas', 'search']);

        $namaFile = 'data_siswa_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new SiswaExport($filters), $namaFile);
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/templates/template_import_siswa.xlsx');

        return response()->download($path, 'template_import_siswa.xlsx');
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

        $kelasList = Kelas::with('tingkat.jenjang')
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->whereHas('tingkat', function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })
            ->orderBy('nama_kelas', 'asc')
            ->get();

        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')->orderByDesc('nama_tahun')->get();

        return compact('jenjangs', 'kelasList', 'tahunAjarans');
    }

    /**
     * Pastikan kelas yang dipilih berada di jenjang admin_jenjang yang login.
     */
    private function authorizeJenjangKelas($kelasId)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            $kelas = Kelas::with('tingkat')->find($kelasId);

            if (!$kelas || optional($kelas->tingkat)->jenjang_id != $jenjangAdmin) {
                abort(403, 'Kelas yang dipilih di luar jenjang Anda.');
            }
        }
    }

    /**
     * Pastikan admin_jenjang tidak bisa mengakses siswa di luar jenjangnya.
     */
    private function authorizeJenjang(Siswa $siswa)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            $siswa->load('kelasAktif.kelas.tingkat');
            $jenjangSiswa = optional(optional(optional($siswa->kelasAktif)->kelas)->tingkat)->jenjang_id;

            if ($jenjangSiswa != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke data siswa ini.');
            }
        }
    }
}