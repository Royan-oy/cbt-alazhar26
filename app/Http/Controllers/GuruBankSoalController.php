<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuruBankSoalController extends Controller
{
    /**
     * Pastikan bank soal ini benar-benar milik guru yang sedang login.
     * Dipakai di show/edit/update/destroy/togglePublish supaya guru A
     * tidak bisa akses/ubah/hapus bank soal guru B lewat DevTools/URL.
     */
    private function authorizeOwnership($guru, BankSoal $bankSoal)
    {
        abort_unless(
            $guru->guruMapels()->where('id', $bankSoal->guru_mapel_id)->exists(),
            403,
            'Anda tidak memiliki akses ke bank soal ini.'
        );
    }

    public function index()
    {
        $guru = Auth::user()->guru;

        // Satu guru bisa punya BEBERAPA guru_mapel (lintas mapel/tahun ajaran),
        // jadi ambil SEMUA guru_mapel_id miliknya, bukan satu id tunggal
        // (sebelumnya: Auth::user()->guru_mapel_id, yang juga salah karena
        // guru_mapel_id tidak ada di tabel users maupun gurus).
        $guruMapelIds = $guru->guruMapels()->pluck('id');

        $bankSoals = BankSoal::whereIn('guru_mapel_id', $guruMapelIds)->get();

        return view('guru.bank-soal.index', compact('bankSoals'));
    }

    public function create()
    {
        $guru = Auth::user()->guru;

        // Hanya mapel yang benar-benar diampu guru ini yang boleh dipilih,
        // bukan MataPelajaran::all() (sebelumnya guru bisa pilih mapel siapa saja).
        $guruMapels = $guru->guruMapels()->with('mataPelajaran')->get();

        return view('guru.bank-soal.create', [
            'guruMapels' => $guruMapels,
            'jenjang' => $guru->jenjang, // hanya untuk ditampilkan, TIDAK dikirim lewat form
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bank_soal' => 'required|string|max:255',
            'guru_mapel_id'  => 'required|exists:guru_mapels,id',
            'kkm'            => 'required|integer|min:0|max:100',
            'deskripsi'      => 'nullable|string',
        ]);

        $guru = Auth::user()->guru;

        // Pastikan guru_mapel yang dipilih benar-benar milik guru yang login
        $guruMapel = $guru->guruMapels()->findOrFail($request->guru_mapel_id);

        BankSoal::create([
            'guru_mapel_id'     => $guruMapel->id,
            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id,
            'jenjang_id'        => $guru->jenjang_id,
            'nama_bank_soal'    => $request->nama_bank_soal,
            'kkm'               => $request->kkm ?? 75,
            'deskripsi'         => $request->deskripsi,
            'is_publish'        => false,
        ]);

        return redirect()
            ->route('dashboard-guru.bank-soal.index')
            ->with('success', 'Bank Soal berhasil dibuat');
    }

    public function show(BankSoal $bank_soal)
    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        $bank_soal->load(['mataPelajaran', 'jenjang', 'soals']);

        return view('guru.bank-soal.show', compact('bank_soal'));
    }

    public function edit(BankSoal $bank_soal)
    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        $guruMapels = $guru->guruMapels()->with('mataPelajaran')->get();

        return view('guru.bank-soal.edit', [
            'bankSoal'   => $bank_soal,
            'guruMapels' => $guruMapels,
            'jenjang'    => $guru->jenjang,
        ]);
    }

    public function update(Request $request, BankSoal $bank_soal)
    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        $request->validate([
            'nama_bank_soal' => 'required|string|max:255',
            'guru_mapel_id'  => 'required|exists:guru_mapels,id',
            'kkm'            => 'required|integer|min:0|max:100',
            'deskripsi'      => 'nullable|string',
        ]);

        $guruMapel = $guru->guruMapels()->findOrFail($request->guru_mapel_id);

        $bank_soal->update([
            'guru_mapel_id'     => $guruMapel->id,
            'mata_pelajaran_id' => $guruMapel->mata_pelajaran_id,
            'nama_bank_soal'    => $request->nama_bank_soal,
            'kkm'               => $request->kkm ?? 75,
            'deskripsi'         => $request->deskripsi,
        ]);

        return redirect()
            ->route('dashboard-guru.bank-soal.index')
            ->with('success', 'Bank Soal berhasil diperbarui');
    }

    public function destroy(BankSoal $bank_soal)

    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        if ($bank_soal->isLocked()) {
            return redirect()
                ->route('dashboard-guru.bank-soal.index')
                ->with('error', 'Bank soal "' . $bank_soal->nama_bank_soal . '" terkunci karena sedang atau telah digunakan dalam Ujian. Silakan duplikat jika ingin membuat versi revisi.');
        }

        if ($bank_soal->is_publish) {
            return redirect()
                ->route('dashboard-guru.bank-soal.index')
                ->with('error', 'Bank soal "' . $bank_soal->nama_bank_soal . '" sedang dipublish. Unpublish dulu sebelum menghapus.');
        }

        $bank_soal->delete();

        return redirect()
            ->route('dashboard-guru.bank-soal.index')
            ->with('success', 'Bank Soal berhasil dihapus');
    }

    public function togglePublish(BankSoal $bank_soal)
    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        if (!$bank_soal->is_publish) {
            $totalBobot = $bank_soal->soals()->sum('bobot');
            if ($totalBobot != 100) {
                return redirect()->back()
                    ->with('error', 'Gagal mempublikasikan! Total bobot soal saat ini adalah ' . $totalBobot . ', syarat publish harus tepat 100.');
            }
        }

        $bank_soal->update(['is_publish' => ! $bank_soal->is_publish]);

        return redirect()
            ->route('dashboard-guru.bank-soal.index')
            ->with('success', $bank_soal->is_publish
                ? 'Bank Soal berhasil dipublish.'
                : 'Bank Soal berhasil di-unpublish.');
    }

    public function duplicate(BankSoal $bank_soal)
    {
        $guru = Auth::user()->guru;
        $this->authorizeOwnership($guru, $bank_soal);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $newBankSoal = $bank_soal->replicate();
            $newBankSoal->nama_bank_soal = 'Salinan - ' . $bank_soal->nama_bank_soal;
            $newBankSoal->is_publish = false;
            $newBankSoal->save();

            foreach ($bank_soal->soals as $oldSoal) {
                $newSoal = $oldSoal->replicate();
                $newSoal->bank_soal_id = $newBankSoal->id;
                $newSoal->save();

                foreach ($oldSoal->pilihanJawabans as $oldPilihan) {
                    $newPilihan = $oldPilihan->replicate();
                    $newPilihan->soal_id = $newSoal->id;
                    $newPilihan->save();
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return redirect()
                ->route('dashboard-guru.bank-soal.soal.index', $newBankSoal->id)
                ->with('success', 'Bank Soal berhasil diduplikasi. Anda dapat mengedit soal pada versi baru ini.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menduplikasi Bank Soal: ' . $e->getMessage());
        }
    }
}