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
    private function authorizeBankSoal(BankSoal $bankSoal)
    {
        $guru = Auth::user()->guru;

        abort_unless(
            $guru->guruMapels()->where('id', $bankSoal->guru_mapel_id)->exists(),
            403,
            'Anda tidak memiliki akses ke bank soal ini.'
        );
    }

    private function authorizeNotLocked(BankSoal $bankSoal)
    {
        if ($bankSoal->isLocked()) {
            abort(403, 'Bank Soal ini terkunci karena sedang atau telah digunakan dalam Ujian. Silakan duplikat Bank Soal jika ingin membuat versi revisi.');
        }
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
        $this->authorizeNotLocked($bank_soal);

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
        $this->authorizeNotLocked($bank_soal);

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
        $this->authorizeNotLocked($bank_soal);

        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,pilihan_ganda_kompleks,benar_salah,mencocokkan,isian,essay',
            'teks_soal' => 'required|string',
            'bobot' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->validasiDinamisPilihan($request);

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

            $this->simpanPilihanJawaban($soal, $request);

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
        $this->authorizeNotLocked($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        $soal->load(['pilihanJawabans' => function ($q) {
            $q->orderBy('urutan');
        }]);

        return view('guru.bank-soal.soal.edit', compact('bank_soal', 'soal'));
    }

    public function update(Request $request, BankSoal $bank_soal, Soal $soal)
    {
        $this->authorizeBankSoal($bank_soal);
        $this->authorizeNotLocked($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,pilihan_ganda_kompleks,benar_salah,mencocokkan,isian,essay',
            'teks_soal' => 'required|string',
            'bobot' => 'required|numeric|min:1',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $this->validasiDinamisPilihan($request);

        DB::beginTransaction();
        try {
            $dataUpdate = [
                'jenis_soal' => $request->jenis_soal,
                'teks_soal' => $request->teks_soal,
                'bobot' => $request->bobot,
            ];

            if ($request->hasFile('gambar')) {
                if ($soal->gambar) {
                    Storage::disk('public')->delete($soal->gambar);
                }
                $dataUpdate['gambar'] = $request->file('gambar')->store('soal-gambar', 'public');
            } elseif ($request->boolean('remove_gambar')) {
                if ($soal->gambar) {
                    Storage::disk('public')->delete($soal->gambar);
                }
                $dataUpdate['gambar'] = null;
            }

            $soal->update($dataUpdate);

            $soal->pilihanJawabans()->delete();

            $this->simpanPilihanJawaban($soal, $request);

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
        $this->authorizeNotLocked($bank_soal);
        abort_unless($soal->bank_soal_id === $bank_soal->id, 404);

        if ($soal->gambar) {
            Storage::disk('public')->delete($soal->gambar);
        }

        $soal->delete();

        return redirect()
            ->route('dashboard-guru.bank-soal.soal.index', $bank_soal->id)
            ->with('success', 'Soal berhasil dihapus');
    }

    private function validasiDinamisPilihan(Request $request): void
    {
        if ($request->jenis_soal === 'pilihan_ganda') {
            $request->validate([
                'teks_pilihan' => 'required|array|min:2',
                'teks_pilihan.*' => 'required|string',
                'jawaban_benar' => 'required|integer',
            ]);
        } elseif ($request->jenis_soal === 'pilihan_ganda_kompleks') {
            $request->validate([
                'teks_pilihan' => 'required|array|min:2',
                'teks_pilihan.*' => 'required|string',
                'jawaban_benar_kompleks' => 'required|array|min:1',
            ]);
        } elseif ($request->jenis_soal === 'benar_salah') {
            $request->validate([
                'teks_pernyataan' => 'required|array|min:1',
                'teks_pernyataan.*' => 'required|string',
                'kunci_bs' => 'required|array',
            ]);
        } elseif ($request->jenis_soal === 'mencocokkan') {
            $request->validate([
                'item_kiri' => 'required|array|min:1',
                'item_kiri.*' => 'required|string',
                'item_kanan' => 'required|array|min:1',
                'item_kanan.*' => 'required|string',
            ]);
        } elseif ($request->jenis_soal === 'isian') {
            $request->validate([
                'kunci_isian' => 'required|string',
            ]);
        }
    }

    private function simpanPilihanJawaban(Soal $soal, Request $request): void
    {
        $kodeList = range('A', 'Z');

        if ($request->jenis_soal === 'pilihan_ganda') {
            foreach (array_values($request->teks_pilihan) as $index => $teks) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => $kodeList[$index] ?? null,
                    'teks_pilihan' => $teks,
                    'is_benar' => ((int) $request->jawaban_benar === $index),
                    'urutan' => $index + 1,
                ]);
            }
        } elseif ($request->jenis_soal === 'pilihan_ganda_kompleks') {
            $checkedIndices = array_map('intval', (array) $request->jawaban_benar_kompleks);
            foreach (array_values($request->teks_pilihan) as $index => $teks) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => $kodeList[$index] ?? null,
                    'teks_pilihan' => $teks,
                    'is_benar' => in_array($index, $checkedIndices, true),
                    'urutan' => $index + 1,
                ]);
            }
        } elseif ($request->jenis_soal === 'benar_salah') {
            foreach (array_values($request->teks_pernyataan) as $index => $teks) {
                $kunciVal = $request->kunci_bs[$index] ?? 'salah';
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => (string) ($index + 1),
                    'teks_pilihan' => $teks,
                    'is_benar' => ($kunciVal === 'benar'),
                    'urutan' => $index + 1,
                ]);
            }
        } elseif ($request->jenis_soal === 'mencocokkan') {
            $kiri = array_values($request->item_kiri);
            $kanan = array_values($request->item_kanan);
            foreach ($kiri as $index => $teksKiri) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => (string) ($index + 1),
                    'teks_pilihan' => $teksKiri,
                    'pasangan' => $kanan[$index] ?? '',
                    'is_benar' => true,
                    'urutan' => $index + 1,
                ]);
            }
        } elseif ($request->jenis_soal === 'isian') {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'kode' => '1',
                'teks_pilihan' => trim($request->kunci_isian),
                'is_benar' => true,
                'urutan' => 1,
            ]);
        }
    }
}