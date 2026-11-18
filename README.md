# Piknik Enthusiast & Lombok Enthusiast — Multi-Linktree (Laravel)

Aplikasi Multi-Linktree berbasis **Laravel** untuk mengelola dua halaman Linktree sekaligus melalui satu halaman Admin (`/admin`):
1. **Piknik Enthusiast** (Jawa Timur) — diakses di `/` atau `/piknik`
2. **Lombok Enthusiast** (Pulau Lombok) — diakses di `/lombok`

## Fitur Utama
- **Satu Admin untuk 2 Linktree (`/admin`)**: Kelola profil, tombol CTA, media sosial, menu tautan, daftar destinasi, serta tema & gradien warna masing-masing Linktree secara terpisah.
- **Penyimpanan Database Server (`SQLite` / `MySQL`)**: Setiap klik tombol **Simpan Live** menyimpan perubahan langsung ke tabel `linktree_contents`.
- **Insight Pengunjung & Generator Link Pelacakan**: Melacak jumlah pengunjung unik & tampilan harian (WIB), sumber trafik (`Instagram`, `TikTok`, `WhatsApp`, `Facebook`, `YouTube`, `Google`), perangkat, dan lokasi secara terpisah untuk Piknik dan Lombok.

## Cara Menjalankan di Lokal
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

- **Piknik Enthusiast**: `http://127.0.0.1:8000/`
- **Lombok Enthusiast**: `http://127.0.0.1:8000/lombok`
- **Halaman Admin**: `http://127.0.0.1:8000/admin` (Password default: `langsungakses`)
