<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use App\Models\Ujian;

class DummyExamSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // 1. Ambil data master acuan
        $tahunAjaran = DB::table('tahun_ajarans')->where('is_aktif', true)->first();
        if (!$tahunAjaran) {
            $tahunAjaranId = DB::table('tahun_ajarans')->insertGetId([
                'nama_tahun' => '2025/2026',
                'semester'   => 'ganjil',
                'is_aktif'   => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $tahunAjaranId = $tahunAjaran->id;
        }

        $smp = DB::table('jenjangs')->where('slug', 'smp')->first();
        $smpId = $smp ? $smp->id : DB::table('jenjangs')->insertGetId([
            'nama_jenjang' => 'SMP',
            'slug'         => 'smp',
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        $tingkat7 = DB::table('tingkats')->where('jenjang_id', $smpId)->first();
        $tingkat7Id = $tingkat7 ? $tingkat7->id : DB::table('tingkats')->insertGetId([
            'jenjang_id'   => $smpId,
            'nama_tingkat' => 'Kelas VII',
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        // Buat kelas baru VII B untuk membedakan dengan data awal
        $kelas7B = DB::table('kelas')->insertGetId([
            'tingkat_id'  => $tingkat7Id,
            'nama_kelas'  => 'VII B',
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        $kelas7A = DB::table('kelas')->where('tingkat_id', $tingkat7Id)->where('nama_kelas', 'VII A')->first();
        $kelas7AId = $kelas7A ? $kelas7A->id : $kelas7B;

        $jenisUjian = DB::table('jenis_ujians')->where('kode', 'PH')->first();
        $jenisUjianId = $jenisUjian ? $jenisUjian->id : 1;

        // 2. Tambah Mata Pelajaran baru jika belum ada
        $mapelIpa = DB::table('mata_pelajarans')->where('jenjang_id', $smpId)->where('nama_mapel', 'IPA')->first();
        $mapelIpaId = $mapelIpa ? $mapelIpa->id : DB::table('mata_pelajarans')->insertGetId([
            'jenjang_id' => $smpId,
            'nama_mapel' => 'IPA Terpadu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mapelIps = DB::table('mata_pelajarans')->where('jenjang_id', $smpId)->where('nama_mapel', 'IPS')->first();
        $mapelIpsId = $mapelIps ? $mapelIps->id : DB::table('mata_pelajarans')->insertGetId([
            'jenjang_id' => $smpId,
            'nama_mapel' => 'IPS Terpadu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $mapelBing = DB::table('mata_pelajarans')->where('jenjang_id', $smpId)->where('nama_mapel', 'Bahasa Inggris')->first();
        $mapelBingId = $mapelBing ? $mapelBing->id : DB::table('mata_pelajarans')->insertGetId([
            'jenjang_id' => $smpId,
            'nama_mapel' => 'Bahasa Inggris',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Tambah Guru Baru (3 Guru)
        $dataGuru = [
            ['nama' => 'Drs. Ahmad Dahlan', 'email' => 'ahmad.dahlan@cbt.com', 'nip' => '198501012010011001', 'mapel_id' => $mapelIpaId],
            ['nama' => 'Siti Nurhaliza, S.Pd', 'email' => 'siti.nurhaliza@cbt.com', 'nip' => '199002152015022002', 'mapel_id' => $mapelIpsId],
            ['nama' => 'John Smith, M.Ed', 'email' => 'john.smith@cbt.com', 'nip' => '198708202012011003', 'mapel_id' => $mapelBingId],
        ];

        $guruMapelIds = [];

        foreach ($dataGuru as $g) {
            $userId = DB::table('users')->insertGetId([
                'email'      => $g['email'],
                'password'   => Hash::make('password123'),
                'role'       => 'guru',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $guruId = DB::table('gurus')->insertGetId([
                'user_id'    => $userId,
                'nama'       => $g['nama'],
                'nip'        => $g['nip'],
                'jenjang_id' => $smpId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $gmId = DB::table('guru_mapels')->insertGetId([
                'guru_id'           => $guruId,
                'mata_pelajaran_id' => $g['mapel_id'],
                'tahun_ajaran_id'   => $tahunAjaranId,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Assign ke kelas VII A & VII B
            DB::table('guru_mapel_kelas')->insert([
                ['guru_mapel_id' => $gmId, 'kelas_id' => $kelas7AId, 'created_at' => now(), 'updated_at' => now()],
                ['guru_mapel_id' => $gmId, 'kelas_id' => $kelas7B, 'created_at' => now(), 'updated_at' => now()],
            ]);

            $guruMapelIds[] = [
                'guru_mapel_id'     => $gmId,
                'guru_id'           => $guruId,
                'mata_pelajaran_id' => $g['mapel_id'],
                'nama_guru'         => $g['nama']
            ];
        }

        // 4. Tambah Siswa Baru (10 Siswa di Kelas VII B)
        for ($i = 1; $i <= 10; $i++) {
            $nis = '2122100' . str_pad($i + 50, 3, '0', STR_PAD_LEFT);
            $siswaUserId = DB::table('users')->insertGetId([
                'nis'        => $nis,
                'password'   => Hash::make('siswa123'),
                'role'       => 'siswa',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $siswaId = DB::table('siswas')->insertGetId([
                'user_id'    => $siswaUserId,
                'nama'       => $faker->name(),
                'nis'        => $nis,
                'nisn'       => '008' . $faker->numerify('#######'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('siswa_kelas')->insert([
                'siswa_id'        => $siswaId,
                'kelas_id'        => $kelas7B,
                'tahun_ajaran_id' => $tahunAjaranId,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        // 5. Buat Bank Soal, Soal, Pilihan Jawaban, & Ujian Aktif
        $templateSoal = [
            $mapelIpaId => [
                'nama_bank' => 'Bank Soal Biologi Sel & Ekosistem',
                'soal' => [
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Perhatikan gambar organel sel berikut. Bagian yang berfungsi sebagai tempat respirasi sel ditunjukkan oleh gambar.',
                        'gambar' => 'https://via.placeholder.com/400x200.png?text=Organel+Mitolondria',
                        'bobot' => 20,
                        'opsi'  => [
                            ['A', 'Mitokondria', true],
                            ['B', 'Ribosom', false],
                            ['C', 'Lisosom', false],
                            ['D', 'Badan Golgi', false],
                            ['E', 'Retikulum Endoplasma', false],
                        ]
                    ],
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Organisme yang mampu membuat makanannya sendiri melalui proses fotosintesis disebut...',
                        'gambar' => null,
                        'bobot' => 20,
                        'opsi'  => [
                            ['A', 'Heterotrof', false],
                            ['B', 'Autotrof', true],
                            ['C', 'Dekomposer', false],
                            ['D', 'Karnivora', false],
                            ['E', 'Omnivora', false],
                        ]
                    ],
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Zat hijau daun yang berperan penting dalam menyerap energi cahaya matahari adalah...',
                        'gambar' => null,
                        'bobot' => 20,
                        'opsi'  => [
                            ['A', 'Klorofil', true],
                            ['B', 'Stomata', false],
                            ['C', 'Xilem', false],
                            ['D', 'Floem', false],
                            ['E', 'Karotenoid', false],
                        ]
                    ],
                    [
                        'jenis' => 'essay',
                        'teks'  => 'Jelaskan perbedaan antara ekosistem alami dan ekosistem buatan serta berikan masing-masing 2 contohnya!',
                        'gambar' => null,
                        'bobot' => 30,
                        'kunci' => 'Ekosistem alami terbentuk secara alami tanpa campur tangan manusia (contoh: hutan, laut). Ekosistem buatan sengaja dibuat oleh manusia (contoh: sawah, akuarium).'
                    ],
                    [
                        'jenis' => 'isian',
                        'teks'  => 'Proses penguapan air dari permukaan tumbuhan disebut...',
                        'gambar' => null,
                        'bobot' => 10,
                        'kunci' => 'Transpirasi'
                    ]
                ]
            ],
            $mapelIpsId => [
                'nama_bank' => 'Bank Soal Interaksi Sosial & Geografi Indonesia',
                'soal' => [
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Garis khayal yang membagi wilayah flora dan fauna Indonesia menjadi bagian Barat dan Tengah adalah...',
                        'gambar' => 'https://via.placeholder.com/400x200.png?text=Peta+Garis+Wallace',
                        'bobot' => 20,
                        'opsi'  => [
                            ['A', 'Garis Wallace', true],
                            ['B', 'Garis Weber', false],
                            ['C', 'Garis Khatulistiwa', false],
                            ['D', 'Garis Lintang', false],
                            ['E', 'Garis Bujur', false],
                        ]
                    ],
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Bentuk interaksi sosial yang bersifat mengarah pada persatuan dan kerjasama disebut interaksi...',
                        'gambar' => null,
                        'bobot' => 20,
                        'opsi'  => [
                            ['A', 'Disosiatif', false],
                            ['B', 'Asosiatif', true],
                            ['C', 'Kompetitif', false],
                            ['D', 'Konflik', false],
                            ['E', 'Oposisi', false],
                        ]
                    ],
                    [
                        'jenis' => 'essay',
                        'teks'  => 'Sebutkan dan jelaskan 3 faktor yang mempengaruhi interaksi sosial antar individu!',
                        'gambar' => null,
                        'bobot' => 40,
                        'kunci' => '1. Imitasi (meniru orang lain), 2. Sugesti (pengaruh pemikiran), 3. Simpati/Empati (perasaan tertarik/merasakan apa yang dirasakan orang lain).'
                    ],
                    [
                        'jenis' => 'isian',
                        'teks'  => 'Candi Buddha terbesar di Indonesia yang terletak di Magelang, Jawa Tengah adalah...',
                        'gambar' => null,
                        'bobot' => 20,
                        'kunci' => 'Borobudur'
                    ]
                ]
            ],
            $mapelBingId => [
                'nama_bank' => 'Bank Soal English Grammar & Reading Comprehension',
                'soal' => [
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'Look at the sign below. What does the sign mean?',
                        'gambar' => 'https://via.placeholder.com/350x200.png?text=No+Smoking+Sign',
                        'bobot' => 25,
                        'opsi'  => [
                            ['A', 'You must smoke here', false],
                            ['B', 'You are not allowed to smoke in this area', true],
                            ['C', 'Smoking is recommended', false],
                            ['D', 'You can buy cigarettes here', false],
                            ['E', 'Smoke area available', false],
                        ]
                    ],
                    [
                        'jenis' => 'pilihan_ganda',
                        'teks'  => 'She _____ to school every morning by bicycle.',
                        'gambar' => null,
                        'bobot' => 25,
                        'opsi'  => [
                            ['A', 'go', false],
                            ['B', 'goes', true],
                            ['C', 'went', false],
                            ['D', 'going', false],
                            ['E', 'gone', false],
                        ]
                    ],
                    [
                        'jenis' => 'essay',
                        'teks'  => 'Write a short paragraph (3-5 sentences) introducing yourself in English!',
                        'gambar' => null,
                        'bobot' => 30,
                        'kunci' => 'Hello, my name is John. I am 13 years old and I live in Jakarta. My favorite hobby is playing football.'
                    ],
                    [
                        'jenis' => 'isian',
                        'teks'  => 'The opposite word of "Big" is...',
                        'gambar' => null,
                        'bobot' => 20,
                        'kunci' => 'Small'
                    ]
                ]
            ]
        ];

        foreach ($guruMapelIds as $info) {
            $mapelId = $info['mata_pelajaran_id'];

            if (!isset($templateSoal[$mapelId])) {
                continue;
            }

            $bankData = $templateSoal[$mapelId];

            // Insert Bank Soal
            $bankSoalId = DB::table('bank_soals')->insertGetId([
                'guru_mapel_id'     => $info['guru_mapel_id'],
                'mata_pelajaran_id' => $mapelId,
                'jenjang_id'        => $smpId,
                'nama_bank_soal'    => $bankData['nama_bank'],
                'deskripsi'         => 'Soal testing evaluasi pembelajaran untuk siswa kelas VII.',
                'is_publish'        => true,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);

            // Insert Soal & Jawaban
            $urutan = 1;
            foreach ($bankData['soal'] as $item) {
                $soalId = DB::table('soals')->insertGetId([
                    'bank_soal_id' => $bankSoalId,
                    'jenis_soal'   => $item['jenis'],
                    'teks_soal'    => $item['teks'],
                    'gambar'       => $item['gambar'],
                    'bobot'        => $item['bobot'],
                    'urutan'       => $urutan++,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                if ($item['jenis'] === 'pilihan_ganda') {
                    $opsiData = [];
                    foreach ($item['opsi'] as $idx => $op) {
                        $opsiData[] = [
                            'soal_id'      => $soalId,
                            'kode'         => $op[0],
                            'teks_pilihan' => $op[1],
                            'is_benar'     => $op[2],
                            'urutan'       => $idx + 1,
                            'created_at'   => now(),
                            'updated_at'   => now(),
                        ];
                    }
                    DB::table('pilihan_jawabans')->insert($opsiData);
                } else {
                    DB::table('pilihan_jawabans')->insert([
                        'soal_id'      => $soalId,
                        'kode'         => null,
                        'teks_pilihan' => $item['kunci'],
                        'is_benar'     => true,
                        'urutan'       => 1,
                        'created_at'   => now(),
                        'updated_at'   => now(),
                    ]);
                }
            }

            // Insert Ujian Aktif
            $tokenUjian = Ujian::generateToken();
            $ujianId = DB::table('ujians')->insertGetId([
                'bank_soal_id'         => $bankSoalId,
                'tahun_ajaran_id'      => $tahunAjaranId,
                'jenis_ujian_id'       => $jenisUjianId,
                'nama_ujian'           => 'Ujian ' . $bankData['nama_bank'],
                'waktu_mulai'          => now()->subHour(), // Sudah mulai 1 jam lalu
                'waktu_selesai'        => now()->addHours(24), // Aktif sampai 24 jam kedepan
                'durasi_minimal'       => 15,
                'token'                => $tokenUjian,
                'acak_soal'            => true,
                'acak_jawaban'         => true,
                'tampilkan_nilai'      => true,
                'tampilkan_pembahasan' => true,
                'created_at'           => now(),
                'updated_at'           => now(),
            ]);

            // Assign Ujian ke Kelas VII A & VII B
            DB::table('ujian_kelas')->insert([
                ['ujian_id' => $ujianId, 'kelas_id' => $kelas7AId, 'created_at' => now(), 'updated_at' => now()],
                ['ujian_id' => $ujianId, 'kelas_id' => $kelas7B, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
