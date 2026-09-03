<p align="center">
  <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)">
    <circle cx="12" cy="12" r="12" fill="url(#grad1)"/>
    <path d="M10 7.5L17 12L10 16.5V7.5Z" fill="white"/>
    <defs>
      <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
        <stop offset="0%" style="stop-color:#facc15;stop-opacity:1" />
        <stop offset="100%" style="stop-color:#f97316;stop-opacity:1" />
      </linearGradient>
    </defs>
  </svg>
</p>

<h1 align="center">AmPlay</h1>
<p align="center"><strong>Nonstop Anime Streaming & Exploration</strong></p>

<p align="center">
  <a href="#"><img src="[https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)" alt="Laravel"></a>
  <a href="#"><img src="[https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white](https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white)" alt="Python Flask"></a>
  <a href="#"><img src="[https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)" alt="Tailwind CSS"></a>
</p>

AmPlay adalah aplikasi web *streaming* dan penjelajahan anime yang fleksibel, interaktif, dan sangat ringan. Dibangun dengan memadukan keandalan **Laravel (PHP)** sebagai kerangka kerja utama dan **Flask (Python)** sebagai *backend scraper* dinamis. 

Antarmuka aplikasi ini dirancang khusus menggunakan konsep *Glassmorphism*, dilengkapi elemen teks dinamis, dan animasi 2D interaktif berbasis *HTML5 Canvas* untuk menghadirkan pengalaman navigasi tingkat tinggi.

## ✨ Fitur Utama

- **Live Search & Filter Cerdas**: Cari anime berdasarkan kata kunci, *genre*, atau tipe (TV, Movie, OVA) secara *real-time* dengan antarmuka dinamis.
- **Pemutar Video Terintegrasi**: Tonton episode anime favorit Anda langsung di dalam aplikasi dengan dukungan pilihan bahasa (Sub/Dub).
- **Sistem Cadangan (Fallback Mode)**: Web tetap responsif dan menampilkan data cadangan secara otomatis jika *server* sumber mengalami *timeout* atau pemblokiran jaringan.
- **Animasi Interaktif 2D**: Dilengkapi partikel kursor interaktif, efek *3D-to-2D tilt* pada poster, dan transisi elemen visual yang super halus tanpa membebani memori peramban.
- **Sistem Autentikasi Pengguna**: Fitur registrasi dan *login* aman untuk mengelola akun pribadi.
- **Riwayat Tontonan & Komentar**: Lanjutkan episode yang terakhir ditonton dengan mudah dan bagikan pendapat di kolom komentar setiap anime.

## 🛠️ Teknologi yang Digunakan

*   **Frontend**: HTML5, Tailwind CSS, Vanilla JavaScript, HTML5 Canvas, Vanilla-Tilt.js.
*   **Backend (Core)**: Laravel (PHP).
*   **Backend (Scraper)**: Python (Flask, Cloudscraper, BeautifulSoup).
*   **Database**: MySQL / SQLite.

## 🚀 Instalasi & Konfigurasi

Ikuti langkah-langkah berikut untuk menjalankan AmPlay di lingkungan pengembangan lokal Anda:

### 1. Kloning Repositori
```bash
git clone https://github.com/USERNAME_ANDA/amplay.git
cd amplay
```

### 2. Pengaturan Laravel (PHP)
Pastikan Anda telah menginstal PHP dan Composer.
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### 3. Pengaturan Flask (Python)
Pastikan Anda telah menginstal Python.
```bash
pip install flask cloudscraper beautifulsoup4
```

### 4. Instalasi Dependensi Frontend
```bash
npm install
```

### 5. Menjalankan Server
AmPlay menggunakan skrip khusus untuk menjalankan server Laravel, *compiler* Vite (Tailwind), dan server Flask secara bersamaan.
```bash
npm run start:all
```
Aplikasi kini dapat diakses melalui `http://localhost:8000`. Jika ingin mengakses melalui perangkat lain di jaringan WiFi yang sama, gunakan alamat IP perangkat Anda beserta *port* 8000.

## 👨‍💻 Pengembang

Dikembangkan oleh **Pranaja Abi Praya Widya Tamaka**, Program Keahlian Rekayasa Perangkat Lunak, SMKN 5 Surakarta.

---

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
