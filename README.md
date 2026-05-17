# Aplikasi Pemesanan dan Persetujuan Kendaraan Operasional

Aplikasi manajemen pemesanan kendaraan operasional perusahaan berbasis web dengan sistem persetujuan bertingkat (_multi-level approval_) yang melibatkan Admin, Approver 1 (Manager Operasional), dan Approver 2 (General Manager).

---

## 🛠️ Spesifikasi Sistem (Tech Stack)

- **Framework:** Laravel v11.x (atau v10.x sesuai dependensi proyek Anda)
- **PHP Version:** v8.2 atau v8.3+ (Direkomendasikan untuk Laravel terbaru)
- **Database Version:** MySQL v8.0+ atau MariaDB v10.4+
- **Driver Database:** `mysql`
- **Penyimpanan Session & Cache:** Database (`SESSION_DRIVER=database`, `CACHE_STORE=database`)

---

## 🔐 Akun Akses Default (Kredensial)

Berdasarkan data dari `UserSeeder`, berikut adalah akun tiruan yang dapat digunakan untuk masuk ke sistem sesuai hak akses (_role_):

| Nama Pengguna           | Email / Username      | Password   | Role / Hak Akses                         |
| :---------------------- | :-------------------- | :--------- | :--------------------------------------- |
| **Admin Nickel**        | `admin@nikel.com`     | `password` | **Admin** (Penginput Data & Monitoring)  |
| **Manager Operasional** | `approver1@nikel.com` | `password` | **Approver 1** (Persetujuan Tahap Awal)  |
| **General Manager**     | `approver2@nikel.com` | `password` | **Approver 2** (Persetujuan Tahap Akhir) |

---

## 🚀 Panduan Instalasi & Konfigurasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di komputer lokal Anda:

1. **Persiapan Project**
   Pastikan folder proyek sudah diekstrak atau diunduh ke direktori server lokal Anda.

2. **Instal Dependensi PHP (Composer)**
   Buka terminal di dalam root folder proyek, lalu jalankan perintah:

```bash
composer install
```

3. **Konfigurasi File .env**

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:am7NNN1kG1Or/YYX9U0Nx6x/b8WKGPX+LcZUzFNWNJg=
APP_DEBUG=true
APP_URL=http://localhost

DB*CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=technicaltest
DB_USERNAME=root
DB_PASSWORD=your_password
```

\_Catatan: Buat database kosong bernama `technicaltest` di phpMyAdmin sebelum lanjut ke langkah berikutnya.\*

4. **Migrasi Struktur & Data Awa (Seeder)**
   Jalankan perintah ini untuk membuat seluruh tabel sistem beserta akun default di atas secara otomatis:

```bash
    php artisan migrate --seed
```

5. **Jalankan Aplikasi**

```bash
    php artisan serve
```

Akses aplikasi melalui browser di alamat: **`http://localhost:8000`**

---

## 📖 Panduan Alur Penggunaan Aplikasi (Workflow)

Sistem ini menggunakan logika birokrasi berantai. Berikut adalah tahapan operasionalnya:

### 1. Peran Admin (`admin@nikel.com`)

- **Input Booking:** Masuk ke menu Pemesanan, buat data baru dengan menentukan Kendaraan, Driver, serta memilih **Approver 1** dan **Approver 2** yang bertanggung jawab.
- **Monitor Dashboard:** Memantau tren grafik total pemesanan bulanan dan diagram persentase pemakaian kendaraan yang aktif.
- **Unduh Laporan Excel:**
    - _Export Semua:_ Mengunduh seluruh riwayat tanpa filter.
    - _Export Periodik:_ Mengunduh laporan terjadwal tahunan yang datanya otomatis dikelompokkan rapi per bulan.
- **Proteksi Penghapusan:** Admin hanya bisa menghapus data pesanan selama statusnya masih `pending`. Jika salah satu approver sudah merespons, tombol hapus dikunci demi validitas audit data.

### 2. Peran Approver 1 (`approver1@nikel.com`)

- **Pemeriksaan Dokumen:** Log in ke dashboard untuk melihat antrean tugas masuk khusus pesanan yang berstatus `pending`.
- **Aksi Persetujuan:**
    - Klik **Setuju** untuk menaikkan status menjadi `approved_lvl_1`. Data akan diteruskan ke dashboard Approver 2.
    - Klik **Tolak** jika pengajuan tidak valid. Status langsung berubah permanen menjadi `rejected`.
- **Pembatalan Aksi (Cancel):** Selama Approver 2 belum mengambil keputusan, Approver 1 dapat membatalkan persetujuannya untuk mengembalikan status ke posisi `pending`.

### 3. Peran Approver 2 (`approver2@nikel.com`)

- **Persetujuan Final:** Hanya dapat melihat dan merespons pesanan yang telah lolos dari verifikasi awal (`approved_lvl_1`).
- **Aksi Selesai:** Klik **Setuju (Final)** untuk menyelesaikan alur birokrasi. Status berubah menjadi `approved_final` dan kendaraan resmi dialokasikan untuk operasional.
- **Penurunan Status:** Dapat membatalkan keputusan final untuk mengembalikan status ke posisi `approved_lvl_1`.

---
