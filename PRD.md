# Product Requirements Document (PRD)

## 1. Nama Produk

Sistem Pendukung Keputusan Penentuan Prioritas Penanganan Ibu Hamil Berisiko Stunting

## 2. Deskripsi Produk

Aplikasi berbasis website yang digunakan untuk membantu tenaga kesehatan dalam mengelola data ibu hamil dan menentukan prioritas penanganan ibu hamil berisiko stunting.

Sistem menggunakan metode Analytical Hierarchy Process (AHP) untuk menentukan bobot kriteria dan metode Technique for Order Preference by Similarity to Ideal Solution (TOPSIS) untuk menentukan peringkat prioritas.

## 3. Tujuan Sistem

Sistem bertujuan untuk:

- Mengelola data ibu hamil.
- Mengelola kriteria dan skala penilaian.
- Menentukan bobot kriteria menggunakan metode AHP.
- Memeriksa konsistensi hasil perbandingan kriteria.
- Menentukan peringkat prioritas menggunakan metode TOPSIS.
- Menampilkan hasil peringkat dan prioritas penanganan secara terstruktur.
- Membantu tenaga kesehatan memperoleh informasi pendukung dalam menentukan prioritas penanganan.

## 4. Pengguna

Sistem memiliki tiga role pengguna:

- Admin Puskesmas
- Bidan Kesehatan
- Ahli Gizi

Sistem menyediakan pengelolaan pengguna dan role.

Hak akses setiap role mengikuti kebutuhan sistem yang ditentukan pada tahap implementasi.

## 5. Modul Sistem

### 5.1 Login

Pengguna melakukan autentikasi sebelum mengakses sistem.

### 5.2 Dashboard

Dashboard menampilkan ringkasan informasi sistem, meliputi:

- Total ibu hamil.
- Jumlah prioritas tinggi.
- Jumlah prioritas sedang.
- Jumlah prioritas rendah.
- Grafik berdasarkan anemia.
- Distribusi prioritas.
- Daftar prioritas penanganan.

Seluruh angka dan informasi dashboard harus berasal dari database. Jangan menggunakan data hard-code.

### 5.3 Data Ibu Hamil

Sistem menyediakan fitur:

- Menampilkan data ibu hamil.
- Menambahkan data ibu hamil.
- Mengubah data ibu hamil.
- Menghapus data ibu hamil.
- Melihat detail data ibu hamil.
- Melakukan pencarian.
- Menggunakan filter lanjutan.
- Mengimpor data dari Excel.
- Mengekspor data ke Excel.

Data ibu hamil meliputi:

- Nama lengkap.
- Tanggal lahir.
- Nomor telepon.
- Desa/Kelurahan.
- HPHT.
- HPL.
- Usia kehamilan.
- Status kehamilan.
- Status anemia.
- IMT.
- LILA.
- Berat badan sebelum hamil.

### 5.4 Kriteria dan Skala Penilaian

Sistem menyediakan pengelolaan kriteria dan skala penilaian.

Kriteria yang digunakan:

- K1: Anemia.
- K2: IMT.
- K3: LILA.
- K4: Usia.

Pengguna yang memiliki hak akses dapat mengelola:

- Kode kriteria.
- Nama kriteria.
- Jenis kriteria.
- Deskripsi kriteria.
- Parameter atau rentang penilaian.
- Skor.
- Keterangan atau kategori.

Struktur skala harus dapat digunakan oleh proses perhitungan AHP dan TOPSIS.

### 5.5 Perhitungan AHP

Sistem menggunakan AHP untuk menentukan bobot kriteria.

Tahapan yang ditampilkan sistem:

1. Matriks perbandingan berpasangan.
2. Normalisasi matriks.
3. Bobot prioritas.
4. Lambda max.
5. Consistency Index (CI).
6. Consistency Ratio (CR).
7. Status konsistensi.

Perbandingan kriteria menggunakan skala yang telah ditentukan dalam sistem.

Sistem menyediakan tampilan detail perhitungan AHP agar proses perhitungan dapat diperiksa.

### 5.6 Perhitungan TOPSIS

Sistem menggunakan TOPSIS untuk menentukan peringkat alternatif.

Tahapan perhitungan meliputi:

1. Matriks keputusan.
2. Normalisasi matriks.
3. Matriks keputusan ternormalisasi.
4. Matriks keputusan ternormalisasi terbobot.
5. Solusi ideal positif.
6. Solusi ideal negatif.
7. Jarak terhadap solusi ideal.
8. Nilai preferensi.
9. Peringkat.

Sistem menyediakan tampilan detail perhitungan TOPSIS.

### 5.7 Hasil Perangkingan

Sistem menampilkan hasil perangkingan ibu hamil berdasarkan nilai preferensi TOPSIS.

Informasi yang ditampilkan meliputi:

- Peringkat.
- Nama ibu hamil.
- Nilai kriteria.
- Nilai preferensi atau skor TOPSIS.
- Prioritas.
- Rekomendasi.
- Detail hasil.

Kategori prioritas mengikuti aturan yang ditetapkan dalam sistem.

### 5.8 Manajemen Pengguna

Sistem menyediakan pengelolaan pengguna yang mencakup:

- Menampilkan pengguna.
- Menambahkan pengguna.
- Mengubah pengguna.
- Menghapus pengguna.
- Mengatur role pengguna.

Role pengguna:

- Admin Puskesmas.
- Bidan Kesehatan.
- Ahli Gizi.

### 5.9 Pengaturan

Sistem menyediakan halaman pengaturan sesuai kebutuhan aplikasi.

## 6. Proses Utama Sistem

Alur utama sistem:

Login
→ Dashboard
→ Data Ibu Hamil
→ Kriteria dan Skala
→ Perhitungan AHP
→ Validasi Konsistensi
→ Perhitungan TOPSIS
→ Hasil Perangkingan
→ Detail Hasil

## 7. Kriteria Penilaian

Kriteria utama:

| Kode | Kriteria |
|------|----------|
| K1 | Anemia |
| K2 | IMT |
| K3 | LILA |
| K4 | Usia |

Nilai dan parameter skala kriteria harus mengikuti ketentuan penelitian.

Jangan membuat nilai ambang baru tanpa requirement yang jelas.

## 8. Perhitungan AHP

Sistem harus mampu menghasilkan:

- Matriks perbandingan berpasangan.
- Matriks normalisasi.
- Bobot prioritas setiap kriteria.
- Lambda max.
- Consistency Index.
- Consistency Ratio.
- Status konsistensi.

Nilai perhitungan tidak boleh di-hard-code.

Perhitungan harus dilakukan berdasarkan data perbandingan yang tersimpan pada database.

## 9. Perhitungan TOPSIS

Sistem harus mampu menghasilkan:

- Matriks keputusan.
- Matriks normalisasi.
- Matriks ternormalisasi terbobot.
- Solusi ideal positif.
- Solusi ideal negatif.
- Jarak solusi ideal positif.
- Jarak solusi ideal negatif.
- Nilai preferensi.
- Peringkat.

Perhitungan harus dilakukan berdasarkan data ibu hamil, skala kriteria, dan bobot AHP.

## 10. Hasil Sistem

Hasil akhir sistem adalah peringkat ibu hamil berdasarkan nilai preferensi TOPSIS.

Hasil tersebut digunakan sebagai informasi pendukung untuk menentukan prioritas penanganan.

Sistem tidak menggantikan keputusan klinis tenaga kesehatan.

## 11. Teknologi

Backend:

- Laravel 13.
- PHP 8.3 atau versi yang kompatibel.
- Eloquent ORM.

Frontend:

- Blade.
- Livewire 4.
- Tailwind CSS.

Database:

- MySQL.

Metode:

- Analytical Hierarchy Process (AHP).
- Technique for Order Preference by Similarity to Ideal Solution (TOPSIS).

## 12. Struktur Database

Tabel utama yang direncanakan:

- users
- ibu_hamil
- criteria
- criterion_scales
- ahp_comparisons
- ahp_calculations
- ahp_weights
- topsis_results

Relasi utama:

criteria
├── criterion_scales
├── ahp_comparisons
└── ahp_weights

ahp_calculations
└── ahp_weights

ibu_hamil
└── topsis_results

Struktur database dapat disesuaikan selama tetap mendukung requirement sistem.

## 13. Validasi

Sistem harus melakukan validasi pada data yang dimasukkan pengguna.

Validasi minimal mencakup:

- Field wajib diisi.
- Format tanggal.
- Format angka.
- Batas nilai sesuai kebutuhan kriteria.
- Data tidak boleh memiliki format yang tidak valid.

Pesan validasi menggunakan Bahasa Indonesia.

## 14. Hak Akses

Sistem menggunakan role pengguna untuk membatasi akses terhadap fitur.

Implementasi detail hak akses harus mengikuti kebutuhan sistem dan tidak boleh memberikan akses administratif kepada pengguna yang tidak memiliki hak tersebut.

## 15. Ketentuan UI

Antarmuka mengikuti desain aplikasi yang telah dibuat.

Navigasi utama:

- Dashboard.
- Daftar Ibu Hamil.
- Perhitungan Bobot.
- Hasil Perangkingan.
- Pengguna.
- Pengaturan.

Gunakan istilah Bahasa Indonesia secara konsisten.

Istilah metodologi seperti AHP, TOPSIS, Consistency Ratio, nilai preferensi, dan matriks tetap digunakan.

## 16. Pengujian

Sistem akan diuji untuk memastikan fungsi berjalan sesuai requirement.

Pengujian mencakup:

- Login.
- Pengelolaan data ibu hamil.
- Validasi data.
- Import Excel.
- Export Excel.
- Pengelolaan kriteria.
- Pengelolaan skala.
- Perhitungan AHP.
- Konsistensi AHP.
- Perhitungan TOPSIS.
- Hasil perangkingan.
- Hak akses pengguna.

Metode pengujian yang digunakan dalam penelitian dapat mencakup Black Box Testing dan User Acceptance Testing (UAT).

## 17. Aturan Implementasi

- Gunakan Laravel 13.
- Gunakan Livewire 4.
- Gunakan MySQL.
- Gunakan Eloquent ORM.
- Gunakan migration untuk struktur database.
- Gunakan validation Laravel.
- Gunakan authorization untuk pembatasan akses.
- Pisahkan logika perhitungan AHP dan TOPSIS dari tampilan.
- Jangan melakukan hard-code terhadap hasil perhitungan.
- Jangan hard-code jumlah data pada dashboard.
- Jangan membuat kriteria baru tanpa requirement.
- Jangan mengubah metode AHP-TOPSIS menjadi metode lain.
- Jangan menambahkan fitur utama yang tidak terdapat dalam requirement tanpa persetujuan.
- Jangan menyimpan credential atau password dalam source code.

## 18. Batasan Sistem

- Sistem berfungsi sebagai sistem pendukung keputusan.
- Sistem tidak menggantikan keputusan tenaga kesehatan.
- Sistem menggunakan kriteria yang telah ditentukan dalam penelitian.
- Sistem menggunakan metode AHP untuk pembobotan dan TOPSIS untuk perangkingan.
- Hasil sistem bergantung pada data ibu hamil dan parameter kriteria yang dimasukkan.
