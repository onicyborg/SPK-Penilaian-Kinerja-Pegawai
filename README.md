# SPK Penilaian Kinerja Karyawan

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="200" alt="Laravel Logo">
</p>

<p align="center">
  <strong>Sistem Pendukung Keputusan (SPK) untuk Penilaian Kinerja Karyawan</strong><br>
  Dibangun dengan Laravel 10 & PostgreSQL
</p>

---

## 📋 Tentang Project

SPK Penilaian Kinerja Karyawan adalah sistem berbasis web yang dirancang untuk membantu perusahaan melakukan evaluasi dan penilaian kinerja karyawan secara objektif, terstruktur, dan transparan. Sistem ini menggunakan metode pengambilan keputusan berbasis kriteria (SAW) untuk menghasilkan ranking kinerja karyawan yang adil dan terukur.

### 🔥 Status Terbaru
- Dashboard sudah **dinamis, modern, dan informatif**: menampilkan ringkasan karyawan, periode penilaian, top performer, distribusi kategori kinerja (pie chart), dan aktivitas terakhir.
- Halaman hasil penilaian (ranking) sudah terintegrasi dengan perhitungan SAW dan breakdown detail.
- Workflow utama: input kriteria → penilaian karyawan → proses SAW → tampilkan hasil dan dashboard.

### 🖥️ Fitur Dashboard
- **Summary Karyawan**: total, aktif, nonaktif
- **Summary Periode Penilaian**: draft, aktif, selesai
- **Periode Selesai Terakhir**: statistik karyawan dinilai dan kriteria
- **Top 3 Performer**: ranking karyawan terbaik periode terakhir
- **Distribusi Kinerja**: pie chart kategori (Excellent, Very Good, dst)
- **Aktivitas Terbaru**: audit log penilaian dan perubahan sistem

### 📝 Catatan Troubleshooting
- Jika migrasi gagal, coba `php artisan migrate:fresh --seed`
- Jika tampilan error/berantakan, jalankan `php artisan cache:clear` dan `php artisan config:clear`
- Untuk pengembangan frontend, pastikan sudah menjalankan `php artisan storage:link` agar foto karyawan tampil

### ✨ Fitur Utama
- 📊 Dashboard analitik kinerja karyawan
- 👥 Manajemen data karyawan
- 📝 Sistem penilaian berbasis kriteria
- 📈 Laporan dan ranking kinerja
- 🔐 Sistem autentikasi dan autorisasi
- 📱 Responsive design

### 🛠️ Teknologi yang Digunakan
- **Backend:** Laravel 10
- **Database:** PostgreSQL
- **Frontend:** Blade Templates, Bootstrap
- **PHP Version:** ^8.1

---

## 🚀 Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan project ini di local environment Anda:

### 📋 Prasyarat
Pastikan Anda telah menginstal:
- PHP >= 8.1
- Composer
- PostgreSQL
- Git

### 1️⃣ Clone Repository
```bash
git clone https://github.com/onicyborg/SPK-Penilaian-Kinerja-Pegawai.git
cd SPK-Penilaian-Kinerja-Pegawai
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Setup Environment
```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4️⃣ Konfigurasi Database
Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=spk_penilaian_kinerja_karyawan
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5️⃣ Buat Database
Buat database PostgreSQL dengan nama `spk_penilaian_kinerja_karyawan`:

```sql
-- Login ke PostgreSQL sebagai superuser
psql -U postgres

-- Buat database
CREATE DATABASE spk_penilaian_kinerja_karyawan;

-- Keluar dari psql
\q
```

### 6️⃣ Migrasi Database
```bash
# Jalankan migrasi
php artisan migrate

# Jalankan seeder
php artisan db:seed UserSeeder
php artisan db:seed EmployeeSeeder

```

### 7️⃣ Setup Storage Link
```bash
php artisan storage:link
```

### 9️⃣ Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000` anda dapat login menggunakan user bawaan yaitu :
```
Username : admin
Password : password123
```

---

## 🔧 Konfigurasi Tambahan

### Cache Configuration
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### File Permissions (Linux/Mac)
```bash
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

---

## 📚 Dokumentasi

### Struktur Database
- `users` - Data pengguna sistem
- `employees` - Data karyawan
- `criteria` - Kriteria penilaian
- `assessments` - Data penilaian kinerja
- `periods` - Periode penilaian

### ERD
![ERD](https://raw.githubusercontent.com/onicyborg/SPK-Penilaian-Kinerja-Pegawai/main/public/ERD.png)

[Link ERD](https://www.dbdiagram.io/d/688da52fcca18e685ce9d8e3)

---

## 🤝 Kontribusi

Kami menerima kontribusi dari siapa saja! Silakan:
1. Fork repository ini
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📞 Kontak

Jika Anda memiliki pertanyaan atau saran, silakan hubungi:
- **Email:** [akhmadfauzy40@gmail.com]
- **GitHub:** [onicyborg](https://github.com/onicyborg)

---

## 🙏 Acknowledgments

- [Laravel Framework](https://laravel.com)
- [PostgreSQL](https://postgresql.org)
- Semua kontributor yang telah membantu pengembangan project ini

---

## 📐 Rumus dan Contoh Perhitungan Metode SAW

Aplikasi ini menggunakan metode Simple Additive Weighting (SAW) untuk menentukan ranking kinerja karyawan berdasarkan nilai penilaian dan bobot kriteria.

### 1. Normalisasi Matriks Penilaian
Setiap nilai penilaian karyawan untuk kriteria tertentu dinormalisasi dengan rumus:


$R_{ij} = \frac{X_{ij}}{X_{j}^{max}}$

- $R_{ij}$ = nilai normalisasi untuk karyawan ke- $i$ pada kriteria ke- $j$
- $X_{ij}$ = nilai asli karyawan ke- $i$ pada kriteria ke- $j$
- $X_{j}^{max}$ = nilai maksimum seluruh karyawan pada kriteria ke- $j$

### 2. Hitung Skor Akhir
Setelah normalisasi, skor akhir setiap karyawan dihitung dengan menjumlahkan hasil normalisasi yang dikalikan bobot masing-masing kriteria:


$S_i = \sum_{j=1}^{n} (w_j \times R_{ij})$

- $S_i$ = skor akhir karyawan ke- $i$
- $w_j$ = bobot kriteria ke- $j$ (dalam desimal, misal 40% = 0.4)
- $R_{ij}$ = nilai normalisasi karyawan ke- $i$ pada kriteria ke- $j$
- $n$ = jumlah kriteria

### 3. Ranking
Karyawan diurutkan berdasarkan skor akhir $S_i$ secara menurun. Skor tertinggi mendapat peringkat pertama.

---

### Contoh Perhitungan SAW

Misal ada 3 karyawan (A, B, C) dan 2 kriteria:
- Kriteria 1 (K1), bobot 60%
- Kriteria 2 (K2), bobot 40%

| Karyawan | Nilai K1 | Nilai K2 |
|----------|----------|----------|
| A        |   80     |   70     |
| B        |   90     |   80     |
| C        |   70     |   60     |

**Langkah 1: Normalisasi**
- Nilai maksimum K1 = 90, K2 = 80
- Normalisasi A: $R_A1 = 80/90 = 0.89$, $R_A2 = 70/80 = 0.88$
- Normalisasi B: $R_B1 = 90/90 = 1.00$, $R_B2 = 80/80 = 1.00$
- Normalisasi C: $R_C1 = 70/90 = 0.78$, $R_C2 = 60/80 = 0.75$

**Langkah 2: Hitung Skor Akhir**
- Skor A = (0.6 × 0.89) + (0.4 × 0.88) = 0.534 + 0.352 = **0.886**
- Skor B = (0.6 × 1.00) + (0.4 × 1.00) = 0.6 + 0.4 = **1.00**
- Skor C = (0.6 × 0.78) + (0.4 × 0.75) = 0.468 + 0.3 = **0.768**

**Ranking:**
1. B (1.00)
2. A (0.886)
3. C (0.768)

---

Dengan metode ini, penilaian kinerja menjadi objektif dan transparan sesuai bobot dan nilai yang diberikan.
