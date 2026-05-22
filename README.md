# Clipfluence

Clipfluence adalah platform berbasis web yang dibangun menggunakan **Laravel 12** dan **TailwindCSS 4**.

## Prasyarat

Sebelum menginstal dan menjalankan website ini, pastikan sistem Anda telah terinstal:
- **PHP** >= 8.2
- **Composer** (untuk dependensi backend PHP)
- **Node.js** dan **npm** (untuk dependensi frontend JS/CSS)
- Server Database (MySQL / MariaDB / SQLite / PostgreSQL dll). Penggunaan **Laragon** sangat disarankan jika Anda menggunakan Windows.
- **FFmpeg** (untuk video processing)
- **yt-dlp** (untuk download video dari YouTube/TikTok)

### Instalasi FFmpeg dan yt-dlp

**Windows (menggunakan WinGet):**
```bash
winget install FFmpeg
winget install yt-dlp
```

**Windows (menggunakan Chocolatey):**
```bash
choco install ffmpeg
choco install yt-dlp
```

**Linux (Ubuntu/Debian):**
```bash
sudo apt update
sudo apt install ffmpeg
sudo apt install yt-dlp
```

**macOS (menggunakan Homebrew):**
```bash
brew install ffmpeg
brew install yt-dlp
```

> **Catatan:** Setelah instalasi, restart terminal Anda dan verifikasi dengan menjalankan `ffmpeg -version` dan `yt-dlp --version`

---

## Langkah-Langkah Menjalankan Website (Lokal)

Ikuti langkah-langkah di bawah ini untuk mengonfigurasi dan menjalankan project Clipfluence di komputer Anda:

### 1. Kloning Repository (Opsional)
Jika Anda mengambil source code melalui Git, lakukan cloning:
```bash
git clone https://github.com/hafisc/clipfluence.git
cd clipfluence
```
*(Lewati langkah ini jika Anda sudah berada di dalam folder source code)*

### 2. Instalasi Dependensi PHP (Backend)
Jalankan perintah Composer di terminal untuk menginstal kerangka kerja Laravel dan package pihak ketiga lainnya:
```bash
composer install
```

### 3. Instalasi Dependensi NPM (Frontend)
Jalankan NPM untuk menginstal library Javascript, Vite, dan Tailwind CSS:
```bash
npm install
```

### 4. Konfigurasi Environment (`.env`)
Laravel membutuhkan file `.env` untuk pengaturan dasar (seperti database). Anda perlu menyalinnya dari file contoh:
```bash
cp .env.example .env
```
*(Pengguna Windows di Command Prompt bisa menggunakan `copy .env.example .env` atau dapat men-copy paste file secara manual).*

Setelah `.env` dibuat, **buka file tersebut** dan sesuaikan baris *Database* (biasanya di baris-baris awal). Contoh penggunaan MySQL dengan Laragon standar:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clipfluence
DB_USERNAME=root
DB_PASSWORD=
```
> **Catatan:** Pastikan Anda sudah membuat database kosong bernama `clipfluence` pada HeidiSQL / phpMyAdmin agar aplikasi bisa terkoneksi dengan sukses. Jika ingin langkah yang instan, ubah `DB_CONNECTION=sqlite` dan hapus konfigurasi koneksi DB lainnya.

### 5. Generate Application Key
Lakukan generate kunci keamanan utama aplikasi Laravel dengan perintah:
```bash
php artisan key:generate
```

### 6. Migrasi Database
Buat dan susun tabel-tabel di database (tabel User, dll) menggunakan fitur migrasi Laravel dengan perintah:
```bash
php artisan migrate
```
*(Bila ada prompt/konfirmasi pembuatan database saat menjalankan migrasi, ketik `yes`)*

### 7. Konfigurasi Queue Driver
Untuk fitur AI Auto-Clipper, aplikasi menggunakan queue system. Ubah konfigurasi queue di file `.env`:
```env
QUEUE_CONNECTION=database
```

Kemudian jalankan migrasi untuk membuat tabel jobs:
```bash
php artisan migrate
```

### 8. Menjalankan Server Pengembangan (Dev Server)
Karena project ini menggunakan Vite untuk kompilasi CSS (Tailwind) dan Javascript, serta Queue Worker untuk processing video, Anda perlu menjalankan **3 terminal** secara bersamaan:

**Terminal 1 - Laravel Server (Backend):**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server (Frontend):**
```bash
npm run dev
```

**Terminal 3 - Queue Worker (Video Processing):**
```bash
php artisan queue:work --timeout=300 --tries=1
```

> **PENTING:** Queue Worker (Terminal 3) **WAJIB** dijalankan agar fitur AI Auto-Clipper berfungsi. Tanpa queue worker, video tidak akan diproses dan akan stuck di status "Menunggu Antrian".

**Tips Queue Worker:**
- Jika Anda melakukan perubahan code di `app/Jobs/`, restart queue worker dengan: `php artisan queue:restart` kemudian jalankan ulang `php artisan queue:work`
- Untuk membersihkan antrian yang gagal: `php artisan queue:clear`
- Untuk test satu job saja: `php artisan queue:work --once`

### 9. Selesai 🎉
Buka browser dan kunjungi: **http://127.0.0.1:8000** atau **http://localhost:8000**.
Jika Anda menggunakan fitur Auto Virtual Hosts Laragon, Anda juga bisa langsung mengakses alamat **http://clipfluence.test**.

**Pastikan 3 terminal tetap berjalan:**
- ✅ Terminal 1: `php artisan serve`
- ✅ Terminal 2: `npm run dev`
- ✅ Terminal 3: `php artisan queue:work --timeout=300 --tries=1`

---

## Tumpukan Teknologi (Tech Stack)
- **Framework Utama:** [Laravel 12](https://laravel.com/)
- **Frontend / Styling:** [Tailwind CSS 4](https://tailwindcss.com/)
- **Bundler:** [Vite](https://vitejs.dev/)
- **HTTP Client (AJAX):** [Axios](https://axios-http.com/)
- **Video Processing:** [FFmpeg](https://ffmpeg.org/)
- **Video Downloader:** [yt-dlp](https://github.com/yt-dlp/yt-dlp)
- **AI API:** [Groq](https://groq.com/) (untuk analisis video)

---

## Troubleshooting

### Video tidak diproses / stuck di "Menunggu Antrian"
**Solusi:** Pastikan queue worker berjalan di terminal ke-3:
```bash
php artisan queue:work --timeout=300 --tries=1
```

### Error "FFmpeg not found" atau "yt-dlp not found"
**Solusi:** Install FFmpeg dan yt-dlp sesuai petunjuk di bagian Prasyarat, kemudian restart terminal.

### Queue worker error setelah update code
**Solusi:** Restart queue worker:
```bash
php artisan queue:restart
php artisan queue:work --timeout=300 --tries=1
```

### Membersihkan antrian yang gagal
```bash
php artisan queue:clear
```

### Test queue worker dengan 1 job saja
```bash
php artisan queue:work --once
```

---

## Fitur Utama

### 🎬 AI Auto-Clipper
Fitur unggulan yang memungkinkan kreator untuk:
- Paste link video dari YouTube, TikTok, atau platform lainnya
- AI akan menganalisis dan memotong bagian terbaik secara otomatis
- Pilih orientasi: Vertical (9:16) untuk TikTok/Reels atau Horizontal (16:9) untuk YouTube
- Pilih kualitas: SD (480p), HD (720p), atau Full HD (1080p)
- Pilih durasi clip: 30, 60, atau 90 detik
- Generate 1-5 clip sekaligus
- Auto-Captions: Subtitle otomatis dari audio
- Real-time progress tracking dengan 4 tahap: Analyzing → Downloading → Processing → Uploading
- Riwayat generate dengan filter dan download ulang

### 👥 Role Management
- **Brand:** Buat campaign, kelola budget, review submission
- **Kreator:** Join campaign, submit konten, withdraw earnings
- **Admin:** Kelola users, campaigns, dan transaksi

### 💰 Finance System
- Deposit untuk Brand
- Withdrawal untuk Kreator
- Integrasi Midtrans payment gateway

---

## Kontribusi
Pull requests are welcome! Untuk perubahan besar, silakan buka issue terlebih dahulu untuk mendiskusikan apa yang ingin Anda ubah.

## Lisensi
[MIT](https://choosealicense.com/licenses/mit/)
