<?php

namespace App\Http\Controllers;

use App\Models\BankSoal;
use App\Models\Jenjang;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankSoalController extends Controller
{
    public function index(Request $request)
    {
        $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;
        $isAdminJenjang = Auth::user()->role == 'admin_jenjang';

        $bankSoals = BankSoal::withCount('soals')
            ->with(['guruMapel.guru', 'mataPelajaran', 'jenjang'])
            ->when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->when($request->filled('jenjang') && !$isAdminJenjang, function ($query) use ($request) {
                $query->where('jenjang_id', $request->jenjang);
            })
            ->when($request->filled('mapel'), function ($query) use ($request) {
                $query->where('mata_pelajaran_id', $request->mapel);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_publish', $request->status == 'publish' ? 1 : 0);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_bank_soal', 'like', '%' . $request->search . '%')
                      ->orWhereHas('guruMapel.guru', function ($guru) use ($request) {
                          $guru->where('nama', 'like', '%' . $request->search . '%');
                      });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalBankSoal = BankSoal::when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })->count();

        $totalPublish = BankSoal::when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })->where('is_publish', true)->count();

        $totalDraft = $totalBankSoal - $totalPublish;

        $totalSoal = \App\Models\Soal::whereHas('bankSoal', function ($query) use ($isAdminJenjang, $jenjangAdmin) {
                $query->when($isAdminJenjang, function ($q) use ($jenjangAdmin) {
                    $q->where('jenjang_id', $jenjangAdmin);
                });
            })->count();

        $jenjangs = Jenjang::orderBy('nama_jenjang', 'asc')->get();

        $mataPelajarans = MataPelajaran::when($isAdminJenjang, function ($query) use ($jenjangAdmin) {
                $query->where('jenjang_id', $jenjangAdmin);
            })
            ->orderBy('nama_mapel', 'asc')
            ->get();

        return view('bank-soal.index', compact(
            'bankSoals', 'totalBankSoal', 'totalPublish', 'totalDraft', 'totalSoal', 'jenjangs', 'mataPelajarans'
        ));
    }

    public function show(BankSoal $bankSoal)
    {
        $this->authorizeJenjang($bankSoal);

        $bankSoal->load(['guruMapel.guru', 'mataPelajaran', 'jenjang', 'soals.pilihanJawabans']);

        $soals = $bankSoal->soals()->orderBy('urutan', 'asc')->paginate(15);

        $rekapJenis = $bankSoal->soals()
            ->selectRaw('jenis_soal, count(*) as jumlah')
            ->groupBy('jenis_soal')
            ->pluck('jumlah', 'jenis_soal');

        return view('bank-soal.show', compact('bankSoal', 'soals', 'rekapJenis'));
    }

    public function togglePublish(BankSoal $bankSoal)
    {
        $this->authorizeJenjang($bankSoal);

        if (!$bankSoal->is_publish) {
            $totalBobot = $bankSoal->soals()->sum('bobot');
            if ($totalBobot != 100) {
                return redirect()->back()
                    ->with('error', 'Gagal mempublikasikan! Total bobot soal saat ini adalah ' . $totalBobot . ', syarat publish harus tepat 100.');
            }
        }

        $bankSoal->update([
            'is_publish' => !$bankSoal->is_publish,
        ]);

        $pesan = $bankSoal->is_publish
            ? 'Bank soal berhasil dipublikasikan dan siap dipakai untuk ujian.'
            : 'Bank soal berhasil ditarik kembali ke draft.';

        return redirect()->back()->with('success', $pesan);
    }

    public function destroy(BankSoal $bankSoal)
    {
        $this->authorizeJenjang($bankSoal);

        $bankSoal->delete();

        return redirect()->route('bank-soal.index')
            ->with('success', 'Bank soal berhasil dihapus beserta seluruh soal di dalamnya.');
    }

    /**
     * Pastikan admin_jenjang tidak bisa mengakses bank soal di luar jenjangnya.
     */
    private function authorizeJenjang(BankSoal $bankSoal)
    {
        if (Auth::user()->role == 'admin_jenjang') {
            $jenjangAdmin = optional(Auth::user()->admin)->jenjang_id;

            if ($bankSoal->jenjang_id != $jenjangAdmin) {
                abort(403, 'Anda tidak memiliki akses ke bank soal ini.');
            }
        }
    }
}