# Linktree — Piknik Enthusiast

Halaman Linktree sederhana, elegan, dan ramah pengguna untuk **Piknik Enthusiast** (`https://piknikenthusiast.com/`), mencakup **seluruh 11 destinasi wisata** beserta tampilan **Admin** yang mendukung penyimpanan live via **Netlify Blobs**.

## Struktur Proyek

- `public/index.html` — Halaman publik Linktree untuk pengunjung.
- `public/admin.html` — Tampilan Admin dengan *live mobile preview* (dilindungi login password).
- `public/data.js` — Data konten & konfigurasi awal (*fallback* offline/lokal).
- `public/renderer.js` — Mesin render bersama dan sinkronisasi cloud/lokal.
- `public/styles.css` & `public/admin.css` — Stylesheet halaman publik dan admin.
- `netlify/functions/content.mts` — Serverless Function (`/api/content`) untuk membaca dan menyimpan perubahan Admin secara live ke Netlify Blobs.
- `netlify.toml` — Konfigurasi publish (`public/`) dan bundling Functions.

## Cara Menggunakan di Netlify (Live)

1. Buka alamat situs Netlify Anda untuk melihat halaman publik.
2. Buka `/admin` dan masukkan password admin.
3. Ubah profil, menu tautan, destinasi, atau tampilan, lalu klik **Simpan Live**. Perubahan langsung tampil untuk seluruh pengunjung.
4. Anda dapat mengganti password Admin kapan saja di tab **Tampilan & Data**.
