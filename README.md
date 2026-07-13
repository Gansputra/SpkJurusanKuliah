# Sistem Pendukung Keputusan (SPK) Pemilihan Jurusan Kuliah (AHP & TOPSIS)

Sistem Pendukung Keputusan (SPK) Pemilihan Jurusan Kuliah adalah aplikasi berbasis web yang dirancang untuk membantu calon mahasiswa menentukan program studi/jurusan kuliah terbaik berdasarkan profil kemampuan akademik, minat, bakat, prospek karir, dan kesiapan finansial mereka. 

Aplikasi ini menggunakan kombinasi dua metode pengambilan keputusan multikriteria (*MCDM*):
1. **Analytic Hierarchy Process (AHP):** Digunakan di sisi administrator untuk menentukan bobot prioritas setiap kriteria penilaian secara konsisten melalui matriks perbandingan berpasangan (*pairwise comparison*).
2. **Technique for Order of Preference by Similarity to Ideal Solution (TOPSIS):** Digunakan di sisi calon mahasiswa untuk melakukan pencocokan profil (*profile matching*) dan perangkingan semua alternatif jurusan secara personal berdasarkan kedekatan dengan solusi ideal terbaik.

---

## Fitur Utama

### 1. Sisi Administrator (Admin)
*   **Dashboard Statistik:** Ringkasan jumlah data kriteria, jurusan, pengguna, riwayat rekomendasi, serta status validitas bobot AHP.
*   **Manajemen Kriteria:** Mengelola data kriteria penilaian lengkap dengan tipe (*benefit* / *cost*).
*   **Manajemen Alternatif (Jurusan):** Mengelola daftar jurusan kuliah beserta detail deskripsinya.
*   **Nilai Karakteristik Alternatif:** Mengatur nilai bobot dasar acuan standar untuk masing-masing jurusan pada setiap kriteria.
*   **Perhitungan AHP Interaktif:** Input matriks perbandingan berpasangan antarkriteria dengan pengecekan rasio konsistensi (*Consistency Ratio - CR*) otomatis secara real-time.
*   **Perangkingan Global & Ekspor PDF:** Melihat hasil perhitungan ranking statis seluruh alternatif dan mencetaknya menjadi laporan PDF.

### 2. Sisi Calon Mahasiswa (User)
*   **Dashboard User:** Visualisasi status rekomendasi terakhir, ringkasan kriteria, dan aksi cepat.
*   **Penilaian Mandiri (Form Slider):** Form penilaian diri sendiri interaktif menggunakan slider (skala 0-10) untuk kriteria Nilai Rapor, Minat, Bakat, Peluang Kerja, dan Kemampuan Biaya.
*   **Hasil Rekomendasi Personal:** 
    *   Highlight Jurusan Terbaik (🥇) yang paling sesuai.
    *   Grafik Preferensi interaktif (Chart.js).
    *   Tabel ranking lengkap semua program studi berdasarkan kecocokan profil diri.
*   **Riwayat Rekomendasi:** Menyimpan riwayat pencarian rekomendasi masa lalu yang dapat dibuka kembali detail hasil perhitungannya kapan saja.

---

## Spesifikasi Teknologi

*   **Framework Utama:** Laravel 12.x
*   **Bahasa Pemrograman:** PHP 8.4.x
*   **Database:** MySQL / MariaDB
*   **Styling & UI:** Tailwind CSS (Vanilla blade styling)
*   **Interaktivitas UI:** Alpine.js (State management sidebar & modals)
*   **Visualisasi Data:** Chart.js v4 (Grafik donat AHP & grafik batang TOPSIS)

---

## Petunjuk Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal Anda:

### 1. Clone Repository & Masuk ke Direktori Projek
```bash
git clone https://github.com/Gansputra/SpkJurusanKuliah.git
cd SpkJurusanKuliah
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Install & Build Dependensi Frontend (Asset)
```bash
npm install
npm run build
```

### 4. Salin File Konfigurasi Lingkungan
Buat salinan file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
*Sesuaikan konfigurasi database Anda di dalam file `.env` (seperti `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`).*

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Jalankan Migrasi & Seeding Database
Perintah ini akan membuat struktur tabel beserta data kriteria acuan awal, alternatif jurusan, nilai standar alternatif, matriks berpasangan AHP, dan akun demo:
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi kini dapat diakses di browser melalui alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---


## Skema Logika AHP & TOPSIS (Profile Matching)

*   **Bobot Kriteria** dihasilkan secara ilmiah melalui perhitungan rasio konsistensi matriks perbandingan berpasangan (AHP) masukan admin.
*   **Kecocokan Personal** dihitung dengan mengukur jarak selisih absolut antara nilai masukan pengguna dengan nilai standar jurusan pada database:
    $$x_{ij} = 10 - |U_j - A_{ij}|$$
    Nilai kecocokan ini kemudian diolah ke dalam rumus normalisasi, pembobotan, penentuan solusi ideal positif/negatif, serta perhitungan jarak euclidean TOPSIS untuk menghasilkan nilai preferensi final $V_i$ (persentase kedekatan solusi ideal 0%-100%).
