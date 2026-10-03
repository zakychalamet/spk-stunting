# AGENTS.md

## Project Overview

Project ini adalah aplikasi Sistem Pendukung Keputusan (SPK) berbasis website untuk menentukan prioritas penanganan ibu hamil berisiko stunting.

Sistem menggunakan:
- Laravel 13 sebagai framework aplikasi.
- Livewire 4 untuk komponen antarmuka interaktif.
- MySQL sebagai database.
- Blade sebagai templating.
- Tailwind CSS untuk antarmuka.
- AHP (Analytical Hierarchy Process) untuk menentukan bobot kriteria.
- TOPSIS (Technique for Order Preference by Similarity to Ideal Solution) untuk menentukan peringkat prioritas.

Desain sistem pada dokumen acuan mencakup Dashboard, Daftar Ibu Hamil, Perhitungan AHP, Perhitungan TOPSIS, Hasil Perangkingan, Manajemen Pengguna, dan Pengaturan.

## Source of Truth

Gunakan `PRD.md` sebagai sumber utama requirement produk.

Gunakan desain UI yang tersedia pada dokumen desain sebagai acuan tampilan dan alur halaman.

Jangan menambahkan fitur besar yang tidak terdapat dalam requirement tanpa alasan yang jelas.

Jika requirement belum ditentukan, pilih implementasi yang paling sederhana, konsisten, dan mudah dipelihara. Jangan mengubah logika AHP atau TOPSIS berdasarkan asumsi tanpa dasar requirement.

## Technology Stack

### Backend
- Laravel 13
- PHP 8.3+
- Eloquent ORM
- MySQL

### Frontend
- Blade
- Livewire 4
- Tailwind CSS
- Alpine.js hanya jika diperlukan untuk interaksi frontend ringan.

### Development Tools
- Composer
- NPM
- Laravel Artisan
- Git

## Core Modules

Implementasikan sistem secara modular:

1. Authentication
2. Dashboard
3. Data Ibu Hamil
4. Kriteria
5. Skala Penilaian
6. Perhitungan AHP
7. Perhitungan TOPSIS
8. Hasil Perangkingan
9. Detail Hasil
10. Manajemen Pengguna
11. Pengaturan

## Data Ibu Hamil

Data ibu hamil yang ditampilkan pada desain meliputi:

- Nama lengkap
- Tanggal lahir
- Nomor telepon
- Desa/Kelurahan
- HPHT
- HPL
- Usia kehamilan
- Status kehamilan
- Status anemia
- IMT
- LILA
- Berat badan sebelum hamil

Gunakan model `IbuHamil` dan migration khusus untuk data tersebut.

Gunakan validasi Laravel pada seluruh input.

Jangan menyimpan data turunan jika data tersebut dapat dihitung kembali dan requirement tidak mengharuskan penyimpanannya.

## Criteria

Kriteria utama pada desain sistem adalah:

- K1: Anemia
- K2: IMT
- K3: LILA
- K4: Usia

Gunakan tabel `criteria` untuk menyimpan data kriteria.

Gunakan tabel `criterion_scales` untuk menyimpan parameter, skor, keterangan, dan kategori penilaian setiap kriteria.

Jangan membuat tabel terpisah untuk setiap kriteria jika struktur datanya sama.

## AHP

AHP digunakan untuk menentukan bobot setiap kriteria.

Implementasikan tahapan:

1. Penyusunan matriks perbandingan berpasangan.
2. Normalisasi matriks.
3. Perhitungan eigen vector atau bobot prioritas.
4. Perhitungan lambda max.
5. Perhitungan Consistency Index (CI).
6. Perhitungan Consistency Ratio (CR).
7. Penentuan status konsistensi.

Gunakan skala perbandingan berpasangan sesuai skala Saaty yang telah ditentukan pada requirement.

Simpan data perbandingan menggunakan `ahp_comparisons`.

Simpan hasil perhitungan menggunakan `ahp_calculations` dan `ahp_weights`.

Jangan menyimpan nilai matriks yang dapat dihitung ulang jika tidak diperlukan.

## TOPSIS

TOPSIS digunakan untuk menentukan peringkat alternatif ibu hamil.

Implementasikan tahapan:

1. Membentuk matriks keputusan.
2. Melakukan normalisasi matriks.
3. Menghitung matriks ternormalisasi terbobot.
4. Menentukan solusi ideal positif.
5. Menentukan solusi ideal negatif.
6. Menghitung jarak setiap alternatif terhadap solusi ideal.
7. Menghitung nilai preferensi.
8. Menentukan peringkat.
9. Menentukan kategori prioritas berdasarkan aturan sistem.

Hasil utama disimpan pada `topsis_results`.

Jangan mencampurkan logika perhitungan TOPSIS dengan kode tampilan Livewire.

## Architecture

Gunakan struktur Laravel standar.

### Models

Model utama:

- `User`
- `IbuHamil`
- `Criterion`
- `CriterionScale`
- `AhpComparison`
- `AhpCalculation`
- `AhpWeight`
- `TopsisResult`

### Livewire

Gunakan Livewire untuk halaman yang membutuhkan interaksi dinamis, seperti:

- daftar data ibu hamil
- tambah dan edit data
- filter
- pengelolaan kriteria
- pengelolaan skala
- input perbandingan AHP
- perhitungan AHP
- perhitungan TOPSIS
- hasil perangkingan

Jangan menaruh seluruh logika aplikasi dalam satu Livewire component.

Pisahkan component berdasarkan tanggung jawab halaman atau fitur.

## Business Logic

Logika AHP dan TOPSIS harus dipisahkan dari UI.

Jika memungkinkan, buat service khusus:

```text
app/Services/AhpService.php
app/Services/TopsisService.php
```

Service bertanggung jawab terhadap perhitungan.

Livewire bertanggung jawab terhadap:
- menerima input
- validasi
- memanggil service
- mengirim hasil ke view

Jangan menulis rumus AHP atau TOPSIS langsung berulang kali di Blade.

## Database

Gunakan migration untuk seluruh tabel.

Relasi utama:

```text
criteria
    ├── criterion_scales
    ├── ahp_comparisons
    └── ahp_weights

ahp_calculations
    └── ahp_weights

ibu_hamil
    └── topsis_results
```

Gunakan foreign key dan relationship Eloquent.

Gunakan nama tabel dan kolom yang konsisten dalam bahasa Inggris pada kode, sedangkan label antarmuka dapat menggunakan Bahasa Indonesia.

## Routes

Gunakan route yang jelas dan konsisten.

Contoh:

```php
Route::get('/dashboard', Dashboard::class)->name('dashboard');
Route::get('/ibu-hamil', IbuHamilIndex::class)->name('ibu-hamil.index');
Route::get('/ibu-hamil/create', IbuHamilCreate::class)->name('ibu-hamil.create');
Route::get('/ibu-hamil/{ibuHamil}', IbuHamilShow::class)->name('ibu-hamil.show');
Route::get('/ibu-hamil/{ibuHamil}/edit', IbuHamilEdit::class)->name('ibu-hamil.edit');
```

Gunakan route name daripada hard-coded URL ketika memungkinkan.

## UI Guidelines

Ikuti struktur visual pada desain acuan.

Navigasi utama:

- Dashboard
- Daftar Ibu Hamil
- Perhitungan Bobot
- Hasil Perangkingan
- Pengguna
- Pengaturan

Gunakan istilah Bahasa Indonesia yang konsisten.

Contoh:
- Ibu Hamil
- Kriteria
- Bobot Prioritas
- Perbandingan Berpasangan
- Konsistensi
- Nilai Preferensi
- Peringkat
- Prioritas Tinggi
- Prioritas Sedang
- Prioritas Rendah

Jangan mengubah istilah metodologi seperti AHP, TOPSIS, Consistency Ratio, dan nilai preferensi tanpa kebutuhan.

## Dashboard

Dashboard menampilkan ringkasan data dan hasil prioritas.

Komponen yang terlihat pada desain:

- Total ibu hamil
- Jumlah prioritas tinggi
- Jumlah prioritas sedang
- Jumlah prioritas rendah
- Grafik berdasarkan anemia
- Distribusi prioritas
- Daftar prioritas penanganan

Data dashboard harus berasal dari database. Jangan hard-code angka hasil.

## Validation

Gunakan Laravel validation.

Contoh:

```php
$this->validate([
    'nama' => ['required', 'string', 'max:255'],
    'tanggal_lahir' => ['required', 'date'],
    'hpht' => ['required', 'date'],
]);
```

Validasi harus dilakukan sebelum data disimpan atau digunakan dalam perhitungan.

Berikan pesan validasi dalam Bahasa Indonesia.

## Security

- Jangan menyimpan password dalam bentuk plaintext.
- Gunakan hashing Laravel.
- Jangan memasukkan credential ke source code.
- Gunakan `.env` untuk konfigurasi sensitif.
- Validasi seluruh input pengguna.
- Gunakan authorization untuk fitur berdasarkan role.
- Jangan menonaktifkan security advisory Composer hanya untuk memaksa instalasi package.

## Coding Style

- Ikuti PSR-12.
- Gunakan type declaration jika sesuai.
- Gunakan nama class PascalCase.
- Gunakan nama method camelCase.
- Gunakan nama database snake_case.
- Gunakan single responsibility.
- Hindari duplikasi kode.
- Gunakan dependency injection jika diperlukan.
- Jangan membuat controller atau Livewire component terlalu besar.
- Tambahkan komentar hanya jika logika tidak jelas.

## Livewire Guidelines

Gunakan property Livewire hanya untuk state yang memang diperlukan oleh tampilan.

Gunakan:

```blade
wire:model
wire:click
wire:submit
```

sesuai kebutuhan.

Untuk operasi yang mengubah data, validasi terlebih dahulu.

Setelah operasi berhasil:
- tampilkan feedback kepada pengguna
- refresh data yang diperlukan
- hindari reload halaman penuh jika Livewire dapat menanganinya

Jangan menggunakan Livewire hanya untuk menggantikan seluruh struktur Laravel. Tetap gunakan Eloquent, validation, authorization, dan service layer sesuai kebutuhan.

## AHP/TOPSIS Accuracy

Perhitungan harus dapat ditelusuri.

Setiap tahap perhitungan harus menggunakan data yang benar dari database.

Jangan membulatkan nilai terlalu awal karena dapat memengaruhi hasil akhir.

Gunakan pembulatan hanya ketika menampilkan hasil kepada pengguna, kecuali requirement metodologi menentukan aturan lain.

Jika nilai perhitungan ditampilkan dalam UI, tampilkan nilai yang cukup untuk verifikasi.

## Testing

Minimal siapkan pengujian untuk:

- login
- tambah ibu hamil
- edit ibu hamil
- hapus ibu hamil
- validasi input
- pengelolaan kriteria
- pengelolaan skala
- input perbandingan AHP
- perhitungan AHP
- validasi konsistensi AHP
- perhitungan TOPSIS
- perangkingan
- akses berdasarkan role

Pengujian sistem dapat digunakan untuk mendukung Black Box Testing dan User Acceptance Testing (UAT).

## Artisan Commands

Gunakan Artisan untuk membuat komponen.

Contoh:

```bash
php artisan make:model IbuHamil -m
php artisan make:livewire IbuHamil/Index
php artisan make:livewire IbuHamil/Create
php artisan make:livewire IbuHamil/Edit
php artisan make:livewire IbuHamil/Show
```

Jalankan migration dengan:

```bash
php artisan migrate
```

Gunakan seeder jika diperlukan:

```bash
php artisan db:seed
```

## Development Workflow

Sebelum mengimplementasikan fitur:

1. Baca `PRD.md`.
2. Periksa struktur database yang sudah ada.
3. Periksa model dan relationship yang sudah ada.
4. Periksa Livewire component yang terkait.
5. Implementasikan migration jika diperlukan.
6. Implementasikan model dan relationship.
7. Implementasikan service untuk business logic.
8. Implementasikan Livewire component.
9. Implementasikan Blade view.
10. Tambahkan validation dan authorization.
11. Jalankan test.
12. Periksa hasil menggunakan browser.

Jangan mengubah fitur yang sudah berjalan jika perubahan tersebut tidak diperlukan oleh requirement.

## Git

Gunakan commit yang jelas dan fokus.

Contoh:

```text
feat: add ibu hamil management
feat: implement AHP calculation
feat: implement TOPSIS ranking
fix: validate pregnancy data
refactor: separate AHP calculation service
```

Jangan memasukkan file `.env` ke repository.

## Important Constraints

- Jangan mengganti Laravel tanpa instruksi.
- Jangan mengganti Livewire tanpa instruksi.
- Jangan mengubah metode AHP atau TOPSIS menjadi metode lain.
- Jangan membuat data dummy sebagai data produksi.
- Jangan hard-code hasil perangkingan.
- Jangan hard-code jumlah prioritas pada dashboard.
- Jangan menghapus data atau migration yang sudah digunakan tanpa memastikan dampaknya.
- Jangan menonaktifkan fitur keamanan Composer.
- Jangan menambahkan dependency baru jika fitur dapat dibuat menggunakan Laravel atau Livewire yang sudah tersedia.

## Expected Result

Hasil akhir harus berupa aplikasi SPK berbasis website yang:

- dapat digunakan oleh pengguna yang berwenang
- dapat mengelola data ibu hamil
- dapat mengelola kriteria dan skala penilaian
- dapat menghitung bobot menggunakan AHP
- dapat memeriksa konsistensi AHP
- dapat melakukan perangkingan menggunakan TOPSIS
- dapat menampilkan hasil prioritas penanganan
- memiliki struktur kode yang rapi
- menggunakan database secara konsisten
- dapat diuji dan dipelihara
