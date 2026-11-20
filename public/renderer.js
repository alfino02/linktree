/**
 * Piknik Enthusiast — Shared Store & Linktree Renderer
 * Digunakan bersama oleh index.html dan live preview di admin.html
 */
(function (global) {
  "use strict";

  var STORAGE_KEY = "piknik_linktree_state_v1";
  var ACTIVE_SITE_ID = "piknik";

  function normalizeSiteId(siteId) {
    var s = String(siteId || "").trim().toLowerCase();
    return s === "lombok" ? "lombok" : "piknik";
  }

  function getSiteMeta(siteId) {
    var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
    var reg = (global.LINKTREE_SITES && global.LINKTREE_SITES[id]) || null;
    if (reg) return reg;
    if (id === "lombok") {
      return {
        id: "lombok",
        label: "Lombok Enthusiast",
        shortLabel: "Lombok",
        regionLabel: "Pulau Lombok",
        publicPath: "/lombok",
        localFile: "lombok.html",
        storageKey: "lombok_linktree_state_v1",
        defaultGlobalKey: "LOMBOK_DEFAULT_DATA"
      };
    }
    return {
      id: "piknik",
      label: "Piknik Enthusiast",
      shortLabel: "Piknik",
      regionLabel: "Jawa Timur",
      publicPath: "/",
      localFile: "index.html",
      storageKey: STORAGE_KEY,
      defaultGlobalKey: "PIKNIK_DEFAULT_DATA"
    };
  }

  function detectSiteIdFromLocation() {
    if (global.LINKTREE_FORCE_SITE) {
      return normalizeSiteId(global.LINKTREE_FORCE_SITE);
    }
    try {
      var params = new URLSearchParams((global.location && global.location.search) || "");
      var qSite = params.get("site");
      if (qSite) return normalizeSiteId(qSite);
    } catch (e) {}
    var pathname = String((global.location && global.location.pathname) || "").toLowerCase();
    if (/\/lombok(?:\.html|\/|$)/i.test(pathname)) {
      return "lombok";
    }
    var hostname = String((global.location && global.location.hostname) || "").toLowerCase();
    if (hostname.indexOf("lombok") !== -1) {
      return "lombok";
    }
    return "piknik";
  }

  ACTIVE_SITE_ID = detectSiteIdFromLocation();

  var FALLBACK_IMG =
    "data:image/svg+xml;charset=UTF-8," +
    encodeURIComponent(
      '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">' +
        '<rect width="120" height="120" fill="#efeae0"/>' +
        '<path d="M25 84l22-26 17 19 13-14 18 21H25z" fill="#c7bfb1"/>' +
        '<circle cx="42" cy="42" r="9" fill="#c7bfb1"/>' +
      "</svg>"
    );

  var CATEGORY_LABELS = {
    all: "Semua",
    gunung: "Gunung & Kawah",
    pantai: "Pantai & Pulau",
    alam: "Alam & Kota"
  };

  var CATEGORY_ICONS = {
    all: "compass",
    gunung: "mountain",
    pantai: "palm",
    alam: "nature"
  };

  function escapeHtml(value) {
    if (value === null || value === undefined) return "";
    return String(value)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#39;");
  }

  function sanitizeUrl(url, allowDataImage) {
    if (!url || typeof url !== "string") return "#";
    var trimmed = url.trim();
    if (!trimmed) return "#";
    if (allowDataImage) {
      if (/^data:image\/(png|jpeg|jpg|webp|gif|x-icon|vnd\.microsoft\.icon);base64,[a-zA-Z0-9+/=]+$/i.test(trimmed)) {
        return trimmed;
      }
      if (/^data:image\/svg\+xml;base64,[a-zA-Z0-9+/=]+$/i.test(trimmed)) {
        return trimmed;
      }
      if (/^data:image\/svg\+xml(?:;charset=utf-?8)?,[a-zA-Z0-9\-_.!~*'()%]+$/i.test(trimmed)) {
        return trimmed;
      }
    }
    if (/^(https?:\/\/|mailto:|tel:|\/|#)/i.test(trimmed)) {
      return trimmed;
    }
    return "https://" + trimmed.replace(/^\/+/, "");
  }

  function normalizePhone(raw) {
    var digits = String(raw || "").replace(/\D/g, "");
    if (!digits) return "6282234784612";
    if (digits.indexOf("0") === 0) return "62" + digits.slice(1);
    if (digits.indexOf("8") === 0) return "62" + digits;
    return digits;
  }

  function buildWaUrl(phone, message) {
    var clean = normalizePhone(phone);
    var text = message ? "?text=" + encodeURIComponent(message) : "";
    return "https://wa.me/" + clean + text;
  }

  function deepClone(obj) {
    return JSON.parse(JSON.stringify(obj));
  }

  function sanitizeHexColor(raw, fallback) {
    var safeFallback = fallback || "#0e2040";
    if (typeof raw !== "string") return safeFallback;
    var s = raw.trim();
    if (/^#[0-9a-fA-F]{6}$/.test(s)) return s.toLowerCase();
    if (/^#[0-9a-fA-F]{3}$/.test(s)) {
      return (
        "#" +
        s.charAt(1) + s.charAt(1) +
        s.charAt(2) + s.charAt(2) +
        s.charAt(3) + s.charAt(3)
      ).toLowerCase();
    }
    return safeFallback;
  }

  function clampAngle(raw, fallback) {
    var n = Number(raw);
    if (!isFinite(n)) return fallback || 145;
    n = Math.round(n) % 360;
    return n < 0 ? n + 360 : n;
  }

  function hexToRgb(hex) {
    var clean = sanitizeHexColor(hex, "#0e2040");
    return [
      parseInt(clean.slice(1, 3), 16),
      parseInt(clean.slice(3, 5), 16),
      parseInt(clean.slice(5, 7), 16)
    ];
  }

  function getLuminance(hex) {
    var rgb = hexToRgb(hex);
    var lin = rgb.map(function (v) {
      var c = v / 255;
      return c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
    });
    return 0.2126 * lin[0] + 0.7152 * lin[1] + 0.0722 * lin[2];
  }

  function mixHex(hexA, hexB, weightB) {
    var a = hexToRgb(hexA);
    var b = hexToRgb(hexB);
    var w = Math.max(0, Math.min(1, Number(weightB) || 0));
    var toHex = function (num) {
      var h = Math.round(num).toString(16);
      return h.length === 1 ? "0" + h : h;
    };
    return (
      "#" +
      toHex(a[0] * (1 - w) + b[0] * w) +
      toHex(a[1] * (1 - w) + b[1] * w) +
      toHex(a[2] * (1 - w) + b[2] * w)
    );
  }

  function hexToRgba(hex, alpha) {
    var rgb = hexToRgb(hex);
    return "rgba(" + rgb[0] + ", " + rgb[1] + ", " + rgb[2] + ", " + alpha + ")";
  }

  function buildCustomThemeConfig(custom) {
    var src = custom || {};
    var bgMode = src.bgMode === "solid" ? "solid" : "gradient";
    var bgStart = sanitizeHexColor(src.bgStart, "#0f2547");
    var bgEnd = sanitizeHexColor(src.bgEnd, "#3b2338");
    var bgAngle = clampAngle(src.bgAngle, 145);
    var accent = sanitizeHexColor(src.accent, "#f48526");
    var ctaBg = sanitizeHexColor(src.ctaBg, "#ea6c05");
    var textMode = src.textMode === "light" || src.textMode === "dark" ? src.textMode : "auto";

    var bgLum =
      bgMode === "gradient"
        ? (getLuminance(bgStart) + getLuminance(bgEnd)) / 2
        : getLuminance(bgStart);
    var tone =
      textMode === "light"
        ? "dark"
        : textMode === "dark"
          ? "light"
          : bgLum < 0.36
            ? "dark"
            : "light";

    var bgGradient =
      bgMode === "gradient"
        ? "linear-gradient(" + bgAngle + "deg, " + bgStart + " 0%, " + bgEnd + " 100%)"
        : "linear-gradient(transparent, transparent)";

    var ctaIsDark = getLuminance(ctaBg) < 0.42;
    var ctaText = ctaIsDark ? "#ffffff" : "#0f172a";
    var ctaSub = ctaIsDark ? "rgba(255, 255, 255, 0.86)" : "rgba(15, 23, 42, 0.74)";
    var ctaHover = ctaIsDark ? mixHex(ctaBg, "#ffffff", 0.12) : mixHex(ctaBg, "#0f172a", 0.08);
    var ctaGradient =
      "linear-gradient(135deg, " +
      ctaBg +
      " 0%, " +
      (ctaIsDark ? mixHex(ctaBg, "#ffffff", 0.16) : mixHex(ctaBg, "#ffffff", 0.22)) +
      " 100%)";

    var accentLum = getLuminance(accent);
    var vars = [];

    if (tone === "dark") {
      var tintedSurface = mixHex(bgStart, "#ffffff", 0.12);
      var heroDeep = mixHex(bgStart, "#ffffff", 0.2);
      var heroMid = mixHex(bgStart, "#ffffff", 0.08);
      var accentReadable = accentLum < 0.34 ? mixHex(accent, "#ffffff", 0.48) : accent;
      vars = [
        "--lt-bg:" + bgStart,
        "--lt-bg-gradient:" + bgGradient,
        "--lt-bg-mesh:radial-gradient(ellipse 85% 50% at 88% -5%, " + hexToRgba(accent, 0.2) + " 0%, transparent 56%), radial-gradient(ellipse 80% 46% at 10% 8%, rgba(255, 255, 255, 0.09) 0%, transparent 55%)",
        "--lt-surface:" + tintedSurface,
        "--lt-surface-glass:" + hexToRgba(tintedSurface, 0.78),
        "--lt-card-bg:linear-gradient(155deg, " + hexToRgba(heroDeep, 0.74) + " 0%, " + hexToRgba(tintedSurface, 0.88) + " 100%)",
        "--lt-hero-surface:" + hexToRgba(tintedSurface, 0.9),
        "--lt-hero-body-bg:linear-gradient(155deg, " + heroDeep + " 0%, " + heroMid + " 60%, " + bgStart + " 100%)",
        "--lt-surface-hover:" + hexToRgba(mixHex(bgStart, "#ffffff", 0.22), 0.92),
        "--lt-surface-subtle:rgba(255, 255, 255, 0.12)",
        "--lt-border:rgba(255, 255, 255, 0.18)",
        "--lt-border-hover:rgba(255, 255, 255, 0.34)",
        "--lt-badge-border:" + hexToRgba(accentReadable, 0.38),
        "--lt-wave-1:" + hexToRgba(mixHex(bgEnd, "#ffffff", 0.16), 0.34),
        "--lt-wave-2:" + hexToRgba(mixHex(bgStart, "#ffffff", 0.22), 0.22),
        "--lt-wave-3:rgba(255, 255, 255, 0.08)",
        "--lt-hero-wave-1:rgba(255, 255, 255, 0.14)",
        "--lt-hero-wave-2:rgba(0, 0, 0, 0.22)",
        "--lt-text:#f8fafc",
        "--lt-text-secondary:rgba(248, 250, 252, 0.82)",
        "--lt-text-muted:rgba(248, 250, 252, 0.6)",
        "--lt-accent:" + accent,
        "--lt-accent-soft:" + hexToRgba(accent, 0.22),
        "--lt-accent-text:" + accentReadable,
        "--lt-cta-bg:" + ctaBg,
        "--lt-cta-gradient:" + ctaGradient,
        "--lt-cta-hover:" + ctaHover,
        "--lt-cta-text:" + ctaText,
        "--lt-cta-sub:" + ctaSub,
        "--lt-pill-active-bg:" + ctaBg,
        "--lt-pill-active-text:" + ctaText,
        "--lt-cover-gradient:linear-gradient(135deg, " + bgStart + " 0%, " + bgEnd + " 60%, " + accent + " 100%)",
        "--lt-shadow:0 2px 10px rgba(0, 0, 0, 0.22)",
        "--lt-shadow-hover:0 12px 26px -8px rgba(0, 0, 0, 0.48)",
        "--lt-hero-shadow:0 16px 38px -14px rgba(0, 0, 0, 0.56)",
        "--lt-verified:#38bdf8"
      ];
    } else {
      var accentDarkReadable = accentLum > 0.42 ? mixHex(accent, "#0f172a", 0.35) : accent;
      var lightTint = mixHex(bgStart, accent, 0.08);
      vars = [
        "--lt-bg:" + bgStart,
        "--lt-bg-gradient:" + bgGradient,
        "--lt-bg-mesh:radial-gradient(ellipse 88% 50% at 90% -4%, " + hexToRgba(accent, 0.13) + " 0%, transparent 58%), radial-gradient(ellipse 80% 45% at 8% 10%, rgba(255, 255, 255, 0.48) 0%, transparent 55%)",
        "--lt-surface:#ffffff",
        "--lt-surface-glass:rgba(255, 255, 255, 0.86)",
        "--lt-card-bg:linear-gradient(160deg, rgba(255, 255, 255, 0.94) 0%, rgba(255, 255, 255, 0.84) 100%)",
        "--lt-hero-surface:rgba(255, 255, 255, 0.94)",
        "--lt-hero-body-bg:linear-gradient(155deg, #ffffff 0%, " + lightTint + " 100%)",
        "--lt-surface-hover:#ffffff",
        "--lt-surface-subtle:rgba(15, 23, 42, 0.06)",
        "--lt-border:rgba(15, 23, 42, 0.1)",
        "--lt-border-hover:rgba(15, 23, 42, 0.24)",
        "--lt-badge-border:" + hexToRgba(accentDarkReadable, 0.22),
        "--lt-wave-1:" + hexToRgba(ctaBg, 0.08),
        "--lt-wave-2:" + hexToRgba(accent, 0.09),
        "--lt-wave-3:rgba(255, 255, 255, 0.35)",
        "--lt-hero-wave-1:" + hexToRgba(ctaBg, 0.06),
        "--lt-hero-wave-2:" + hexToRgba(accent, 0.08),
        "--lt-text:#0f172a",
        "--lt-text-secondary:#475569",
        "--lt-text-muted:#718096",
        "--lt-accent:" + accent,
        "--lt-accent-soft:" + hexToRgba(accent, 0.14),
        "--lt-accent-text:" + accentDarkReadable,
        "--lt-cta-bg:" + ctaBg,
        "--lt-cta-gradient:" + ctaGradient,
        "--lt-cta-hover:" + ctaHover,
        "--lt-cta-text:" + ctaText,
        "--lt-cta-sub:" + ctaSub,
        "--lt-pill-active-bg:" + ctaBg,
        "--lt-pill-active-text:" + ctaText,
        "--lt-cover-gradient:linear-gradient(135deg, " + mixHex(ctaBg, "#0f172a", 0.25) + " 0%, " + ctaBg + " 55%, " + accent + " 100%)",
        "--lt-shadow:0 2px 8px -3px rgba(15, 23, 42, 0.06)",
        "--lt-shadow-hover:0 10px 24px -10px rgba(15, 23, 42, 0.14)",
        "--lt-hero-shadow:0 12px 32px -14px rgba(15, 23, 42, 0.14), 0 1px 2px rgba(15, 23, 42, 0.04)",
        "--lt-verified:#1476b9"
      ];
    }

    return {
      tone: tone,
      styleAttr: vars.join(";")
    };
  }

  function getRawDefaultForSite(siteId) {
    var meta = getSiteMeta(siteId);
    var raw = global[meta.defaultGlobalKey] || global.PIKNIK_DEFAULT_DATA || {};
    return deepClone(raw);
  }

  function mergeDefaults(saved, siteId) {
    var base = getRawDefaultForSite(siteId);
    if (!saved || typeof saved !== "object") return base;
    var savedAp = saved.appearance || {};
    var baseAp = base.appearance || {};
    return {
      profile: Object.assign({}, base.profile, saved.profile || {}),
      cta: Object.assign({}, base.cta, saved.cta || {}),
      socials: Array.isArray(saved.socials) ? saved.socials : base.socials,
      quickLinks: Array.isArray(saved.quickLinks) ? saved.quickLinks : base.quickLinks,
      destinations: Array.isArray(saved.destinations) ? saved.destinations : base.destinations,
      appearance: Object.assign({}, baseAp, savedAp, {
        customTheme: Object.assign({}, baseAp.customTheme || {}, savedAp.customTheme || {}),
        savedThemes: Array.isArray(savedAp.savedThemes) ? savedAp.savedThemes : (baseAp.savedThemes || [])
      }),
      settings: Object.assign({}, base.settings, saved.settings || {})
    };
  }

  var Store = {
    key: getSiteMeta(ACTIVE_SITE_ID).storageKey,
    normalizeSiteId: normalizeSiteId,
    detectSiteId: detectSiteIdFromLocation,
    getSite: function () {
      return ACTIVE_SITE_ID;
    },
    setSite: function (siteId) {
      ACTIVE_SITE_ID = normalizeSiteId(siteId);
      this.key = getSiteMeta(ACTIVE_SITE_ID).storageKey;
      return ACTIVE_SITE_ID;
    },
    getSiteMeta: function (siteId) {
      return getSiteMeta(siteId || ACTIVE_SITE_ID);
    },
    getSites: function () {
      return [getSiteMeta("piknik"), getSiteMeta("lombok")];
    },
    getDefault: function (siteId) {
      return getRawDefaultForSite(siteId || ACTIVE_SITE_ID);
    },
    merge: function (saved, siteId) {
      return mergeDefaults(saved, siteId || ACTIVE_SITE_ID);
    },
    load: function (siteId) {
      var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
      var key = getSiteMeta(id).storageKey;
      try {
        var raw = global.localStorage.getItem(key);
        if (!raw) return this.getDefault(id);
        return mergeDefaults(JSON.parse(raw), id);
      } catch (e) {
        return this.getDefault(id);
      }
    },
    hasLocalDraft: function (siteId) {
      var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
      var key = getSiteMeta(id).storageKey;
      try {
        return !!global.localStorage.getItem(key);
      } catch (e) {
        return false;
      }
    },
    save: function (data, siteId) {
      var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
      var key = getSiteMeta(id).storageKey;
      try {
        global.localStorage.setItem(key, JSON.stringify(data));
        return true;
      } catch (e) {
        return false;
      }
    },
    reset: function (siteId) {
      var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
      var key = getSiteMeta(id).storageKey;
      try {
        global.localStorage.removeItem(key);
      } catch (e) {}
      return this.getDefault(id);
    },
    fetchCloud: function (siteId) {
      var id = normalizeSiteId(siteId || ACTIVE_SITE_ID);
      if (global.location.protocol === "file:" || !global.fetch) {
        return Promise.resolve({ available: false, site: id });
      }
      return global
        .fetch("/api/content?site=" + encodeURIComponent(id), { cache: "no-store" })
        .then(function (res) {
          if (!res.ok) return { available: false, site: id };
          var ct = res.headers.get("content-type") || "";
          if (ct.indexOf("application/json") === -1) return { available: false, site: id };
          return res.json().then(function (json) {
            return {
              available: true,
              site: id,
              exists: !!json.exists,
              content: json.exists && json.content ? mergeDefaults(json.content, id) : null
            };
          });
        })
        .catch(function () {
          return { available: false, site: id };
        });
    },
    postCloud: function (payload) {
      var bodyObj = Object.assign({ site: ACTIVE_SITE_ID }, payload || {});
      return global
        .fetch("/api/content", {
          method: "POST",
          headers: { "content-type": "application/json" },
          body: JSON.stringify(bodyObj)
        })
        .then(function (res) {
          return res.json().then(function (json) {
            return Object.assign({ status: res.status }, json);
          });
        });
    }
  };

  /* Clean inline SVG icon dictionary */
  var ICONS = {
    verified:
      '<svg class="lt-verified-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
      '<path d="M12 2l2.39 1.72 2.94-.32 1.18 2.71 2.71 1.18-.32 2.94L22 12l-1.72 2.39.32 2.94-2.71 1.18-1.18 2.71-2.94-.32L12 22l-2.39-1.72-2.94.32-1.18-2.71-2.71-1.18.32-2.94L2 12l1.72-2.39-.32-2.94 2.71-1.18 1.18-2.71 2.94.32L12 2zm-1.1 13.4l5.2-5.2-1.4-1.4-3.8 3.79-1.7-1.7-1.4 1.42 3.1 3.09z"/>' +
      "</svg>",
    pin:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M12 21s-6-5.33-6-10a6 6 0 1 1 12 0c0 4.67-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/>' +
      "</svg>",
    share:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M4 12v7a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-7"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/>' +
      "</svg>",
    whatsapp:
      '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
      '<path d="M12.04 2a9.9 9.9 0 0 0-8.52 14.95L2 22l5.2-1.46A9.92 9.92 0 0 0 12.04 22c5.48 0 9.94-4.46 9.94-9.96A9.95 9.95 0 0 0 12.04 2zm5.77 14.12c-.24.68-1.4 1.3-1.94 1.38-.5.07-1.12.1-1.8-.12-.42-.14-.96-.32-1.65-.62-2.9-1.26-4.79-4.19-4.94-4.38-.14-.19-1.18-1.57-1.18-3 0-1.42.74-2.12 1-2.41.26-.29.58-.36.77-.36h.55c.18 0 .42-.07.65.5.24.58.82 2 .9 2.15.07.14.12.31.02.5-.1.19-.14.31-.29.48-.14.17-.31.38-.44.5-.14.15-.3.31-.13.61.17.29.75 1.24 1.62 2 1.11.99 2.05 1.3 2.34 1.44.29.15.46.12.63-.07.17-.19.72-.84.91-1.13.19-.29.39-.24.65-.14.26.1 1.68.79 1.97.94.29.14.48.21.55.33.07.12.07.7-.17 1.38z"/>' +
      "</svg>",
    arrowRight:
      '<svg class="lt-cta-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>' +
      "</svg>",
    chevronRight:
      '<svg class="lt-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<polyline points="9 18 15 12 9 6"/>' +
      "</svg>",
    search:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>' +
      "</svg>",
    /* Quick link icons */
    tag:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><circle cx="7" cy="7" r="1.2" fill="currentColor"/>' +
      "</svg>",
    compass:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<circle cx="12" cy="12" r="9"/><polygon points="16 8 14 14 8 16 10 10 16 8"/>' +
      "</svg>",
    sparkles:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M12 3l1.8 4.2L18 9l-4.2 1.8L12 15l-1.8-4.2L6 9l4.2-1.8L12 3z"/><path d="M19 15l.9 2.1L22 18l-2.1.9L19 21l-.9-2.1L16 18l2.1-.9L19 15z"/>' +
      "</svg>",
    camera:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>' +
      "</svg>",
    calendar:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>' +
      "</svg>",
    link:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>' +
      "</svg>",
    /* Social icons */
    website:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<circle cx="12" cy="12" r="9"/><line x1="3" y1="12" x2="21" y2="12"/><path d="M12 3a14 14 0 0 1 0 18 14 14 0 0 1 0-18z"/>' +
      "</svg>",
    instagram:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.8" fill="currentColor"/>' +
      "</svg>",
    tiktok:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5"/>' +
      "</svg>",
    linkedin:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>' +
      "</svg>",
    maps:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M12 21s-6-5.33-6-10a6 6 0 1 1 12 0c0 4.67-6 10-6 10z"/><circle cx="12" cy="11" r="2.2"/>' +
      "</svg>",
    email:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/>' +
      "</svg>",
    /* Destination category & card UI icons */
    mountain:
      '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
      '<path d="M9.6 5.2a1 1 0 0 1 1.72 0l5.9 10.22A1 1 0 0 1 16.36 17H2.84a1 1 0 0 1-.86-1.5L9.6 5.2z"/>' +
      '<path d="M16.5 9.2a.9.9 0 0 1 1.54 0l3.7 6.32a.9.9 0 0 1-.77 1.48h-4.7l.23-7.8z" opacity="0.82"/>' +
      "</svg>",
    palm:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<path d="M11 19.5c-.4-3.8.1-7.2 1.5-10.2"/>' +
      '<path d="M12.5 9.3C9.8 6.5 6.4 6.1 4 7.8"/>' +
      '<path d="M12.5 9.3c-1.7-3.6-.3-5.9 2.2-6.6"/>' +
      '<path d="M12.5 9.3c2.5-2.3 5.8-2.2 8-.3"/>' +
      '<path d="M12.5 9.3c2.3.3 4.5 1.7 5.5 3.8"/>' +
      '<path d="M4.5 20.2c2.3-1.2 4.9-1.7 7.5-1.7s5.2.5 7.5 1.7"/>' +
      "</svg>",
    nature:
      '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' +
      '<rect x="3.5" y="5" width="9" height="15" rx="1"/>' +
      '<line x1="6.8" y1="9" x2="6.8" y2="9.01"/><line x1="9.6" y1="9" x2="9.6" y2="9.01"/>' +
      '<line x1="6.8" y1="13" x2="6.8" y2="13.01"/><line x1="9.6" y1="13" x2="9.6" y2="13.01"/>' +
      '<circle cx="17.2" cy="12" r="3"/>' +
      '<line x1="17.2" y1="15" x2="17.2" y2="20"/>' +
      '<line x1="2" y1="20" x2="22" y2="20"/>' +
      "</svg>",
    thumbsUp:
      '<svg class="lt-badge-icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">' +
      '<path d="M7.5 10.5H4A1.5 1.5 0 0 0 2.5 12v7A1.5 1.5 0 0 0 4 20.5h3.5v-10zm2 10h6.62a2 2 0 0 0 1.94-1.52l1.2-4.8a2 2 0 0 0-1.94-2.48H13.5V7.2A2.2 2.2 0 0 0 11.3 5h-.35L8.9 10.3a1.5 1.5 0 0 0-.4 1.03V19a1.5 1.5 0 0 0 1 1.5z"/>' +
      "</svg>"
  };

  var SAFE_SVG_TAGS = {
    svg: "svg",
    g: "g",
    path: "path",
    circle: "circle",
    rect: "rect",
    line: "line",
    polyline: "polyline",
    polygon: "polygon",
    ellipse: "ellipse",
    defs: "defs",
    lineargradient: "linearGradient",
    radialgradient: "radialGradient",
    stop: "stop",
    clippath: "clipPath",
    mask: "mask",
    title: "title"
  };

  var SAFE_SVG_ATTRS = {
    id: "id",
    "class": "class",
    style: "style",
    viewbox: "viewBox",
    preserveaspectratio: "preserveAspectRatio",
    fill: "fill",
    "fill-opacity": "fill-opacity",
    "fill-rule": "fill-rule",
    "clip-rule": "clip-rule",
    "clip-path": "clip-path",
    mask: "mask",
    stroke: "stroke",
    "stroke-width": "stroke-width",
    "stroke-linecap": "stroke-linecap",
    "stroke-linejoin": "stroke-linejoin",
    "stroke-miterlimit": "stroke-miterlimit",
    "stroke-dasharray": "stroke-dasharray",
    "stroke-dashoffset": "stroke-dashoffset",
    "stroke-opacity": "stroke-opacity",
    opacity: "opacity",
    color: "color",
    d: "d",
    cx: "cx",
    cy: "cy",
    r: "r",
    rx: "rx",
    ry: "ry",
    fx: "fx",
    fy: "fy",
    x: "x",
    y: "y",
    x1: "x1",
    y1: "y1",
    x2: "x2",
    y2: "y2",
    width: "width",
    height: "height",
    points: "points",
    transform: "transform",
    offset: "offset",
    "stop-color": "stop-color",
    "stop-opacity": "stop-opacity",
    gradientunits: "gradientUnits",
    gradienttransform: "gradientTransform",
    spreadmethod: "spreadMethod"
  };

  function isSafeSvgAttrValue(attrLower, val) {
    if (!val || /(?:javascript|vbscript|data):|expression\s*\(|@import|behavior\s*:|-moz-binding/i.test(val)) {
      return false;
    }
    if (/url\s*\(/i.test(val)) {
      if (attrLower === "style") return false;
      return /^url\(\s*#[a-zA-Z0-9_\-:]+\s*\)$/i.test(val.trim());
    }
    return true;
  }

  function sanitizeSvgNode(node, opts) {
    if (!node || node.nodeType !== 1) return "";
    var lowerTag = (node.localName || node.tagName || "").toLowerCase();
    if (lowerTag === "style") {
      if (!opts.allowStyleTag) return "";
      var cssText = String(node.textContent || "");
      if (/(?:@import|url\s*\(|expression\s*\(|javascript:|<)/i.test(cssText)) return "";
      return "<style>" + cssText + "</style>";
    }
    var canonTag = SAFE_SVG_TAGS[lowerTag];
    if (!canonTag) return "";

    var attrsStr = "";
    var isRoot = lowerTag === "svg" && opts.isRoot;
    var hasViewBox = false;
    var hasFill = false;
    var hasStroke = false;
    var vbW = "";
    var vbH = "";

    var attrs = node.attributes || [];
    for (var i = 0; i < attrs.length; i++) {
      var rawName = attrs[i].name;
      var lowerName = rawName.toLowerCase();
      if (lowerName.indexOf("on") === 0 || lowerName === "xmlns" || lowerName.indexOf("xmlns:") === 0) continue;
      if (isRoot && lowerName === "viewbox") hasViewBox = true;
      if (isRoot && lowerName === "fill") hasFill = true;
      if (isRoot && lowerName === "stroke") hasStroke = true;
      if (isRoot && (lowerName === "width" || lowerName === "height")) {
        var numMatch = String(attrs[i].value || "").trim().match(/^([0-9]+(?:\.[0-9]+)?)(?:px)?$/i);
        if (numMatch && Number(numMatch[1]) > 0) {
          if (lowerName === "width") vbW = numMatch[1];
          if (lowerName === "height") vbH = numMatch[1];
        }
        if (opts.forInline) continue;
      }
      var canonAttr = SAFE_SVG_ATTRS[lowerName];
      if (!canonAttr) continue;
      var val = attrs[i].value;
      if (!isSafeSvgAttrValue(lowerName, val)) continue;
      attrsStr += " " + canonAttr + '="' + escapeHtml(val) + '"';
    }

    if (canonTag === "title") {
      return "<title>" + escapeHtml(node.textContent || "") + "</title>";
    }

    var childrenStr = "";
    var childNodes = node.childNodes || [];
    for (var j = 0; j < childNodes.length; j++) {
      childrenStr += sanitizeSvgNode(childNodes[j], {
        allowStyleTag: opts.allowStyleTag,
        forInline: opts.forInline,
        isRoot: false
      });
    }

    if (isRoot) {
      var prefix = ' xmlns="http://www.w3.org/2000/svg"';
      if (!hasViewBox) {
        prefix += ' viewBox="0 0 ' + (vbW || "24") + " " + (vbH || "24") + '"';
      }
      if (opts.forInline && !hasFill && !hasStroke) {
        prefix += ' fill="currentColor"';
      }
      if (opts.forInline) {
        prefix += ' aria-hidden="true"';
      }
      attrsStr = prefix + attrsStr;
    }

    return "<" + canonTag + attrsStr + ">" + childrenStr + "</" + canonTag + ">";
  }

  function parseSvgDocument(rawSvg) {
    if (!rawSvg || typeof rawSvg !== "string" || typeof global.DOMParser === "undefined") return null;
    var trimmed = rawSvg.trim();
    if (!trimmed) return null;
    try {
      var parser = new global.DOMParser();
      var doc = parser.parseFromString(trimmed, "image/svg+xml");
      if (!doc || !doc.documentElement) return null;
      if (doc.getElementsByTagName("parsererror").length > 0) return null;
      if ((doc.documentElement.localName || doc.documentElement.tagName || "").toLowerCase() !== "svg") {
        return null;
      }
      return doc.documentElement;
    } catch (e) {
      return null;
    }
  }

  function sanitizeSvgMarkup(rawSvg, allowStyleTag) {
    var root = parseSvgDocument(rawSvg);
    if (!root) return null;
    if (!allowStyleTag && root.getElementsByTagName("style").length > 0) return null;
    var clean = sanitizeSvgNode(root, {
      allowStyleTag: !!allowStyleTag,
      forInline: !allowStyleTag,
      isRoot: true
    });
    return clean && clean.indexOf("<svg") === 0 ? clean : null;
  }

  function svgToDataUrl(rawSvg) {
    var clean = sanitizeSvgMarkup(rawSvg, true);
    if (!clean) return "";
    return "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(clean);
  }

  function decodeSvgDataUrl(dataUrl) {
    var commaIdx = dataUrl.indexOf(",");
    if (commaIdx === -1) return "";
    var header = dataUrl.slice(0, commaIdx);
    var body = dataUrl.slice(commaIdx + 1);
    try {
      if (/;base64$/i.test(header)) {
        var bin = global.atob(body);
        if (typeof global.TextDecoder !== "undefined") {
          var bytes = new Uint8Array(bin.length);
          for (var i = 0; i < bin.length; i++) bytes[i] = bin.charCodeAt(i);
          return new global.TextDecoder("utf-8").decode(bytes);
        }
        return bin;
      }
      return decodeURIComponent(body);
    } catch (e) {
      return "";
    }
  }

  function isCustomIconValue(val) {
    if (!val || typeof val !== "string") return false;
    var t = val.trim();
    if (!t || ICONS[t]) return false;
    return /^(data:image\/|https?:\/\/|\/|<svg[\s>])/i.test(t);
  }

  function getIconSvg(name, fallbackName) {
    if (typeof name === "string") {
      var trimmed = name.trim();
      if (ICONS[trimmed]) return ICONS[trimmed];
      if (/^<svg[\s>]/i.test(trimmed)) {
        var inlineFromRaw = sanitizeSvgMarkup(trimmed, false);
        if (inlineFromRaw) return inlineFromRaw;
        var rawAsData = svgToDataUrl(trimmed);
        if (rawAsData) return '<img class="lt-icon-img" src="' + escapeHtml(rawAsData) + '" alt="" />';
      }
      if (/^data:image\/svg\+xml/i.test(trimmed)) {
        var decodedSvg = decodeSvgDataUrl(trimmed);
        var inlineSvg = sanitizeSvgMarkup(decodedSvg, false);
        if (inlineSvg) return inlineSvg;
        var safeSvgUrl = svgToDataUrl(decodedSvg);
        if (safeSvgUrl) return '<img class="lt-icon-img" src="' + escapeHtml(safeSvgUrl) + '" alt="" />';
      }
      if (/^(data:image\/|https?:\/\/|\/)/i.test(trimmed)) {
        var safeSrc = sanitizeUrl(trimmed, true);
        if (safeSrc !== "#") {
          return '<img class="lt-icon-img" src="' + escapeHtml(safeSrc) + '" alt="" loading="lazy" />';
        }
      }
    }
    return ICONS[fallbackName || "link"];
  }

  function showToast(msg) {
    var toast = document.getElementById("ltToast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "ltToast";
      toast.className = "lt-toast";
      document.body.appendChild(toast);
    }
    toast.textContent = msg;
    toast.classList.add("is-visible");
    clearTimeout(showToast._timer);
    showToast._timer = setTimeout(function () {
      toast.classList.remove("is-visible");
    }, 2200);
  }

  function handleShare(profile) {
    var shareUrl = global.location.protocol === "file:" ? "https://piknikenthusiast.com/" : global.location.href;
    var shareTitle = (profile && profile.name) || "Piknik Enthusiast";
    if (navigator.share) {
      navigator
        .share({ title: shareTitle, text: (profile && profile.tagline) || "", url: shareUrl })
        .catch(function () {});
      return;
    }
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard
        .writeText(shareUrl)
        .then(function () {
          showToast("Tautan disalin ke clipboard");
        })
        .catch(function () {
          showToast("Salin URL di browser Anda");
        });
    } else {
      showToast("Salin URL di browser Anda");
    }
  }

  function renderDestinationCards(listContainer, destinations, appearance) {
    if (!listContainer) return;

    if (!destinations || !destinations.length) {
      listContainer.className = "";
      listContainer.innerHTML = '<div class="lt-empty">Tidak ada destinasi yang sesuai.</div>';
      return;
    }

    var isGrid = appearance.destLayout === "grid";
    listContainer.className = isGrid ? "lt-dest-grid" : "lt-dest-list";

    var html = destinations
      .map(function (dest) {
        var href = sanitizeUrl(dest.url, false);
        var thumb = sanitizeUrl(dest.image, true);
        if (thumb === "#") thumb = FALLBACK_IMG;

        var badgeHtml = "";
        if (appearance.showBadges && dest.badge) {
          var isPop = /populer/i.test(String(dest.badge));
          badgeHtml =
            '<span class="lt-badge lt-dest-badge' +
            (isPop ? " is-popular" : "") +
            '">' +
            (isPop ? ICONS.thumbsUp : "") +
            "<span>" +
            escapeHtml(dest.badge) +
            "</span>" +
            "</span>";
        }

        var locHtml = dest.location
          ? '<span class="lt-dest-meta-row lt-dest-loc">' +
            ICONS.maps +
            "<span>" +
            escapeHtml(dest.location) +
            "</span></span>"
          : "";

        var priceHtml =
          appearance.showPrice && dest.price
            ? '<span class="lt-dest-meta-row lt-dest-price">' +
              ICONS.tag +
              "<span>" +
              escapeHtml(dest.price) +
              "</span></span>"
            : "";

        return (
          '<a class="lt-dest-card" href="' +
          escapeHtml(href) +
          '" target="_blank" rel="noopener noreferrer">' +
          '<div class="lt-dest-media">' +
          '<img class="lt-dest-thumb" src="' +
          escapeHtml(thumb) +
          '" alt="' +
          escapeHtml(dest.name) +
          '" loading="lazy" onerror="this.onerror=null;this.src=\'' +
          FALLBACK_IMG +
          '\'" />' +
          '<div class="lt-dest-media-scrim" aria-hidden="true"></div>' +
          "</div>" +
          '<div class="lt-dest-body">' +
          '<div class="lt-dest-title-row">' +
          '<h3 class="lt-dest-name">' +
          escapeHtml(dest.name) +
          "</h3>" +
          badgeHtml +
          "</div>" +
          '<div class="lt-dest-meta-stack">' +
          locHtml +
          priceHtml +
          "</div>" +
          "</div>" +
          ICONS.chevronRight +
          "</a>"
        );
      })
      .join("");

    listContainer.innerHTML = html;
  }

  function renderLinktree(container, data, options) {
    var opts = Object.assign(
      {
        embedded: false,
        activeCategory: "all",
        searchQuery: ""
      },
      options || {}
    );

    var profile = data.profile || {};
    var cta = data.cta || {};
    var appearance = data.appearance || {};
    var settings = data.settings || {};

    var activeSocials = (data.socials || []).filter(function (s) {
      return s && s.enabled !== false && s.url;
    });
    var activeLinks = (data.quickLinks || []).filter(function (l) {
      return l && l.enabled !== false && l.title;
    });
    var activeDestinations = (data.destinations || []).filter(function (d) {
      return d && d.enabled !== false && d.name;
    });

    // Category counts
    var counts = { all: activeDestinations.length, gunung: 0, pantai: 0, alam: 0 };
    activeDestinations.forEach(function (d) {
      if (counts[d.category] !== undefined) counts[d.category] += 1;
    });

    var avatarSrc = sanitizeUrl(profile.avatar, true);
    if (avatarSrc === "#") avatarSrc = FALLBACK_IMG;

    var ctaRawUrl = cta.url && String(cta.url).trim();
    var ctaHref = ctaRawUrl ? sanitizeUrl(ctaRawUrl, false) : buildWaUrl(cta.phone, cta.prefill);
    var defaultCtaIcon =
      ctaRawUrl && !/wa\.me|whatsapp/i.test(ctaRawUrl) ? "compass" : "whatsapp";
    var mapsHref = sanitizeUrl(profile.mapsUrl, false);

    // Build social buttons
    var socialsHtml = activeSocials
      .map(function (s) {
        return (
          '<a class="lt-social-btn" href="' +
          escapeHtml(sanitizeUrl(s.url, false)) +
          '" target="_blank" rel="noopener noreferrer" aria-label="' +
          escapeHtml(s.label) +
          '" title="' +
          escapeHtml(s.label) +
          '">' +
          getIconSvg(s.icon || s.id, s.id || "website") +
          "</a>"
        );
      })
      .join("");

    // Build quick links
    var quickLinksHtml = activeLinks
      .map(function (link) {
        var badge =
          appearance.showBadges && link.badge
            ? '<span class="lt-badge">' + escapeHtml(link.badge) + "</span>"
            : "";
        return (
          '<a class="lt-link-card" href="' +
          escapeHtml(sanitizeUrl(link.url, false)) +
          '" target="_blank" rel="noopener noreferrer">' +
          '<div class="lt-link-left">' +
          '<div class="lt-link-icon">' +
          getIconSvg(link.icon, "link") +
          "</div>" +
          '<div class="lt-link-body">' +
          '<div class="lt-link-title-row">' +
          '<h3 class="lt-link-title">' +
          escapeHtml(link.title) +
          "</h3>" +
          badge +
          "</div>" +
          (link.subtitle ? '<p class="lt-link-sub">' + escapeHtml(link.subtitle) + "</p>" : "") +
          "</div>" +
          "</div>" +
          ICONS.chevronRight +
          "</a>"
        );
      })
      .join("");

    // Build category filter pills
    var categoriesOrder = ["all", "gunung", "pantai", "alam"];
    var filterPillsHtml = categoriesOrder
      .filter(function (cat) {
        return cat === "all" || counts[cat] > 0;
      })
      .map(function (cat) {
        var activeCls = opts.activeCategory === cat ? " is-active" : "";
        var iconKey = CATEGORY_ICONS[cat] || "compass";
        return (
          '<button type="button" class="lt-filter-pill' +
          activeCls +
          '" data-cat="' +
          cat +
          '">' +
          '<span class="lt-pill-icon" aria-hidden="true">' +
          ICONS[iconKey] +
          "</span>" +
          "<span>" +
          escapeHtml(CATEGORY_LABELS[cat]) +
          " (" +
          counts[cat] +
          ")</span>" +
          "</button>"
        );
      })
      .join("");

    var destSubtitle =
      settings.destSubtitle !== undefined
        ? settings.destSubtitle
        : "Jelajahi keindahan Indonesia bersama kami";

    var rootClasses = "lt-root" + (opts.embedded ? " lt-embedded" : "");
    var siteId = normalizeSiteId((opts && opts.site) || ACTIVE_SITE_ID);
    var isLombokSite = siteId === "lombok";
    var themeVal = appearance.theme || "navy";
    var bgStyleVal = appearance.bgStyle || "wave";
    var radiusVal = appearance.cardRadius || "rounded";
    var isWave = bgStyleVal === "wave";
    var hasPatternBg = isWave || bgStyleVal === "panorama" || bgStyleVal === "topography";
    var isHeroCover = isWave || bgStyleVal === "panorama";
    var isFramed = bgStyleVal === "topography" || bgStyleVal === "mesh";

    var DARK_PRESET_THEMES = { navy: true, sunrise: true, forest: true };
    var customConfig = themeVal === "custom" ? buildCustomThemeConfig(appearance.customTheme) : null;
    var toneVal = customConfig ? customConfig.tone : (DARK_PRESET_THEMES[themeVal] ? "dark" : "light");
    var customStyleAttr = customConfig ? ' style="' + escapeHtml(customConfig.styleAttr) + '"' : "";

    var coverSrc = sanitizeUrl(profile.coverImage, true);
    var wid = (renderLinktree._seq = (renderLinktree._seq || 0) + 1);

    var LOMBOK_ISLAND_PATH =
      "M62,52 C72,54 80,46 92,38 C106,30 124,28 140,33 C154,38 166,48 172,62 C176,74 173,90 167,104 C163,115 166,126 172,138 C177,148 180,160 174,168 C168,174 156,172 150,164 C145,156 144,145 136,145 C129,145 128,156 124,164 C118,170 106,168 95,169 C83,170 72,173 60,170 C46,166 30,164 18,158 C10,154 10,144 18,140 C28,136 44,142 56,138 C64,135 66,126 60,118 C54,110 51,98 54,86 C57,75 53,66 56,58 C58,54 60,52 62,52 Z";
    var LOMBOK_COAST_RING_PATH =
      "M56,44 C70,46 78,36 90,29 C106,20 126,18 145,24 C162,30 175,43 181,60 C186,74 182,92 176,106 C173,116 176,126 181,138 C187,151 189,166 180,176 C171,184 155,181 146,172 C140,165 139,156 136,156 C133,156 131,166 126,173 C117,180 103,178 90,179 C76,180 65,182 52,178 C36,173 18,171 7,163 C-2,156 -1,141 11,134 C23,128 40,133 50,130 C55,128 56,123 52,117 C44,107 41,93 45,79 C48,68 45,58 50,49 C52,45 54,44 56,44 Z";

    function buildLombokIslandMotif(scaleClass, mode) {
      var isHero = mode === "hero";
      var opAcc = isHero ? "0.62" : "0.44";
      var opAccStrong = isHero ? "0.74" : "0.52";
      var opFill = isHero ? "0.13" : "0.08";
      var opTxt = isHero ? "0.38" : "0.24";
      var opTxtSoft = isHero ? "0.28" : "0.18";
      return (
        '<g class="' + (scaleClass || "lt-lombok-island-group") + '" fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        /* Outer bathymetry contour */
        '<path d="' + LOMBOK_COAST_RING_PATH + '" stroke="var(--lt-text)" stroke-opacity="' + opTxtSoft + '" stroke-width="1.15" stroke-dasharray="3.5 4"/>' +
        /* Main Pulau Lombok silhouette & coastline */
        '<path d="' + LOMBOK_ISLAND_PATH + '" fill="var(--lt-accent)" fill-opacity="' + opFill + '" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="' + (isHero ? "1.75" : "1.5") + '"/>' +
        /* 3 Gili Islands (Trawangan, Meno, Air) + Reef halo */
        '<path d="M18,34 C26,28 48,36 56,46 C59,51 54,56 45,55 C34,53 20,46 17,40 C16,37 16,35 18,34 Z" stroke="var(--lt-text)" stroke-opacity="' + opTxtSoft + '" stroke-width="1" stroke-dasharray="2 3"/>' +
        '<ellipse cx="49" cy="49" rx="4.2" ry="2.9" transform="rotate(-18 49 49)" fill="var(--lt-accent)" fill-opacity="0.28" stroke="var(--lt-accent)" stroke-opacity="' + opAccStrong + '" stroke-width="1.3"/>' +
        '<ellipse cx="39" cy="44" rx="3.5" ry="2.5" transform="rotate(-14 39 44)" fill="var(--lt-accent)" fill-opacity="0.28" stroke="var(--lt-accent)" stroke-opacity="' + opAccStrong + '" stroke-width="1.3"/>' +
        '<ellipse cx="27" cy="39" rx="5.5" ry="3.8" transform="rotate(-22 27 39)" fill="var(--lt-accent)" fill-opacity="0.34" stroke="var(--lt-accent)" stroke-opacity="' + opAccStrong + '" stroke-width="1.4"/>' +
        /* Secret Gili Sekotong (Nanggu, Sudak, Kedis) & East Gili */
        '<circle cx="35" cy="131" r="2.2" fill="var(--lt-accent)" fill-opacity="0.26" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.1"/>' +
        '<circle cx="42" cy="129" r="1.8" fill="var(--lt-accent)" fill-opacity="0.26" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.1"/>' +
        '<circle cx="48" cy="132" r="1.7" fill="var(--lt-accent)" fill-opacity="0.26" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.1"/>' +
        '<circle cx="179" cy="76" r="1.9" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.1"/>' +
        '<circle cx="163" cy="173" r="1.8" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.1"/>' +
        /* Gunung Rinjani topographic elevation rings & Danau Segara Anak caldera */
        '<path d="M80,75 C78,52 99,38 124,40 C150,42 164,57 160,79 C156,98 135,106 109,102 C90,98 82,88 80,75 Z" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.2"/>' +
        '<path d="M93,74 C92,59 106,48 124,49 C142,50 152,61 149,76 C146,90 131,95 113,92 C100,89 94,82 93,74 Z" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.2"/>' +
        '<path d="M104,72 C103,63 112,56 124,57 C136,58 142,65 140,74 C138,83 128,86 117,84 C108,82 105,77 104,72 Z" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.25"/>' +
        '<path d="M111,68 C109,75 115,81 124,80 C131,79 135,74 134,69 C129,73 122,74 116,72 C113,71 112,70 111,68 Z" fill="var(--lt-text)" fill-opacity="0.22" stroke="var(--lt-text)" stroke-opacity="' + (isHero ? "0.48" : "0.34") + '" stroke-width="1.15"/>' +
        '<path d="M133,61 L139,70 L127,70 Z" fill="var(--lt-accent)" fill-opacity="0.45" stroke="var(--lt-accent)" stroke-opacity="' + opAccStrong + '" stroke-width="1.15"/>' +
        /* Central valley & Southern hills (Mandalika / Bukit Merese) contours */
        '<path d="M68,116 C88,108 122,110 154,120" stroke="var(--lt-text)" stroke-opacity="' + opTxtSoft + '" stroke-width="1" stroke-dasharray="2.5 3"/>' +
        '<path d="M76,154 C85,145 105,144 118,153 C124,157 115,164 98,164 C83,164 72,159 76,154 Z" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.1"/>' +
        '<path d="M88,155 C93,150 104,150 110,154 C113,157 108,160 99,160 C91,160 85,158 88,155 Z" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.1"/>' +
        /* Maritime island-hopping route (Senggigi -> 3 Gili & Sekotong -> Mandalika -> Pink Beach) */
        '<path d="M53,75 C42,68 32,54 27,43" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.2" stroke-dasharray="2.5 3"/>' +
        '<path d="M35,134 C22,138 6,152 16,166 C34,182 88,184 122,178 C144,174 158,180 166,174" stroke="var(--lt-text)" stroke-opacity="' + opTxtSoft + '" stroke-width="1.05" stroke-dasharray="2.5 3.5"/>' +
        "</g>"
      );
    }

    function buildLombokCulturalMotif(mode) {
      var isHero = mode === "hero";
      var opAcc = isHero ? "0.60" : "0.42";
      var opTxt = isHero ? "0.40" : "0.28";
      return (
        /* Bale Lumbung Sasak traditional curved roof barn */
        '<g fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<path d="M0,-32 C6,-32 15,-16 20,4 C22,10 24,13 28,15 L-28,15 C-24,13 -22,10 -20,4 C-15,-16 -6,-32 0,-32 Z" fill="var(--lt-accent)" fill-opacity="' + (isHero ? "0.14" : "0.08") + '" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="' + (isHero ? "1.6" : "1.35") + '"/>' +
        '<path d="M0,-26 C4,-26 11,-12 15,5 M0,-26 C-4,-26 -11,-12 -15,5" stroke="var(--lt-text)" stroke-opacity="' + (isHero ? "0.32" : "0.20") + '" stroke-width="1.05"/>' +
        '<path d="M0,-17 L5.5,-5.5 L-5.5,-5.5 Z" fill="var(--lt-accent)" fill-opacity="0.22" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.2"/>' +
        '<path d="M-22,15 L22,15 M-18,19 L18,19 M-13,21 L-13,32 M13,21 L13,32 M-5,21 L-5,32 M5,21 L5,32 M-22,32 L22,32" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.25"/>' +
        '<ellipse cx="-13" cy="20" rx="2.8" ry="1.15" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.15"/>' +
        '<ellipse cx="13" cy="20" rx="2.8" ry="1.15" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.15"/>' +
        "</g>"
      );
    }

    function buildSongketDiamondMotif(mode) {
      var isHero = mode === "hero";
      var opAcc = isHero ? "0.56" : "0.38";
      var opTxt = isHero ? "0.36" : "0.24";
      return (
        '<g fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<path d="M0,-26 L26,0 L0,26 L-26,0 Z" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="' + (isHero ? "1.45" : "1.2") + '"/>' +
        '<path d="M0,-18 L18,0 L0,18 L-18,0 Z" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.15"/>' +
        '<path d="M0,-9 L9,0 L0,9 L-9,0 Z" fill="var(--lt-accent)" fill-opacity="' + (isHero ? "0.22" : "0.14") + '" stroke="var(--lt-accent)" stroke-opacity="' + opAcc + '" stroke-width="1.2"/>' +
        '<circle cx="0" cy="0" r="2.3" fill="var(--lt-accent)" fill-opacity="' + (isHero ? "0.62" : "0.42") + '"/>' +
        '<path d="M-33,0 L-26,0 M26,0 L33,0 M0,-33 L0,-26 M0,26 L0,33" stroke="var(--lt-text)" stroke-opacity="' + opTxt + '" stroke-width="1.15"/>' +
        "</g>"
      );
    }

    function buildCompassRoseMotif(mode) {
      var isHero = mode === "hero";
      return (
        '<g fill="none" stroke-linecap="round" stroke-linejoin="round">' +
        '<circle cx="0" cy="0" r="19" stroke="var(--lt-text)" stroke-opacity="' + (isHero ? "0.30" : "0.18") + '" stroke-width="1.1" stroke-dasharray="2.5 3"/>' +
        '<circle cx="0" cy="0" r="11.5" stroke="var(--lt-accent)" stroke-opacity="' + (isHero ? "0.48" : "0.30") + '" stroke-width="1.15"/>' +
        '<path d="M0,-23 L3.5,-4 L23,0 L3.5,4 L0,23 L-3.5,4 L-23,0 L-3.5,-4 Z" fill="var(--lt-accent)" fill-opacity="' + (isHero ? "0.18" : "0.10") + '" stroke="var(--lt-accent)" stroke-opacity="' + (isHero ? "0.58" : "0.38") + '" stroke-width="1.2"/>' +
        '<circle cx="0" cy="0" r="2.2" fill="var(--lt-text)" fill-opacity="' + (isHero ? "0.45" : "0.28") + '"/>' +
        "</g>"
      );
    }

    var lombokBgLayerHtml =
      isLombokSite && hasPatternBg
        ? '<g class="lt-lombok-bg-art">' +
          /* Desktop flank ornaments */
          '<g transform="translate(535, 36) scale(1.72)">' +
          buildLombokIslandMotif("lt-lombok-map-top", "bg") +
          "</g>" +
          '<g transform="translate(18, 470) scale(1.55)">' +
          buildLombokIslandMotif("lt-lombok-map-mid", "bg") +
          "</g>" +
          '<g transform="translate(545, 980) scale(1.62)">' +
          buildLombokIslandMotif("lt-lombok-map-bot", "bg") +
          "</g>" +
          /* Mobile-safe center-band Pulau Lombok & Sasak motifs (visible even under narrow slice) */
          '<g transform="translate(355, 295) scale(1.12)">' +
          buildLombokIslandMotif("lt-lombok-map-center-1", "bg") +
          "</g>" +
          '<g transform="translate(345, 865) scale(1.18)">' +
          buildLombokIslandMotif("lt-lombok-map-center-2", "bg") +
          "</g>" +
          '<g transform="translate(365, 1340) scale(1.08)">' +
          buildLombokIslandMotif("lt-lombok-map-center-3", "bg") +
          "</g>" +
          '<g transform="translate(105, 195) scale(1.25)">' +
          buildLombokCulturalMotif("bg") +
          "</g>" +
          '<g transform="translate(785, 620) scale(1.25)">' +
          buildLombokCulturalMotif("bg") +
          "</g>" +
          '<g transform="translate(115, 1180) scale(1.2)">' +
          buildLombokCulturalMotif("bg") +
          "</g>" +
          '<g transform="translate(760, 415) scale(1.15)">' +
          buildSongketDiamondMotif("bg") +
          "</g>" +
          '<g transform="translate(120, 860) scale(1.15)">' +
          buildSongketDiamondMotif("bg") +
          "</g>" +
          '<g transform="translate(770, 1380) scale(1.15)">' +
          buildSongketDiamondMotif("bg") +
          "</g>" +
          "</g>"
        : "";

    var bgWavesHtml =
      isWave || (isLombokSite && hasPatternBg)
        ? '<svg class="lt-bg-waves" viewBox="0 0 900 1600" preserveAspectRatio="xMidYMid slice" aria-hidden="true">' +
          (isWave
            ? "<defs>" +
              '<linearGradient id="ltBw1_' + wid + '" x1="0%" y1="0%" x2="92%" y2="100%">' +
              '<stop offset="0%" style="stop-color:var(--lt-wave-1);stop-opacity:0.95"/>' +
              '<stop offset="52%" style="stop-color:var(--lt-wave-1);stop-opacity:0.42"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-wave-1);stop-opacity:0.04"/>' +
              "</linearGradient>" +
              '<linearGradient id="ltBw2_' + wid + '" x1="95%" y1="8%" x2="8%" y2="95%">' +
              '<stop offset="0%" style="stop-color:var(--lt-wave-2);stop-opacity:0.95"/>' +
              '<stop offset="55%" style="stop-color:var(--lt-wave-2);stop-opacity:0.50"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-wave-2);stop-opacity:0.08"/>' +
              "</linearGradient>" +
              '<linearGradient id="ltBw3_' + wid + '" x1="0%" y1="20%" x2="100%" y2="75%">' +
              '<stop offset="0%" style="stop-color:var(--lt-wave-3);stop-opacity:0.15"/>' +
              '<stop offset="48%" style="stop-color:var(--lt-wave-3);stop-opacity:1"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-wave-3);stop-opacity:0.12"/>' +
              "</linearGradient>" +
              "</defs>" +
              '<path d="M-100,110 C190,10 510,270 1000,40 L1000,540 C610,410 240,590 -100,400 Z" fill="url(#ltBw1_' + wid + ')"/>' +
              '<path d="M-100,460 C240,610 620,400 1000,540 L1000,1140 C630,980 260,1190 -100,970 Z" fill="url(#ltBw2_' + wid + ')"/>' +
              '<path d="M-100,760 C230,610 590,890 1000,720 L1000,1660 L-100,1660 Z" fill="url(#ltBw1_' + wid + ')"/>' +
              '<path d="M-60,300 C220,175 540,475 960,255 C610,450 270,225 -60,385 Z" fill="url(#ltBw3_' + wid + ')"/>' +
              '<path d="M-60,990 C270,835 580,1125 960,915 C600,1075 270,885 -60,1080 Z" fill="url(#ltBw3_' + wid + ')"/>'
            : "") +
          lombokBgLayerHtml +
          "</svg>"
        : "";

    /* Responsive corner-anchored Lombok Hero Ornaments (zero cropping on mobile & desktop) */
    var lombokHeroOrnamentsHtml =
      isLombokSite && hasPatternBg
        ? '<div class="lt-lombok-hero-art" aria-hidden="true">' +
          '<svg class="lt-lombok-hero-map-svg" viewBox="0 12 195 180" preserveAspectRatio="xMidYMid meet">' +
          buildLombokIslandMotif("lt-lombok-hero-map", "hero") +
          "</svg>" +
          '<svg class="lt-lombok-hero-lumbung-svg" viewBox="-36 -38 72 78" preserveAspectRatio="xMidYMid meet">' +
          buildLombokCulturalMotif("hero") +
          "</svg>" +
          '<svg class="lt-lombok-hero-compass-svg" viewBox="-26 -26 52 52" preserveAspectRatio="xMidYMid meet">' +
          buildCompassRoseMotif("hero") +
          "</svg>" +
          '<svg class="lt-lombok-hero-songket-svg" viewBox="-36 -36 72 72" preserveAspectRatio="xMidYMid meet">' +
          buildSongketDiamondMotif("hero") +
          "</svg>" +
          '<div class="lt-lombok-songket-border"></div>' +
          "</div>"
        : "";

    var heroWavesHtml =
      isWave || (isLombokSite && hasPatternBg)
        ? '<div class="lt-hero-waves-wrap" aria-hidden="true">' +
          (isWave
            ? '<svg class="lt-hero-waves" viewBox="0 0 600 320" preserveAspectRatio="none">' +
              "<defs>" +
              '<linearGradient id="ltHw1_' + wid + '" x1="0%" y1="0%" x2="100%" y2="85%">' +
              '<stop offset="0%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.78"/>' +
              '<stop offset="48%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.26"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.68"/>' +
              "</linearGradient>" +
              '<linearGradient id="ltHw2_' + wid + '" x1="25%" y1="0%" x2="50%" y2="100%">' +
              '<stop offset="0%" style="stop-color:var(--lt-hero-wave-2);stop-opacity:0.96"/>' +
              '<stop offset="52%" style="stop-color:var(--lt-hero-wave-2);stop-opacity:0.86"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-hero-wave-2);stop-opacity:0.42"/>' +
              "</linearGradient>" +
              '<linearGradient id="ltHw3_' + wid + '" x1="0%" y1="0%" x2="100%" y2="25%">' +
              '<stop offset="0%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.10"/>' +
              '<stop offset="52%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.44"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.78"/>' +
              "</linearGradient>" +
              '<linearGradient id="ltHw4_' + wid + '" x1="0%" y1="25%" x2="100%" y2="55%">' +
              '<stop offset="0%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.12"/>' +
              '<stop offset="50%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.46"/>' +
              '<stop offset="100%" style="stop-color:var(--lt-hero-wave-1);stop-opacity:0.82"/>' +
              "</linearGradient>" +
              "</defs>" +
              '<path d="M-10,0 L610,0 L610,58 C465,10 315,66 180,22 C88,-6 25,34 -10,54 Z" fill="url(#ltHw1_' + wid + ')"/>' +
              '<path d="M-10,6 C135,46 340,-10 610,48 L610,122 C375,38 165,102 -10,48 Z" fill="url(#ltHw4_' + wid + ')"/>' +
              '<path d="M-10,66 C120,12 250,36 430,82 C515,102 565,84 610,52 L610,330 L-10,330 Z" fill="url(#ltHw2_' + wid + ')"/>' +
              '<path d="M350,0 L610,0 L610,38 C525,18 435,30 350,0 Z" fill="url(#ltHw3_' + wid + ')"/>' +
              "</svg>"
            : "") +
          lombokHeroOrnamentsHtml +
          "</div>"
        : '<div class="lt-hero-waves-wrap" aria-hidden="true"></div>';

    var lombokFooterEmblemHtml =
      isLombokSite && hasPatternBg
        ? '<div class="lt-lombok-footer-emblem" aria-hidden="true">' +
          '<svg viewBox="0 0 320 56" width="248" height="44" fill="none">' +
          '<line x1="8" y1="28" x2="88" y2="28" stroke="var(--lt-accent)" stroke-opacity="0.35" stroke-width="1"/>' +
          '<line x1="232" y1="28" x2="312" y2="28" stroke="var(--lt-accent)" stroke-opacity="0.35" stroke-width="1"/>' +
          '<g transform="translate(104, 28) scale(0.42)">' +
          buildSongketDiamondMotif("hero") +
          "</g>" +
          '<g transform="translate(132, 4) scale(0.24)">' +
          buildLombokIslandMotif("lt-lombok-footer-map", "hero") +
          "</g>" +
          '<g transform="translate(216, 28) scale(0.42)">' +
          buildSongketDiamondMotif("hero") +
          "</g>" +
          "</svg>" +
          "</div>"
        : "";

    var topbarHtml =
      '<div class="lt-topbar' +
      (isHeroCover ? " is-over-cover" : "") +
      '">' +
      (profile.location
        ? '<a class="lt-location-chip" href="' +
          escapeHtml(mapsHref) +
          '" target="_blank" rel="noopener noreferrer">' +
          ICONS.pin +
          "<span>" +
          escapeHtml(profile.location) +
          "</span></a>"
        : "<span></span>") +
      '<button type="button" class="lt-share-btn" data-action="share" aria-label="Bagikan halaman" title="Bagikan">' +
      ICONS.share +
      "</button>" +
      "</div>";

    var profileBodyHtml =
      '<div class="lt-avatar-wrap">' +
      '<img class="lt-avatar-img" src="' +
      escapeHtml(avatarSrc) +
      '" alt="' +
      escapeHtml(profile.name || "Logo") +
      '" onerror="this.onerror=null;this.src=\'' +
      FALLBACK_IMG +
      '\'" />' +
      "</div>" +
      '<div class="lt-name-row">' +
      '<h1 class="lt-name">' +
      escapeHtml(profile.name) +
      "</h1>" +
      (profile.verified ? ICONS.verified : "") +
      "</div>" +
      (profile.tagline ? '<p class="lt-tagline">' + escapeHtml(profile.tagline) + "</p>" : "") +
      (profile.bio ? '<p class="lt-bio">' + escapeHtml(profile.bio) + "</p>" : "") +
      (socialsHtml ? '<nav class="lt-socials" aria-label="Media sosial">' + socialsHtml + "</nav>" : "");

    var headerBlockHtml;
    if (isHeroCover) {
      headerBlockHtml =
        '<div class="lt-hero-card">' +
        '<div class="lt-hero-cover">' +
        (coverSrc !== "#"
          ? '<img class="lt-hero-cover-img" src="' +
            escapeHtml(coverSrc) +
            '" alt="" onerror="this.style.display=\'none\'" />'
          : "") +
        '<div class="lt-hero-scrim" aria-hidden="true"></div>' +
        topbarHtml +
        "</div>" +
        '<div class="lt-hero-body">' +
        heroWavesHtml +
        '<header class="lt-profile has-cover">' +
        profileBodyHtml +
        "</header>" +
        "</div>" +
        "</div>";
    } else if (isFramed) {
      headerBlockHtml =
        topbarHtml +
        '<div class="lt-hero-card is-compact">' +
        '<div class="lt-hero-body">' +
        heroWavesHtml +
        '<header class="lt-profile is-framed">' +
        profileBodyHtml +
        "</header>" +
        "</div>" +
        "</div>";
    } else {
      headerBlockHtml = topbarHtml + '<header class="lt-profile">' + profileBodyHtml + "</header>";
    }

    var year = new Date().getFullYear();

    container.innerHTML =
      '<div class="' +
      rootClasses +
      '" data-site="' +
      escapeHtml(siteId) +
      '" data-theme="' +
      escapeHtml(themeVal) +
      '" data-tone="' +
      escapeHtml(toneVal) +
      '" data-bg="' +
      escapeHtml(bgStyleVal) +
      '" data-radius="' +
      escapeHtml(radiusVal) +
      '"' +
      customStyleAttr +
      ">" +
      bgWavesHtml +
      '<div class="lt-container">' +
      headerBlockHtml +
      /* Primary CTA */
      (cta.enabled
        ? '<a class="lt-cta-card" href="' +
          escapeHtml(ctaHref) +
          '" target="_blank" rel="noopener noreferrer">' +
          '<div class="lt-cta-left">' +
          '<div class="lt-cta-icon">' +
          getIconSvg(cta.icon || defaultCtaIcon, defaultCtaIcon) +
          "</div>" +
          '<div class="lt-cta-text">' +
          '<h2 class="lt-cta-title">' +
          escapeHtml(cta.title) +
          "</h2>" +
          (cta.subtitle ? '<p class="lt-cta-sub">' + escapeHtml(cta.subtitle) + "</p>" : "") +
          "</div>" +
          "</div>" +
          ICONS.arrowRight +
          "</a>"
        : "") +
      /* Quick Menu */
      (activeLinks.length
        ? '<section class="lt-section" aria-label="Menu Utama">' +
          '<div class="lt-section-head">' +
          '<h2 class="lt-section-title">Menu Utama</h2>' +
          '<span class="lt-section-count">' +
          activeLinks.length +
          " Tautan</span>" +
          "</div>" +
          '<div class="lt-links-stack">' +
          quickLinksHtml +
          "</div>" +
          "</section>"
        : "") +
      /* Destinations */
      '<section class="lt-section lt-dest-section" id="destSection" aria-label="Semua Destinasi Wisata">' +
      '<div class="lt-section-head is-dest-head">' +
      '<div class="lt-dest-head-left">' +
      '<h2 class="lt-section-title is-dest-title">Semua Destinasi</h2>' +
      (destSubtitle ? '<p class="lt-section-sub">' + escapeHtml(destSubtitle) + "</p>" : "") +
      "</div>" +
      '<span class="lt-section-count is-dest-count" data-role="dest-count"><strong data-role="dest-count-num">' +
      activeDestinations.length +
      "</strong> Destinasi</span>" +
      "</div>" +
      (appearance.showSearch || appearance.showFilter
        ? '<div class="lt-dest-controls">' +
          (appearance.showSearch
            ? '<div class="lt-search-box">' +
              ICONS.search +
              '<input type="search" class="lt-search-input" data-role="dest-search" placeholder="Cari destinasi (mis. Bromo, Ijen, Pantai)..." value="' +
              escapeHtml(opts.searchQuery) +
              '" />' +
              "</div>"
            : "") +
          (appearance.showFilter && activeDestinations.length > 0
            ? '<div class="lt-filter-row" role="tablist" aria-label="Filter kategori destinasi">' +
              filterPillsHtml +
              "</div>"
            : "") +
          "</div>"
        : "") +
      '<div data-role="dest-list"></div>' +
      "</section>" +
      /* Footer */
      '<footer class="lt-footer">' +
      lombokFooterEmblemHtml +
      "<div>© " +
      year +
      " " +
      escapeHtml(profile.name || "Piknik Enthusiast") +
      "</div>" +
      (settings.footerText ? "<div>" + escapeHtml(settings.footerText) + "</div>" : "") +
      '<div class="lt-footer-row">' +
      '<a href="https://piknikenthusiast.com/id" target="_blank" rel="noopener noreferrer">piknikenthusiast.com</a>' +
      "</div>" +
      "</footer>" +
      "</div>" +
      "</div>";

    var destListEl = container.querySelector('[data-role="dest-list"]');
    var destCountEl = container.querySelector('[data-role="dest-count"]');
    var searchInput = container.querySelector('[data-role="dest-search"]');
    var filterRow = container.querySelector(".lt-filter-row");
    var shareBtn = container.querySelector('[data-action="share"]');

    var state = {
      category: opts.activeCategory || "all",
      query: opts.searchQuery || ""
    };

    function applyFilter() {
      var q = state.query.trim().toLowerCase();
      var filtered = activeDestinations.filter(function (d) {
        var catOk = state.category === "all" || d.category === state.category;
        var qOk =
          !q ||
          (d.name && d.name.toLowerCase().indexOf(q) !== -1) ||
          (d.location && d.location.toLowerCase().indexOf(q) !== -1);
        return catOk && qOk;
      });
      renderDestinationCards(destListEl, filtered, appearance);
      if (destCountEl) {
        destCountEl.innerHTML =
          '<strong data-role="dest-count-num">' + filtered.length + "</strong> Destinasi";
      }
      if (opts.onFilterChange) {
        opts.onFilterChange(state.category, state.query);
      }
    }

    if (filterRow) {
      filterRow.addEventListener("click", function (e) {
        var btn = e.target.closest(".lt-filter-pill");
        if (!btn) return;
        state.category = btn.getAttribute("data-cat") || "all";
        var allBtns = filterRow.querySelectorAll(".lt-filter-pill");
        for (var i = 0; i < allBtns.length; i++) {
          allBtns[i].classList.toggle("is-active", allBtns[i] === btn);
        }
        applyFilter();
      });
    }

    if (searchInput) {
      searchInput.addEventListener("input", function (e) {
        state.query = e.target.value || "";
        applyFilter();
      });
    }

    if (shareBtn) {
      shareBtn.addEventListener("click", function () {
        handleShare(profile);
      });
    }

    applyFilter();
  }

  global.PiknikStore = Store;
  global.PiknikRenderer = {
    render: renderLinktree,
    escapeHtml: escapeHtml,
    sanitizeUrl: sanitizeUrl,
    sanitizeHexColor: sanitizeHexColor,
    clampAngle: clampAngle,
    buildWaUrl: buildWaUrl,
    getIconSvg: getIconSvg,
    svgToDataUrl: svgToDataUrl,
    isCustomIconValue: isCustomIconValue,
    showToast: showToast,
    FALLBACK_IMG: FALLBACK_IMG,
    CATEGORY_LABELS: CATEGORY_LABELS
  };
})(window);
