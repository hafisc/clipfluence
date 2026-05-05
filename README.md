<div align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">

  <h1>✨ Clipfluence ✨</h1>
  
  <p><strong>Platform Berbasis Web Inovatif untuk Kolaborasi Brand dan Kreator</strong></p>

  <p>
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
    <a href="https://tailwindcss.com/"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="TailwindCSS"></a>
    <a href="https://www.php.net/"><img src="https://img.shields.io/badge/PHP_8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP"></a>
    <a href="https://vitejs.dev/"><img src="https://img.shields.io/badge/Vite-B73BFE?style=for-the-badge&logo=vite&logoColor=FFD62E" alt="Vite"></a>
  </p>
</div>

---

## 🚀 Fitur Utama
- 🌟 **Dashboard Premium** untuk Brand dan Kreator.
- 💳 **Integrasi Midtrans** untuk sistem pembayaran yang aman.
- 🔒 **Google OAuth** untuk proses registrasi dan login yang mulus.
- 🎨 **UI/UX Modern** menggunakan TailwindCSS 4.

---

## 🛠️ Prasyarat (*Prerequisites*)

Sebelum menginstal dan menjalankan website ini, pastikan sistem Anda telah memiliki perangkat lunak berikut:

| Kebutuhan | Versi Minimal | Keterangan |
| --------- | ------------- | ---------- |
| **PHP** | `>= 8.2` | Bahasa pemrograman utama backend. |
| **Composer** | `Latest` | Dependency manager untuk PHP. |
| **Node.js** | `>= 18.0` | Environment Javascript untuk frontend (Vite). |
| **NPM** | `Latest` | Node Package Manager untuk dependensi frontend. |
| **Database**| `MySQL 8 / SQLite`| Penggunaan **Laragon** sangat direkomendasikan untuk pengguna Windows. |

---

## ⚙️ Cara Menjalankan Project (Local Setup)

Ikuti langkah-langkah di bawah ini untuk mengonfigurasi dan menjalankan aplikasi Clipfluence di komputer Anda:

<details>
<summary><b>1. Kloning Repository (Opsional)</b></summary>

Jika Anda belum mengunduh source code, lakukan *cloning* melalui terminal:
```bash
git clone https://github.com/hafisc/clipfluence.git
cd clipfluence
```
</details>

<details>
<summary><b>2. Install Dependencies (PHP & Node)</b></summary>

Instal paket backend dan frontend yang dibutuhkan aplikasi:

```bash
# 1. Install dependensi backend (Laravel)
composer install

# 2. Install dependensi frontend (Vite & Tailwind)
npm install
```
</details>

<details>
<summary><b>3. Konfigurasi Environment (`.env`)</b></summary>

Salin file konfigurasi bawaan agar dapat diatur sesuai perangkat lokal Anda:

```bash
cp .env.example .env
```
*(Pengguna Windows CMD dapat menggunakan perintah: `copy .env.example .env`)*

Buka file `.env` dan atur koneksi database Anda (Contoh menggunakan MySQL Laragon):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clipfluence
DB_USERNAME=root
DB_PASSWORD=
```
> 💡 **Tip:** Pastikan Anda membuat database kosong bernama `clipfluence` pada HeidiSQL/phpMyAdmin. Jika ingin cepat tanpa MySQL, ubah `DB_CONNECTION=sqlite` dan hapus variabel koneksi lainnya.
</details>

<details>
<summary><b>4. Generate Key & Migrasi Database</b></summary>

Buat kunci enkripsi aplikasi dan buat struktur tabel database beserta data awal (*seed*):

```bash
# Generate app key
php artisan key:generate

# Migrasi tabel dan seeding data
php artisan migrate --seed
```
</details>

<details>
<summary><b>5. Konfigurasi Storage & Serve! 🚀</b></summary>

Hubungkan storage gambar dan jalankan server pengembangan:

```bash
# Link storage folder
php artisan storage:link

# Jalankan server backend & frontend secara bersamaan
composer run dev
```

> **Cara Alternatif (Buka 2 Terminal):**
> Terminal 1: `php artisan serve`
> Terminal 2: `npm run dev`
</details>

---

## 🎉 Selesai!

Jika semuanya berhasil, buka browser Anda dan akses aplikasi melalui:

👉 **[http://localhost:8000](http://localhost:8000)** atau **[http://clipfluence.test](http://clipfluence.test)** (jika menggunakan Laragon Auto Virtual Host).

---

<div align="center">
  <i>Dibuat dengan ❤️ oleh Kelompok 4.</i>
</div>
