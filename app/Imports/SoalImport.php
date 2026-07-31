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

    private const JENIS_VALID = [
        'pilihan_ganda',
        'pilihan_ganda_kompleks',
        'benar_salah',
        'mencocokkan',
        'isian',
        'essay'
    ];

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

            // Lewati baris yang benar-benar kosong
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

                $this->simpanPilihanUntukSemuaJenis($soal, $jenisSoal, $opsiList, $jawabanBenarRaw);

                DB::commit();

                $this->successCount++;
                $this->urutan++;
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = "Baris {$nomorBaris}: gagal disimpan ke database ({$e->getMessage()}).";
            }
        }
    }

    private function validasiBaris(string $jenisSoal, string $bobotRaw, string $teksSoal, array $opsiRaw, string $jawabanBenarRaw): ?string
    {
        if (!in_array($jenisSoal, self::JENIS_VALID, true)) {
            return "jenis soal '{$jenisSoal}' tidak valid.";
        }

        if ($teksSoal === '') {
            return 'teks soal tidak boleh kosong.';
        }

        if ($bobotRaw === '' || !is_numeric($bobotRaw) || (float) $bobotRaw < 1) {
            return 'bobot harus berupa angka minimal 1.';
        }

        $opsiTerisi = array_values(array_filter(
            array_map(function ($opsi) { return trim((string) $opsi); }, $opsiRaw),
            function ($opsi) { return $opsi !== ''; }
        ));

        if ($jenisSoal === 'pilihan_ganda') {
            if (count($opsiTerisi) < 2) {
                return 'soal pilihan ganda butuh minimal 2 opsi jawaban.';
            }
            if ($jawabanBenarRaw === '') {
                return "kolom 'Kunci Jawaban' tidak boleh kosong.";
            }
        } elseif ($jenisSoal === 'pilihan_ganda_kompleks') {
            if (count($opsiTerisi) < 2) {
                return 'soal PG Kompleks butuh minimal 2 opsi jawaban.';
            }
            if ($jawabanBenarRaw === '') {
                return "kolom 'Kunci Jawaban' PG Kompleks tidak boleh kosong.";
            }
        } elseif ($jenisSoal === 'benar_salah') {
            if (count($opsiTerisi) < 1) {
                return 'soal Benar/Salah butuh minimal 1 pernyataan.';
            }
            if ($jawabanBenarRaw === '') {
                return "kolom 'Kunci Jawaban' Benar/Salah tidak boleh kosong (misal: B, S, B).";
            }
        } elseif ($jenisSoal === 'mencocokkan') {
            if (count($opsiTerisi) < 1) {
                return 'soal Mencocokkan butuh minimal 1 item kiri.';
            }
            if ($jawabanBenarRaw === '') {
                return "kolom 'Kunci Jawaban' Mencocokkan tidak boleh kosong (misal: Pasangan 1 | Pasangan 2).";
            }
        } elseif ($jenisSoal === 'isian') {
            if ($jawabanBenarRaw === '') {
                return "kolom 'Kunci Jawaban' Isian Singkat tidak boleh kosong.";
            }
        }

        return null;
    }

    private function isRowEmpty($row): bool
    {
        return collect($row)->filter(function ($cell) { return trim((string) $cell) !== ''; })->isEmpty();
    }

    private function simpanPilihanUntukSemuaJenis(Soal $soal, string $jenisSoal, array $opsiList, string $jawabanBenarRaw): void
    {
        $kodeList = range('A', 'Z');

        if ($jenisSoal === 'pilihan_ganda') {
            // parse single index/letter (misal: 3 atau C)
            $targetIdx = $this->parseSingleIndex($jawabanBenarRaw, $kodeList);
            foreach ($opsiList as $i => $teksOpsi) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => $kodeList[$i] ?? null,
                    'teks_pilihan' => $teksOpsi,
                    'is_benar' => ($i === $targetIdx),
                    'urutan' => $i + 1,
                ]);
            }
        } elseif ($jenisSoal === 'pilihan_ganda_kompleks') {
            // parse multiple indices/letters (misal: 1,3,4 atau A,C,D)
            $targetIndices = $this->parseMultiIndices($jawabanBenarRaw, $kodeList);
            foreach ($opsiList as $i => $teksOpsi) {
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => $kodeList[$i] ?? null,
                    'teks_pilihan' => $teksOpsi,
                    'is_benar' => in_array($i, $targetIndices, true),
                    'urutan' => $i + 1,
                ]);
            }
        } elseif ($jenisSoal === 'benar_salah') {
            // parse comma separated list: "Benar, Salah, Benar" atau "B, S, B"
            $keys = array_map(function ($s) { return strtolower(trim($s)); }, explode(',', $jawabanBenarRaw));
            foreach ($opsiList as $i => $teksPernyataan) {
                $kunciVal = $keys[$i] ?? 'salah';
                $isBenar = ($kunciVal === 'benar' || $kunciVal === 'b' || $kunciVal === 'true' || $kunciVal === '1');
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => (string) ($i + 1),
                    'teks_pilihan' => $teksPernyataan,
                    'is_benar' => $isBenar,
                    'urutan' => $i + 1,
                ]);
            }
        } elseif ($jenisSoal === 'mencocokkan') {
            // parse pipe separated pairs: "Nabi Musa AS | Nabi Daud AS | Nabi Isa AS"
            $pairs = array_map('trim', explode('|', $jawabanBenarRaw));
            foreach ($opsiList as $i => $itemKiri) {
                $pasanganKanan = $pairs[$i] ?? '';
                PilihanJawaban::create([
                    'soal_id' => $soal->id,
                    'kode' => (string) ($i + 1),
                    'teks_pilihan' => $itemKiri,
                    'pasangan' => $pasanganKanan,
                    'is_benar' => true,
                    'urutan' => $i + 1,
                ]);
            }
        } elseif ($jenisSoal === 'isian') {
            // Kunci alternatif dipisah semikolon ;
            PilihanJawaban::create([
                'soal_id' => $soal->id,
                'kode' => '1',
                'teks_pilihan' => $jawabanBenarRaw,
                'is_benar' => true,
                'urutan' => 1,
            ]);
        }
    }

    private function parseSingleIndex(string $raw, array $kodeList): int
    {
        $raw = strtoupper(trim($raw));
        if (ctype_digit($raw)) {
            return (int) $raw - 1;
        }
        $idx = array_search($raw, $kodeList, true);
        return ($idx !== false) ? $idx : 0;
    }

    private function parseMultiIndices(string $raw, array $kodeList): array
    {
        $parts = explode(',', $raw);
        $indices = [];
        foreach ($parts as $p) {
            $p = strtoupper(trim($p));
            if ($p === '') continue;
            if (ctype_digit($p)) {
                $indices[] = (int) $p - 1;
            } else {
                $idx = array_search($p, $kodeList, true);
                if ($idx !== false) {
                    $indices[] = $idx;
                }
            }
        }
        return $indices;
    }
}