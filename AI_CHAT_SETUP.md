# Panduan Setup AI Chat Customer Service - Clipfluence

## 📋 Persyaratan

Untuk mengaktifkan AI Chat Customer Service, Anda memerlukan:

1. **GROQ API Key** - untuk akses ke Groq AI API (free tier tersedia)
2. **Konfigurasi .env** yang tepat

---

## 🔧 Langkah-Langkah Setup

### 1. Dapatkan GROQ API Key

1. Kunjungi: https://console.groq.com/keys
2. Login atau buat akun Groq (gratis)
3. Buat API key baru
4. Copy API key tersebut

### 2. Konfigurasi .env File

Buka file `.env` di root project Clipfluence dan tambahkan/ubah konfigurasi berikut:

```env
# ===== AI Chat Configuration =====
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxxxx  # Ganti dengan API key Anda dari Groq
```

Contoh lengkap variabel yang diperlukan:

```env
APP_NAME=Clipfluence
APP_ENV=local
APP_KEY=base64:xxxxxxxxxxxxxxxxxxxxxxxxxxxx
APP_DEBUG=true
APP_URL=http://localhost

# ... konfigurasi lainnya ...

# AI Chat - GROQ API
GROQ_API_KEY=gsk_xxxxxxxxxxxxxxxxxxxxxx
```

### 3. Restart Server Laravel

Jika server sudah berjalan, restart untuk apply perubahan .env:

```bash
# Jika menggunakan Artisan server
# Ctrl+C untuk stop, lalu jalankan lagi:
php artisan serve

# Atau jika menggunakan Laravel Valet / Laragon
# Tinggal refresh browser
```

---

## ✅ Testing AI Chat

1. Buka website Clipfluence
2. Klik tombol **"Chat dengan AI"** di pojok kanan bawah
3. Coba kirim pesan seperti:
   - "Bagaimana cara daftar sebagai brand?"
   - "Apa syarat untuk submit konten?"
   - "Bagaimana sistem pembayaran di Clipfluence?"

### Respon yang Diharapkan:
AI akan menjawab dengan ramah dan profesional dalam Bahasa Indonesia.

---

## 🐛 Troubleshooting

### Problem 1: Chat Tidak Merespons
**Solusi:**
- Cek apakah GROQ_API_KEY sudah benar di `.env`
- Buka browser console (F12) dan lihat error message
- Cek file log di `storage/logs/laravel.log`

### Problem 2: Error "AI sedang sibuk"
**Solusi:**
- API Groq sedang overload, coba lagi dalam beberapa detik
- Pastikan internet connection stabil
- Cek status Groq API di https://status.groq.com

### Problem 3: "Terjadi kesalahan teknis"
**Solusi:**
- Cek `.env` apakah GROQ_API_KEY sudah set
- Pastikan tidak ada typo di API key
- Cek Laravel logs: `tail -f storage/logs/laravel.log`

### Debug Logs
Untuk melihat error lebih detail, periksa file log:
```bash
# Linux/Mac
tail -f storage/logs/laravel.log

# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50 -Wait
```

---

## 📊 Fitur AI Chat Customer Service

AI Chat kami dilengkapi dengan:

✅ **Customer Service Role**
- Menjawab pertanyaan tentang platform
- Menjelaskan fitur-fitur Clipfluence
- Memberikan panduan pendaftaran dan usage

✅ **Ramah & Professional**
- Jawaban singkat dan to-the-point
- Bahasa Indonesia yang baik
- Referensi ke WhatsApp support jika diperlukan

✅ **Smart Context**
- Mempertahankan riwayat chat
- Memahami konteks percakapan
- Memberikan jawaban yang konsisten

✅ **Error Handling**
- Graceful error messages
- Fallback ke WhatsApp support
- Detailed logging untuk debugging

---

## 📞 Contact & Support

Jika ada masalah yang tidak bisa diselesaikan:

1. **WhatsApp Support**: [Link WhatsApp Anda]
2. **Email**: support@clipfluence.com
3. **Check Logs**: `storage/logs/laravel.log`

---

## 🔐 Security Notes

⚠️ **PENTING:**
- **Jangan share GROQ_API_KEY** di public repositories
- Selalu gunakan `.env` untuk sensitive data
- Update API key jika terjadi exposure
- Gunakan `.env.example` sebagai template saja

---

## 📌 Referensi

- [Groq Console](https://console.groq.com)
- [Groq API Documentation](https://console.groq.com/docs/quickstart)
- [Groq Status](https://status.groq.com)
- [Laravel .env Documentation](https://laravel.com/docs/configuration)

---

**Last Updated**: 2026-05-27  
**Status**: ✅ Ready for Production
