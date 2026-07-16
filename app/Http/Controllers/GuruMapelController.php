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

class GuruMapelController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | QUERY UTAMA
        |--------------------------------------------------------------------------
        */

        $query = GuruMapel::with([
            'guru.jenjang',
            'mataPelajaran',
            'kelas.tingkat',
            'tahunAjaran'
        ]);



        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        if ($user->role == 'admin_jenjang') {


            $jenjangId = optional($user->admin)->jenjang_id;


            $query->whereHas('guru', function ($q) use ($jenjangId) {

                $q->where('jenjang_id', $jenjangId);

            });



            // Admin jenjang tidak memilih jenjang

            $jenjangs = collect();



            $gurus = Guru::where('jenjang_id',$jenjangId)
                ->orderBy('nama')
                ->get();



            $totalGuruMapel = GuruMapel::whereHas('guru', function($q) use($jenjangId){

                $q->where('jenjang_id',$jenjangId);

            })->count();



        } else {



            $jenjangs = Jenjang::orderBy('nama_jenjang')
                ->get();



            $gurus = Guru::with('jenjang')
                ->orderBy('nama')
                ->get();



            $totalGuruMapel = GuruMapel::count();

        }




        /*
        |--------------------------------------------------------------------------
        | FILTER SEARCH
        |--------------------------------------------------------------------------
        */


        if($request->filled('search')){


            $search = $request->search;


            $query->where(function($q) use($search){


                $q->whereHas('guru',function($guru) use($search){

                    $guru->where('nama','like','%'.$search.'%');

                })

                ->orWhereHas('mataPelajaran',function($mapel) use($search){

                    $mapel->where(
                        'nama_mapel',
                        'like',
                        '%'.$search.'%'
                    );

                });


            });


        }




        /*
        |--------------------------------------------------------------------------
        | FILTER JENJANG
        |--------------------------------------------------------------------------
        */


        if($request->filled('jenjang')){


            $query->whereHas('guru',function($q) use($request){

                $q->where(
                    'jenjang_id',
                    $request->jenjang
                );

            });


        }




        /*
        |--------------------------------------------------------------------------
        | FILTER GURU
        |--------------------------------------------------------------------------
        */


        if($request->filled('guru')){


            $query->where(
                'guru_id',
                $request->guru
            );


        }





        /*
        |--------------------------------------------------------------------------
        | FILTER TAHUN AJARAN
        |--------------------------------------------------------------------------
        */

        if($request->filled('tahun_ajaran')){


            $query->where(
                'tahun_ajaran_id',
                $request->tahun_ajaran
            );


        }

        /*
        |--------------------------------------------------------------------------
        | DATA LIST
        |--------------------------------------------------------------------------
        */


        $guruMapels = $query

            ->orderByDesc('tahun_ajaran_id')

            ->orderBy('guru_id')

            ->paginate(10)

            ->withQueryString();







        /*
        |--------------------------------------------------------------------------
        | DATA FILTER
        |--------------------------------------------------------------------------
        */


        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')

            ->orderByDesc('nama_tahun')

            ->get();







        /*
        |--------------------------------------------------------------------------
        | STATISTIK
        |--------------------------------------------------------------------------
        */


        $totalGuru = $guruMapels

            ->pluck('guru_id')

            ->unique()

            ->count();




        $totalMapel = $guruMapels

            ->pluck('mata_pelajaran_id')

            ->unique()

            ->count();




        $tahunAktif = TahunAjaran::where('is_aktif',1)

            ->first();







        return view('guru-mapel.index',compact(

            'guruMapels',

            'gurus',

            'jenjangs',

            'tahunAjarans',

            'totalGuruMapel',

            'totalGuru',

            'totalMapel',

            'tahunAktif'

        ));

    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role == 'admin_jenjang') {

            $jenjangId = optional($user->admin)->jenjang_id;

            $jenjangs = Jenjang::where('id', $jenjangId)->get();

            $gurus = Guru::where('jenjang_id', $jenjangId)
                ->orderBy('nama')
                ->get();

            $mataPelajarans = MataPelajaran::where('jenjang_id', $jenjangId)
                ->orderBy('nama_mapel')
                ->get();

            $kelasList = Kelas::whereHas('tingkat', function ($q) use ($jenjangId) {
                    $q->where('jenjang_id', $jenjangId);
                })
                ->with('tingkat')
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

            $kelasList = Kelas::with('tingkat')
                ->orderBy('nama_kelas')
                ->get();
        }

        $tahunAjarans = TahunAjaran::orderByDesc('is_aktif')
            ->orderByDesc('nama_tahun')
            ->get();

        return view('guru-mapel.create', compact(
            'jenjangs',
            'gurus',
            'mataPelajarans',
            'kelasList',
            'tahunAjarans'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id'           => 'required|exists:gurus,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajarans,id',
            'kelas_id'          => 'required|array|min:1',
            'kelas_id.*'        => 'exists:kelas,id',
            'tahun_ajaran_id'   => 'required|exists:tahun_ajarans,id',
        ]);

        $guru  = Guru::findOrFail($request->guru_id);
        $mapel = MataPelajaran::findOrFail($request->mata_pelajaran_id);

        if ($guru->jenjang_id != $mapel->jenjang_id) {

            return back()->withInput()->withErrors([
                'mata_pelajaran_id' => 'Guru dan mata pelajaran harus satu jenjang.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cek Duplicate Guru Mapel
        |--------------------------------------------------------------------------
        */

        $guruMapel = GuruMapel::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $mapel->id)
            ->where('tahun_ajaran_id', $request->tahun_ajaran_id)
            ->first();

        if (!$guruMapel) {

            $guruMapel = GuruMapel::create([
                'guru_id'           => $guru->id,
                'mata_pelajaran_id' => $mapel->id,
                'tahun_ajaran_id'   => $request->tahun_ajaran_id,
            ]);
        }

        foreach ($request->kelas_id as $kelasId) {

            $kelas = Kelas::with('tingkat')->findOrFail($kelasId);

            if ($kelas->tingkat->jenjang_id != $guru->jenjang_id) {

                return back()->withInput()->withErrors([
                    'kelas_id' => 'Ada kelas yang tidak sesuai dengan jenjang guru.'
                ]);
            }

            DB::table('guru_mapel_kelas')->updateOrInsert(
                [
                    'guru_mapel_id' => $guruMapel->id,
                    'kelas_id'      => $kelasId,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return redirect()
            ->route('guru-mapel.index')
            ->with('success', 'Penugasan guru mapel berhasil ditambahkan.');
    }

    public function edit(GuruMapel $guru_mapel)
    {
        $this->authorizeJenjang($guru_mapel);

        $data = $this->formData();
        $data['guruMapel'] = $guru_mapel;

        return view('guru-mapel.edit', $data);
    }

    public function update(Request $request, GuruMapel $guru_mapel)
    {
        $this->authorizeJenjang($guru_mapel);


        $request->validate([

            'guru_id'=>'required|exists:gurus,id',

            'mata_pelajaran_id'=>'required|exists:mata_pelajarans,id',

            'kelas_id'=>'nullable|array',

            'kelas_id.*'=>'exists:kelas,id',

            'tahun_ajaran_id'=>'required|exists:tahun_ajarans,id',

        ],[

            'guru_id.required'=>'Guru wajib dipilih.',

            'mata_pelajaran_id.required'=>'Mata pelajaran wajib dipilih.',

            'tahun_ajaran_id.required'=>'Tahun ajaran wajib dipilih.',

        ]);



        $this->validateJenjangMatch($request);



        $exists = GuruMapel::where('guru_id',$request->guru_id)

            ->where(
                'mata_pelajaran_id',
                $request->mata_pelajaran_id
            )

            ->where(
                'tahun_ajaran_id',
                $request->tahun_ajaran_id
            )

            ->where('id','!=',$guru_mapel->id)

            ->exists();



        if($exists){

            return back()
                ->withInput()
                ->withErrors([
                    'guru_id'=>'Penugasan guru mapel ini sudah ada.'
                ]);

        }



        $guru_mapel->update([

            'guru_id'=>$request->guru_id,

            'mata_pelajaran_id'=>$request->mata_pelajaran_id,

            'tahun_ajaran_id'=>$request->tahun_ajaran_id,

        ]);



        // update kelas pivot

        $guru_mapel->kelas()->sync(
            $request->kelas_id ?? []
        );



        return redirect()
            ->route('guru-mapel.index')
            ->with(
                'success',
                'Penugasan guru mapel berhasil diperbarui.'
            );

    }

    public function destroy(GuruMapel $guru_mapel)
    {
        $this->authorizeJenjang($guru_mapel);


        DB::transaction(function () use ($guru_mapel) {

            // hapus relasi kelas terlebih dahulu
            $guru_mapel->kelas()->detach();

            // hapus guru mapel
            $guru_mapel->delete();

        });



        return redirect()
            ->route('guru-mapel.index')
            ->with(
                'success',
                'Penugasan guru mata pelajaran berhasil dihapus.'
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





        return compact(

            'jenjangs',

            'gurus',

            'mataPelajarans',

            'kelasList',

            'tahunAjarans'

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
        $filters = $request->only(['search', 'jenjang', 'tahun_ajaran']);

        $namaFile = 'data_guru_mapel_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new GuruMapelExport($filters), $namaFile);
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
        $path = storage_path('app/templates/template_import_guru_mapel.xlsx');

        return response()->download($path, 'template_import_guru_mapel.xlsx');
    }
}