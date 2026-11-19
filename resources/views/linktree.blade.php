<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @if(($site ?? 'piknik') === 'lombok')
    <title>Lombok Enthusiast — Link &amp; Semua Destinasi Wisata Lombok</title>
    <meta name="description" content="Open Trip &amp; Private Trip Pulau Lombok: Gili Trawangan, Pantai Pink, Kuta Mandalika, Bukit Merese, Sembalun, Rinjani &amp; Desa Adat Sade." />
  @else
    <title>Piknik Enthusiast — Link &amp; Semua Destinasi Wisata</title>
    <meta name="description" content="Open Trip &amp; Private Trip Jawa Timur: Gunung Bromo, Kawah Ijen, Tumpak Sewu, Baluran, Green Island, Pantai Malang Selatan &amp; City Tour Malang-Batu." />
  @endif
  <meta name="theme-color" content="#EA6C05" />
  <link rel="icon" href="https://piknikenthusiast.com/logo3f_optimized.webp" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/styles.css?v={{ @filemtime(public_path('styles.css')) ?: '20261119' }}" />
  <script>window.LINKTREE_FORCE_SITE = @json($site ?? 'piknik');</script>
</head>
<body class="lt-page-body">
  <main id="app"></main>

  <script src="/data.js?v={{ @filemtime(public_path('data.js')) ?: '20261119' }}"></script>
  <script src="/renderer.js?v={{ @filemtime(public_path('renderer.js')) ?: '20261119' }}"></script>
  <script>
    (function () {
      var app = document.getElementById("app");
      var store = window.PiknikStore || {};
      var activeSite =
        typeof store.detectSiteId === "function"
          ? store.detectSiteId()
          : (window.LINKTREE_FORCE_SITE || "piknik");
      if (typeof store.setSite === "function") {
        store.setSite(activeSite);
      }
      var activeCategory = "all";
      var searchQuery = "";
      var cloudData = null;
      var cloudChecked = false;

      function getCurrentData() {
        if (cloudChecked && cloudData) return cloudData;
        if (cloudChecked && !cloudData) return window.PiknikStore.getDefault(activeSite);
        return window.PiknikStore.load(activeSite);
      }

      function mount(nextData) {
        var data = nextData || getCurrentData();
        if (data.profile && data.profile.name) {
          document.title = data.profile.name + " — Link & Destinasi Wisata";
        }
        window.PiknikRenderer.render(app, data, {
          embedded: false,
          site: activeSite,
          activeCategory: activeCategory,
          searchQuery: searchQuery,
          onFilterChange: function (cat, query) {
            activeCategory = cat;
            searchQuery = query;
          }
        });
      }

      function getVisitorId() {
        var key = "piknik_vid_v1";
        try {
          var existing = localStorage.getItem(key);
          if (existing && /^[a-zA-Z0-9_-]{8,64}$/.test(existing)) {
            return existing;
          }
          var generated =
            "v_" +
            Date.now().toString(36) +
            "_" +
            Math.random().toString(36).slice(2, 10);
          localStorage.setItem(key, generated);
          return generated;
        } catch (e) {
          return "v_" + Date.now().toString(36) + "_" + Math.random().toString(36).slice(2, 10);
        }
      }

      function detectDeviceType() {
        var ua = (navigator.userAgent || "").toLowerCase();
        var w = Math.min(window.innerWidth || 0, (window.screen && window.screen.width) || 9999);
        if (/(ipad|tablet|(android(?!.*mobile)))/i.test(ua) || (w >= 640 && w <= 1024 && /mobi|android|touch/.test(ua))) {
          return "tablet";
        }
        if (/(mobi|iphone|ipod|android.*mobile|windows phone)/i.test(ua) || (w > 0 && w < 640)) {
          return "mobile";
        }
        return "desktop";
      }

      function detectTrafficSource() {
        try {
          var params = new URLSearchParams(window.location.search || "");
          var utm =
            params.get("utm_source") ||
            params.get("source") ||
            params.get("s") ||
            params.get("src") ||
            params.get("ref") ||
            params.get("utm_medium");
          if (utm && String(utm).trim()) return String(utm).trim();
          if (params.get("fbclid")) return "facebook";
          if (params.get("ttclid")) return "tiktok";
          if (params.get("gclid")) return "google";
        } catch (e) {}
        try {
          var ref = document.referrer || "";
          if (ref) {
            var refUrl = new URL(ref);
            if (refUrl.hostname && refUrl.hostname !== window.location.hostname) {
              return refUrl.hostname;
            }
          }
        } catch (e) {}
        return "direct";
      }

      function trackPublicVisit() {
        if (!window.location || !String(window.location.protocol).startsWith("http")) return;
        try {
          fetch("/api/analytics", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              action: "track",
              site: activeSite,
              vid: getVisitorId(),
              device: detectDeviceType(),
              source: detectTrafficSource(),
              path: window.location.pathname || (activeSite === "lombok" ? "/lombok" : "/")
            }),
            keepalive: true
          }).catch(function () {});
        } catch (e) {}
      }

      mount(window.PiknikStore.load(activeSite));
      trackPublicVisit();

      window.PiknikStore.fetchCloud(activeSite).then(function (res) {
        if (!res.available) return;
        cloudChecked = true;
        cloudData = res.exists && res.content ? res.content : null;
        mount(cloudData || window.PiknikStore.getDefault(activeSite));
      });

      window.addEventListener("storage", function (e) {
        if (e.key === window.PiknikStore.key && !cloudChecked) {
          mount();
        }
      });
    })();
  </script>
</body>
</html>
