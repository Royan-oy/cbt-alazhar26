<?php

namespace App\Http\Controllers;

use App\Models\GuruMapel;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\Jenjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuruMapelExport;
use App\Imports\GuruMapelImport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class GuruMapelController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
    
        $tahunAktif = TahunAjaran::where('is_aktif', true)->first();
    
        $query = GuruMapel::with([
            'guru.jenjang',
            'mataPelajaran',
            'kelas.tingkat',
            'tahunAjaran',
        ]);
    
        if ($user->role == 'admin_jenjang') {
    
            $jenjangId = optional($user->admin)->jenjang_id;
    
            $query->whereHas('guru', function ($q) use ($jenjangId) {
                $q->where('jenjang_id', $jenjangId);
            });
    
            $jenjangs = collect();
    
            $gurus = Guru::where('jenjang_id', $jenjangId)
                ->orderBy('nama')
                ->get();
    
        } else {
    
            $jenjangs = Jenjang::orderBy('nama_jenjang')->get();
    
            $gurus = Guru::with('jenjang')
                ->orderBy('nama')
                ->get();
        }
    
        if ($request->filled('search')) {
    
            $search = $request->search;
    
            $query->where(function ($q) use ($search) {
    
                $q->whereHas('guru', function ($guru) use ($search) {
                    $guru->where('nama', 'like', "%{$search}%");
                })
    
                ->orWhereHas('mataPelajaran', function ($mapel) use ($search) {
                    $mapel->where('nama_mapel', 'like', "%{$search}%");
                });
    
            });
    
        }
    
        if ($request->filled('jenjang')) {
    
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('jenjang_id', $request->jenjang);
            });
    
        }
    
        if ($request->filled('guru')) {
    
            $query->where('guru_id', $request->guru);
    
        }
    
        if ($request->filled('tahun_ajaran')) {
    
            $query->where('tahun_ajaran_id', $request->tahun_ajaran);
    
        } elseif ($tahunAktif) {
    
            $query->where('tahun_ajaran_id', $tahunAktif->id);
    
        }
    
        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */
    
        $totalGuruMapel = (clone $query)->count();
    
        $totalGuru = (clone $query)
            ->distinct('guru_id')
            ->count('guru_id');
    
        $totalMapel = (clone $query)
            ->distinct('mata_pelajaran_id')
            ->count('mata_pelajaran_id');
    
        /*
        |--------------------------------------------------------------------------
        | Ambil semua data (sesuai filter), lalu group per guru_id
        |--------------------------------------------------------------------------
        */
    
        $semuaPenugasan = (clone $query)
            ->orderBy('guru_id')
            ->orderBy('mata_pelajaran_id')
            ->get();
    
        $grouped = $semuaPenugasan
            ->groupBy('guru_id')
            ->map(function ($items) {
    
                $first = $items->first();
    
                return (object) [
                    'guru'        => $first->guru,
                    'tahunAjaran' => $first->tahunAjaran,
                    'items'       => $items,
                    'total_mapel' => $items->count(),
                    'total_kelas' => $items->pluck('kelas')->collapse()->unique('id')->count(),
                ];
            })
            // FIX: closure biasa, bukan arrow function (fn), supaya jalan di PHP 7
            ->sortBy(function ($row) {
                return optional($row->guru)->nama;
            })
            ->values();
    
        /*
        |--------------------------------------------------------------------------
        | Pagination manual atas hasil grouping
        |--------------------------------------------------------------------------
        */
    
        $perPage     = 10;
        $currentPage = Paginator::resolveCurrentPage('page');
    
        $guruMapels = new LengthAwarePaginator(
            $grouped->forPage($currentPage, $perPage)->values(),
            $grouped->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );
    
        $guruMapels->withQueryString();
    
        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')
            ->orderByDesc('nama_tahun')
            ->get();
    
        return view('guru-mapel.index', compact(
            'guruMapels',
            'gurus',
            'jenjangs',
            'tahunAjarans',
            'tahunAktif',
            'totalGuruMapel',
            'totalGuru',
            'totalMapel'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Method create() — TIDAK ADA PERUBAHAN
    |--------------------------------------------------------------------------
    | Query data untuk dropdown/checkbox (guru, mapel, kelas, tahun ajaran)
    | tetap sama seperti sebelumnya. Filtering per jenjang untuk role
    | admin_jenjang vs super_admin tetap dipertahankan.
    */

    public function create()
    {
        $user = Auth::user();

        if ($user->role == 'admin_jenjang') {

            $jenjangId = optional($user->admin)->jenjang_id;

            $jenjangs = Jenjang::whereKey($jenjangId)->get();

            $gurus = Guru::where('jenjang_id', $jenjangId)
                ->orderBy('nama')
                ->get();

            $mataPelajarans = MataPelajaran::where('jenjang_id', $jenjangId)
                ->orderBy('nama_mapel')
                ->get();

            $kelasList = Kelas::with('tingkat')
                ->whereHas('tingkat', function ($q) use ($jenjangId) {
                    $q->where('jenjang_id', $jenjangId);
                })
                ->orderBy('nama_kelas')
                ->get();

        } else {

            $jenjangs = Jenjang::orderBy('nama_jenjang')->get();

            $gurus = Guru::with('jenjang')
                ->orderBy('nama')
                ->get();

            $mataPelajarans = MataPelajaran::with('jenjang')
                ->orderBy('nama_mapel')
                ->get();

            $kelasList = Kelas::with('tingkat.jenjang')
                ->orderBy('nama_kelas')
                ->get();
        }

        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')
            ->orderByDesc('nama_tahun')
            ->get();

        $takenClasses = DB::table('guru_mapel_kelas')
            ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
            ->select(
                'guru_mapels.tahun_ajaran_id',
                'guru_mapels.mata_pelajaran_id',
                'guru_mapels.guru_id',
                'guru_mapel_kelas.kelas_id'
            )
            ->get();

        $takenClassesMap = [];
        foreach ($takenClasses as $row) {
            $key = $row->tahun_ajaran_id . '_' . $row->mata_pelajaran_id . '_' . $row->kelas_id;
            $takenClassesMap[$key] = $row->guru_id;
        }

        return view('guru-mapel.create', compact(
            'jenjangs',
            'gurus',
            'mataPelajarans',
            'kelasList',
            'tahunAjarans',
            'takenClassesMap'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Method store() — DIPERBARUI
    |--------------------------------------------------------------------------
    | Sekarang menerima input berbentuk:
    |
    |   guru_id
    |   tahun_ajaran_id
    |   penugasan => [
    |       0 => ['mata_pelajaran_id' => x, 'kelas_id' => [1,2,3]],
    |       1 => ['mata_pelajaran_id' => y, 'kelas_id' => [4,5]],
    |       ...
    |   ]
    |
    | Setiap item di 'penugasan' merepresentasikan satu blok
    | "Mata Pelajaran + Kelas" pada form (bisa banyak sekaligus).
    */

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'         => 'required|exists:gurus,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',

            'penugasan'                     => 'required|array|min:1',
            'penugasan.*.mata_pelajaran_id' => 'required|exists:mata_pelajarans,id|distinct',
            'penugasan.*.kelas_id'          => 'required|array|min:1',
            'penugasan.*.kelas_id.*'        => 'exists:kelas,id',
        ], [
            'penugasan.required'                     => 'Minimal harus ada 1 penugasan mata pelajaran.',
            'penugasan.*.mata_pelajaran_id.required'  => 'Mata pelajaran wajib dipilih untuk setiap penugasan.',
            'penugasan.*.mata_pelajaran_id.distinct'  => 'Mata pelajaran tidak boleh dipilih lebih dari sekali.',
            'penugasan.*.kelas_id.required'           => 'Pilih minimal satu kelas untuk setiap penugasan.',
        ]);

        DB::transaction(function () use ($request) {

            $guru = Guru::findOrFail($request->guru_id);

            foreach ($request->penugasan as $item) {

                $mapel = MataPelajaran::findOrFail($item['mata_pelajaran_id']);

                /*
                |--------------------------------------------------------------------------
                | Jenjang guru & mapel harus sama
                |--------------------------------------------------------------------------
                */

                if ($guru->jenjang_id != $mapel->jenjang_id) {

                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'penugasan' => 'Guru dan mata pelajaran "' . $mapel->nama_mapel . '" harus berada pada jenjang yang sama.'
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | Guru Mapel (satu baris per kombinasi guru + mapel + tahun ajaran)
                |--------------------------------------------------------------------------
                */

                $guruMapel = GuruMapel::firstOrCreate([
                    'guru_id'           => $guru->id,
                    'mata_pelajaran_id' => $mapel->id,
                    'tahun_ajaran_id'   => $request->tahun_ajaran_id,
                ]);

                /*
                |--------------------------------------------------------------------------
                | Simpan kelas untuk penugasan ini
                |--------------------------------------------------------------------------
                */

                foreach ($item['kelas_id'] as $kelasId) {

                    $kelas = Kelas::with('tingkat')->findOrFail($kelasId);

                    if ($kelas->tingkat->jenjang_id != $guru->jenjang_id) {

                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'penugasan' => 'Ada kelas yang berbeda jenjang untuk mata pelajaran "' . $mapel->nama_mapel . '".'
                        ]);
                    }

                    $alreadyTakenByOther = DB::table('guru_mapel_kelas')
                        ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
                        ->where('guru_mapels.tahun_ajaran_id', $request->tahun_ajaran_id)
                        ->where('guru_mapels.mata_pelajaran_id', $mapel->id)
                        ->where('guru_mapel_kelas.kelas_id', $kelasId)
                        ->where('guru_mapels.guru_id', '!=', $guru->id)
                        ->exists();

                    if ($alreadyTakenByOther) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'penugasan' => 'Kelas "' . $kelas->nama_kelas . '" sudah memiliki guru pengajar lain untuk mata pelajaran "' . $mapel->nama_mapel . '".'
                        ]);
                    }

                    $guruMapel->kelas()->syncWithoutDetaching([$kelasId]);
                }
            }

        });

        return redirect()
            ->route('guru-mapel.index')
            ->with('success', 'Penugasan guru berhasil disimpan.');
    }

    public function edit(GuruMapel $guru_mapel)
    {
        $this->authorizeJenjang($guru_mapel);
    
        $guru           = $guru_mapel->guru;
        $tahunAjaranId  = $guru_mapel->tahun_ajaran_id;
    
        $penugasanList = GuruMapel::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->orderBy('mata_pelajaran_id')
            ->get();
    
        $data = $this->formData();


        /*
        |--------------------------------------------------------------------------
        | Filter Mata Pelajaran sesuai jenjang guru
        |--------------------------------------------------------------------------
        */

        $data['mataPelajarans'] = MataPelajaran::where(
                'jenjang_id',
                $guru->jenjang_id
            )
            ->orderBy('nama_mapel')
            ->get();


        $data['kelasList'] = Kelas::with('tingkat')
            ->whereHas('tingkat', function($q) use ($guru){

                $q->where(
                    'jenjang_id',
                    $guru->jenjang_id
                );

            })
            ->orderBy('nama_kelas')
            ->get();



        $data['guruMapel']     = $guru_mapel;
        $data['guru']          = $guru;
        $data['tahunAjaranId'] = $tahunAjaranId;
        $data['penugasanList'] = $penugasanList;
    
        return view('guru-mapel.edit', $data);
    }

    public function update(Request $request, GuruMapel $guru_mapel)
    {
        $this->authorizeJenjang($guru_mapel);
    
        $request->validate([
            'guru_id'         => 'required|exists:gurus,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajarans,id',
    
            'penugasan'                     => 'required|array|min:1',
            'penugasan.*.mata_pelajaran_id' => 'required|exists:mata_pelajarans,id|distinct',
            'penugasan.*.kelas_id'          => 'required|array|min:1',
            'penugasan.*.kelas_id.*'        => 'exists:kelas,id',
        ], [
            'penugasan.required'                     => 'Minimal harus ada 1 penugasan mata pelajaran.',
            'penugasan.*.mata_pelajaran_id.required'  => 'Mata pelajaran wajib dipilih untuk setiap penugasan.',
            'penugasan.*.mata_pelajaran_id.distinct'  => 'Mata pelajaran tidak boleh dipilih lebih dari sekali.',
            'penugasan.*.kelas_id.required'           => 'Pilih minimal satu kelas untuk setiap penugasan.',
        ]);
    
        // Guru + tahun ajaran ASAL (sebelum diedit), dipakai untuk membersihkan
        // penugasan lama yang sudah tidak ada lagi di submit.
        $originalGuruId  = $guru_mapel->guru_id;
        $originalTahunId = $guru_mapel->tahun_ajaran_id;
    
        DB::transaction(function () use ($request, $originalGuruId, $originalTahunId) {
    
            $guru = Guru::findOrFail($request->guru_id);
    
            $keepIds = [];
    
            foreach ($request->penugasan as $item) {
    
                $mapel = MataPelajaran::findOrFail($item['mata_pelajaran_id']);
    
                if ($guru->jenjang_id != $mapel->jenjang_id) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'penugasan' => 'Guru dan mata pelajaran "' . $mapel->nama_mapel . '" harus berada pada jenjang yang sama.'
                    ]);
                }
    
                $guruMapelRow = GuruMapel::updateOrCreate(
                    [
                        'guru_id'           => $guru->id,
                        'mata_pelajaran_id' => $mapel->id,
                        'tahun_ajaran_id'   => $request->tahun_ajaran_id,
                    ]
                );
    
                $keepIds[] = $guruMapelRow->id;
    
                $kelasIds = [];
    
                foreach ($item['kelas_id'] as $kelasId) {
    
                    $kelas = Kelas::with('tingkat')->findOrFail($kelasId);
    
                    if ($kelas->tingkat->jenjang_id != $guru->jenjang_id) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'penugasan' => 'Ada kelas yang berbeda jenjang untuk mata pelajaran "' . $mapel->nama_mapel . '".'
                        ]);
                    }
    
                    $alreadyTakenByOther = DB::table('guru_mapel_kelas')
                        ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
                        ->where('guru_mapels.tahun_ajaran_id', $request->tahun_ajaran_id)
                        ->where('guru_mapels.mata_pelajaran_id', $mapel->id)
                        ->where('guru_mapel_kelas.kelas_id', $kelasId)
                        ->where('guru_mapels.guru_id', '!=', $guru->id)
                        ->exists();
    
                    if ($alreadyTakenByOther) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'penugasan' => 'Kelas "' . $kelas->nama_kelas . '" sudah memiliki guru pengajar lain untuk mata pelajaran "' . $mapel->nama_mapel . '".'
                        ]);
                    }
    
                    $kelasIds[] = $kelasId;
                }
    
                // sync = kelas yang tidak dicentang lagi otomatis lepas dari mapel ini
                $guruMapelRow->kelas()->sync($kelasIds);
            }
    
            // Hapus penugasan lama (dari guru + tahun ajaran asal) yang sudah
            // tidak dikirim lagi di submit (mis. mata pelajaran yang dihapus dari form)
            GuruMapel::where('guru_id', $originalGuruId)
                ->where('tahun_ajaran_id', $originalTahunId)
                ->whereNotIn('id', $keepIds)
                ->get()
                ->each(function ($old) {
                    $old->kelas()->detach();
                    $old->delete();
                });
    
        });
    
        return redirect()
            ->route('guru-mapel.index')
            ->with('success', 'Penugasan guru berhasil diperbarui.');
    }

    public function show($id)
    {
        $guru = Guru::with([
            'jenjang'
        ])
        ->findOrFail($id);


        $guruMapels = GuruMapel::with([
            'mataPelajaran',
            'kelas.tingkat',
            'tahunAjaran'
        ])
        ->where('guru_id', $guru->id)
        ->orderBy('tahun_ajaran_id','desc')
        ->get();


        $this->authorizeJenjang($guruMapels->first());


        return view('guru-mapel.show', compact(
            'guru',
            'guruMapels'
        ));
    }

    public function destroy($guru_id)
    {

        DB::transaction(function () use ($guru_id) {


            $guruMapels = GuruMapel::where('guru_id',$guru_id)
                ->get();


            foreach($guruMapels as $guruMapel){

                // hapus semua kelas yang terhubung
                $guruMapel->kelas()->detach();


                // hapus penugasan
                $guruMapel->delete();

            }


        });


        return redirect()
            ->route('guru-mapel.index')
            ->with(
                'success',
                'Seluruh penugasan guru mata pelajaran berhasil dihapus.'
            );

    }

    /**
     * Data dropdown untuk form create/edit, sudah discope sesuai jenjang admin_jenjang.
     */
    private function formData()
    {
        $user = Auth::user();

        $jenjangAdmin = optional($user->admin)->jenjang_id;


        /*
        |--------------------------------------------------------------------------
        | JENJANG
        |--------------------------------------------------------------------------
        */

        if($user->role == 'admin_jenjang'){

            $jenjangs = Jenjang::where(
                'id',
                $jenjangAdmin
            )->get();

        }else{

            $jenjangs = Jenjang::orderBy(
                'nama_jenjang'
            )->get();

        }




        /*
        |--------------------------------------------------------------------------
        | GURU
        |--------------------------------------------------------------------------
        */

        $gurus = Guru::with('jenjang')

            ->when(
                $user->role == 'admin_jenjang',
                function($query) use($jenjangAdmin){

                    $query->where(
                        'jenjang_id',
                        $jenjangAdmin
                    );

                }
            )

            ->orderBy('nama')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | MAPEL
        |--------------------------------------------------------------------------
        */

        $mataPelajarans = MataPelajaran::with('jenjang')

            ->when(
                $user->role == 'admin_jenjang',
                function($query) use($jenjangAdmin){

                    $query->where(
                        'jenjang_id',
                        $jenjangAdmin
                    );

                }
            )

            ->orderBy('nama_mapel')

            ->get();


        /*
        |--------------------------------------------------------------------------
        | KELAS
        |--------------------------------------------------------------------------
        */

        $kelasList = Kelas::with('tingkat.jenjang')

            ->when(
                $user->role == 'admin_jenjang',
                function($query) use($jenjangAdmin){

                    $query->whereHas(
                        'tingkat',
                        function($q) use($jenjangAdmin){

                            $q->where(
                                'jenjang_id',
                                $jenjangAdmin
                            );

                        }
                    );

                }
            )

            ->orderBy('nama_kelas')

            ->get();







        /*
        |--------------------------------------------------------------------------
        | TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        $tahunAjarans = TahunAjaran::orderByDesc(
            'is_aktif'
        )
        ->orderByDesc(
            'nama_tahun'
        )
        ->get();

        $takenClasses = DB::table('guru_mapel_kelas')
            ->join('guru_mapels', 'guru_mapel_kelas.guru_mapel_id', '=', 'guru_mapels.id')
            ->select(
                'guru_mapels.tahun_ajaran_id',
                'guru_mapels.mata_pelajaran_id',
                'guru_mapels.guru_id',
                'guru_mapel_kelas.kelas_id'
            )
            ->get();

        $takenClassesMap = [];
        foreach ($takenClasses as $row) {
            $key = $row->tahun_ajaran_id . '_' . $row->mata_pelajaran_id . '_' . $row->kelas_id;
            $takenClassesMap[$key] = $row->guru_id;
        }

        return compact(

            'jenjangs',

            'gurus',

            'mataPelajarans',

            'kelasList',

            'tahunAjarans',

            'takenClassesMap'

        );

    }

    /**
     * Pastikan mapel & kelas yang dipilih berada di jenjang yang sama dengan guru.
     */
    private function validateJenjangMatch(Request $request)
    {
        $guru = Guru::find($request->guru_id);

        $mapel = MataPelajaran::find($request->mata_pelajaran_id);



        /*
        |--------------------------------------------------------------------------
        | CEK GURU & MAPEL
        |--------------------------------------------------------------------------
        */

        if ($guru && $mapel) {


            if($guru->jenjang_id != $mapel->jenjang_id){


                throw \Illuminate\Validation\ValidationException::withMessages([

                    'mata_pelajaran_id'
                    =>
                    'Mata pelajaran tidak sesuai dengan jenjang guru.'

                ]);

            }

        }





        /*
        |--------------------------------------------------------------------------
        | CEK KELAS MULTIPLE
        |--------------------------------------------------------------------------
        */


        if($request->filled('kelas_id')){


            $kelasList = Kelas::with('tingkat')

                ->whereIn(
                    'id',
                    $request->kelas_id
                )

                ->get();




            foreach($kelasList as $kelas){



                if(
                    optional($kelas->tingkat)->jenjang_id
                    !=
                    $guru->jenjang_id
                ){


                    throw \Illuminate\Validation\ValidationException::withMessages([

                        'kelas_id'
                        =>
                        'Ada kelas yang tidak sesuai dengan jenjang guru.'

                    ]);

                }


            }


        }

    }

    /**
     * Pastikan admin_jenjang tidak bisa mengelola penugasan guru di luar jenjangnya.
     */
    private function authorizeJenjang(GuruMapel $guruMapel)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            if (optional($guruMapel->guru)->jenjang_id != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke penugasan ini.');
            }
        }
    }

    public function export(Request $request)
    {
        $filters = $request->only([
            'search',
            'jenjang',
            'tahun_ajaran'
        ]);

        // Nama Jenjang
        if ($request->filled('jenjang')) {

            $jenjang = Jenjang::find($request->jenjang);
            $namaJenjang = $jenjang
                ? str_replace(' ', '_', $jenjang->nama_jenjang)
                : 'Semua_Jenjang';

        } elseif (Auth::user()->role == 'admin_jenjang') {

            $namaJenjang = str_replace(
                ' ',
                '_',
                optional(optional(Auth::user()->admin)->jenjang)->nama_jenjang ?? 'Jenjang'
            );

        } else {

            $namaJenjang = 'Semua_Jenjang';

        }

        // Tahun Ajaran
        if ($request->filled('tahun_ajaran')) {

            $tahun = TahunAjaran::find($request->tahun_ajaran);

        } else {

            // Sama seperti halaman index
            $tahun = TahunAjaran::where('is_aktif', 1)->first();

        }

        $namaTahun = $tahun
            ? str_replace(['/', ' '], ['-', '_'], $tahun->nama_tahun) . '_' . ucfirst($tahun->semester)
            : 'Semua_Tahun';

        $namaFile = sprintf(
            'guru_mapel_%s_%s_%s.xlsx',
            $namaJenjang,
            $namaTahun,
            now()->format('Ymd_His')
        );

        return Excel::download(
            new GuruMapelExport($filters),
            $namaFile
        );
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

        $import = new GuruMapelImport();

        Excel::import($import, $request->file('file'));

        $pesan = $import->berhasilBaru . ' penugasan baru ditambahkan, ' . $import->berhasilUpdate . ' penugasan diperbarui.';

        if (count($import->failures()) > 0) {
            $pesan .= ' ' . count($import->failures()) . ' baris gagal validasi.';
        }

        if (count($import->gagalLainnya) > 0) {
            $pesan .= ' ' . count($import->gagalLainnya) . ' catatan perlu dicek.';
        }

        return redirect()->route('guru-mapel.index')
            ->with('success', $pesan)
            ->with('import_failures', $import->failures())
            ->with('import_gagal', $import->gagalLainnya);
    }

    public function downloadTemplate()
    {
        $path = public_path('storage/app/templates/template_import_guru_mapel.xlsx');

        if (!file_exists($path)) {
            abort(404, 'Template tidak ditemukan.');
        }

        return response()->download(
            $path,
            'template_import_guru_mapel.xlsx'
        );
    }
}