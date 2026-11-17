/**
 * Piknik Enthusiast — Linktree Configuration Data
 * Edit manual di file ini atau kelola secara visual lewat admin.html
 */
window.PIKNIK_DEFAULT_DATA = {
  profile: {
    name: "Piknik Enthusiast",
    verified: true,
    tagline: "Open Trip & Private Trip Jawa Timur · Start Malang & Surabaya",
    bio: "Perjalanan wisata Bromo, Kawah Ijen, Tumpak Sewu, hingga pesisir Banyuwangi dengan layanan rapi, nyaman, dan transparan.",
    avatar: "https://piknikenthusiast.com/logo3f_optimized.webp",
    coverImage: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/ecf41e47-4f42-4e76-8d8c-31816fa74d3a.webp",
    location: "Dau, Kab. Malang · 09.00–17.00 WIB",
    mapsUrl: "https://maps.app.goo.gl/UT4ZceBakJN8RY8FA"
  },
  cta: {
    enabled: true,
    title: "Pesan & Pilih Paket Wisata",
    subtitle: "Lihat pilihan Open Trip & Private Trip langsung di website",
    url: "https://piknikenthusiast.com/id/paket-wisata",
    icon: "compass",
    phone: "6282234784612",
    prefill: "Halo Admin Piknik Enthusiast, saya ingin tanya paket wisata."
  },
  socials: [
    {
      id: "website",
      label: "Website",
      url: "https://piknikenthusiast.com/id",
      enabled: true
    },
    {
      id: "instagram",
      label: "Instagram",
      url: "https://www.instagram.com/piknik_enthusiast",
      enabled: true
    },
    {
      id: "tiktok",
      label: "TikTok",
      url: "https://www.tiktok.com/@piknik.enthusiast",
      enabled: true
    },
    {
      id: "linkedin",
      label: "LinkedIn",
      url: "https://www.linkedin.com/in/piknik-enthusiast-25115838b/",
      enabled: true
    },
    {
      id: "maps",
      label: "Lokasi Kantor",
      url: "https://maps.app.goo.gl/UT4ZceBakJN8RY8FA",
      enabled: true
    },
    {
      id: "email",
      label: "Email",
      url: "mailto:ptastharaasiagroup@gmail.com",
      enabled: false
    }
  ],
  quickLinks: [
    {
      id: "ql-promo",
      title: "Promo & Diskon Terbaru",
      subtitle: "Early bird & harga spesial open trip Jawa Timur",
      url: "https://piknikenthusiast.com/id/promo",
      icon: "tag",
      badge: "Promo",
      enabled: true
    },
    {
      id: "ql-cs-wa",
      title: "Customer Service via WhatsApp",
      subtitle: "Tanya jadwal, harga & custom itinerary · Fast response",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Piknik%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20wisata.",
      icon: "whatsapp",
      badge: "",
      enabled: true
    },
    {
      id: "ql-custom",
      title: "Request Custom Trip & Gathering",
      subtitle: "Family trip, corporate outing & honeymoon",
      url: "https://piknikenthusiast.com/id/custom-page",
      icon: "sparkles",
      badge: "",
      enabled: true
    },
    {
      id: "ql-galeri",
      title: "Galeri & Dokumentasi Perjalanan",
      subtitle: "Momen seru Piknikers di berbagai destinasi",
      url: "https://piknikenthusiast.com/id/galeri",
      icon: "camera",
      badge: "",
      enabled: true
    }
  ],
  destinations: [
    {
      id: "dest-bromo",
      name: "Gunung Bromo",
      slug: "gunung-bromo",
      location: "Probolinggo, Jawa Timur",
      price: "Mulai Rp 340.000",
      category: "gunung",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/ecf41e47-4f42-4e76-8d8c-31816fa74d3a.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/gunung-bromo",
      enabled: true
    },
    {
      id: "dest-ijen",
      name: "Kawah Ijen",
      slug: "kawah-ijen",
      location: "Banyuwangi, Jawa Timur",
      price: "Mulai Rp 350.000",
      category: "gunung",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasi/B8222BFF-5180-4416-A183-E64A3E47B5BE_1782281542_1782576434.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/kawah-ijen",
      enabled: true
    },
    {
      id: "dest-tumpak-sewu",
      name: "Air Terjun Tumpak Sewu",
      slug: "air-terjun-tumpak-sewu",
      location: "Lumajang, Jawa Timur",
      price: "Mulai Rp 270.000",
      category: "alam",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/0236a2e8-fa35-44df-96c1-2934b6233b4b.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/air-terjun-tumpak-sewu",
      enabled: true
    },
    {
      id: "dest-semeru",
      name: "Lava Tour Semeru",
      slug: "lava-tour-semeru",
      location: "Lumajang, Jawa Timur",
      price: "Mulai Rp 480.000",
      category: "gunung",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/2abab52c-108c-49d3-9c0c-78734c9d6055.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/lava-tour-semeru",
      enabled: true
    },
    {
      id: "dest-menjangan",
      name: "Pulau Menjangan & Tabuhan",
      slug: "pulau-menjangan-tabuhan",
      location: "Banyuwangi – Bali Barat",
      price: "Mulai Rp 295.000",
      category: "pantai",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fe1ea6b7-c9d6-4142-8702-1dd969706fbb.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/pulau-menjangan-tabuhan",
      enabled: true
    },
    {
      id: "dest-baluran",
      name: "Taman Nasional Baluran",
      slug: "taman-nasional-baluran",
      location: "Situbondo, Jawa Timur",
      price: "Mulai Rp 240.000",
      category: "alam",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/8ab22568-9d7c-4236-8bd8-d5dada5391b6.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/taman-nasional-baluran",
      enabled: true
    },
    {
      id: "dest-green-island",
      name: "Green Island",
      slug: "green-island",
      location: "Banyuwangi, Jawa Timur",
      price: "Mulai Rp 540.000",
      category: "pantai",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fd3382bd-58dc-42fc-85a9-bbd82d2c5c90.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/green-island",
      enabled: true
    },
    {
      id: "dest-djawatan",
      name: "De Djawatan Forest",
      slug: "de-djawatan-forest",
      location: "Banyuwangi, Jawa Timur",
      price: "Mulai Rp 280.000",
      category: "alam",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/6c74fe81-f3f8-44f6-a06c-14387bfef5bc.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/de-djawatan-forest",
      enabled: true
    },
    {
      id: "dest-3-warna",
      name: "Pantai 3 Warna",
      slug: "pantai-3-warna",
      location: "Kab. Malang, Jawa Timur",
      price: "Mulai Rp 330.000",
      category: "pantai",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/c2aabf18-99f1-4355-aa67-fa1d68c6e9c3.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/pantai-3-warna",
      enabled: true
    },
    {
      id: "dest-malang-selatan",
      name: "Pantai Malang Selatan",
      slug: "pantai-malang-selatan",
      location: "Kab. Malang, Jawa Timur",
      price: "Mulai Rp 280.000",
      category: "pantai",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/8cab1590-05ba-4efb-852e-cc7a6dac9ff9.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/pantai-malang-selatan",
      enabled: true
    },
    {
      id: "dest-city-tour",
      name: "City Tour Malang Batu",
      slug: "city-tour-malang-batu",
      location: "Malang & Batu, Jawa Timur",
      price: "Mulai Rp 440.000",
      category: "alam",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/25b06532-93b6-4029-aa47-e4b4b55bb4b3.webp",
      url: "https://piknikenthusiast.com/id/paket-wisata/city-tour-malang-batu",
      enabled: true
    }
  ],
  appearance: {
    theme: "navy",
    bgStyle: "wave",
    destLayout: "grid",
    cardRadius: "rounded",
    showFilter: true,
    showSearch: false,
    showPrice: true,
    showBadges: true,
    activeSavedThemeId: "",
    customTheme: {
      bgMode: "gradient",
      bgStart: "#0f2547",
      bgEnd: "#3b2338",
      bgAngle: 145,
      accent: "#f48526",
      ctaBg: "#ea6c05",
      textMode: "auto"
    },
    savedThemes: []
  },
  settings: {
    adminPassword: "langsungakses",
    destSubtitle: "Jelajahi keindahan Indonesia bersama kami",
    footerText: "PT Asthara Asia Group · Malang, Jawa Timur"
  }
};

/**
 * Lombok Enthusiast — Linktree Configuration Data
 */
window.LOMBOK_DEFAULT_DATA = {
  profile: {
    name: "Lombok Enthusiast",
    verified: true,
    tagline: "Open Trip & Private Trip Pulau Lombok · Start Bandara LOP & Mataram",
    bio: "Perjalanan wisata Gili Trawangan, Pantai Pink, Kuta Mandalika, Bukit Merese, Sembalun Rinjani, hingga Desa Adat Sade dengan layanan ramah, nyaman, dan transparan.",
    avatar: "https://piknikenthusiast.com/logo3f_optimized.webp",
    coverImage: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fe1ea6b7-c9d6-4142-8702-1dd969706fbb.webp",
    location: "Mataram, Lombok NTB · 08.00–17.00 WITA",
    mapsUrl: "https://maps.google.com/?q=Mataram+Lombok"
  },
  cta: {
    enabled: true,
    title: "Pesan & Pilih Paket Wisata Lombok",
    subtitle: "Lihat pilihan Open Trip, Honeymoon & Private Trip Lombok",
    url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20wisata%20Lombok.",
    icon: "compass",
    phone: "6282234784612",
    prefill: "Halo Admin Lombok Enthusiast, saya ingin tanya paket wisata Lombok."
  },
  socials: [
    {
      id: "website",
      label: "Website",
      url: "https://piknikenthusiast.com/id",
      enabled: true
    },
    {
      id: "instagram",
      label: "Instagram",
      url: "https://www.instagram.com/lombok_enthusiast",
      enabled: true
    },
    {
      id: "tiktok",
      label: "TikTok",
      url: "https://www.tiktok.com/@lombok.enthusiast",
      enabled: true
    },
    {
      id: "linkedin",
      label: "LinkedIn",
      url: "https://www.linkedin.com/in/piknik-enthusiast-25115838b/",
      enabled: false
    },
    {
      id: "maps",
      label: "Lokasi Kantor",
      url: "https://maps.google.com/?q=Mataram+Lombok",
      enabled: true
    },
    {
      id: "email",
      label: "Email",
      url: "mailto:ptastharaasiagroup@gmail.com",
      enabled: false
    }
  ],
  quickLinks: [
    {
      id: "ql-lombok-promo",
      title: "Promo & Paket Spesial Lombok",
      subtitle: "Early bird open trip Gili, Pantai Pink & Mandalika",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20info%20promo%20paket%20wisata%20Lombok.",
      icon: "tag",
      badge: "Promo",
      enabled: true
    },
    {
      id: "ql-lombok-cs",
      title: "Customer Service via WhatsApp",
      subtitle: "Tanya jadwal, harga & custom itinerary Lombok · Fast response",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20konsultasi%20rencana%20liburan%20ke%20Lombok.",
      icon: "whatsapp",
      badge: "",
      enabled: true
    },
    {
      id: "ql-lombok-custom",
      title: "Private Trip, Honeymoon & Gathering",
      subtitle: "Rencana perjalanan fleksibel untuk keluarga, pasangan & kantor",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20request%20custom%20trip%20Lombok.",
      icon: "sparkles",
      badge: "",
      enabled: true
    },
    {
      id: "ql-lombok-galeri",
      title: "Galeri & Dokumentasi Trip Lombok",
      subtitle: "Momen seru liburan di Gili, Sembalun, dan pantai selatan Lombok",
      url: "https://www.instagram.com/lombok_enthusiast",
      icon: "camera",
      badge: "",
      enabled: true
    }
  ],
  destinations: [
    {
      id: "dest-gili-trawangan",
      name: "Gili Trawangan, Meno & Air",
      slug: "gili-trawangan-meno-air",
      location: "Lombok Utara, NTB",
      price: "Mulai Rp 350.000",
      category: "pantai",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fe1ea6b7-c9d6-4142-8702-1dd969706fbb.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Gili%20Trawangan%20Meno%20Air.",
      enabled: true
    },
    {
      id: "dest-pantai-pink",
      name: "Pantai Pink & Gili Petelu",
      slug: "pantai-pink-gili-petelu",
      location: "Jerowaru, Lombok Timur",
      price: "Mulai Rp 420.000",
      category: "pantai",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/fd3382bd-58dc-42fc-85a9-bbd82d2c5c90.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Pantai%20Pink.",
      enabled: true
    },
    {
      id: "dest-mandalika-merese",
      name: "Kuta Mandalika & Bukit Merese",
      slug: "kuta-mandalika-bukit-merese",
      location: "Pujut, Lombok Tengah",
      price: "Mulai Rp 280.000",
      category: "pantai",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/8cab1590-05ba-4efb-852e-cc7a6dac9ff9.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Mandalika%20dan%20Bukit%20Merese.",
      enabled: true
    },
    {
      id: "dest-sembalun",
      name: "Bukit Selong & Desa Sembalun",
      slug: "bukit-selong-desa-sembalun",
      location: "Sembalun, Lombok Timur",
      price: "Mulai Rp 320.000",
      category: "gunung",
      badge: "Populer",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/ecf41e47-4f42-4e76-8d8c-31816fa74d3a.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Sembalun.",
      enabled: true
    },
    {
      id: "dest-sendang-gile",
      name: "Air Terjun Sendang Gile & Tiu Kelep",
      slug: "sendang-gile-tiu-kelep",
      location: "Senaru, Lombok Utara",
      price: "Mulai Rp 310.000",
      category: "alam",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/0236a2e8-fa35-44df-96c1-2934b6233b4b.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Sendang%20Gile%20dan%20Tiu%20Kelep.",
      enabled: true
    },
    {
      id: "dest-desa-sade",
      name: "Tour Sasak Desa Sade & Sukarara",
      slug: "desa-sade-sukarara",
      location: "Lombok Tengah, NTB",
      price: "Mulai Rp 250.000",
      category: "alam",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/8ab22568-9d7c-4236-8bd8-d5dada5391b6.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Desa%20Adat%20Sade.",
      enabled: true
    },
    {
      id: "dest-gili-nanggu",
      name: "Secret Gili: Nanggu, Sudak & Kedis",
      slug: "gili-nanggu-sudak-kedis",
      location: "Sekotong, Lombok Barat",
      price: "Mulai Rp 390.000",
      category: "pantai",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasis/c2aabf18-99f1-4355-aa67-fa1d68c6e9c3.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Gili%20Nanggu.",
      enabled: true
    },
    {
      id: "dest-rinjani",
      name: "Trekking Gunung Rinjani",
      slug: "trekking-gunung-rinjani",
      location: "Taman Nasional Gunung Rinjani",
      price: "Mulai Rp 1.450.000",
      category: "gunung",
      badge: "",
      image: "https://nos.wjv-1.neo.id/piknikenthusiast/app/public/destinasi/B8222BFF-5180-4416-A183-E64A3E47B5BE_1782281542_1782576434.webp",
      url: "https://wa.me/6282234784612?text=Halo%20Admin%20Lombok%20Enthusiast%2C%20saya%20ingin%20tanya%20paket%20Trekking%20Rinjani.",
      enabled: true
    }
  ],
  appearance: {
    theme: "navy",
    bgStyle: "wave",
    destLayout: "grid",
    cardRadius: "rounded",
    showFilter: true,
    showSearch: false,
    showPrice: true,
    showBadges: true,
    activeSavedThemeId: "",
    customTheme: {
      bgMode: "gradient",
      bgStart: "#0b2b40",
      bgEnd: "#0f4c5c",
      bgAngle: 145,
      accent: "#f48526",
      ctaBg: "#ea6c05",
      textMode: "auto"
    },
    savedThemes: []
  },
  settings: {
    adminPassword: "langsungakses",
    destSubtitle: "Jelajahi pesona Pulau Lombok & Gili bersama kami",
    footerText: "PT Asthara Asia Group · Mataram, Lombok NTB"
  }
};

window.LINKTREE_SITES = {
  piknik: {
    id: "piknik",
    label: "Piknik Enthusiast",
    shortLabel: "Piknik",
    regionLabel: "Jawa Timur",
    publicPath: "/",
    localFile: "index.html",
    storageKey: "piknik_linktree_state_v1",
    defaultGlobalKey: "PIKNIK_DEFAULT_DATA"
  },
  lombok: {
    id: "lombok",
    label: "Lombok Enthusiast",
    shortLabel: "Lombok",
    regionLabel: "Pulau Lombok",
    publicPath: "/lombok",
    localFile: "lombok.html",
    storageKey: "lombok_linktree_state_v1",
    defaultGlobalKey: "LOMBOK_DEFAULT_DATA"
  }
};
