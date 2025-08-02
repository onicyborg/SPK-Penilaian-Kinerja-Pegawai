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

# Jalankan seeder (jika tersedia)
php artisan db:seed --class=DataSeeder
```

### 7️⃣ Setup Storage Link
```bash
php artisan storage:link
```

### 9️⃣ Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

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

### API Endpoints
Dokumentasi API akan tersedia setelah implementasi lengkap.

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
