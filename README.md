<p align="center">
  <img src="https://cdn.myanimelist.net/images/anime/10/47347.jpg" width="100%" alt="AmPlay Hero Image" style="border-radius: 12px; object-fit: cover; max-height: 300px;">
</p>

<h1 align="center">AmPlay - Nonstop Anime Streaming</h1>

<p align="center">
  <a href="#"><img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel"></a>
  <a href="#"><img src="https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white" alt="Python Flask"></a>
  <a href="#"><img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS"></a>
</p>

AmPlay adalah aplikasi web *streaming* dan penjelajahan anime yang fleksibel, interaktif, dan sangat ringan. Dibangun dengan memadukan keandalan **Laravel (PHP)** sebagai kerangka kerja utama dan **Flask (Python)** sebagai *backend scraper* dinamis. 

Antarmuka aplikasi ini dirancang khusus menggunakan konsep *Glassmorphism*, dilengkapi elemen teks dinamis, dan animasi 2D interaktif berbasis *HTML5 Canvas* untuk menghadirkan pengalaman navigasi tingkat tinggi.

## ✨ Fitur Utama

- **Live Search & Filter Cerdas**: Cari anime berdasarkan kata kunci, *genre*, atau tipe (TV, Movie, OVA) secara *real-time* dengan antarmuka dinamis.
- **Pemutar Video Terintegrasi**: Tonton episode anime favorit Anda langsung di dalam aplikasi dengan dukungan pilihan bahasa (Sub/Dub).
- **Sistem Cadangan (Fallback Mode)**: Web tetap responsif dan menampilkan data cadangan (*dummy data*) secara otomatis jika *server* sumber mengalami *timeout* atau diblokir.
- **Animasi Interaktif 2D**: Dilengkapi partikel kursor interaktif, efek *3D-to-2D tilt* pada poster, dan transisi elemen visual yang super halus tanpa membebani memori peramban.
- **Sistem Autentikasi Pengguna**: Fitur registrasi dan *login* aman untuk mengelola akun pribadi.
- **Riwayat Tontonan & Komentar**: Lanjutkan episode yang terakhir ditonton dengan mudah dan bagikan pendapat Anda di kolom komentar setiap anime.

## 🛠️ Teknologi yang Digunakan

*   **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript, HTML5 Canvas, Vanilla-Tilt.js.
*   **Backend (Core)**: Laravel (PHP).
*   **Backend (Scraper)**: Python (Flask, Cloudscraper, BeautifulSoup).
*   **Database**: MySQL / SQLite.

## 🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan AmPlay di lingkungan pengembangan lokal Anda:

### 1. Kloning Repositori
```bash
git clone [https://github.com/USERNAME_ANDA/amplay.git](https://github.com/USERNAME_ANDA/amplay.git)
cd amplay
