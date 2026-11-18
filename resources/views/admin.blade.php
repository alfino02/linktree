<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Linktree — Piknik Enthusiast</title>
  <link rel="icon" href="https://piknikenthusiast.com/logo3f_optimized.webp" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  <link rel="stylesheet" href="{{ asset('admin.css') }}" />
</head>
<body class="adm-body">

  <!-- =========================================================
       PASSWORD LOGIN GATE
       ========================================================= -->
  <div id="passwordGate" class="adm-gate">
    <div class="adm-gate-card">
      <div class="adm-gate-logo">
        <img src="https://piknikenthusiast.com/logo3f_optimized.webp" alt="Piknik Enthusiast" />
      </div>
      <h1 class="adm-gate-title">Admin Linktree</h1>
      <p class="adm-gate-sub">Masukkan password untuk mengelola Linktree Piknik Enthusiast &amp; Lombok Enthusiast.</p>

      <form id="passwordForm">
        <div class="adm-password-wrap">
          <input id="passwordInput" type="password" maxlength="64" class="adm-password-input" placeholder="Masukkan password admin..." autocomplete="current-password" autofocus required />
          <button type="button" id="togglePasswordVis" class="adm-password-toggle" aria-label="Tampilkan password">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        <p id="passwordError" class="adm-password-error hidden">Password tidak sesuai. Silakan coba lagi.</p>
        <button type="submit" class="adm-btn adm-btn-primary" style="width:100%;padding:11px 16px;">Masuk ke Admin</button>
      </form>

      <p class="adm-gate-hint">Gunakan password admin Anda (dapat diganti di tab Tampilan &amp; Data)</p>
      <a href="{{ url('/') }}" class="adm-gate-back">← Kembali ke Halaman Publik</a>
    </div>
  </div>

  <!-- =========================================================
       ADMIN DASHBOARD SHELL
       ========================================================= -->
  <div id="adminApp" class="hidden">
    <header class="adm-header">
      <div class="adm-header-inner">
        <div class="adm-brand">
          <div class="adm-brand-logo">
            <img id="headerLogo" src="https://piknikenthusiast.com/logo3f_optimized.webp" alt="Logo" />
          </div>
          <div class="adm-brand-text">
            <h1 class="adm-brand-title">Admin Linktree</h1>
            <div class="adm-status-pill">
              <span class="adm-status-dot"></span>
              <span id="saveStatusText">Tersimpan otomatis di browser</span>
            </div>
          </div>
        </div>

        <div class="adm-header-actions">
          <button type="button" id="btnOpenMobilePreview" class="adm-btn adm-mobile-preview-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="7" y="2" width="10" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
            <span>Preview</span>
          </button>
          <a id="btnOpenPublicPage" href="{{ url('/') }}" target="_blank" class="adm-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            <span id="btnOpenPublicLabel" class="adm-btn-text-hide-sm">Buka Halaman</span>
          </a>
          <button type="button" id="btnPublishData" class="adm-btn adm-btn-accent">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <span id="btnPublishLabel">Simpan data.js</span>
          </button>
          <button type="button" id="btnLockAdmin" class="adm-icon-btn" title="Kunci Admin" aria-label="Kunci Admin">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 1 1 8 0v4"/></svg>
          </button>
        </div>
      </div>
    </header>

    <div class="adm-workspace">
      <!-- LEFT: EDITOR -->
      <main>
        <div class="adm-site-bar" role="region" aria-label="Pilih Linktree yang Dikelola">
          <div class="adm-site-bar-label">
            <span class="adm-site-bar-kicker">Pilih Linktree yang Dikelola</span>
            <span id="activeSiteUrlHint" class="adm-site-bar-path">Aktif: Piknik Enthusiast · Halaman publik / (index.html)</span>
          </div>
          <div id="siteSwitcher" class="adm-site-switcher" role="tablist" aria-label="Daftar Linktree">
            <button type="button" class="adm-site-btn is-active" data-site="piknik">
              <span class="adm-site-dot"></span>
              <span class="adm-site-btn-text">
                <strong>Piknik Enthusiast</strong>
                <small>Jawa Timur · /</small>
              </span>
            </button>
            <button type="button" class="adm-site-btn" data-site="lombok">
              <span class="adm-site-dot"></span>
              <span class="adm-site-btn-text">
                <strong>Lombok Enthusiast</strong>
                <small>Pulau Lombok · /lombok</small>
              </span>
            </button>
          </div>
        </div>

        <nav class="adm-tabs" role="tablist" aria-label="Menu Admin">
          <button type="button" class="adm-tab is-active" data-tab="profile">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Profil &amp; CTA</span>
          </button>
          <button type="button" class="adm-tab" data-tab="links">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <span>Menu Tautan</span>
          </button>
          <button type="button" class="adm-tab" data-tab="destinations">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-6-5.33-6-10a6 6 0 1 1 12 0c0 4.67-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/></svg>
            <span>Destinasi (<span id="destTabCount">11</span>)</span>
          </button>
          <button type="button" class="adm-tab" data-tab="appearance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9c.26.6.79 1.02 1.51 1H21a2 2 0 1 1 0 4h-.09c-.72-.02-1.25.4-1.51 1z"/></svg>
            <span>Tampilan &amp; Data</span>
          </button>
          <button type="button" class="adm-tab" data-tab="insights">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 16l4-4 4 3 5-6"/></svg>
            <span>Insight <span id="insightTabBadge" class="adm-tab-badge hidden">0</span></span>
          </button>
        </nav>

        <!-- TAB 1: PROFIL & CTA -->
        <section id="tab-profile" class="adm-tab-panel">
          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Identitas Brand</h2>
                <p class="adm-card-desc">Tampil di bagian paling atas halaman Linktree</p>
              </div>
              <label class="adm-switch" title="Badge Terverifikasi">
                <input type="checkbox" id="inpVerified" />
                <span class="adm-switch-track"></span>
              </label>
            </div>

            <div class="adm-form-grid">
              <div class="adm-field is-full">
                <label class="adm-label">Logo / Foto Profil</label>
                <div class="adm-media-row">
                  <img id="avatarPreview" class="adm-media-thumb is-contain" src="" alt="Preview logo" />
                  <div class="adm-media-controls">
                    <input type="url" id="inpAvatar" class="adm-input" placeholder="https://..." />
                    <label class="adm-btn adm-btn-sm" style="cursor:pointer;">
                      Upload
                      <input type="file" id="fileAvatar" accept=".svg,image/*" class="hidden" />
                    </label>
                  </div>
                </div>
              </div>

              <div class="adm-field is-full">
                <label class="adm-label">Foto Sampul Header (Banner)</label>
                <div class="adm-media-row">
                  <img id="coverPreview" class="adm-media-thumb is-wide" src="" alt="Preview sampul" />
                  <div class="adm-media-controls">
                    <input type="url" id="inpCoverImage" class="adm-input" placeholder="https://... (kosongkan untuk gradien saja)" />
                    <label class="adm-btn adm-btn-sm" style="cursor:pointer;">
                      Upload
                      <input type="file" id="fileCoverImage" accept="image/*" class="hidden" />
                    </label>
                  </div>
                </div>
                <div class="adm-url-presets">
                  <button type="button" class="adm-chip-btn" data-cover-preset="https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/ecf41e47-4f42-4e76-8d8c-31816fa74d3a.webp">Gunung Bromo</button>
                  <button type="button" class="adm-chip-btn" data-cover-preset="https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasi/B8222BFF-5180-4416-A183-E64A3E47B5BE_1782281542_1782576434.webp">Kawah Ijen</button>
                  <button type="button" class="adm-chip-btn" data-cover-preset="https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/0236a2e8-fa35-44df-96c1-2934b6233b4b.webp">Tumpak Sewu</button>
                  <button type="button" class="adm-chip-btn" data-cover-preset="https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fd3382bd-58dc-42fc-85a9-bbd82d2c5c90.webp">Green Island</button>
                  <button type="button" class="adm-chip-btn" data-cover-preset="">Gradien Saja</button>
                </div>
              </div>

              <div class="adm-field">
                <label class="adm-label" for="inpName">Nama Brand</label>
                <input type="text" id="inpName" class="adm-input" placeholder="Piknik Enthusiast" />
              </div>

              <div class="adm-field">
                <label class="adm-label" for="inpLocation">Lokasi &amp; Jam Buka (Chip Atas)</label>
                <input type="text" id="inpLocation" class="adm-input" placeholder="Dau, Kab. Malang · 09.00–17.00 WIB" />
              </div>

              <div class="adm-field is-full">
                <label class="adm-label" for="inpTagline">Tagline Singkat</label>
                <input type="text" id="inpTagline" class="adm-input" placeholder="Open Trip & Private Trip Jawa Timur..." />
              </div>

              <div class="adm-field is-full">
                <label class="adm-label" for="inpBio">Bio / Deskripsi</label>
                <textarea id="inpBio" class="adm-textarea" placeholder="Deskripsi singkat layanan..."></textarea>
              </div>

              <div class="adm-field is-full">
                <label class="adm-label" for="inpMapsUrl">Link Google Maps Kantor</label>
                <input type="url" id="inpMapsUrl" class="adm-input" placeholder="https://maps.app.goo.gl/..." />
              </div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Tombol Utama (CTA)</h2>
                <p class="adm-card-desc">Kartu prioritas di bawah profil (direct link website atau WhatsApp)</p>
              </div>
              <label class="adm-switch" title="Aktifkan Tombol CTA">
                <input type="checkbox" id="inpCtaEnabled" />
                <span class="adm-switch-track"></span>
              </label>
            </div>

            <div class="adm-form-grid">
              <div class="adm-field is-full">
                <label class="adm-label" for="inpCtaUrl">URL Tujuan Langsung (Kosongkan untuk pakai Nomor WhatsApp)</label>
                <input type="url" id="inpCtaUrl" class="adm-input" placeholder="https://piknikenthusiast.com/id/paket-wisata" />
              </div>
              <div class="adm-field">
                <label class="adm-label" for="inpCtaPhone">Nomor WhatsApp (Cadangan / Preset)</label>
                <input type="text" id="inpCtaPhone" class="adm-input" placeholder="6282234784612" />
              </div>
              <div class="adm-field">
                <label class="adm-label" for="inpCtaTitle">Judul Tombol</label>
                <input type="text" id="inpCtaTitle" class="adm-input" placeholder="Pesan &amp; Pilih Paket Wisata" />
              </div>
              <div class="adm-field is-full">
                <label class="adm-label" for="inpCtaSub">Subjudul</label>
                <input type="text" id="inpCtaSub" class="adm-input" placeholder="Lihat pilihan Open Trip &amp; Private Trip langsung di website" />
              </div>
              <div class="adm-field is-full">
                <label class="adm-label" for="inpCtaPrefill">Pesan Otomatis WhatsApp (Jika URL kosong)</label>
                <input type="text" id="inpCtaPrefill" class="adm-input" placeholder="Halo Admin Piknik Enthusiast..." />
              </div>
              <div class="adm-field is-full">
                <label class="adm-label" for="inpCtaIcon">Ikon Tombol CTA (Opsional · Bawaan Otomatis)</label>
                <div class="adm-icon-row">
                  <div id="ctaIconPreview" class="adm-icon-preview"></div>
                  <input type="url" id="inpCtaIcon" class="adm-input adm-input-sm adm-icon-url-input" placeholder="Kosongkan untuk bawaan, atau URL SVG / PNG..." />
                  <label class="adm-btn adm-btn-sm" style="cursor:pointer;">
                    Upload SVG / Gambar
                    <input type="file" id="fileCtaIcon" accept=".svg,image/*" class="hidden" />
                  </label>
                  <button type="button" id="btnResetCtaIcon" class="adm-chip-btn">Reset</button>
                </div>
              </div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Ikon Sosial Media &amp; Kontak</h2>
                <p class="adm-card-desc">Atur tautan &amp; unggah ikon SVG atau gambar kustom bila perlu</p>
              </div>
            </div>
            <div id="socialsEditorList"></div>
          </div>
        </section>

        <!-- TAB 2: MENU TAUTAN -->
        <section id="tab-links" class="adm-tab-panel hidden">
          <div class="adm-card">
            <div class="adm-toolbar">
              <div>
                <h2 class="adm-card-title">Menu Tautan Utama</h2>
                <p class="adm-card-desc">Tautan promo, katalog paket, custom trip, galeri, dll.</p>
              </div>
              <button type="button" id="btnAddQuickLink" class="adm-btn adm-btn-primary adm-btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Tautan
              </button>
            </div>
            <div id="quickLinksEditorList" class="adm-item-list"></div>
          </div>
        </section>

        <!-- TAB 3: DESTINASI -->
        <section id="tab-destinations" class="adm-tab-panel hidden">
          <div class="adm-card">
            <div class="adm-toolbar">
              <div class="adm-search-wrap">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                <input type="search" id="adminDestSearch" class="adm-input" placeholder="Cari destinasi untuk diedit..." />
              </div>
              <button type="button" id="btnAddDest" class="adm-btn adm-btn-primary adm-btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Destinasi
              </button>
            </div>
            <div id="destEditorList" class="adm-item-list"></div>
          </div>
        </section>

        <!-- TAB 4: TAMPILAN & DATA -->
        <section id="tab-appearance" class="adm-tab-panel hidden">
          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Tema Warna &amp; Gradien</h2>
                <p class="adm-card-desc">Pilih tema solid, tema gradien siap pakai, atau tambahkan warna custom Anda sendiri</p>
              </div>
            </div>

            <div id="themeOptions">
              <div class="adm-theme-subhead" style="margin-top:0;">Tema Minimalis</div>
              <div class="adm-theme-grid">
                <button type="button" class="adm-theme-opt" data-theme="ivory">
                  <span class="adm-swatch" style="background:#f8f7f4;"><span class="adm-swatch-dot" style="background:#0e2040;"></span></span>
                  <span><span class="adm-theme-name">Ivory Minimal</span><br><span class="adm-theme-desc">Hangat &amp; bersih</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="white">
                  <span class="adm-swatch" style="background:#ffffff;"><span class="adm-swatch-dot" style="background:#ea6c05;"></span></span>
                  <span><span class="adm-theme-name">Pure White</span><br><span class="adm-theme-desc">Putih klasik minimalis</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="sand">
                  <span class="adm-swatch" style="background:#f4efe6;"><span class="adm-swatch-dot" style="background:#c95d0a;"></span></span>
                  <span><span class="adm-theme-name">Warm Sand</span><br><span class="adm-theme-desc">Nuansa earth tone</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="navy">
                  <span class="adm-swatch" style="background:linear-gradient(155deg,#04183a,#1b78ec);"><span class="adm-swatch-dot" style="background:#ffa31a;"></span></span>
                  <span><span class="adm-theme-name">Royal Navy Wave</span><br><span class="adm-theme-desc">Gelombang biru laut elegan</span></span>
                </button>
              </div>

              <div class="adm-theme-subhead">Tema Gradien</div>
              <div class="adm-theme-grid">
                <button type="button" class="adm-theme-opt" data-theme="sunrise">
                  <span class="adm-swatch" style="background:linear-gradient(155deg,#0b162c 0%,#19294c 52%,#522927 100%);"><span class="adm-swatch-dot" style="background:#f48526;"></span></span>
                  <span><span class="adm-theme-name">Sunset Bromo</span><br><span class="adm-theme-desc">Gradien senja &amp; navy</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="coastal">
                  <span class="adm-swatch" style="background:linear-gradient(155deg,#dfeff8 0%,#edf6f6 52%,#f9f0e4 100%);"><span class="adm-swatch-dot" style="background:#0d3b54;"></span></span>
                  <span><span class="adm-theme-name">Ocean Breeze</span><br><span class="adm-theme-desc">Gradien pesisir segar</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="forest">
                  <span class="adm-swatch" style="background:linear-gradient(155deg,#091d18 0%,#12352c 54%,#1e4b3d 100%);"><span class="adm-swatch-dot" style="background:#ee922b;"></span></span>
                  <span><span class="adm-theme-name">Emerald Djawatan</span><br><span class="adm-theme-desc">Gradien hutan tropis</span></span>
                </button>
                <button type="button" class="adm-theme-opt" data-theme="custom">
                  <span class="adm-swatch" style="background:linear-gradient(135deg,#0f2547 0%,#ea6c05 55%,#fbbf24 100%);"><span class="adm-swatch-dot" style="background:#ffffff;"></span></span>
                  <span><span class="adm-theme-name">Warna Custom</span><br><span class="adm-theme-desc">Racik warna &amp; gradien sendiri</span></span>
                </button>
              </div>
            </div>

            <div id="savedThemesSection" class="hidden">
              <div class="adm-theme-subhead">Warna Custom Tersimpan</div>
              <div id="savedThemesList" class="adm-theme-grid"></div>
            </div>

            <div id="customThemePanel" class="adm-custom-panel hidden">
              <div class="adm-form-grid">
                <div class="adm-field">
                  <label class="adm-label">Jenis Latar Custom</label>
                  <div class="adm-seg" id="customBgModeSeg">
                    <button type="button" class="adm-seg-btn" data-bgmode="gradient">Gradasi</button>
                    <button type="button" class="adm-seg-btn" data-bgmode="solid">Warna Solid</button>
                  </div>
                </div>

                <div class="adm-field">
                  <label class="adm-label">Kontras Teks</label>
                  <div class="adm-seg" id="customTextModeSeg">
                    <button type="button" class="adm-seg-btn" data-textmode="auto">Otomatis</button>
                    <button type="button" class="adm-seg-btn" data-textmode="dark">Gelap</button>
                    <button type="button" class="adm-seg-btn" data-textmode="light">Putih</button>
                  </div>
                </div>

                <div class="adm-field">
                  <label class="adm-label" for="custBgStartHex">Warna Utama / Awal</label>
                  <div class="adm-color-pair">
                    <input type="color" id="custBgStartPicker" class="adm-color-picker" aria-label="Pilih warna awal" />
                    <input type="text" id="custBgStartHex" class="adm-input" maxlength="7" placeholder="#0f2547" />
                  </div>
                </div>

                <div class="adm-field" id="custBgEndField">
                  <label class="adm-label" for="custBgEndHex">Warna Akhir (Gradasi)</label>
                  <div class="adm-color-pair">
                    <input type="color" id="custBgEndPicker" class="adm-color-picker" aria-label="Pilih warna akhir" />
                    <input type="text" id="custBgEndHex" class="adm-input" maxlength="7" placeholder="#3b2338" />
                  </div>
                </div>

                <div class="adm-field is-full" id="custAngleField">
                  <label class="adm-label" for="custBgAngleRange">Arah Gradasi</label>
                  <div class="adm-url-presets" style="margin-top:0;">
                    <button type="button" class="adm-chip-btn" data-angle="145">Diagonal (145°)</button>
                    <button type="button" class="adm-chip-btn" data-angle="180">Atas → Bawah (180°)</button>
                    <button type="button" class="adm-chip-btn" data-angle="90">Kiri → Kanan (90°)</button>
                    <button type="button" class="adm-chip-btn" data-angle="35">Menanjak (35°)</button>
                  </div>
                  <div class="adm-range-row">
                    <input type="range" id="custBgAngleRange" class="adm-range" min="0" max="360" step="5" value="145" />
                    <span id="custBgAngleVal" class="adm-angle-badge">145°</span>
                  </div>
                </div>

                <div class="adm-field">
                  <label class="adm-label" for="custAccentHex">Warna Aksen (Tagline &amp; Badge)</label>
                  <div class="adm-color-pair">
                    <input type="color" id="custAccentPicker" class="adm-color-picker" aria-label="Pilih warna aksen" />
                    <input type="text" id="custAccentHex" class="adm-input" maxlength="7" placeholder="#f48526" />
                  </div>
                </div>

                <div class="adm-field">
                  <label class="adm-label" for="custCtaHex">Warna Tombol WhatsApp</label>
                  <div class="adm-color-pair">
                    <input type="color" id="custCtaPicker" class="adm-color-picker" aria-label="Pilih warna tombol WhatsApp" />
                    <input type="text" id="custCtaHex" class="adm-input" maxlength="7" placeholder="#ea6c05" />
                  </div>
                </div>
              </div>

              <div class="adm-save-custom-row">
                <input type="text" id="inpNewThemeName" class="adm-input" placeholder="Nama tema baru (opsional, mis. Bromo Senja)" />
                <button type="button" id="btnSaveCustomTheme" class="adm-btn adm-btn-sm" style="white-space:nowrap;">+ Tambah ke Daftar Tema</button>
              </div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Gaya Latar &amp; Visual</h2>
                <p class="adm-card-desc">Agar halaman punya tekstur dan foto suasana tanpa terlihat berlebihan</p>
              </div>
            </div>
            <div id="bgStyleOptions" class="adm-theme-grid">
              <button type="button" class="adm-theme-opt" data-bg="wave">
                <span class="adm-swatch" style="background:linear-gradient(155deg,#04183a,#1b78ec);"><span class="adm-swatch-dot" style="background:#ffa31a;"></span></span>
                <span><span class="adm-theme-name">Foto &amp; Gelombang</span><br><span class="adm-theme-desc">Sampul foto + gelombang sutra</span></span>
              </button>
              <button type="button" class="adm-theme-opt" data-bg="panorama">
                <span class="adm-swatch" style="background:linear-gradient(135deg,#0e2040,#c25500);"><span class="adm-swatch-dot" style="background:#ffffff;"></span></span>
                <span><span class="adm-theme-name">Foto &amp; Topografi</span><br><span class="adm-theme-desc">Sampul foto + kontur peta halus</span></span>
              </button>
              <button type="button" class="adm-theme-opt" data-bg="topography">
                <span class="adm-swatch" style="background:linear-gradient(135deg,#f1ede4,#e3ebf5);"><span class="adm-swatch-dot" style="background:#ea6c05;"></span></span>
                <span><span class="adm-theme-name">Topografi Halus</span><br><span class="adm-theme-desc">Kontur gunung &amp; gradasi lembut</span></span>
              </button>
              <button type="button" class="adm-theme-opt" data-bg="mesh">
                <span class="adm-swatch" style="background:radial-gradient(circle at top right,rgba(234,108,5,.28),rgba(20,118,185,.22));"><span class="adm-swatch-dot" style="background:#0e2040;"></span></span>
                <span><span class="adm-theme-name">Gradasi Atmosfer</span><br><span class="adm-theme-desc">Gradasi halus tanpa pola garis</span></span>
              </button>
              <button type="button" class="adm-theme-opt" data-bg="clean">
                <span class="adm-swatch" style="background:#f8f7f4;"><span class="adm-swatch-dot" style="background:#cbd5e1;"></span></span>
                <span><span class="adm-theme-name">Polos Minimalis</span><br><span class="adm-theme-desc">Warna solid sederhana</span></span>
              </button>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-form-grid">
              <div class="adm-field">
                <label class="adm-label">Tata Letak Destinasi</label>
                <div class="adm-seg" id="layoutSeg">
                  <button type="button" class="adm-seg-btn" data-layout="list">List Rapi</button>
                  <button type="button" class="adm-seg-btn" data-layout="grid">Grid 2 Kolom</button>
                </div>
              </div>

              <div class="adm-field">
                <label class="adm-label">Sudut Kartu</label>
                <div class="adm-seg" id="radiusSeg">
                  <button type="button" class="adm-seg-btn" data-radius="rounded">Rounded</button>
                  <button type="button" class="adm-seg-btn" data-radius="pill">Pill</button>
                  <button type="button" class="adm-seg-btn" data-radius="minimal">Minimal</button>
                </div>
              </div>
            </div>

            <div style="margin-top:18px;">
              <div class="adm-switch-row">
                <div>
                  <div class="adm-social-label">Tampilkan Harga Mulai</div>
                  <div class="adm-help">Menampilkan estimasi harga pada tiap kartu destinasi</div>
                </div>
                <label class="adm-switch"><input type="checkbox" id="optShowPrice" /><span class="adm-switch-track"></span></label>
              </div>
              <div class="adm-switch-row">
                <div>
                  <div class="adm-social-label">Tampilkan Filter Kategori</div>
                  <div class="adm-help">Tombol filter Semua, Gunung &amp; Kawah, Pantai &amp; Pulau, Alam &amp; Kota</div>
                </div>
                <label class="adm-switch"><input type="checkbox" id="optShowFilter" /><span class="adm-switch-track"></span></label>
              </div>
              <div class="adm-switch-row">
                <div>
                  <div class="adm-social-label">Tampilkan Kolom Pencarian</div>
                  <div class="adm-help">Kotak cari destinasi di halaman publik</div>
                </div>
                <label class="adm-switch"><input type="checkbox" id="optShowSearch" /><span class="adm-switch-track"></span></label>
              </div>
              <div class="adm-switch-row">
                <div>
                  <div class="adm-social-label">Tampilkan Badge (Promo / Populer)</div>
                  <div class="adm-help">Label kecil pada menu atau destinasi unggulan</div>
                </div>
                <label class="adm-switch"><input type="checkbox" id="optShowBadges" /><span class="adm-switch-track"></span></label>
              </div>
            </div>

            <div class="adm-field" style="margin-top:14px;">
              <label class="adm-label" for="inpFooterText">Teks Footer</label>
              <input type="text" id="inpFooterText" class="adm-input" placeholder="PT Asthara Asia Group · Malang, Jawa Timur" />
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Publish &amp; Backup Data</h2>
                <p class="adm-card-desc">Terbitkan perubahan ke hosting atau cadangkan konfigurasi</p>
              </div>
            </div>

            <div id="publishNotice" class="adm-notice">
              Setiap editan langsung tersimpan otomatis di browser ini untuk preview. Untuk memperbarui halaman bagi semua pengunjung di hosting, klik <strong>Simpan / Download data.js</strong> lalu gantikan file <code>data.js</code> di folder proyek atau server Anda.
            </div>

            <div class="adm-btn-row">
              <button type="button" id="btnPublishCloud2" class="adm-btn adm-btn-primary hidden">Simpan Live ke Server</button>
              <button type="button" id="btnPublishData2" class="adm-btn adm-btn-accent">Download data.js</button>
              <button type="button" id="btnExportJson" class="adm-btn">Export JSON</button>
              <label class="adm-btn" style="cursor:pointer;">
                Import JSON
                <input type="file" id="fileImportJson" accept=".json,application/json" class="hidden" />
              </label>
              <button type="button" id="btnResetDefault" class="adm-btn adm-btn-danger">Reset Default</button>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Keamanan Password Admin</h2>
                <p class="adm-card-desc">Ubah password akses untuk membuka halaman /admin</p>
              </div>
            </div>
            <div class="adm-inline-submit-row">
              <input type="password" id="inpNewPassword" class="adm-input" maxlength="64" autocomplete="new-password" placeholder="Masukkan password baru (min. 4 karakter)" />
              <button type="button" id="btnSavePassword" class="adm-btn adm-btn-primary">Simpan Password</button>
            </div>
          </div>
        </section>

        <!-- TAB 5: INSIGHT PENGUNJUNG -->
        <section id="tab-insights" class="adm-tab-panel hidden">
          <div id="insightOfflineNotice" class="adm-notice hidden">
            Statistik pengunjung real-time aktif ketika halaman dibuka melalui hosting <strong>Netlify</strong>. Saat dibuka offline lewat <code>file://</code>, metrik cloud tidak dapat dimuat.
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Insight Pengunjung</h2>
                <p id="insightUpdatedText" class="adm-card-desc">Statistik pengunjung halaman utama · Zona waktu WIB</p>
              </div>
              <div class="adm-insight-head-actions">
                <button type="button" id="btnRefreshInsights" class="adm-btn">Muat Ulang</button>
              </div>
            </div>

            <div class="adm-kpi-grid">
              <div class="adm-kpi-card is-highlight">
                <div class="adm-kpi-label">Hari Ini (WIB)</div>
                <div id="kpiTodayVisitors" class="adm-kpi-value">-</div>
                <div id="kpiTodayViews" class="adm-kpi-sub">Pengunjung unik · - tampilan</div>
              </div>
              <div class="adm-kpi-card">
                <div class="adm-kpi-label">7 Hari Terakhir</div>
                <div id="kpi7dVisitors" class="adm-kpi-value">-</div>
                <div id="kpi7dViews" class="adm-kpi-sub">Pengunjung unik · - tampilan</div>
              </div>
              <div class="adm-kpi-card">
                <div class="adm-kpi-label">30 Hari Terakhir</div>
                <div id="kpi30dVisitors" class="adm-kpi-value">-</div>
                <div id="kpi30dViews" class="adm-kpi-sub">Pengunjung unik · - tampilan</div>
              </div>
              <div class="adm-kpi-card">
                <div class="adm-kpi-label">Total Semua Waktu</div>
                <div id="kpiAllVisitors" class="adm-kpi-value">-</div>
                <div id="kpiAllViews" class="adm-kpi-sub">Pengunjung unik · - tampilan</div>
              </div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Tren 14 Hari Terakhir</h2>
                <p class="adm-card-desc">Ketuk atau klik batang grafik untuk melihat rincian harian</p>
              </div>
              <div class="adm-chart-legend">
                <span class="adm-legend-item"><span class="adm-legend-dot"></span>Hari sebelumnya</span>
                <span class="adm-legend-item"><span class="adm-legend-dot is-today"></span>Hari ini</span>
              </div>
            </div>
            <div id="insightChartBars" class="adm-chart-bars"></div>
            <div id="insightBarDetail" class="adm-bar-detail">
              <span>Pilih tanggal pada grafik untuk melihat rincian kunjungan.</span>
            </div>
          </div>

          <div class="adm-insight-split">
            <div class="adm-card">
              <div class="adm-card-head">
                <div>
                  <h2 class="adm-card-title">Sumber Trafik</h2>
                  <p class="adm-card-desc">Asal rujukan / UTM pengunjung 30 hari &amp; arsip</p>
                </div>
              </div>
              <div id="insightSourcesList" class="adm-stat-list"></div>
            </div>

            <div class="adm-card">
              <div class="adm-card-head">
                <div>
                  <h2 class="adm-card-title">Perangkat &amp; Lokasi</h2>
                  <p class="adm-card-desc">Distribusi perangkat dan lokasi kunjungan terbaru</p>
                </div>
              </div>
              <div id="insightDevicesList" class="adm-stat-list"></div>
              <div class="adm-insight-subhead">Lokasi Teratas</div>
              <div id="insightLocationsList" class="adm-stat-list"></div>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Link Khusus per Platform</h2>
                <p class="adm-card-desc">Salin dan tempel link di bawah sesuai platform agar sumber pengunjung terbaca tepat</p>
              </div>
            </div>

            <div id="insightTrackLinksGrid" class="adm-track-grid"></div>

            <div class="adm-track-custom">
              <label class="adm-label" for="inpCustomTrackSource">Buat Link Custom (Penempatan Lain)</label>
              <div class="adm-inline-submit-row">
                <input type="text" id="inpCustomTrackSource" class="adm-input" maxlength="24" placeholder="Contoh: threads, ig-story, qr-brosur" />
                <button type="button" id="btnCopyCustomTrack" class="adm-btn adm-btn-primary" disabled>Salin Link</button>
              </div>
              <div id="customTrackPreview" class="adm-track-custom-preview">Masukkan nama sumber untuk membuat link pelacakan</div>
              <p class="adm-help">Gunakan tag singkat (maks. 24 karakter, huruf kecil/angka/tanda hubung) untuk memisahkan penempatan seperti story, brosur, atau promo khusus.</p>
            </div>
          </div>

          <div class="adm-card">
            <div class="adm-card-head">
              <div>
                <h2 class="adm-card-title">Kunjungan Terbaru</h2>
                <p class="adm-card-desc">Aktivitas kunjungan halaman publik terakhir (WIB)</p>
              </div>
              <button type="button" id="btnResetInsights" class="adm-btn adm-btn-danger">Reset Data Pengunjung</button>
            </div>
            <div id="insightRecentList" class="adm-recent-list"></div>
          </div>
        </section>
      </main>

      <!-- RIGHT: LIVE MOBILE PREVIEW -->
      <aside id="previewDrawer" class="adm-preview-col" aria-label="Live Preview">
        <div id="previewDrawerLabel" class="adm-preview-label">Preview Langsung · Piknik Enthusiast</div>
        <div class="adm-phone">
          <div class="adm-phone-notch"><span class="adm-phone-speaker"></span></div>
          <div id="livePreviewScreen" class="adm-phone-screen"></div>
        </div>
        <button type="button" id="btnCloseMobilePreview" class="adm-btn adm-preview-close">Tutup Preview</button>
      </aside>
    </div>
  </div>

  <script src="{{ asset('data.js') }}"></script>
  <script src="{{ asset('renderer.js') }}"></script>
  <script>
    (function () {
      "use strict";

      var SESSION_KEY = "piknik_admin_unlocked_v1";
      var PASSWORD_SESSION_KEY = "piknik_admin_password_v1";
      var ACTIVE_SITE_SESSION_KEY = "piknik_admin_active_site_v1";

      var initialSiteParam = "";
      try {
        initialSiteParam = new URLSearchParams(window.location.search || "").get("site") || "";
      } catch (e) {}
      var activeSiteId = window.PiknikStore.normalizeSiteId(
        initialSiteParam || sessionStorage.getItem(ACTIVE_SITE_SESSION_KEY) || "piknik"
      );
      window.PiknikStore.setSite(activeSiteId);

      var siteDataMap = {
        piknik: window.PiknikStore.load("piknik"),
        lombok: window.PiknikStore.load("lombok")
      };
      var siteUnsavedMap = {
        piknik: false,
        lombok: false
      };

      var data = siteDataMap[activeSiteId];
      var previewCat = "all";
      var previewSearch = "";
      var expandedLinkId = null;
      var expandedDestId = null;
      var adminDestQuery = "";
      var cloudMode = false;
      var hasUnsavedCloudChanges = false;

      var R = window.PiknikRenderer;

      /* ---------- Utilities ---------- */
      function uid(prefix) {
        return prefix + "-" + Date.now().toString(36) + Math.random().toString(36).slice(2, 5);
      }

      function slugify(str) {
        return String(str || "")
          .toLowerCase()
          .trim()
          .replace(/[^a-z0-9\s-]/g, "")
          .replace(/\s+/g, "-")
          .replace(/-+/g, "-");
      }

      function compressImageFile(file, maxSize, cb, opts) {
        var options = opts || {};
        var isSvg = file.type === "image/svg+xml" || /\.svg$/i.test(file.name || "");
        if (options.allowSvg && isSvg) {
          if (file.size > 250 * 1024) {
            R.showToast("Ukuran file SVG maksimal 250 KB");
            return;
          }
          var svgReader = new FileReader();
          svgReader.onload = function (ev) {
            var encoded = R.svgToDataUrl(ev.target.result);
            if (!encoded) {
              R.showToast("File SVG tidak valid atau kosong");
              return;
            }
            cb(encoded);
          };
          svgReader.readAsText(file);
          return;
        }

        var isIco =
          file.type === "image/x-icon" ||
          file.type === "image/vnd.microsoft.icon" ||
          /\.ico$/i.test(file.name || "");
        if (options.allowSvg && isIco) {
          if (file.size > 150 * 1024) {
            R.showToast("Ukuran file ICO maksimal 150 KB");
            return;
          }
          var icoReader = new FileReader();
          icoReader.onload = function (ev) {
            cb(ev.target.result);
          };
          icoReader.readAsDataURL(file);
          return;
        }

        var reader = new FileReader();
        reader.onload = function (ev) {
          var img = new Image();
          img.onerror = function () {
            R.showToast("Gagal membaca file gambar");
          };
          img.onload = function () {
            var w = img.width || maxSize;
            var h = img.height || maxSize;
            if (w > maxSize || h > maxSize) {
              if (w > h) {
                h = Math.max(1, Math.round((h * maxSize) / w));
                w = maxSize;
              } else {
                w = Math.max(1, Math.round((w * maxSize) / h));
                h = maxSize;
              }
            }
            var canvas = document.createElement("canvas");
            canvas.width = w;
            canvas.height = h;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, w, h);
            var quality = options.preferAlpha ? 0.9 : 0.84;
            var out = canvas.toDataURL("image/webp", quality);
            if (out.indexOf("data:image/webp") !== 0) {
              out = options.preferAlpha
                ? canvas.toDataURL("image/png")
                : canvas.toDataURL("image/jpeg", 0.84);
            }
            cb(out);
          };
          img.src = ev.target.result;
        };
        reader.readAsDataURL(file);
      }

      function syncSaveStatusText(localSaveOk) {
        var statusEl = document.getElementById("saveStatusText");
        if (!statusEl) return;
        var siteMeta = window.PiknikStore.getSiteMeta(activeSiteId);
        if (cloudMode) {
          statusEl.textContent = siteUnsavedMap[activeSiteId]
            ? "Draft lokal (" + siteMeta.shortLabel + ") — klik Simpan Live"
            : "Tersinkron dengan Server Laravel (" + siteMeta.shortLabel + ")";
        } else {
          statusEl.textContent =
            localSaveOk === false
              ? "Gagal menyimpan (storage penuh)"
              : "Tersimpan otomatis di browser (" + siteMeta.shortLabel + ")";
        }
      }

      function syncActiveSiteUI() {
        var siteMeta = window.PiknikStore.getSiteMeta(activeSiteId);
        document.querySelectorAll("#siteSwitcher .adm-site-btn").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-site") === activeSiteId);
        });
        var urlHint = document.getElementById("activeSiteUrlHint");
        if (urlHint) {
          urlHint.textContent =
            "Aktif: " +
            siteMeta.label +
            " · Halaman publik " +
            siteMeta.publicPath +
            " (" +
            siteMeta.localFile +
            ")";
        }
        var openBtn = document.getElementById("btnOpenPublicPage");
        if (openBtn) {
          openBtn.setAttribute("href", window.location.protocol === "file:" ? siteMeta.localFile : siteMeta.publicPath);
          openBtn.setAttribute("title", "Buka halaman " + siteMeta.label);
        }
        var openLbl = document.getElementById("btnOpenPublicLabel");
        if (openLbl) {
          openLbl.textContent = "Buka " + siteMeta.shortLabel;
        }
        var prevLabel = document.getElementById("previewDrawerLabel");
        if (prevLabel) {
          prevLabel.textContent = "Preview Langsung · " + siteMeta.label;
        }
        var isLombok = activeSiteId === "lombok";
        var bgLabels = isLombok
          ? {
              wave: ["Foto, Gelombang & Pulau Lombok", "Sampul foto + siluet Pulau Lombok, 3 Gili & Sasak"],
              panorama: ["Foto & Topografi Pulau Lombok", "Sampul foto + kontur peta Lombok, Rinjani & Gili"],
              topography: ["Topografi Pulau Lombok", "Kontur Pulau Lombok, Rinjani & motif Lumbung Sasak"]
            }
          : {
              wave: ["Foto & Gelombang", "Sampul foto + gelombang sutra"],
              panorama: ["Foto & Topografi", "Sampul foto + kontur peta halus"],
              topography: ["Topografi Halus", "Kontur gunung & gradasi lembut"]
            };
        Object.keys(bgLabels).forEach(function (bgKey) {
          var btn = document.querySelector('#bgStyleOptions [data-bg="' + bgKey + '"]');
          if (!btn) return;
          var nameEl = btn.querySelector(".adm-theme-name");
          var descEl = btn.querySelector(".adm-theme-desc");
          if (nameEl) nameEl.textContent = bgLabels[bgKey][0];
          if (descEl) descEl.textContent = bgLabels[bgKey][1];
        });
        syncSaveStatusText(true);
      }

      function switchManagedSite(nextSiteRaw) {
        var nextId = window.PiknikStore.normalizeSiteId(nextSiteRaw);
        siteDataMap[activeSiteId] = data;
        activeSiteId = window.PiknikStore.setSite(nextId);
        try {
          sessionStorage.setItem(ACTIVE_SITE_SESSION_KEY, activeSiteId);
        } catch (e) {}
        data = siteDataMap[activeSiteId] || window.PiknikStore.load(activeSiteId);
        siteDataMap[activeSiteId] = data;
        hasUnsavedCloudChanges = !!siteUnsavedMap[activeSiteId];
        previewCat = "all";
        previewSearch = "";
        expandedLinkId = null;
        expandedDestId = null;
        adminDestQuery = "";
        var searchDestInp = document.getElementById("searchAdminDest");
        if (searchDestInp) searchDestInp.value = "";
        syncActiveSiteUI();
        refreshAllEditors();
        renderPreview();
        renderTrackLinksGenerator();
        var inpCustom = document.getElementById("inpCustomTrackSource");
        if (inpCustom) inpCustom.dispatchEvent(new Event("input"));
        clearInsightsDisplay();
        loadInsights({ silent: true });
        R.showToast("Mengelola " + window.PiknikStore.getSiteMeta(activeSiteId).label);
      }

      function persistAndPreview() {
        siteDataMap[activeSiteId] = data;
        var ok = window.PiknikStore.save(data, activeSiteId);
        siteUnsavedMap[activeSiteId] = true;
        hasUnsavedCloudChanges = true;
        syncSaveStatusText(ok);
        renderPreview();
      }

      function renderPreview() {
        var screen = document.getElementById("livePreviewScreen");
        if (!screen) return;
        R.render(screen, data, {
          embedded: true,
          site: activeSiteId,
          activeCategory: previewCat,
          searchQuery: previewSearch,
          onFilterChange: function (cat, q) {
            previewCat = cat;
            previewSearch = q;
          }
        });
      }

      /* ---------- Password Gate ---------- */
      var passwordGate = document.getElementById("passwordGate");
      var adminApp = document.getElementById("adminApp");
      var passwordInput = document.getElementById("passwordInput");
      var passwordError = document.getElementById("passwordError");
      var cloudReadyPromise = Promise.all([
        window.PiknikStore.fetchCloud("piknik"),
        window.PiknikStore.fetchCloud("lombok")
      ]).then(function (results) {
        var resPiknik = results[0] || {};
        var resLombok = results[1] || {};
        if (!resPiknik.available && !resLombok.available) return;
        cloudMode = true;
        if (resPiknik.exists && resPiknik.content) {
          siteDataMap.piknik = resPiknik.content;
        }
        if (resLombok.exists && resLombok.content) {
          siteDataMap.lombok = resLombok.content;
        }
        data = siteDataMap[activeSiteId];
        var btnLabel = document.getElementById("btnPublishLabel");
        if (btnLabel) btnLabel.textContent = "Simpan Live";
        var btnCloud2 = document.getElementById("btnPublishCloud2");
        if (btnCloud2) btnCloud2.classList.remove("hidden");
        var notice = document.getElementById("publishNotice");
        if (notice) {
          notice.innerHTML =
            "<strong>Mode Server Laravel Aktif:</strong> setiap klik tombol <strong>Simpan Live</strong> akan langsung memperbarui Linktree yang sedang dipilih (Piknik / Lombok) untuk seluruh pengunjung.";
        }
        syncSaveStatusText(true);
      });

      function unlockAdmin() {
        sessionStorage.setItem(SESSION_KEY, "1");
        passwordGate.classList.add("hidden");
        adminApp.classList.remove("hidden");
        initDashboard();
      }

      function lockAdmin() {
        sessionStorage.removeItem(SESSION_KEY);
        sessionStorage.removeItem(PASSWORD_SESSION_KEY);
        sessionStorage.removeItem("piknik_admin_pin_v1");
        clearInsightsDisplay();
        adminApp.classList.add("hidden");
        passwordGate.classList.remove("hidden");
        passwordInput.value = "";
        passwordError.classList.add("hidden");
        passwordInput.focus();
      }

      document.getElementById("passwordForm").addEventListener("submit", async function (e) {
        e.preventDefault();
        var entered = passwordInput.value.trim();
        await cloudReadyPromise;
        if (cloudMode) {
          try {
            var res = await window.PiknikStore.postCloud({ action: "verify", password: entered });
            if (res && res.ok) {
              sessionStorage.setItem(PASSWORD_SESSION_KEY, entered);
              passwordError.classList.add("hidden");
              unlockAdmin();
              return;
            }
          } catch (err) {}
          passwordError.classList.remove("hidden");
          passwordInput.select();
          return;
        }
        var expected = (data.settings && data.settings.adminPassword) || "langsungakses";
        if (entered === String(expected)) {
          sessionStorage.setItem(PASSWORD_SESSION_KEY, entered);
          passwordError.classList.add("hidden");
          unlockAdmin();
        } else {
          passwordError.classList.remove("hidden");
          passwordInput.select();
        }
      });

      document.getElementById("togglePasswordVis").addEventListener("click", function () {
        var nextShow = passwordInput.type === "password";
        passwordInput.type = nextShow ? "text" : "password";
        this.setAttribute("aria-label", nextShow ? "Sembunyikan password" : "Tampilkan password");
      });

      document.getElementById("btnLockAdmin").addEventListener("click", lockAdmin);

      /* ---------- Tabs ---------- */
      var tabBtns = document.querySelectorAll(".adm-tab");
      var tabPanels = document.querySelectorAll(".adm-tab-panel");

      tabBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
          var target = btn.getAttribute("data-tab");
          tabBtns.forEach(function (b) {
            b.classList.toggle("is-active", b === btn);
          });
          tabPanels.forEach(function (p) {
            p.classList.toggle("hidden", p.id !== "tab-" + target);
          });
          if (target === "insights") {
            loadInsights({ silent: !!cachedInsights });
          }
        });
      });

      /* ---------- Mobile Preview Drawer ---------- */
      var previewDrawer = document.getElementById("previewDrawer");
      function setMobilePreviewOpen(open) {
        previewDrawer.classList.toggle("is-open", open);
        document.body.classList.toggle("adm-preview-open", open);
      }
      document.getElementById("btnOpenMobilePreview").addEventListener("click", function () {
        setMobilePreviewOpen(true);
      });
      document.getElementById("btnCloseMobilePreview").addEventListener("click", function () {
        setMobilePreviewOpen(false);
      });
      previewDrawer.addEventListener("click", function (e) {
        if (e.target === previewDrawer) {
          setMobilePreviewOpen(false);
        }
      });
      document.addEventListener("keydown", function (e) {
        if (e.key === "Escape" && previewDrawer.classList.contains("is-open")) {
          setMobilePreviewOpen(false);
        }
      });

      /* ---------- Profile, CTA & Socials ---------- */
      function populateProfileTab() {
        var p = data.profile;
        var c = data.cta;

        document.getElementById("inpName").value = p.name || "";
        document.getElementById("inpVerified").checked = !!p.verified;
        document.getElementById("inpLocation").value = p.location || "";
        document.getElementById("inpTagline").value = p.tagline || "";
        document.getElementById("inpBio").value = p.bio || "";
        document.getElementById("inpMapsUrl").value = p.mapsUrl || "";
        document.getElementById("inpAvatar").value = p.avatar || "";
        updateAvatarThumb(p.avatar);
        document.getElementById("inpCoverImage").value = p.coverImage || "";
        updateCoverThumb(p.coverImage);

        document.getElementById("inpCtaEnabled").checked = c.enabled !== false;
        document.getElementById("inpCtaUrl").value = c.url || "";
        document.getElementById("inpCtaPhone").value = c.phone || "";
        document.getElementById("inpCtaTitle").value = c.title || "";
        document.getElementById("inpCtaSub").value = c.subtitle || "";
        document.getElementById("inpCtaPrefill").value = c.prefill || "";
        document.getElementById("inpCtaIcon").value = c.icon || "";
        updateCtaIconPreview(c.icon);

        renderSocialsEditor();
      }

      function updateCtaIconPreview(iconVal) {
        var box = document.getElementById("ctaIconPreview");
        if (!box) return;
        var cUrl = (data.cta && data.cta.url) || "";
        var defIcon = cUrl && !/wa\.me|whatsapp/i.test(cUrl) ? "compass" : "whatsapp";
        box.innerHTML = R.getIconSvg(iconVal || defIcon, defIcon);
      }

      function normalizeIconValue(rawVal) {
        var raw = String(rawVal || "").trim();
        if (/^<svg[\s>]/i.test(raw)) {
          var encoded = R.svgToDataUrl(raw);
          if (encoded) return encoded;
        }
        return raw;
      }

      function uploadIconFile(file, onDone) {
        if (!file) return;
        compressImageFile(
          file,
          128,
          function (iconUrl) {
            onDone(iconUrl);
          },
          { allowSvg: true, preferAlpha: true }
        );
      }

      function updateAvatarThumb(src) {
        var safe = R.sanitizeUrl(src, true);
        if (safe === "#") safe = R.FALLBACK_IMG;
        document.getElementById("avatarPreview").src = safe;
        document.getElementById("headerLogo").src = safe;
      }

      var TRANSPARENT_PIXEL = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7";

      function updateCoverThumb(src) {
        var safe = R.sanitizeUrl(src, true);
        var imgEl = document.getElementById("coverPreview");
        if (!imgEl) return;
        imgEl.src = safe === "#" ? TRANSPARENT_PIXEL : safe;
      }

      function renderSocialsEditor() {
        var wrap = document.getElementById("socialsEditorList");
        wrap.innerHTML = data.socials
          .map(function (s, idx) {
            var hasCustom = R.isCustomIconValue(s.icon);
            return (
              '<div class="adm-social-item" data-soc-item="' +
              idx +
              '">' +
              '<div class="adm-social-row">' +
              '<div class="adm-icon-preview" data-soc-icon-box="' +
              idx +
              '">' +
              R.getIconSvg(s.icon || s.id, s.id || "website") +
              "</div>" +
              '<div class="adm-social-label">' +
              R.escapeHtml(s.label) +
              "</div>" +
              '<input type="text" class="adm-input" data-soc-url="' +
              idx +
              '" value="' +
              R.escapeHtml(s.url) +
              '" placeholder="https://..." />' +
              '<label class="adm-switch"><input type="checkbox" data-soc-en="' +
              idx +
              '" ' +
              (s.enabled !== false ? "checked" : "") +
              ' /><span class="adm-switch-track"></span></label>' +
              "</div>" +
              '<div class="adm-social-icon-bar">' +
              '<label class="adm-btn adm-btn-sm adm-btn-xs" style="cursor:pointer;">Upload SVG/Ikon' +
              '<input type="file" class="hidden" accept=".svg,image/*" data-soc-icon-file="' +
              idx +
              '" />' +
              "</label>" +
              '<input type="text" class="adm-input adm-input-sm" data-soc-icon-url="' +
              idx +
              '" value="' +
              R.escapeHtml(s.icon || "") +
              '" placeholder="Atau URL SVG/gambar ikon kustom..." />' +
              (hasCustom
                ? '<button type="button" class="adm-chip-btn" data-soc-icon-reset="' + idx + '">Reset</button>'
                : "") +
              "</div>" +
              "</div>"
            );
          })
          .join("");

        wrap.querySelectorAll("[data-soc-url]").forEach(function (inp) {
          inp.addEventListener("input", function () {
            var i = Number(inp.getAttribute("data-soc-url"));
            data.socials[i].url = inp.value;
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-soc-en]").forEach(function (chk) {
          chk.addEventListener("change", function () {
            var i = Number(chk.getAttribute("data-soc-en"));
            data.socials[i].enabled = chk.checked;
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-soc-icon-file]").forEach(function (fileInp) {
          fileInp.addEventListener("change", function (e) {
            var i = Number(fileInp.getAttribute("data-soc-icon-file"));
            var file = e.target.files && e.target.files[0];
            uploadIconFile(file, function (iconUrl) {
              data.socials[i].icon = iconUrl;
              renderSocialsEditor();
              persistAndPreview();
              R.showToast("Ikon " + (data.socials[i].label || "sosial") + " diperbarui");
            });
            e.target.value = "";
          });
        });

        wrap.querySelectorAll("[data-soc-icon-url]").forEach(function (inp) {
          inp.addEventListener("input", function () {
            var i = Number(inp.getAttribute("data-soc-icon-url"));
            var normalized = normalizeIconValue(inp.value);
            data.socials[i].icon = normalized;
            var box = wrap.querySelector('[data-soc-icon-box="' + i + '"]');
            if (box) box.innerHTML = R.getIconSvg(normalized || data.socials[i].id, data.socials[i].id || "website");
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-soc-icon-reset]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var i = Number(btn.getAttribute("data-soc-icon-reset"));
            data.socials[i].icon = "";
            renderSocialsEditor();
            persistAndPreview();
            R.showToast("Ikon dikembalikan ke bawaan");
          });
        });
      }

      function bindProfileInputs() {
        var map = [
          ["inpName", "profile", "name"],
          ["inpLocation", "profile", "location"],
          ["inpTagline", "profile", "tagline"],
          ["inpBio", "profile", "bio"],
          ["inpMapsUrl", "profile", "mapsUrl"],
          ["inpCtaUrl", "cta", "url"],
          ["inpCtaPhone", "cta", "phone"],
          ["inpCtaTitle", "cta", "title"],
          ["inpCtaSub", "cta", "subtitle"],
          ["inpCtaPrefill", "cta", "prefill"]
        ];
        map.forEach(function (entry) {
          document.getElementById(entry[0]).addEventListener("input", function (e) {
            data[entry[1]][entry[2]] = e.target.value;
            persistAndPreview();
          });
        });

        document.getElementById("inpVerified").addEventListener("change", function (e) {
          data.profile.verified = e.target.checked;
          persistAndPreview();
        });

        document.getElementById("inpCtaUrl").addEventListener("input", function () {
          updateCtaIconPreview(data.cta.icon);
        });

        document.getElementById("inpCtaEnabled").addEventListener("change", function (e) {
          data.cta.enabled = e.target.checked;
          persistAndPreview();
        });

        document.getElementById("inpCtaIcon").addEventListener("input", function (e) {
          var normalized = normalizeIconValue(e.target.value);
          data.cta.icon = normalized;
          updateCtaIconPreview(normalized);
          persistAndPreview();
        });

        document.getElementById("fileCtaIcon").addEventListener("change", function (e) {
          var file = e.target.files && e.target.files[0];
          uploadIconFile(file, function (iconUrl) {
            data.cta.icon = iconUrl;
            document.getElementById("inpCtaIcon").value = iconUrl;
            updateCtaIconPreview(iconUrl);
            persistAndPreview();
            R.showToast("Ikon tombol WhatsApp diperbarui");
          });
          e.target.value = "";
        });

        document.getElementById("btnResetCtaIcon").addEventListener("click", function () {
          data.cta.icon = "";
          document.getElementById("inpCtaIcon").value = "";
          updateCtaIconPreview("");
          persistAndPreview();
          R.showToast("Ikon tombol CTA dikembalikan ke bawaan");
        });

        document.getElementById("inpAvatar").addEventListener("input", function (e) {
          data.profile.avatar = e.target.value;
          updateAvatarThumb(e.target.value);
          persistAndPreview();
        });

        document.getElementById("fileAvatar").addEventListener("change", function (e) {
          var file = e.target.files && e.target.files[0];
          if (!file) return;
          compressImageFile(
            file,
            360,
            function (dataUrl) {
              data.profile.avatar = dataUrl;
              document.getElementById("inpAvatar").value = dataUrl;
              updateAvatarThumb(dataUrl);
              persistAndPreview();
              R.showToast("Logo berhasil diperbarui");
            },
            { allowSvg: true, preferAlpha: true }
          );
          e.target.value = "";
        });

        document.getElementById("inpCoverImage").addEventListener("input", function (e) {
          data.profile.coverImage = e.target.value;
          updateCoverThumb(e.target.value);
          persistAndPreview();
        });

        document.getElementById("fileCoverImage").addEventListener("change", function (e) {
          var file = e.target.files && e.target.files[0];
          if (!file) return;
          compressImageFile(file, 960, function (dataUrl) {
            data.profile.coverImage = dataUrl;
            document.getElementById("inpCoverImage").value = dataUrl;
            updateCoverThumb(dataUrl);
            persistAndPreview();
            R.showToast("Foto sampul berhasil diperbarui");
          });
          e.target.value = "";
        });

        document.querySelectorAll("[data-cover-preset]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var nextUrl = btn.getAttribute("data-cover-preset") || "";
            data.profile.coverImage = nextUrl;
            document.getElementById("inpCoverImage").value = nextUrl;
            updateCoverThumb(nextUrl);
            persistAndPreview();
          });
        });
      }

      /* ---------- Quick Links Editor ---------- */
      var LINK_ICONS = [
        { value: "whatsapp", label: "WhatsApp / CS" },
        { value: "tag", label: "Promo / Tag" },
        { value: "compass", label: "Kompas / Paket" },
        { value: "sparkles", label: "Custom / Spesial" },
        { value: "camera", label: "Galeri / Foto" },
        { value: "calendar", label: "Jadwal / Kalender" },
        { value: "link", label: "Tautan Umum" }
      ];

      function renderQuickLinksEditor() {
        var wrap = document.getElementById("quickLinksEditorList");
        if (!data.quickLinks.length) {
          wrap.innerHTML = '<div class="lt-empty">Belum ada menu tautan. Klik "Tambah Tautan" di atas.</div>';
          return;
        }

        wrap.innerHTML = data.quickLinks
          .map(function (item, idx) {
            var isOpen = expandedLinkId === item.id;
            var isCustom = R.isCustomIconValue(item.icon);
            var iconOpts =
              LINK_ICONS.map(function (ic) {
                return (
                  '<option value="' +
                  ic.value +
                  '"' +
                  (!isCustom && item.icon === ic.value ? " selected" : "") +
                  ">" +
                  ic.label +
                  "</option>"
                );
              }).join("") +
              '<option value="__custom__"' +
              (isCustom ? " selected" : "") +
              ">Kustom (SVG / Gambar)</option>";

            return (
              '<div class="adm-item' +
              (item.enabled === false ? " is-disabled" : "") +
              '" data-link-card="' +
              R.escapeHtml(item.id) +
              '">' +
              '<div class="adm-item-summary">' +
              '<div class="adm-item-main" data-toggle-link="' +
              R.escapeHtml(item.id) +
              '">' +
              '<div class="adm-item-iconbox">' +
              R.getIconSvg(item.icon, "link") +
              "</div>" +
              '<div class="adm-item-meta">' +
              '<p class="adm-item-title" data-summary-title>' +
              R.escapeHtml(item.title || "Tautan Tanpa Judul") +
              "</p>" +
              '<p class="adm-item-sub" data-summary-sub>' +
              R.escapeHtml(item.url || "-") +
              "</p>" +
              "</div>" +
              "</div>" +
              '<div class="adm-item-actions">' +
              '<button type="button" class="adm-icon-btn" data-move-link="-1" data-idx="' +
              idx +
              '" ' +
              (idx === 0 ? "disabled" : "") +
              ' title="Geser naik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg></button>' +
              '<button type="button" class="adm-icon-btn" data-move-link="1" data-idx="' +
              idx +
              '" ' +
              (idx === data.quickLinks.length - 1 ? "disabled" : "") +
              ' title="Geser turun"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>' +
              '<label class="adm-switch" title="Tampilkan tautan"><input type="checkbox" data-link-en="' +
              idx +
              '" ' +
              (item.enabled !== false ? "checked" : "") +
              ' /><span class="adm-switch-track"></span></label>' +
              '<button type="button" class="adm-icon-btn" data-toggle-link="' +
              R.escapeHtml(item.id) +
              '" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>' +
              "</div>" +
              "</div>" +
              (isOpen
                ? '<div class="adm-item-body">' +
                  '<div class="adm-form-grid">' +
                  '<div class="adm-field"><label class="adm-label">Judul Tautan</label><input type="text" class="adm-input" data-link-field="title" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(item.title) +
                  '" /></div>' +
                  '<div class="adm-field"><label class="adm-label">Badge Opsional</label><input type="text" class="adm-input" data-link-field="badge" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(item.badge || "") +
                  '" placeholder="Contoh: Promo" /></div>' +
                  '<div class="adm-field is-full"><label class="adm-label">Subjudul Singkat</label><input type="text" class="adm-input" data-link-field="subtitle" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(item.subtitle || "") +
                  '" /></div>' +
                  '<div class="adm-field is-full"><label class="adm-label">URL Tujuan</label><input type="url" class="adm-input" data-link-field="url" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(item.url) +
                  '" /></div>' +
                  '<div class="adm-field is-full">' +
                  '<label class="adm-label">Ikon (Bawaan atau Upload SVG / Gambar)</label>' +
                  '<div class="adm-icon-row">' +
                  '<div class="adm-icon-preview" data-link-icon-preview>' +
                  R.getIconSvg(item.icon, "link") +
                  "</div>" +
                  '<select class="adm-select adm-select-inline" data-link-icon-preset data-idx="' +
                  idx +
                  '">' +
                  iconOpts +
                  "</select>" +
                  '<label class="adm-btn adm-btn-sm" style="cursor:pointer;">Upload SVG / Gambar' +
                  '<input type="file" class="hidden" accept=".svg,image/*" data-link-icon-file data-idx="' +
                  idx +
                  '" />' +
                  "</label>" +
                  (isCustom
                    ? '<button type="button" class="adm-chip-btn" data-link-icon-reset data-idx="' + idx + '">Reset Bawaan</button>'
                    : "") +
                  "</div>" +
                  '<input type="text" class="adm-input adm-input-sm" style="margin-top:6px;" data-link-icon-url data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(isCustom ? item.icon : "") +
                  '" placeholder="Atau tempel URL ikon SVG / PNG / kode &lt;svg&gt;..." />' +
                  "</div>" +
                  "</div>" +
                  '<div style="display:flex;justify-content:flex-end;margin-top:12px;">' +
                  '<button type="button" class="adm-btn adm-btn-danger adm-btn-sm" data-del-link="' +
                  idx +
                  '">Hapus Tautan</button>' +
                  "</div>" +
                  "</div>"
                : "") +
              "</div>"
            );
          })
          .join("");

        wrap.querySelectorAll("[data-toggle-link]").forEach(function (el) {
          el.addEventListener("click", function () {
            var id = el.getAttribute("data-toggle-link");
            expandedLinkId = expandedLinkId === id ? null : id;
            renderQuickLinksEditor();
          });
        });

        wrap.querySelectorAll("[data-move-link]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-idx"));
            var dir = Number(btn.getAttribute("data-move-link"));
            var target = idx + dir;
            if (target < 0 || target >= data.quickLinks.length) return;
            var tmp = data.quickLinks[idx];
            data.quickLinks[idx] = data.quickLinks[target];
            data.quickLinks[target] = tmp;
            renderQuickLinksEditor();
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-link-en]").forEach(function (chk) {
          chk.addEventListener("change", function () {
            var idx = Number(chk.getAttribute("data-link-en"));
            data.quickLinks[idx].enabled = chk.checked;
            var card = chk.closest(".adm-item");
            if (card) card.classList.toggle("is-disabled", !chk.checked);
            persistAndPreview();
          });
        });

        function syncQuickLinkIconBoxes(card, iconVal) {
          if (!card) return;
          var html = R.getIconSvg(iconVal, "link");
          var sumBox = card.querySelector(".adm-item-iconbox");
          var prevBox = card.querySelector("[data-link-icon-preview]");
          if (sumBox) sumBox.innerHTML = html;
          if (prevBox) prevBox.innerHTML = html;
        }

        wrap.querySelectorAll("[data-link-field]").forEach(function (el) {
          var eventName = el.tagName === "SELECT" ? "change" : "input";
          el.addEventListener(eventName, function () {
            var idx = Number(el.getAttribute("data-idx"));
            var field = el.getAttribute("data-link-field");
            data.quickLinks[idx][field] = el.value;
            var card = el.closest(".adm-item");
            if (card) {
              if (field === "title") {
                card.querySelector("[data-summary-title]").textContent = el.value || "Tautan Tanpa Judul";
              } else if (field === "url") {
                card.querySelector("[data-summary-sub]").textContent = el.value || "-";
              }
            }
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-link-icon-preset]").forEach(function (sel) {
          sel.addEventListener("change", function () {
            var idx = Number(sel.getAttribute("data-idx"));
            var val = sel.value;
            if (val === "__custom__") {
              var urlInp = sel.closest(".adm-item-body").querySelector("[data-link-icon-url]");
              if (urlInp) urlInp.focus();
              return;
            }
            data.quickLinks[idx].icon = val;
            renderQuickLinksEditor();
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-link-icon-file]").forEach(function (fileInp) {
          fileInp.addEventListener("change", function (e) {
            var idx = Number(fileInp.getAttribute("data-idx"));
            var file = e.target.files && e.target.files[0];
            uploadIconFile(file, function (iconUrl) {
              data.quickLinks[idx].icon = iconUrl;
              renderQuickLinksEditor();
              persistAndPreview();
              R.showToast("Ikon tautan diperbarui");
            });
            e.target.value = "";
          });
        });

        wrap.querySelectorAll("[data-link-icon-url]").forEach(function (inp) {
          inp.addEventListener("input", function () {
            var idx = Number(inp.getAttribute("data-idx"));
            var normalized = normalizeIconValue(inp.value);
            var card = inp.closest(".adm-item");
            var presetSel = card ? card.querySelector("[data-link-icon-preset]") : null;
            if (normalized) {
              data.quickLinks[idx].icon = normalized;
              if (presetSel) presetSel.value = "__custom__";
            } else {
              var fallback = presetSel && presetSel.value !== "__custom__" ? presetSel.value : "link";
              data.quickLinks[idx].icon = fallback;
            }
            syncQuickLinkIconBoxes(card, data.quickLinks[idx].icon);
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-link-icon-reset]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-idx"));
            data.quickLinks[idx].icon = "link";
            renderQuickLinksEditor();
            persistAndPreview();
            R.showToast("Ikon dikembalikan ke bawaan");
          });
        });

        wrap.querySelectorAll("[data-del-link]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-del-link"));
            if (!confirm('Hapus tautan "' + (data.quickLinks[idx].title || "ini") + '"?')) return;
            data.quickLinks.splice(idx, 1);
            expandedLinkId = null;
            renderQuickLinksEditor();
            persistAndPreview();
          });
        });
      }

      document.getElementById("btnAddQuickLink").addEventListener("click", function () {
        var id = uid("ql");
        data.quickLinks.push({
          id: id,
          title: "Tautan Baru",
          subtitle: "Deskripsi singkat tautan",
          url: "https://piknikenthusiast.com/id",
          icon: "link",
          badge: "",
          enabled: true
        });
        expandedLinkId = id;
        renderQuickLinksEditor();
        persistAndPreview();
      });

      /* ---------- Destinations Editor ---------- */
      function updateDestBadgeCount() {
        var el = document.getElementById("destTabCount");
        if (el) el.textContent = String(data.destinations.length);
      }

      function renderDestinationsEditor() {
        updateDestBadgeCount();
        var wrap = document.getElementById("destEditorList");
        var q = adminDestQuery.trim().toLowerCase();

        var indexed = data.destinations
          .map(function (d, idx) {
            return { item: d, idx: idx };
          })
          .filter(function (row) {
            if (!q) return true;
            return (
              (row.item.name && row.item.name.toLowerCase().indexOf(q) !== -1) ||
              (row.item.location && row.item.location.toLowerCase().indexOf(q) !== -1)
            );
          });

        if (!indexed.length) {
          wrap.innerHTML = '<div class="lt-empty">Destinasi tidak ditemukan.</div>';
          return;
        }

        wrap.innerHTML = indexed
          .map(function (row) {
            var d = row.item;
            var idx = row.idx;
            var isOpen = expandedDestId === d.id;
            var thumb = R.sanitizeUrl(d.image, true);
            if (thumb === "#") thumb = R.FALLBACK_IMG;

            var catOptions = [
              { v: "gunung", l: "Gunung & Kawah" },
              { v: "pantai", l: "Pantai & Pulau" },
              { v: "alam", l: "Alam & Kota" }
            ]
              .map(function (c) {
                return '<option value="' + c.v + '"' + (d.category === c.v ? " selected" : "") + ">" + c.l + "</option>";
              })
              .join("");

            var subText = [d.location, d.price].filter(Boolean).join(" · ") || "Belum ada detail";

            return (
              '<div class="adm-item' +
              (d.enabled === false ? " is-disabled" : "") +
              '" data-dest-card="' +
              R.escapeHtml(d.id) +
              '">' +
              '<div class="adm-item-summary">' +
              '<div class="adm-item-main" data-toggle-dest="' +
              R.escapeHtml(d.id) +
              '">' +
              '<img class="adm-item-thumb" data-summary-thumb src="' +
              R.escapeHtml(thumb) +
              '" alt="' +
              R.escapeHtml(d.name) +
              '" onerror="this.onerror=null;this.src=\'' +
              R.FALLBACK_IMG +
              '\'" />' +
              '<div class="adm-item-meta">' +
              '<p class="adm-item-title" data-summary-title>' +
              R.escapeHtml(d.name || "Destinasi Baru") +
              "</p>" +
              '<p class="adm-item-sub" data-summary-sub>' +
              R.escapeHtml(subText) +
              "</p>" +
              "</div>" +
              "</div>" +
              '<div class="adm-item-actions">' +
              '<button type="button" class="adm-icon-btn" data-move-dest="-1" data-idx="' +
              idx +
              '" ' +
              (idx === 0 || q ? "disabled" : "") +
              ' title="Geser naik"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg></button>' +
              '<button type="button" class="adm-icon-btn" data-move-dest="1" data-idx="' +
              idx +
              '" ' +
              (idx === data.destinations.length - 1 || q ? "disabled" : "") +
              ' title="Geser turun"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></button>' +
              '<label class="adm-switch" title="Tampilkan destinasi"><input type="checkbox" data-dest-en="' +
              idx +
              '" ' +
              (d.enabled !== false ? "checked" : "") +
              ' /><span class="adm-switch-track"></span></label>' +
              '<button type="button" class="adm-icon-btn" data-toggle-dest="' +
              R.escapeHtml(d.id) +
              '" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg></button>' +
              "</div>" +
              "</div>" +
              (isOpen
                ? '<div class="adm-item-body">' +
                  '<div class="adm-form-grid">' +
                  '<div class="adm-field"><label class="adm-label">Nama Destinasi</label><input type="text" class="adm-input" data-dest-field="name" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.name) +
                  '" /></div>' +
                  '<div class="adm-field"><label class="adm-label">Lokasi</label><input type="text" class="adm-input" data-dest-field="location" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.location || "") +
                  '" placeholder="Probolinggo, Jawa Timur" /></div>' +
                  '<div class="adm-field"><label class="adm-label">Harga Mulai / Info Singkat</label><input type="text" class="adm-input" data-dest-field="price" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.price || "") +
                  '" placeholder="Mulai Rp 340.000" /></div>' +
                  '<div class="adm-field"><label class="adm-label">Kategori Filter</label><select class="adm-select" data-dest-field="category" data-idx="' +
                  idx +
                  '">' +
                  catOptions +
                  "</select></div>" +
                  '<div class="adm-field"><label class="adm-label">Badge Opsional</label><input type="text" class="adm-input" data-dest-field="badge" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.badge || "") +
                  '" placeholder="Populer / Favorit / Promo" /></div>' +
                  '<div class="adm-field"><label class="adm-label">Slug Website</label><input type="text" class="adm-input" data-dest-field="slug" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.slug || "") +
                  '" placeholder="gunung-bromo" /></div>' +
                  '<div class="adm-field is-full">' +
                  '<label class="adm-label">URL Tujuan saat diklik</label>' +
                  '<input type="url" class="adm-input" data-dest-field="url" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.url || "") +
                  '" />' +
                  '<div class="adm-url-presets">' +
                  '<button type="button" class="adm-chip-btn" data-url-preset="paket" data-idx="' +
                  idx +
                  '">Pakai Link Paket Web</button>' +
                  '<button type="button" class="adm-chip-btn" data-url-preset="destinasi" data-idx="' +
                  idx +
                  '">Pakai Link Info Destinasi</button>' +
                  '<button type="button" class="adm-chip-btn" data-url-preset="wa" data-idx="' +
                  idx +
                  '">Pakai Chat WA Destinasi</button>' +
                  "</div>" +
                  "</div>" +
                  '<div class="adm-field is-full">' +
                  '<label class="adm-label">Foto Thumbnail</label>' +
                  '<div class="adm-media-row">' +
                  '<img class="adm-media-thumb" data-body-thumb src="' +
                  R.escapeHtml(thumb) +
                  '" alt="Thumb" />' +
                  '<div class="adm-media-controls">' +
                  '<input type="text" class="adm-input" data-dest-field="image" data-idx="' +
                  idx +
                  '" value="' +
                  R.escapeHtml(d.image || "") +
                  '" placeholder="https://..." />' +
                  '<label class="adm-btn adm-btn-sm" style="cursor:pointer;">Upload<input type="file" accept="image/*" class="hidden" data-dest-upload="' +
                  idx +
                  '" /></label>' +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  '<div style="display:flex;justify-content:flex-end;margin-top:12px;">' +
                  '<button type="button" class="adm-btn adm-btn-danger adm-btn-sm" data-del-dest="' +
                  idx +
                  '">Hapus Destinasi</button>' +
                  "</div>" +
                  "</div>"
                : "") +
              "</div>"
            );
          })
          .join("");

        wrap.querySelectorAll("[data-toggle-dest]").forEach(function (el) {
          el.addEventListener("click", function () {
            var id = el.getAttribute("data-toggle-dest");
            expandedDestId = expandedDestId === id ? null : id;
            renderDestinationsEditor();
          });
        });

        wrap.querySelectorAll("[data-move-dest]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-idx"));
            var dir = Number(btn.getAttribute("data-move-dest"));
            var target = idx + dir;
            if (target < 0 || target >= data.destinations.length) return;
            var tmp = data.destinations[idx];
            data.destinations[idx] = data.destinations[target];
            data.destinations[target] = tmp;
            renderDestinationsEditor();
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-dest-en]").forEach(function (chk) {
          chk.addEventListener("change", function () {
            var idx = Number(chk.getAttribute("data-dest-en"));
            data.destinations[idx].enabled = chk.checked;
            var card = chk.closest(".adm-item");
            if (card) card.classList.toggle("is-disabled", !chk.checked);
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-dest-field]").forEach(function (el) {
          var eventName = el.tagName === "SELECT" ? "change" : "input";
          el.addEventListener(eventName, function () {
            var idx = Number(el.getAttribute("data-idx"));
            var field = el.getAttribute("data-dest-field");
            data.destinations[idx][field] = el.value;
            var card = el.closest(".adm-item");
            if (card) {
              var d = data.destinations[idx];
              if (field === "name") {
                card.querySelector("[data-summary-title]").textContent = d.name || "Destinasi Baru";
              }
              if (field === "location" || field === "price") {
                card.querySelector("[data-summary-sub]").textContent =
                  [d.location, d.price].filter(Boolean).join(" · ") || "Belum ada detail";
              }
              if (field === "image") {
                var safe = R.sanitizeUrl(el.value, true);
                if (safe === "#") safe = R.FALLBACK_IMG;
                card.querySelector("[data-summary-thumb]").src = safe;
                var bt = card.querySelector("[data-body-thumb]");
                if (bt) bt.src = safe;
              }
            }
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-url-preset]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-idx"));
            var kind = btn.getAttribute("data-url-preset");
            var d = data.destinations[idx];
            var slug = d.slug || slugify(d.name) || "paket-wisata";
            var nextUrl = "";
            if (kind === "paket") {
              nextUrl = "https://piknikenthusiast.com/id/paket-wisata/" + slug;
            } else if (kind === "destinasi") {
              nextUrl = "https://piknikenthusiast.com/id/destinasi/" + slug;
            } else {
              nextUrl = R.buildWaUrl(
                data.cta.phone,
                "Halo Admin Piknik Enthusiast, saya ingin tanya paket wisata " + (d.name || "")
              );
            }
            d.url = nextUrl;
            var card = btn.closest(".adm-item");
            if (card) {
              var inp = card.querySelector('[data-dest-field="url"]');
              if (inp) inp.value = nextUrl;
            }
            persistAndPreview();
            R.showToast("URL destinasi diperbarui");
          });
        });

        wrap.querySelectorAll("[data-dest-upload]").forEach(function (inp) {
          inp.addEventListener("change", function (e) {
            var idx = Number(inp.getAttribute("data-dest-upload"));
            var file = e.target.files && e.target.files[0];
            if (!file) return;
            compressImageFile(file, 480, function (dataUrl) {
              data.destinations[idx].image = dataUrl;
              var card = inp.closest(".adm-item");
              if (card) {
                var urlInp = card.querySelector('[data-dest-field="image"]');
                if (urlInp) urlInp.value = dataUrl;
                card.querySelector("[data-summary-thumb]").src = dataUrl;
                var bt = card.querySelector("[data-body-thumb]");
                if (bt) bt.src = dataUrl;
              }
              persistAndPreview();
              R.showToast("Foto destinasi diperbarui");
            });
            e.target.value = "";
          });
        });

        wrap.querySelectorAll("[data-del-dest]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var idx = Number(btn.getAttribute("data-del-dest"));
            if (!confirm('Hapus destinasi "' + (data.destinations[idx].name || "ini") + '"?')) return;
            data.destinations.splice(idx, 1);
            expandedDestId = null;
            renderDestinationsEditor();
            persistAndPreview();
          });
        });
      }

      document.getElementById("adminDestSearch").addEventListener("input", function (e) {
        adminDestQuery = e.target.value || "";
        renderDestinationsEditor();
      });

      document.getElementById("btnAddDest").addEventListener("click", function () {
        var id = uid("dest");
        data.destinations.push({
          id: id,
          name: "Destinasi Baru",
          slug: "destinasi-baru",
          location: "Jawa Timur",
          price: "Mulai Rp 300.000",
          category: "alam",
          badge: "",
          image: R.FALLBACK_IMG,
          url: "https://piknikenthusiast.com/id/paket-wisata",
          enabled: true
        });
        adminDestQuery = "";
        document.getElementById("adminDestSearch").value = "";
        expandedDestId = id;
        renderDestinationsEditor();
        persistAndPreview();
      });

      /* ---------- Appearance & Settings ---------- */
      function ensureCustomThemeObj() {
        if (!data.appearance.customTheme || typeof data.appearance.customTheme !== "object") {
          data.appearance.customTheme = {
            bgMode: "gradient",
            bgStart: "#0f2547",
            bgEnd: "#3b2338",
            bgAngle: 145,
            accent: "#f48526",
            ctaBg: "#ea6c05",
            textMode: "auto"
          };
        }
        if (!Array.isArray(data.appearance.savedThemes)) {
          data.appearance.savedThemes = [];
        }
        return data.appearance.customTheme;
      }

      function renderSavedThemesList() {
        var sec = document.getElementById("savedThemesSection");
        var wrap = document.getElementById("savedThemesList");
        var list = Array.isArray(data.appearance.savedThemes) ? data.appearance.savedThemes : [];
        if (!list.length) {
          sec.classList.add("hidden");
          wrap.innerHTML = "";
          return;
        }
        sec.classList.remove("hidden");
        var isCustomActive = data.appearance.theme === "custom";
        var activeSavedId = data.appearance.activeSavedThemeId || "";

        wrap.innerHTML = list
          .map(function (item, idx) {
            var s = R.sanitizeHexColor(item.bgStart, "#0f2547");
            var e = R.sanitizeHexColor(item.bgEnd, "#3b2338");
            var acc = R.sanitizeHexColor(item.accent, "#f48526");
            var ang = R.clampAngle(item.bgAngle, 145);
            var bgCss =
              item.bgMode === "solid"
                ? s
                : "linear-gradient(" + ang + "deg, " + s + " 0%, " + e + " 100%)";
            var isSelected = isCustomActive && activeSavedId === item.id;
            return (
              '<div class="adm-theme-opt adm-saved-theme-card' +
              (isSelected ? " is-active" : "") +
              '" data-use-saved="' +
              idx +
              '" role="button" tabindex="0">' +
              '<div class="adm-saved-theme-main">' +
              '<span class="adm-swatch" style="background:' +
              R.escapeHtml(bgCss) +
              ';"><span class="adm-swatch-dot" style="background:' +
              R.escapeHtml(acc) +
              ';"></span></span>' +
              '<span style="min-width:0;overflow:hidden;text-overflow:ellipsis;">' +
              '<span class="adm-theme-name">' +
              R.escapeHtml(item.name || "Custom " + (idx + 1)) +
              "</span><br>" +
              '<span class="adm-theme-desc">' +
              (item.bgMode === "solid" ? "Solid" : "Gradasi " + ang + "°") +
              "</span></span></div>" +
              '<button type="button" class="adm-theme-del" data-del-saved="' +
              idx +
              '" title="Hapus warna custom" aria-label="Hapus warna custom">' +
              '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
              "</button></div>"
            );
          })
          .join("");

        wrap.querySelectorAll("[data-use-saved]").forEach(function (card) {
          card.addEventListener("click", function (ev) {
            if (ev.target.closest("[data-del-saved]")) return;
            var idx = Number(card.getAttribute("data-use-saved"));
            var chosen = data.appearance.savedThemes[idx];
            if (!chosen) return;
            data.appearance.theme = "custom";
            data.appearance.activeSavedThemeId = chosen.id;
            data.appearance.customTheme = {
              bgMode: chosen.bgMode === "solid" ? "solid" : "gradient",
              bgStart: R.sanitizeHexColor(chosen.bgStart, "#0f2547"),
              bgEnd: R.sanitizeHexColor(chosen.bgEnd, "#3b2338"),
              bgAngle: R.clampAngle(chosen.bgAngle, 145),
              accent: R.sanitizeHexColor(chosen.accent, "#f48526"),
              ctaBg: R.sanitizeHexColor(chosen.ctaBg, "#ea6c05"),
              textMode: chosen.textMode || "auto"
            };
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        wrap.querySelectorAll("[data-del-saved]").forEach(function (btn) {
          btn.addEventListener("click", function (ev) {
            ev.stopPropagation();
            var idx = Number(btn.getAttribute("data-del-saved"));
            var removed = data.appearance.savedThemes[idx];
            data.appearance.savedThemes.splice(idx, 1);
            if (removed && data.appearance.activeSavedThemeId === removed.id) {
              data.appearance.activeSavedThemeId = "";
            }
            populateAppearanceTab();
            persistAndPreview();
          });
        });
      }

      function populateCustomThemeControls() {
        var ct = ensureCustomThemeObj();
        var panel = document.getElementById("customThemePanel");
        var isCustom = data.appearance.theme === "custom";
        panel.classList.toggle("hidden", !isCustom);

        var bgMode = ct.bgMode === "solid" ? "solid" : "gradient";
        var isGrad = bgMode === "gradient";

        document.querySelectorAll("#customBgModeSeg .adm-seg-btn").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-bgmode") === bgMode);
        });
        document.querySelectorAll("#customTextModeSeg .adm-seg-btn").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-textmode") === (ct.textMode || "auto"));
        });

        document.getElementById("custBgEndField").classList.toggle("hidden", !isGrad);
        document.getElementById("custAngleField").classList.toggle("hidden", !isGrad);

        var bgStart = R.sanitizeHexColor(ct.bgStart, "#0f2547");
        var bgEnd = R.sanitizeHexColor(ct.bgEnd, "#3b2338");
        var accent = R.sanitizeHexColor(ct.accent, "#f48526");
        var ctaBg = R.sanitizeHexColor(ct.ctaBg, "#ea6c05");
        var angle = R.clampAngle(ct.bgAngle, 145);

        document.getElementById("custBgStartPicker").value = bgStart;
        document.getElementById("custBgStartHex").value = bgStart;
        document.getElementById("custBgEndPicker").value = bgEnd;
        document.getElementById("custBgEndHex").value = bgEnd;
        document.getElementById("custAccentPicker").value = accent;
        document.getElementById("custAccentHex").value = accent;
        document.getElementById("custCtaPicker").value = ctaBg;
        document.getElementById("custCtaHex").value = ctaBg;
        document.getElementById("custBgAngleRange").value = String(angle);
        document.getElementById("custBgAngleVal").textContent = angle + "°";
      }

      function populateAppearanceTab() {
        var ap = data.appearance;
        var st = data.settings;
        var activeSavedId = ap.activeSavedThemeId || "";

        document.querySelectorAll("#themeOptions .adm-theme-opt").forEach(function (btn) {
          var t = btn.getAttribute("data-theme");
          var active = t === ap.theme && !(t === "custom" && activeSavedId);
          btn.classList.toggle("is-active", active);
        });
        renderSavedThemesList();
        populateCustomThemeControls();

        document.querySelectorAll("#bgStyleOptions .adm-theme-opt").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-bg") === (ap.bgStyle || "wave"));
        });
        document.querySelectorAll("#layoutSeg .adm-seg-btn").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-layout") === ap.destLayout);
        });
        document.querySelectorAll("#radiusSeg .adm-seg-btn").forEach(function (btn) {
          btn.classList.toggle("is-active", btn.getAttribute("data-radius") === ap.cardRadius);
        });

        document.getElementById("optShowPrice").checked = ap.showPrice !== false;
        document.getElementById("optShowFilter").checked = ap.showFilter !== false;
        document.getElementById("optShowSearch").checked = !!ap.showSearch;
        document.getElementById("optShowBadges").checked = ap.showBadges !== false;
        document.getElementById("inpFooterText").value = st.footerText || "";
      }

      function bindAppearanceControls() {
        document.querySelectorAll("#themeOptions .adm-theme-opt").forEach(function (btn) {
          btn.addEventListener("click", function () {
            data.appearance.theme = btn.getAttribute("data-theme");
            data.appearance.activeSavedThemeId = "";
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        document.querySelectorAll("#customBgModeSeg .adm-seg-btn").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var ct = ensureCustomThemeObj();
            ct.bgMode = btn.getAttribute("data-bgmode");
            data.appearance.activeSavedThemeId = "";
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        document.querySelectorAll("#customTextModeSeg .adm-seg-btn").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var ct = ensureCustomThemeObj();
            ct.textMode = btn.getAttribute("data-textmode");
            data.appearance.activeSavedThemeId = "";
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        [
          ["custBgStartPicker", "custBgStartHex", "bgStart"],
          ["custBgEndPicker", "custBgEndHex", "bgEnd"],
          ["custAccentPicker", "custAccentHex", "accent"],
          ["custCtaPicker", "custCtaHex", "ctaBg"]
        ].forEach(function (cfg) {
          var picker = document.getElementById(cfg[0]);
          var hexInp = document.getElementById(cfg[1]);
          var field = cfg[2];

          picker.addEventListener("input", function () {
            var ct = ensureCustomThemeObj();
            ct[field] = picker.value;
            hexInp.value = picker.value;
            data.appearance.activeSavedThemeId = "";
            persistAndPreview();
          });

          hexInp.addEventListener("input", function () {
            var raw = hexInp.value.trim();
            if (/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/.test(raw)) {
              var safe = R.sanitizeHexColor(raw, picker.value);
              var ct = ensureCustomThemeObj();
              ct[field] = safe;
              picker.value = safe;
              data.appearance.activeSavedThemeId = "";
              persistAndPreview();
            }
          });

          hexInp.addEventListener("blur", function () {
            var ct = ensureCustomThemeObj();
            var safe = R.sanitizeHexColor(hexInp.value, ct[field]);
            hexInp.value = safe;
            picker.value = safe;
          });
        });

        document.querySelectorAll("#custAngleField [data-angle]").forEach(function (btn) {
          btn.addEventListener("click", function () {
            var ct = ensureCustomThemeObj();
            var ang = R.clampAngle(btn.getAttribute("data-angle"), 145);
            ct.bgAngle = ang;
            data.appearance.activeSavedThemeId = "";
            document.getElementById("custBgAngleRange").value = String(ang);
            document.getElementById("custBgAngleVal").textContent = ang + "°";
            persistAndPreview();
          });
        });

        document.getElementById("custBgAngleRange").addEventListener("input", function (e) {
          var ct = ensureCustomThemeObj();
          var ang = R.clampAngle(e.target.value, 145);
          ct.bgAngle = ang;
          data.appearance.activeSavedThemeId = "";
          document.getElementById("custBgAngleVal").textContent = ang + "°";
          persistAndPreview();
        });

        document.getElementById("btnSaveCustomTheme").addEventListener("click", function () {
          var ct = ensureCustomThemeObj();
          var nameInp = document.getElementById("inpNewThemeName");
          var customName = nameInp.value.trim() || "Custom " + (data.appearance.savedThemes.length + 1);
          var newEntry = {
            id: uid("theme"),
            name: customName,
            bgMode: ct.bgMode === "solid" ? "solid" : "gradient",
            bgStart: R.sanitizeHexColor(ct.bgStart, "#0f2547"),
            bgEnd: R.sanitizeHexColor(ct.bgEnd, "#3b2338"),
            bgAngle: R.clampAngle(ct.bgAngle, 145),
            accent: R.sanitizeHexColor(ct.accent, "#f48526"),
            ctaBg: R.sanitizeHexColor(ct.ctaBg, "#ea6c05"),
            textMode: ct.textMode || "auto"
          };
          data.appearance.savedThemes.push(newEntry);
          data.appearance.theme = "custom";
          data.appearance.activeSavedThemeId = newEntry.id;
          nameInp.value = "";
          populateAppearanceTab();
          persistAndPreview();
          R.showToast("Tema \"" + customName + "\" ditambahkan");
        });

        document.querySelectorAll("#bgStyleOptions .adm-theme-opt").forEach(function (btn) {
          btn.addEventListener("click", function () {
            data.appearance.bgStyle = btn.getAttribute("data-bg");
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        document.querySelectorAll("#layoutSeg .adm-seg-btn").forEach(function (btn) {
          btn.addEventListener("click", function () {
            data.appearance.destLayout = btn.getAttribute("data-layout");
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        document.querySelectorAll("#radiusSeg .adm-seg-btn").forEach(function (btn) {
          btn.addEventListener("click", function () {
            data.appearance.cardRadius = btn.getAttribute("data-radius");
            populateAppearanceTab();
            persistAndPreview();
          });
        });

        [
          ["optShowPrice", "appearance", "showPrice"],
          ["optShowFilter", "appearance", "showFilter"],
          ["optShowSearch", "appearance", "showSearch"],
          ["optShowBadges", "appearance", "showBadges"]
        ].forEach(function (entry) {
          document.getElementById(entry[0]).addEventListener("change", function (e) {
            data[entry[1]][entry[2]] = e.target.checked;
            persistAndPreview();
          });
        });

        document.getElementById("inpFooterText").addEventListener("input", function (e) {
          data.settings.footerText = e.target.value;
          persistAndPreview();
        });
      }

      /* ---------- Publish / Export / Import / Reset ---------- */
      function buildDataJsContent() {
        siteDataMap[activeSiteId] = data;
        var piknikObj = siteDataMap.piknik || window.PiknikStore.load("piknik");
        var lombokObj = siteDataMap.lombok || window.PiknikStore.load("lombok");
        var sitesReg = window.LINKTREE_SITES || {
          piknik: window.PiknikStore.getSiteMeta("piknik"),
          lombok: window.PiknikStore.getSiteMeta("lombok")
        };
        return (
          "/**\n" +
          " * Piknik Enthusiast & Lombok Enthusiast — Linktree Configuration Data\n" +
          " * Terakhir diperbarui via Admin: " +
          new Date().toISOString() +
          "\n" +
          " */\n" +
          "window.PIKNIK_DEFAULT_DATA = " +
          JSON.stringify(piknikObj, null, 2) +
          ";\n\n" +
          "window.LOMBOK_DEFAULT_DATA = " +
          JSON.stringify(lombokObj, null, 2) +
          ";\n\n" +
          "window.LINKTREE_SITES = " +
          JSON.stringify(sitesReg, null, 2) +
          ";\n"
        );
      }

      function downloadTextFile(filename, content, mimeType) {
        var blob = new Blob([content], { type: mimeType });
        var url = URL.createObjectURL(blob);
        var a = document.createElement("a");
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        setTimeout(function () {
          URL.revokeObjectURL(url);
        }, 1000);
      }

      async function publishDataJs() {
        var content = buildDataJsContent();
        if (window.showSaveFilePicker && window.location.protocol !== "file:") {
          try {
            var handle = await window.showSaveFilePicker({
              suggestedName: "data.js",
              types: [{ description: "JavaScript Config", accept: { "application/javascript": [".js"] } }]
            });
            var writable = await handle.createWritable();
            await writable.write(content);
            await writable.close();
            R.showToast("File data.js (Piknik & Lombok) berhasil disimpan");
            return;
          } catch (err) {
            if (err && err.name === "AbortError") return;
          }
        }
        downloadTextFile("data.js", content, "application/javascript;charset=utf-8");
        R.showToast("data.js didownload — timpa file data.js di hosting Anda");
      }

      async function publishToCloud() {
        var password = sessionStorage.getItem(PASSWORD_SESSION_KEY) || "";
        if (!password) {
          lockAdmin();
          R.showToast("Silakan masukkan password kembali");
          return;
        }
        var targetSite = activeSiteId;
        var siteMeta = window.PiknikStore.getSiteMeta(targetSite);
        siteDataMap[targetSite] = data;
        var btn = document.getElementById("btnPublishData");
        var lbl = document.getElementById("btnPublishLabel");
        if (btn) btn.disabled = true;
        if (lbl) lbl.textContent = "Menyimpan...";
        try {
          var res = await window.PiknikStore.postCloud({
            action: "save",
            site: targetSite,
            password: password,
            content: data
          });
          if (res && res.ok) {
            siteUnsavedMap[targetSite] = false;
            hasUnsavedCloudChanges = false;
            syncSaveStatusText(true);
            R.showToast("Berhasil! " + siteMeta.label + " sudah live di website");
          } else if (res && res.status === 401) {
            R.showToast("Sesi password kedaluwarsa, silakan login ulang");
            lockAdmin();
          } else {
            alert((res && res.error) || "Gagal menyimpan ke server.");
          }
        } catch (err) {
          alert("Gagal terhubung ke server: " + err.message);
        } finally {
          if (btn) btn.disabled = false;
          if (lbl) lbl.textContent = cloudMode ? "Simpan Live" : "Simpan data.js";
        }
      }

      document.getElementById("btnPublishData").addEventListener("click", function () {
        if (cloudMode) {
          publishToCloud();
        } else {
          publishDataJs();
        }
      });
      document.getElementById("btnPublishCloud2").addEventListener("click", publishToCloud);
      document.getElementById("btnPublishData2").addEventListener("click", publishDataJs);

      document.getElementById("btnExportJson").addEventListener("click", function () {
        var filename = activeSiteId + "-linktree-backup.json";
        downloadTextFile(filename, JSON.stringify(data, null, 2), "application/json;charset=utf-8");
        R.showToast("Backup JSON (" + window.PiknikStore.getSiteMeta(activeSiteId).label + ") diunduh");
      });

      document.getElementById("fileImportJson").addEventListener("change", function (e) {
        var file = e.target.files && e.target.files[0];
        if (!file) return;
        var reader = new FileReader();
        reader.onload = function (ev) {
          try {
            var parsed = JSON.parse(ev.target.result);
            if (!parsed || typeof parsed !== "object" || !parsed.profile || !Array.isArray(parsed.destinations)) {
              alert("Format file JSON tidak sesuai struktur Linktree.");
              return;
            }
            window.PiknikStore.save(parsed, activeSiteId);
            data = window.PiknikStore.load(activeSiteId);
            siteDataMap[activeSiteId] = data;
            siteUnsavedMap[activeSiteId] = true;
            hasUnsavedCloudChanges = true;
            syncSaveStatusText(true);
            refreshAllEditors();
            renderPreview();
            R.showToast(cloudMode ? "JSON dimuat — klik Simpan Live untuk terbitkan" : "Backup JSON berhasil dipulihkan");
          } catch (err) {
            alert("Gagal membaca file JSON: " + err.message);
          }
        };
        reader.readAsText(file);
        e.target.value = "";
      });

      document.getElementById("btnResetDefault").addEventListener("click", async function () {
        var siteMeta = window.PiknikStore.getSiteMeta(activeSiteId);
        if (!confirm("Kembalikan konten dan tampilan " + siteMeta.label + " ke pengaturan awal dari data.js?")) return;
        if (cloudMode) {
          var password = sessionStorage.getItem(PASSWORD_SESSION_KEY) || "";
          try {
            var res = await window.PiknikStore.postCloud({
              action: "reset",
              site: activeSiteId,
              password: password
            });
            if (!res || !res.ok) {
              alert((res && res.error) || "Gagal mereset konten live.");
              return;
            }
          } catch (err) {
            alert("Gagal terhubung ke server: " + err.message);
            return;
          }
        }
        data = window.PiknikStore.reset(activeSiteId);
        siteDataMap[activeSiteId] = data;
        siteUnsavedMap[activeSiteId] = false;
        hasUnsavedCloudChanges = false;
        expandedLinkId = null;
        expandedDestId = null;
        refreshAllEditors();
        renderPreview();
        syncSaveStatusText(true);
        R.showToast("Konten " + siteMeta.label + " dikembalikan ke default");
      });

      document.getElementById("btnSavePassword").addEventListener("click", async function () {
        var newPassword = document.getElementById("inpNewPassword").value.trim();
        if (newPassword.length < 4) {
          alert("Password minimal harus 4 karakter.");
          return;
        }
        if (cloudMode) {
          var currentPassword = sessionStorage.getItem(PASSWORD_SESSION_KEY) || "";
          try {
            var res = await window.PiknikStore.postCloud({
              action: "change-password",
              password: currentPassword,
              newPassword: newPassword
            });
            if (!res || !res.ok) {
              alert((res && res.error) || "Gagal memperbarui password.");
              return;
            }
            sessionStorage.setItem(PASSWORD_SESSION_KEY, newPassword);
            document.getElementById("inpNewPassword").value = "";
            R.showToast("Password Admin live berhasil diperbarui");
            return;
          } catch (err) {
            alert("Gagal terhubung ke server: " + err.message);
            return;
          }
        }
        data.settings.adminPassword = newPassword;
        if (siteDataMap.piknik && siteDataMap.piknik.settings) {
          siteDataMap.piknik.settings.adminPassword = newPassword;
          window.PiknikStore.save(siteDataMap.piknik, "piknik");
        }
        if (siteDataMap.lombok && siteDataMap.lombok.settings) {
          siteDataMap.lombok.settings.adminPassword = newPassword;
          window.PiknikStore.save(siteDataMap.lombok, "lombok");
        }
        sessionStorage.setItem(PASSWORD_SESSION_KEY, newPassword);
        document.getElementById("inpNewPassword").value = "";
        persistAndPreview();
        R.showToast("Password Admin berhasil diperbarui");
      });

      /* ---------- Insight Pengunjung ---------- */
      var cachedInsights = null;
      var selectedChartDate = null;
      var insightsLoading = false;

      function formatInsightNumber(n) {
        return Number(n || 0).toLocaleString("id-ID");
      }

      function formatWibClock(isoStr) {
        try {
          return new Intl.DateTimeFormat("id-ID", {
            timeZone: "Asia/Jakarta",
            day: "2-digit",
            month: "short",
            hour: "2-digit",
            minute: "2-digit",
            hourCycle: "h23",
          }).format(isoStr ? new Date(isoStr) : new Date());
        } catch (e) {
          return "";
        }
      }

      async function postAnalytics(payload) {
        var bodyObj = Object.assign({ site: activeSiteId }, payload || {});
        var res = await fetch("/api/analytics", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(bodyObj),
        });
        var json = null;
        try {
          json = await res.json();
        } catch (e) {}
        if (!res.ok) {
          var err = new Error((json && json.error) || ("HTTP " + res.status));
          err.status = res.status;
          throw err;
        }
        return json;
      }

      function renderStatBars(containerId, items, useNavy) {
        var el = document.getElementById(containerId);
        if (!el) return;
        if (!items || !items.length) {
          el.innerHTML = '<p class="adm-empty-insight">Belum ada data tercatat.</p>';
          return;
        }
        var sum = items.reduce(function (acc, it) {
          return acc + (Number(it.count) || 0);
        }, 0);
        if (sum <= 0) {
          el.innerHTML = '<p class="adm-empty-insight">Belum ada data tercatat.</p>';
          return;
        }
        el.innerHTML = items
          .map(function (it) {
            var count = Number(it.count) || 0;
            var pct = Math.round((count / sum) * 100);
            var barW = Math.max(6, pct);
            return (
              '<div class="adm-stat-row">' +
              '<div class="adm-stat-top">' +
              '<span class="adm-stat-name">' + R.escapeHtml(it.name || "-") + "</span>" +
              '<span class="adm-stat-val">' + R.escapeHtml(formatInsightNumber(count)) + " (" + pct + "%)</span>" +
              "</div>" +
              '<div class="adm-stat-bar"><div class="adm-stat-fill' +
              (useNavy ? " is-navy" : "") +
              '" style="width:' + barW + '%"></div></div>' +
              "</div>"
            );
          })
          .join("");
      }

      function updateSelectedBarDetail() {
        var detailEl = document.getElementById("insightBarDetail");
        var barsEl = document.getElementById("insightChartBars");
        if (!detailEl || !barsEl) return;
        var daily = (cachedInsights && cachedInsights.daily) || [];
        var chosen = null;
        for (var i = 0; i < daily.length; i++) {
          if (daily[i].date === selectedChartDate) {
            chosen = daily[i];
            break;
          }
        }
        barsEl.querySelectorAll(".adm-bar-col").forEach(function (col) {
          col.classList.toggle("is-selected", col.getAttribute("data-date") === selectedChartDate);
        });
        if (!chosen) {
          detailEl.innerHTML = "<span>Pilih tanggal pada grafik untuk melihat rincian kunjungan.</span>";
          return;
        }
        var dayTag = chosen.isToday ? " · Hari ini" : "";
        detailEl.innerHTML =
          "<span><strong>" +
          R.escapeHtml(chosen.label + dayTag) +
          "</strong> (WIB) · " +
          R.escapeHtml(formatInsightNumber(chosen.visitors)) +
          " pengunjung unik</span>" +
          "<span><strong>" +
          R.escapeHtml(formatInsightNumber(chosen.views)) +
          "</strong> total tampilan</span>";
      }

      function renderInsights(summary) {
        cachedInsights = summary;
        var totals = (summary && summary.totals) || {};
        var badge = document.getElementById("insightTabBadge");
        if (badge) {
          badge.textContent = formatInsightNumber(totals.todayVisitors);
          badge.classList.remove("hidden");
        }

        var updatedEl = document.getElementById("insightUpdatedText");
        if (updatedEl) {
          var stamp = formatWibClock(summary.generatedAt);
          updatedEl.textContent = stamp
            ? "Diperbarui " + stamp + " WIB · Pengunjung halaman utama"
            : "Statistik pengunjung halaman utama · Zona waktu WIB";
        }

        var setText = function (id, val) {
          var node = document.getElementById(id);
          if (node) node.textContent = val;
        };
        setText("kpiTodayVisitors", formatInsightNumber(totals.todayVisitors));
        setText("kpiTodayViews", "Pengunjung unik · " + formatInsightNumber(totals.todayViews) + " tampilan");
        setText("kpi7dVisitors", formatInsightNumber(totals.visitors7d));
        setText("kpi7dViews", "Pengunjung unik · " + formatInsightNumber(totals.views7d) + " tampilan");
        setText("kpi30dVisitors", formatInsightNumber(totals.visitors30d));
        setText("kpi30dViews", "Pengunjung unik · " + formatInsightNumber(totals.views30d) + " tampilan");
        setText("kpiAllVisitors", formatInsightNumber(totals.allVisitors));
        setText("kpiAllViews", "Pengunjung unik · " + formatInsightNumber(totals.allViews) + " tampilan");

        var daily = Array.isArray(summary.daily) ? summary.daily : [];
        var maxViews = daily.reduce(function (m, d) {
          return Math.max(m, Number(d.views) || 0);
        }, 0);
        if (!selectedChartDate || !daily.some(function (d) { return d.date === selectedChartDate; })) {
          selectedChartDate = summary.todayDate || (daily.length ? daily[daily.length - 1].date : null);
        }

        var barsEl = document.getElementById("insightChartBars");
        if (barsEl) {
          if (!daily.length) {
            barsEl.innerHTML = "";
          } else {
            barsEl.innerHTML = daily
              .map(function (d) {
                var views = Number(d.views) || 0;
                var hPct = maxViews > 0 && views > 0 ? Math.max(10, Math.round((views / maxViews) * 100)) : 4;
                var cls = "adm-bar-col";
                if (views === 0) cls += " is-zero";
                if (d.isToday) cls += " is-today";
                if (d.date === selectedChartDate) cls += " is-selected";
                var labelAttr = R.escapeHtml((d.label || d.date) + ": " + formatInsightNumber(d.visitors) + " unik, " + formatInsightNumber(views) + " tampilan");
                return (
                  '<button type="button" class="' + cls + '" data-date="' + R.escapeHtml(d.date) + '" title="' + labelAttr + '" aria-label="' + labelAttr + '">' +
                  '<div class="adm-bar-track"><div class="adm-bar-fill" style="height:' + hPct + '%"></div></div>' +
                  '<span class="adm-bar-tick">' + R.escapeHtml(d.dayNum || "") + "</span>" +
                  "</button>"
                );
              })
              .join("");
          }
        }
        updateSelectedBarDetail();

        renderStatBars("insightSourcesList", summary.sources || [], false);
        renderStatBars("insightDevicesList", summary.devices || [], true);
        renderStatBars("insightLocationsList", summary.locations || [], false);

        var recentEl = document.getElementById("insightRecentList");
        if (recentEl) {
          var recent = Array.isArray(summary.recent) ? summary.recent : [];
          if (!recent.length) {
            recentEl.innerHTML = '<p class="adm-empty-insight">Belum ada kunjungan tercatat.</p>';
          } else {
            recentEl.innerHTML = recent
              .map(function (rec) {
                return (
                  '<div class="adm-recent-row">' +
                  '<div class="adm-recent-meta">' +
                  '<span class="adm-recent-pill is-source">' + R.escapeHtml(rec.source || "Langsung") + "</span>" +
                  '<span class="adm-recent-pill">' + R.escapeHtml(rec.device || "Desktop") + "</span>" +
                  '<span class="adm-recent-loc">' + R.escapeHtml(rec.location || "—") + "</span>" +
                  "</div>" +
                  '<span class="adm-recent-time">' +
                  R.escapeHtml((rec.date || "") + (rec.timeWib ? " · " + rec.timeWib + " WIB" : "")) +
                  "</span>" +
                  "</div>"
                );
              })
              .join("");
          }
        }
      }

      function clearInsightsDisplay() {
        cachedInsights = null;
        selectedChartDate = null;
        insightsLoading = false;
        var badge = document.getElementById("insightTabBadge");
        if (badge) {
          badge.textContent = "0";
          badge.classList.add("hidden");
        }
        ["kpiTodayVisitors", "kpi7dVisitors", "kpi30dVisitors", "kpiAllVisitors"].forEach(function (id) {
          var el = document.getElementById(id);
          if (el) el.textContent = "-";
        });
        ["kpiTodayViews", "kpi7dViews", "kpi30dViews", "kpiAllViews"].forEach(function (id) {
          var el = document.getElementById(id);
          if (el) el.textContent = "Pengunjung unik · - tampilan";
        });
        var barsEl = document.getElementById("insightChartBars");
        if (barsEl) barsEl.innerHTML = "";
        var detailEl = document.getElementById("insightBarDetail");
        if (detailEl) detailEl.innerHTML = "<span>Pilih tanggal pada grafik untuk melihat rincian kunjungan.</span>";
        ["insightSourcesList", "insightDevicesList", "insightLocationsList", "insightRecentList"].forEach(function (id) {
          var el = document.getElementById(id);
          if (el) el.innerHTML = '<p class="adm-empty-insight">Belum ada data tersedia.</p>';
        });
      }

      async function loadInsights(options) {
        var opts = options || {};
        var offlineNotice = document.getElementById("insightOfflineNotice");
        var refreshBtn = document.getElementById("btnRefreshInsights");
        var resetBtn = document.getElementById("btnResetInsights");
        if (!cloudMode) {
          if (offlineNotice) offlineNotice.classList.remove("hidden");
          if (refreshBtn) refreshBtn.disabled = true;
          if (resetBtn) resetBtn.disabled = true;
          clearInsightsDisplay();
          return;
        }
        if (offlineNotice) offlineNotice.classList.add("hidden");
        if (refreshBtn) refreshBtn.disabled = false;
        if (resetBtn) resetBtn.disabled = false;

        var pwd = sessionStorage.getItem(PASSWORD_SESSION_KEY) || "";
        if (!pwd || insightsLoading) return;
        insightsLoading = true;
        if (refreshBtn) {
          refreshBtn.disabled = true;
          refreshBtn.textContent = "Memuat...";
        }
        try {
          var res = await postAnalytics({ action: "summary", password: pwd });
          if (res && res.ok) {
            renderInsights(res);
          }
        } catch (err) {
          if (err && err.status === 401) {
            R.showToast("Sesi berakhir, silakan masukkan password lagi");
            lockAdmin();
          } else if (!opts.silent) {
            R.showToast("Gagal memuat insight: " + (err ? err.message : "Error"));
          }
        } finally {
          insightsLoading = false;
          if (refreshBtn) {
            refreshBtn.disabled = !cloudMode;
            refreshBtn.textContent = "Muat Ulang";
          }
        }
      }

      var TRACK_LINK_PRESETS = [
        { code: "ig", name: "Instagram", hint: "Bio, Story, atau link di Reels IG" },
        { code: "tt", name: "TikTok", hint: "Bio profil atau deskripsi video TikTok" },
        { code: "wa", name: "WhatsApp", hint: "Chat, Status WA, atau pesan broadcast" },
        { code: "fb", name: "Facebook", hint: "Postingan halaman, grup, atau iklan FB" },
        { code: "yt", name: "YouTube", hint: "Deskripsi atau komentar disematkan" },
        { code: "google", name: "Google / Maps", hint: "Profil Bisnis Google atau Google Maps" },
      ];

      function getPublicBaseUrl() {
        var siteMeta = window.PiknikStore.getSiteMeta(activeSiteId);
        var pathSuffix = siteMeta.id === "lombok" ? "/lombok" : "/";
        if (window.location && String(window.location.protocol).startsWith("http")) {
          return window.location.origin + pathSuffix;
        }
        return "https://piknik-asik.netlify.app" + pathSuffix;
      }

      function slugifySourceTag(raw) {
        return String(raw || "")
          .trim()
          .toLowerCase()
          .replace(/[^a-z0-9.-]+/g, "-")
          .replace(/^-+|-+$/g, "")
          .slice(0, 24);
      }

      function buildTrackedUrl(tag) {
        return getPublicBaseUrl() + "?source=" + encodeURIComponent(tag);
      }

      function copyTextValue(text) {
        if (navigator.clipboard && window.isSecureContext) {
          return navigator.clipboard.writeText(text).catch(function () {
            return fallbackCopyText(text);
          });
        }
        return Promise.resolve(fallbackCopyText(text));
      }

      function fallbackCopyText(text) {
        var ta = document.createElement("textarea");
        ta.value = text;
        ta.setAttribute("readonly", "");
        ta.style.position = "fixed";
        ta.style.top = "-9999px";
        ta.style.opacity = "0";
        document.body.appendChild(ta);
        ta.select();
        var ok = false;
        try {
          ok = document.execCommand("copy");
        } catch (e) {}
        document.body.removeChild(ta);
        return ok;
      }

      function copyTrackedUrl(url, label, btnEl, defaultText) {
        copyTextValue(url).then(function () {
          R.showToast("Link " + label + " disalin");
          if (btnEl) {
            btnEl.classList.add("is-copied");
            btnEl.textContent = "Tersalin";
            clearTimeout(btnEl._copyTimer);
            btnEl._copyTimer = setTimeout(function () {
              btnEl.classList.remove("is-copied");
              btnEl.textContent = defaultText || "Salin";
            }, 1600);
          }
        });
      }

      function renderTrackLinksGenerator() {
        var grid = document.getElementById("insightTrackLinksGrid");
        if (!grid) return;
        grid.innerHTML = TRACK_LINK_PRESETS.map(function (item) {
          var url = buildTrackedUrl(item.code);
          return (
            '<div class="adm-track-item">' +
            '<div>' +
            '<div class="adm-track-name">' + R.escapeHtml(item.name) + "</div>" +
            '<div class="adm-track-hint">' + R.escapeHtml(item.hint) + "</div>" +
            "</div>" +
            '<div class="adm-track-url-row">' +
            '<span class="adm-track-code" title="' + R.escapeHtml(url) + '">' + R.escapeHtml(url) + "</span>" +
            '<button type="button" class="adm-btn adm-track-copy-btn" data-copy-url="' +
            R.escapeHtml(url) +
            '" data-copy-label="' +
            R.escapeHtml(item.name) +
            '">Salin</button>' +
            "</div>" +
            "</div>"
          );
        }).join("");
      }

      function bindInsightsControls() {
        var barsEl = document.getElementById("insightChartBars");
        if (barsEl) {
          barsEl.addEventListener("click", function (e) {
            var col = e.target.closest(".adm-bar-col");
            if (!col) return;
            selectedChartDate = col.getAttribute("data-date");
            updateSelectedBarDetail();
          });
        }

        renderTrackLinksGenerator();

        var trackGrid = document.getElementById("insightTrackLinksGrid");
        if (trackGrid) {
          trackGrid.addEventListener("click", function (e) {
            var btn = e.target.closest("[data-copy-url]");
            if (!btn) return;
            copyTrackedUrl(
              btn.getAttribute("data-copy-url"),
              btn.getAttribute("data-copy-label") || "Pelacakan",
              btn,
              "Salin"
            );
          });
        }

        var inpCustom = document.getElementById("inpCustomTrackSource");
        var customPreview = document.getElementById("customTrackPreview");
        var btnCustomCopy = document.getElementById("btnCopyCustomTrack");
        if (inpCustom && customPreview && btnCustomCopy) {
          var syncCustomPreview = function () {
            var slug = slugifySourceTag(inpCustom.value);
            if (!slug) {
              customPreview.textContent = "Masukkan nama sumber untuk membuat link pelacakan";
              btnCustomCopy.disabled = true;
              return "";
            }
            var url = buildTrackedUrl(slug);
            customPreview.textContent = url;
            btnCustomCopy.disabled = false;
            return { slug: slug, url: url };
          };
          inpCustom.addEventListener("input", syncCustomPreview);
          btnCustomCopy.addEventListener("click", function () {
            var res = syncCustomPreview();
            if (!res) return;
            copyTrackedUrl(res.url, res.slug, btnCustomCopy, "Salin Link");
          });
        }

        var refreshBtn = document.getElementById("btnRefreshInsights");
        if (refreshBtn) {
          refreshBtn.addEventListener("click", function () {
            loadInsights({ force: true });
          });
        }

        var resetBtn = document.getElementById("btnResetInsights");
        if (resetBtn) {
          resetBtn.addEventListener("click", async function () {
            if (!cloudMode) return;
            if (!confirm("Reset semua data statistik pengunjung? Riwayat kunjungan di server akan dihapus permanen.")) {
              return;
            }
            var pwd = sessionStorage.getItem(PASSWORD_SESSION_KEY) || "";
            if (!pwd) return;
            resetBtn.disabled = true;
            resetBtn.textContent = "Mereset...";
            try {
              await postAnalytics({ action: "reset", password: pwd });
              selectedChartDate = null;
              R.showToast("Data pengunjung berhasil direset");
              await loadInsights({ silent: true });
            } catch (err) {
              R.showToast("Gagal reset data: " + (err ? err.message : "Error"));
            } finally {
              resetBtn.disabled = !cloudMode;
              resetBtn.textContent = "Reset Data Pengunjung";
            }
          });
        }
      }

      /* ---------- Init ---------- */
      var dashboardInitialized = false;
      function refreshAllEditors() {
        populateProfileTab();
        renderQuickLinksEditor();
        renderDestinationsEditor();
        populateAppearanceTab();
      }

      function bindSiteSwitcher() {
        var switcher = document.getElementById("siteSwitcher");
        if (!switcher) return;
        switcher.addEventListener("click", function (e) {
          var btn = e.target.closest("[data-site]");
          if (!btn) return;
          var targetSite = btn.getAttribute("data-site");
          if (!targetSite || targetSite === activeSiteId) return;
          switchManagedSite(targetSite);
        });
      }

      function initDashboard() {
        if (!dashboardInitialized) {
          bindSiteSwitcher();
          bindProfileInputs();
          bindAppearanceControls();
          bindInsightsControls();
          dashboardInitialized = true;
        }
        syncActiveSiteUI();
        refreshAllEditors();
        renderPreview();
        loadInsights({ silent: true });
      }

      cloudReadyPromise.then(function () {
        var hasSession = sessionStorage.getItem(SESSION_KEY) === "1";
        var hasPassword = !cloudMode || !!sessionStorage.getItem(PASSWORD_SESSION_KEY);
        if (hasSession && hasPassword) {
          unlockAdmin();
        }
      });
    })();
  </script>
</body>
</html>
