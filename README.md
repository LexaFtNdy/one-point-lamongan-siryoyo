# Gym One Point Lamongan - Sistem Rekomendasi Personal Trainer

Sistem Informasi berbasis web untuk merekomendasikan Personal Trainer (PT) di Gym One Point Lamongan. Dibangun menggunakan arsitektur modern (Monolith SPA) untuk memenuhi kebutuhan Skripsi dengan mengimplementasikan algoritma **Multi-Dimensional Weighted Scoring** dan sistem pemesanan (booking) 3 Role.

## 🚀 Teknologi yang Digunakan
- **Backend:** Laravel 11 (PHP)
- **Frontend:** Vue.js 3 + Inertia.js (Composition API)
- **Styling:** Tailwind CSS + Headless UI / Phosphor Icons
- **Database:** MySQL

## 🔑 Fitur Utama
1. **Sistem 3 Role terintegrasi:** Admin, Trainer, dan Member.
2. **Algoritma Rekomendasi (Fuzzy Match):** Menggunakan pembobotan multi-dimensi (Fisik 30%, Medis 25%, Logistik 25%, Psikologis 20%). Jika kriteria ketat tidak terpenuhi, sistem otomatis mencari *Alternatif Trainer* terbaik.
3. **Manajemen Booking:** Alur booking sesi latihan, konfirmasi/penolakan oleh PT, hingga pembatalan oleh Member (dengan pesan kustom).
4. **Keamanan & Skalabilitas:**
   - Soft Deletes untuk menjaga riwayat transaksi.
   - Proteksi Route menggunakan middleware ketat.
   - *Activity Log* yang lengkap untuk laporan Admin.

---

## 🛠️ Cara Instalasi & Setup Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal:

### 1. Kebutuhan Sistem
Pastikan Anda sudah menginstal:
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL (Bisa pakai XAMPP/Laragon)

### 2. Clone Repository
```bash
git clone https://github.com/LexaFtNdy/one-point-lamongan-siryoyo.git
cd one-point-lamongan-siryoyo
```

### 3. Install Dependensi
Buka terminal di dalam folder project dan jalankan:
```bash
composer install
npm install
```

### 4. Setup Environment Variable
Duplikat file `.env.example` dan ubah namanya menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan koneksi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skripsi_gym_lamongan
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key
```bash
php artisan key:generate
```

### 6. Migrasi Database & Seeder
Perintah ini akan membuat tabel di database sekaligus mengisi data *dummy* untuk 3 Role:
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Server
Buka 2 tab terminal. Terminal pertama untuk menjalankan server backend Laravel:
```bash
php artisan serve
```
Terminal kedua untuk mem-build aset frontend (Vue/Vite):
```bash
npm run dev
```
Aplikasi sekarang bisa diakses di `http://localhost:8000`.

---

## 👥 Akun Testing (Database Seeder)

Gunakan akun berikut untuk menguji sistem (Password untuk semua akun: `password`):

| Role | Email | Deskripsi |
| :--- | :--- | :--- |
| **Admin** | `admin@gym.com` | Dashboard laporan, suspend user, activity log. |
| **Trainer** | `trainer1@gym.com` | (PT Male) Harga 150k, Ahli Bulking/Rehab. |
| **Trainer** | `trainer2@gym.com` | (PT Female) Harga 100k, Ahli Weight Loss. |
| **Member** | `member@gym.com` | User biasa untuk mencoba booking dan rekomendasi. |

---

## 📝 Catatan Untuk Skripsi (UAT)
Aplikasi ini sudah disesuaikan dengan alur *Use Case* dokumen skripsi:
- Pastikan **BAB 3 dan 4** diubah untuk merefleksikan penggunaan *Weighted Scoring* (bukan *Certainty Factor*).
- Fitur pembatalan (*cancel*) dan pesan *booking* sudah ditambahkan untuk melengkapi alur interaksi.
- Jangan pernah menghapus tabel secara manual, gunakan `php artisan migrate:fresh --seed` jika ingin mereset data ke keadaan semula.
