# Redesign Export PDF Rekap Kelas: Leaderboard vs Matriks Jenis Ujian

Perubahan ini bertujuan untuk membenahi fitur **Export PDF** rekap kelas agar Wali Kelas memiliki dua pilihan ekspor yang jelas dan rapi:
1. **Export Leaderboard Kelas (PDF Keseluruhan)**: Berformat portrait, menampilkan peringkat dan nilai rata-rata kumulatif semua siswa.
2. **Export Matriks Nilai (PDF Jenis Ujian Aktif)**: Berformat landscape, menampilkan matriks nilai per mata pelajaran khusus untuk jenis ujian yang sedang dipilih (misalnya PTS saja).

## Proposed Changes

### 1. View Utama — Dropdown Export PDF

#### [MODIFY] [rekap-nilai.blade.php](file:///c:/laragon/www/cbt-alazhar26/resources/views/guru/wali-kelas/rekap-nilai.blade.php)

- Mengubah tombol `<a href="..." id="btnExportPdf">Export PDF</a>` menjadi **Bootstrap Dropdown Button**.
- Dropdown akan memiliki 2 opsi:
  - **Cetak Leaderboard Kelas** (`jenis_ujian` dikosongkan).
  - **Cetak Matriks [Jenis Ujian]** (`jenis_ujian` diisi sesuai filter aktif). Opsi ini dinonaktifkan/disembunyikan secara visual jika Wali Kelas sedang memilih "Semua Jenis Ujian" pada filter dropdown.

---

### 2. Controller — Pengkondisian Format PDF

#### [MODIFY] [GuruWaliKelasController.php](file:///c:/laragon/www/cbt-alazhar26/app/Http/Controllers/GuruWaliKelasController.php)

**Method `exportPdf(Request $request)` (line 591-714):**
- Mengabaikan parameter pencarian nama (`search`) agar file PDF resmi selalu berisi seluruh siswa kelas.
- Mengecek keberadaan parameter `jenis_ujian`.
- **Kasus A: `jenis_ujian` tidak dipilih (Ekspor Keseluruhan)**
  - Hitung rata-rata kumulatif dan peringkat (*leaderboard*) seluruh siswa.
  - Load view PDF baru/disesuaikan: `pdf.rekap-leaderboard` berorientasi **portrait**.
- **Kasus B: `jenis_ujian` dipilih (Ekspor Jenis Ujian)**
  - Filter ujian hanya untuk jenis tersebut.
  - Susun matriks mata pelajaran per siswa.
  - Load view PDF: `pdf.rekap-wali-kelas` (matriks mapel horizontal) berorientasi **landscape**.

---

### 3. View PDF — Pemisahan/Pembaruan Template

#### [NEW/MODIFY] [rekap-leaderboard.blade.php](file:///c:/laragon/www/cbt-alazhar26/resources/views/pdf/rekap-leaderboard.blade.php)
- View PDF baru khusus untuk mencetak leaderboard kelas (Portrait). 
- Menampilkan kolom: Peringkat, NIS, Nama Siswa, Rata-rata Nilai, dan Status.

#### [MODIFY] [rekap-wali-kelas.blade.php](file:///c:/laragon/www/cbt-alazhar26/resources/views/pdf/rekap-wali-kelas.blade.php)
- Memastikan template landscape matriks nilai ini bersih dan dinamis hanya untuk menampilkan kolom mata pelajaran terpilih berdasarkan jenis ujian yang difilter.

---

## Verification Plan

### Manual Verification
1. Buka halaman rekap-nilai.
2. Ketika filter **Jenis Ujian** adalah "Semua Jenis Ujian":
   - Klik dropdown Export PDF → Opsi "Cetak Matriks Jenis Ujian" disembunyikan atau dinonaktifkan.
   - Klik "Cetak Leaderboard Kelas" → Pastikan PDF berformat portrait terunduh dengan benar berisi leaderboard.
3. Ketika filter **Jenis Ujian** diubah ke salah satu jenis (misal: "PAS"):
   - Klik dropdown Export PDF → Kedua opsi aktif.
   - Klik "Cetak Matriks PAS" → Pastikan PDF berformat landscape terunduh dengan benar berisi matriks nilai per-mapel khusus PAS.
4. Pastikan pencarian nama siswa di web tidak memengaruhi jumlah siswa dalam file PDF yang diunduh.
