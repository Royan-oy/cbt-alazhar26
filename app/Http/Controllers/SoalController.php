<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\PilihanJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoalController extends Controller
{
    public function store(Request $request, $bank_soal_id)
    {
        $request->validate([
            'jenis_soal' => 'required|in:pilihan_ganda,essay,isian',
            'teks_soal' => 'required',
            'bobot' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan Soal
            $soal = Soal::create([
                'bank_soal_id' => $bank_soal_id,
                'jenis_soal'   => $request->jenis_soal,
                'teks_soal'    => $request->teks_soal,
                'bobot'        => $request->bobot,
                'urutan'       => Soal::where('bank_soal_id', $bank_soal_id)->count() + 1,
            ]);

            // 2. Jika Pilihan Ganda, Simpan Opsi Jawaban
            if ($request->jenis_soal === 'pilihan_ganda') {
                $opsiList = ['A', 'B', 'C', 'D', 'E'];
                foreach ($opsiList as $opsi) {
                    PilihanJawaban::create([
                        'soal_id' => $soal->id,
                        'opsi' => $opsi,
                        'teks_pilihan' => $request->input("teks_pilihan_{$opsi}"),
                        'is_correct' => ($request->kunci_jawaban == $opsi) ? 1 : 0,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Soal berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($bank_soal_id, $soal_id)
    {
        // Pilihan jawaban akan terhapus otomatis jika di migration diset onDelete('cascade')
        Soal::where('id', $soal_id)->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus');
    }
}