<?php

namespace App\Http\Controllers;

use App\Exports\SoalTemplateExport;
use App\Imports\SoalImport;
use App\Models\BankSoal;
use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SoalController extends Controller
{
    /**
     * Pastikan bank soal ini benar-benar milik guru yang sedang login.
     * (pola sama seperti GuruBankSoalController::authorizeOwnership)
     */
    private function authorizeBankSoal(BankSoal $bankSoal)
    {
        $guru = Auth::user()->guru;

        abort_unless(
            $guru->guruMapels()->where('id', $bankSoal->guru_mapel_id)->exists(),
            403,
            'Anda tidak memiliki akses ke bank soal ini.'
        );
    }

    public function index(BankSoal $bank_soal)
    {
        $this->authorizeBankSoal($bank_soal);

        $soals = $bank_soal->soals()->orderBy('urutan')->withCount('pilihanJawabans')->get();

        return view('guru.bank-soal.soal.index', compact('bank_soal', 'soals'));
    }

    public function create(BankSoal $bank_soal)
    {
        $this->authorizeBankSoal($bank_soal);

        return view('guru.bank-soal.soal.create', compact('bank_soal'));
    }

    public function downloadTemplate(BankSoal $bank_soal)
    {
        $this->authorizeBankSoal($bank_soal);

        return Excel::download(new SoalTemplateExport, 'template-import-soal.xlsx');
    }

    public function import(Request $request, BankSoal $bank_soal)
    {
        $this->authorizeBankSoal($bank_soal);

        $request->validate([
            'file_import' => 'required|file|mimes:xlsx,xls|max:2048',
        ]);

        $urutanAwal = $bank_soal->soals()->count() + 1;
        $import = new SoalImport($bank_soal, $urutanAwal);

        try {
            Excel::import($import, $request->file('file_import'));
        } catch (\Exception $e) {
            return redirect()
                ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
                ->with('error', 'Gagal membaca file: pastikan formatnya sesuai template. (' . $e->getMessage() . ')');
        }

        $message = "{$import->successCount} soal berhasil diimport.";

        if (!empty($import->errors)) {
            session()->flash('import_errors', $import->errors);
            $message .= ' ' . count($import->errors) . ' baris dilewati karena tidak valid — lihat rinciannya di bawah.';
        }

        return redirect()
            ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
            ->with($import->successCount > 0 ? 'success' : 'error', $message);
    }

    public function store(Request $request, BankSoal $bank_soal)
    {
        $this->authorizeBankSoal($bank_soal);

        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,essay,isian',
            'teks_soal' => 'required|string',
            'bobot' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->jenis_soal === 'pilihan_ganda') {
            $request->validate([
                'teks_pilihan' => 'required|array|min:2',
                'teks_pilihan.*' => 'required|string',
                'jawaban_benar' => 'required|integer',
            ]);
        }

        DB::beginTransaction();
        try {
            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('soal-gambar', 'public');
            }

            $soal = Soal::create([
                'bank_soal_id' => $bank_soal->id,
                'jenis_soal' => $request->jenis_soal,
                'teks_soal' => $request->teks_soal,
                'gambar' => $gambarPath,
                'bobot' => $request->bobot,
                'urutan' => $bank_soal->soals()->count() + 1,
            ]);

            if ($request->jenis_soal === 'pilihan_ganda') {
                $this->simpanPilihanJawaban($soal, $request->teks_pilihan, $request->jawaban_benar);
            }

            DB::commit();

            return redirect()
                ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
                ->with('success', 'Soal berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(BankSoal $bank_soal, Soal $soal)
    {
        $this->authorizeBankSoal($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        $soal->load(['pilihanJawabans' => function ($q) {
            $q->orderBy('urutan');
        }]);

        return view('guru.bank-soal.soal.edit', compact('bank_soal', 'soal'));
    }

    public function update(Request $request, BankSoal $bank_soal, Soal $soal)
    {
        $this->authorizeBankSoal($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,essay,isian',
            'teks_soal' => 'required|string',
            'bobot' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->jenis_soal === 'pilihan_ganda') {
            $request->validate([
                'teks_pilihan' => 'required|array|min:2',
                'teks_pilihan.*' => 'required|string',
                'jawaban_benar' => 'required|integer',
            ]);
        }

        DB::beginTransaction();
        try {
            $dataUpdate = [
                'jenis_soal' => $request->jenis_soal,
                'teks_soal' => $request->teks_soal,
                'bobot' => $request->bobot,
            ];

            if ($request->hasFile('gambar')) {
                // Ganti gambar: hapus file lama (kalau ada), simpan yang baru
                if ($soal->gambar) {
                    Storage::disk('public')->delete($soal->gambar);
                }
                $dataUpdate['gambar'] = $request->file('gambar')->store('soal-gambar', 'public');
            } elseif ($request->boolean('remove_gambar')) {
                // Guru menekan "Hapus Gambar" tanpa upload gambar baru
                if ($soal->gambar) {
                    Storage::disk('public')->delete($soal->gambar);
                }
                $dataUpdate['gambar'] = null;
            }
            // Kalau tidak ada file baru & remove_gambar=0, gambar lama dibiarkan apa adanya.

            $soal->update($dataUpdate);

            // Opsi lama dihapus & diganti baru — supaya tidak ada opsi "nyangkut"
            // kalau guru mengubah jumlah opsi atau mengganti jenis soal.
            $soal->pilihanJawabans()->delete();

            if ($request->jenis_soal === 'pilihan_ganda') {
                $this->simpanPilihanJawaban($soal, $request->teks_pilihan, $request->jawaban_benar);
            }

            DB::commit();

            return redirect()
                ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
                ->with('success', 'Soal berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(BankSoal $bank_soal, Soal $soal)
    {
        $this->authorizeBankSoal($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        // Hapus file gambar dari storage (kalau ada) sebelum baris DB-nya dihapus
        if ($soal->gambar) {
            Storage::disk('public')->delete($soal->gambar);
        }

        // pilihan_jawabans ikut terhapus otomatis (cascadeOnDelete di migration)
        $soal->delete();

        return redirect()
            ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
            ->with('success', 'Soal berhasil dihapus');
    }

    /**
     * Simpan opsi pilihan ganda. Kode opsi (A, B, C, ...) digenerate otomatis
     * dari urutan input — supaya jumlah opsi fleksibel (2-26), tidak dihardcode
     * 5 opsi seperti implementasi sebelumnya.
     */
    private function simpanPilihanJawaban(Soal $soal, array $teksPilihan, $jawabanBenarIndex)
    {
        $kodeList = range('A', 'Z');

        foreach (array_values($teksPilihan) as $index => $teks) {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'kode' => $kodeList[$index] ?? null,
                'teks_pilihan' => $teks,
                'is_benar' => ((int) $jawabanBenarIndex === $index),
                'urutan' => $index + 1,
            ]);
        }
    }
}