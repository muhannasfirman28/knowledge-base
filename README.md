# Muhammad Annas Firman — Portfolio (Laravel)

Personal portfolio of Muhammad Annas Firman, Full-Stack Web Developer (Information Systems graduate, strong interest in backend), Serang, Banten, Indonesia.

**Design direction:** Swiss / Editorial. Light theme only. Warm paper background (`#ECE9E2`), single vermilion accent (`#FF4A1C`), visible 12-column grid, oversized typography, mono labels, 1px rules.

## Stack

- **Backend:** PHP 8.2+, Laravel 12, MySQL
- **Frontend:** Laravel Blade, HTML5, CSS3, Vanilla JavaScript
- **Animation & 3D:** GSAP + ScrollTrigger, Lenis, Three.js
- **Build:** Vite (Tailwind CSS v4)
- **Localization:** Laravel built-in localization (`lang/id/`, `lang/en/`)

No React, No Next.js, No Vue, No TypeScript, No component library.

## Requirements

- PHP 8.2+
- Composer
- Node.js 18+ & npm
- MySQL (optional — portfolio falls back to language files if DB unavailable)

## Installation

```bash
# 1. Masuk ke folder proyek
cd "C:\Users\Muhammad Annas\Downloads"

# 2. Install PHP dependencies
composer install --no-interaction

# 3. Salin .env.example menjadi .env
cp .env.example .env

# 4. Generate APP_KEY
php artisan key:generate

# 5. (Opsional) Jalankan migration jika MySQL tersedia
php artisan migrate --force

# 6. Install JS dependencies
npm install

# 7. Build frontend assets
npm run build
```

## Running

```bash
# Laravel development server
php artisan serve --port=8000

# Vite dev server (untuk development aset)
npm run dev

# Buka di browser
# http://localhost:8000/portfolio
```

## Configuration

Edit `.env` untuk menyesuaikan:
- `APP_NAME`, `APP_URL`
- `APP_LOCALE=id` (default Bahasa Indonesia)
- Database MySQL (opsional)
- Mail SMTP untuk contact form

## Bilingual Content

Semua teks tersimpan dalam file lokalisasi:
- `lang/id/portfolio.php` — Bahasa Indonesia
- `lang/en/portfolio.php` — English

Perubahan bahasa disimpan di session dan preserved saat navigate / refresh.

Pengguna dapat berbahasa melalui tombol **ID | EN** di navbar.

## Contact Form

Contact form dihalaman portfolio menggunakan Laravel validation + CSRF protection. Email akan dikirim jika SMTP dikonfigurasi di `.env`. Jika belum, pesan hanya dicatat ke log dan ditampilkan flash message sukses.

## Portrait Component

Komponen portrait ada di `resources/views/components/portrait-slot.blade.php`. Secara default berjalan dalam mode **illustration** (SVG geometris editorial).

Untuk beralih ke mode **photo** (tidak perlu mengubah section lain):
1. Letakkan `public/photo/portrait.jpg` dan opsional `public/photo/portrait-cutout.png`
2. Sesuaikan `portrait-slot.blade.php` untuk render `` alih-nya SVG

## Project Screenshots

Project cards menggunakan **screenshot placeholder 16:9** (outline box). Ganti dengan screenshot proyek nyata di `public/images/` atau `public/screenshots/`.

## Deploy

```bash
npm run build      # Vite production build → dist/
# Unggah isi dist/ dan semua file Laravel ke web server
# Konfigurasi web server untuk menjalankan Laravel (public/ sebagai document root)
```

## Project Structure

```text
app/
├── Http/
│   └── Controllers/
│       ├── PortfolioController.php
│       ├── LanguageController.php
│       └── ContactController.php
resources/
├── views/
│   ├── layouts/portfolio.blade.php
│   ├── components/
│   │   ├── navbar.blade.php
│   │   ├── footer.blade.php
│   │   ├── language-switcher.blade.php
│   │   └── portrait-slot.blade.php
│   ├── sections/
│   │   ├── hero.blade.php
│   │   ├── about.blade.php
│   │   ├── experience.blade.php
│   │   ├── projects.blade.php
│   │   ├── skills.blade.php
│   │   ├── education.blade.php
│   │   ├── contact.blade.php
│   │   └── preloader.blade.php
│   ├── sections/partials/project-card.blade.php
│   ├── pages/project-detail.blade.php
│   └── portfolio.blade.php
├── css/app.css
└── js/app.js
routes/
└── web.php          # /portfolio, /portfolio/project/{key}, /contact, /locale/{locale}
lang/
├── id/portfolio.php
└── en/portfolio.php
```

## Sections

01 Hero — Nama besar, judul, ID\|EN switcher, PortraitSlot, scroll hint  
02 About — Summary, pull statement, fakta kecil, PortraitSlot (kecil), animated counters  
03 Experience — Diskominfo Banten, indexed responsibilities, tech tags  
04 Projects — 2 proyek editorial, screenshot slot 16:9, horizontal scroll, detail route  
05 Skills — 5 grup (Backend, Frontend, Database, Tools, Additional), tanpa persen  
06 Education & Certification — S1 Sistem Informasi, sertifikasi kosong state  
07 Contact — Email, telepon, lokasi, social disabled, contact form  
