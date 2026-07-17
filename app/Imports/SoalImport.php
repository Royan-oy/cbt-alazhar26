<?php

namespace App\Imports;

use App\Models\BankSoal;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class SoalImport implements ToCollection
{
    protected $bankSoal;
    protected $urutan;

    /** @var array<int, string> pesan error per baris yang dilewati */
    public $errors = [];

    public $successCount = 0;

    private const JENIS_VALID = ['pilihan_ganda', 'essay', 'isian'];
    private const MAX_OPSI = 6;

    public function __construct(BankSoal $bankSoal, int $urutanAwal)
    {
        $this->bankSoal = $bankSoal;
        $this->urutan = $urutanAwal;
    }

    public function collection($rows)
    {
        foreach ($rows as $index => $row) {
            // Baris 1 di file adalah header, jadi baris ke-1 data = baris ke-2 di Excel.
            $nomorBaris = $index + 2;

            // Lewati baris yang benar-benar kosong (misal sisa baris kosong di bawah)
            if ($this->isRowEmpty($row)) {
                continue;
            }

            $jenisSoal = strtolower(trim((string) ($row[0] ?? '')));
            $bobotRaw = trim((string) ($row[1] ?? ''));
            $teksSoal = trim((string) ($row[2] ?? ''));
            $opsiRaw = [
                $row[3] ?? '', $row[4] ?? '', $row[5] ?? '',
                $row[6] ?? '', $row[7] ?? '', $row[8] ?? '',
            ];
            $jawabanBenarRaw = trim((string) ($row[9] ?? ''));

            $error = $this->validasiBaris($jenisSoal, $bobotRaw, $teksSoal, $opsiRaw, $jawabanBenarRaw);

            if ($error !== null) {
                $this->errors[] = "Baris {$nomorBaris}: {$error}";
                continue;
            }

            $opsiList = array_values(array_filter(
    array_map(function ($opsi) { return trim((string) $opsi); }, $opsiRaw),
    function ($opsi) { return $opsi !== ''; }
));

            try {
                DB::beginTransaction();

                $soal = Soal::create([
                    'bank_soal_id' => $this->bankSoal->id,
                    'jenis_soal' => $jenisSoal,
                    'teks_soal' => $teksSoal,
                    'bobot' => (int) $bobotRaw,
                    'urutan' => $this->urutan,
                ]);

                if ($jenisSoal === 'pilihan_ganda') {
                    $this->simpanPilihanJawaban($soal, $opsiList, (int) $jawabanBenarRaw - 1);
                }

                DB::commit();

                $this->successCount++;
                $this->urutan++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Baris {$nomorBaris}: gagal disimpan ke database ({$e->getMessage()}).";
            }
        }
    }

    /**
     * Validasi satu baris. Mengembalikan pesan error (string) kalau tidak valid,
     * atau null kalau lolos semua pengecekan.
     */
    private function validasiBaris(string $jenisSoal, string $bobotRaw, string $teksSoal, array $opsiRaw, string $jawabanBenarRaw): ?string
    {
        if (!in_array($jenisSoal, self::JENIS_VALID, true)) {
            return "jenis soal '{$jenisSoal}' tidak valid (harus pilihan_ganda, essay, atau isian).";
        }

        if ($teksSoal === '') {
            return 'teks soal tidak boleh kosong.';
        }

        if ($bobotRaw === '' || !is_numeric($bobotRaw) || (float) $bobotRaw < 1) {
            return 'bobot harus berupa angka minimal 1.';
        }

        if ($jenisSoal === 'pilihan_ganda') {
    $opsiTerisi = array_values(array_filter(
        array_map(function ($opsi) {
            return trim((string) $opsi);
        }, $opsiRaw),
        function ($opsi) {
            return $opsi !== '';
        }
    ));

            if (count($opsiTerisi) < 2) {
                return 'soal pilihan ganda butuh minimal 2 opsi jawaban (kolom Opsi 1-6).';
            }

            if (count($opsiTerisi) > self::MAX_OPSI) {
                return 'jumlah opsi tidak boleh lebih dari ' . self::MAX_OPSI . '.';
            }

            if ($jawabanBenarRaw === '' || !ctype_digit($jawabanBenarRaw)) {
                return "kolom 'Jawaban Benar' harus diisi angka nomor opsi.";
            }

            $jawabanBenar = (int) $jawabanBenarRaw;
            if ($jawabanBenar < 1 || $jawabanBenar > count($opsiTerisi)) {
                return "kolom 'Jawaban Benar' harus antara 1 dan " . count($opsiTerisi) . " (jumlah opsi yang diisi).";
            }
        }

        return null;
    }

private function isRowEmpty($row): bool
{
    return collect($row)->filter(function ($cell) { return trim((string) $cell) !== ''; })->isEmpty();
}

    /**
     * Sama persis dengan SoalController::simpanPilihanJawaban — kode opsi (A, B, C...)
     * digenerate otomatis dari urutan, bukan diketik manual.
     */
    private function simpanPilihanJawaban(Soal $soal, array $opsiList, int $jawabanBenarIndex): void
    {
        $kodeList = range('A', 'Z');

        foreach ($opsiList as $i => $teksOpsi) {
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'kode' => $kodeList[$i] ?? null,
                'teks_pilihan' => $teksOpsi,
                'is_benar' => ($i === $jawabanBenarIndex),
                'urutan' => $i + 1,
            ]);
        }
    }
}