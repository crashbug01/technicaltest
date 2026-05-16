# Aplikasi Pemesanan dan Persetujuan Kendaraan Operasional

Aplikasi berbasis web untuk mengelola pemesanan kendaraan operasional perusahaan, dilengkapi dengan sistem persetujuan bertingkat (_multi-level approval_) oleh dua tingkat atasan.

---

## 🛠️ Spesifikasi Sistem (Tech Stack)

- **Framework:** Laravel v10.x / v11.x (PHP Framework)
- **Bahasa Pemrograman:** PHP v8.2+
- **Database:** MySQL v8.0+ / MariaDB v10.4+
- **Template UI:** AdminLTE v4 (Bootstrap 5)

---

## 🔐 Akun Akses Default (Kredensial)

Gunakan akun di bawah ini untuk menguji coba aplikasi berdasarkan masing-masing hak akses (_role_):

| Role / Hak Akses     | Username / Email         | Password      | Kegunaan                                                      |
| :------------------- | :----------------------- | :------------ | :------------------------------------------------------------ |
| **Super Admin**      | `admin@perusahaan.com`   | `password123` | Mengelola kendaraan, driver, dan input pesanan baru.          |
| **Approver Level 1** | `atasan1@perusahaan.com` | `password123` | Memberikan persetujuan tahap awal (_pending_ -> _level 1_).   |
| **Approver Level 2** | `atasan2@perusahaan.com` | `password123` | Memberikan persetujuan akhir (_level 1_ -> _final approved_). |

---

## 🚀 Panduan Instalasi Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan aplikasi di lingkungan lokal (_localhost_):

1. **Clone atau Ekstrak Proyek**
   Pastikan folder proyek sudah berada di direktori web server Anda.

2. **Instal Dependensi PHP (Composer)**
   Buka terminal di dalam folder proyek, lalu jalankan:
    ```bash
    composer install
    ```

Konfigurasi Environment (.env)
Salin file .env.example menjadi .env:
