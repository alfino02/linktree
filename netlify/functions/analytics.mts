import { createHash, randomBytes } from "node:crypto";
import type { Config, Context } from "@netlify/functions";
import {
  ANALYTICS_STORE_NAME,
  type SiteId,
  getAnalyticsPrefixes,
  jsonResponse,
  normalizeSiteId,
  openScopedStore,
  verifyAdminPassword,
} from "../lib/admin-auth";

const BOT_UA_RE =
  /(googlebot|bingbot|yandexbot|baiduspider|duckduckbot|slurp|ahrefsbot|semrushbot|mj12bot|dotbot|bytespider|petalbot)/i;

const SOURCE_CODE_TO_LABEL: Record<string, string> = {
  dir: "Langsung",
  ig: "Instagram",
  wa: "WhatsApp",
  tt: "TikTok",
  ggl: "Google",
  fb: "Facebook",
  yt: "YouTube",
};

const DEVICE_CODE_TO_NAME: Record<string, string> = {
  mob: "Mobile",
  tab: "Tablet",
  dsk: "Desktop",
};

interface DailyArchive {
  date: string;
  views: number;
  uniqueVids: string[];
  devices: Record<string, number>;
  sources: Record<string, number>;
}

interface VisitRecord {
  ts: number;
  date: string;
  timeWib: string;
  vid: string;
  device: string;
  source: string;
  country: string;
  city: string;
  path: string;
}

function getJakartaDateParts(date: Date) {
  const parts = new Intl.DateTimeFormat("en-GB", {
    timeZone: "Asia/Jakarta",
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
    hourCycle: "h23",
  }).formatToParts(date);

  const map: Record<string, string> = {};
  for (const part of parts) {
    if (part.type !== "literal") map[part.type] = part.value;
  }
  const dateStr = `${map.year}-${map.month}-${map.day}`;
  const hhmmss = `${map.hour}${map.minute}${map.second}`;
  const timeWib = `${map.hour}:${map.minute}`;
  return { dateStr, hhmmss, timeWib };
}

function shiftCalendarDate(yyyyMmDd: string, deltaDays: number): string {
  const [y, m, d] = yyyyMmDd.split("-").map(Number);
  const utc = Date.UTC(y, m - 1, d) + deltaDays * 86400000;
  return new Date(utc).toISOString().slice(0, 10);
}

function formatShortWibDay(yyyyMmDd: string): string {
  const [y, m, d] = yyyyMmDd.split("-").map(Number);
  const utcDate = new Date(Date.UTC(y, m - 1, d, 12, 0, 0));
  return new Intl.DateTimeFormat("id-ID", {
    timeZone: "Asia/Jakarta",
    day: "2-digit",
    month: "short",
  }).format(utcDate);
}

function normalizeDevice(raw: unknown): { code: string; label: string } {
  const s = String(raw || "").toLowerCase();
  if (s === "mobile" || s === "mob") return { code: "mob", label: "Mobile" };
  if (s === "tablet" || s === "tab") return { code: "tab", label: "Tablet" };
  return { code: "dsk", label: "Desktop" };
}

function normalizeSource(raw: unknown): { code: string; label: string } {
  const s = String(raw || "")
    .trim()
    .toLowerCase();
  if (!s || s === "direct" || s === "langsung") {
    return { code: "dir", label: "Langsung" };
  }
  if (s.includes("instagram") || s === "ig") {
    return { code: "ig", label: "Instagram" };
  }
  if (s.includes("whatsapp") || s === "wa" || s.includes("wa.me")) {
    return { code: "wa", label: "WhatsApp" };
  }
  if (s.includes("tiktok") || s === "tt") {
    return { code: "tt", label: "TikTok" };
  }
  if (s.includes("google") || s === "ggl") {
    return { code: "ggl", label: "Google" };
  }
  if (s.includes("facebook") || s === "fb" || s === "fbclid") {
    return { code: "fb", label: "Facebook" };
  }
  if (s.includes("youtube") || s === "yt" || s.includes("youtu.be")) {
    return { code: "yt", label: "YouTube" };
  }
  const cleanHost = s
    .replace(/^https?:\/\//, "")
    .replace(/^www\./, "")
    .split("/")[0]
    .replace(/[^a-z0-9.-]/g, "-")
    .slice(0, 24);
  if (!cleanHost) return { code: "dir", label: "Langsung" };
  return {
    code: cleanHost.replace(/_/g, "-"),
    label: cleanHost,
  };
}

function sourceLabelFromCode(code: string): string {
  return SOURCE_CODE_TO_LABEL[code] || code || "Langsung";
}

function computeVidHash8(rawVid: unknown, req: Request, context: Context, dateStr: string): string {
  const cleaned = typeof rawVid === "string" ? rawVid.trim().slice(0, 64) : "";
  const seed =
    cleaned && /^[a-zA-Z0-9_-]{6,64}$/.test(cleaned)
      ? `vid:${cleaned}`
      : `anon:${context.ip || "0.0.0.0"}|${req.headers.get("user-agent") || ""}|${dateStr}`;
  return createHash("sha256").update(seed).digest("hex").slice(0, 8);
}

function parseRawVisitKey(key: string, rawPrefix: string) {
  if (!key.startsWith(rawPrefix)) return null;
  const parts = key.slice(rawPrefix.length).split("/");
  if (parts.length !== 2) return null;
  const [dateStr, filename] = parts;
  if (!/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return null;
  const tokens = filename.split("_");
  if (tokens.length < 6) return null;
  const ts = Number(tokens[1]) || 0;
  const vid = tokens[3];
  const devCode = tokens[4];
  const srcCode = tokens.slice(5).join("_");
  return {
    key,
    date: dateStr,
    ts,
    vid,
    device: DEVICE_CODE_TO_NAME[devCode] || "Desktop",
    source: sourceLabelFromCode(srcCode),
  };
}

async function archiveClosedOldDays(
  store: ReturnType<typeof openScopedStore>,
  rawKeys: Array<ReturnType<typeof parseRawVisitKey>>,
  cutoffDate30d: string,
  dailyPrefix: string
): Promise<void> {
  const byOldDate = new Map<string, Array<NonNullable<ReturnType<typeof parseRawVisitKey>>>>();
  for (const item of rawKeys) {
    if (!item || item.date >= cutoffDate30d) continue;
    const list = byOldDate.get(item.date) || [];
    list.push(item);
    byOldDate.set(item.date, list);
  }
  if (byOldDate.size === 0) return;

  for (const [dateStr, items] of byOldDate.entries()) {
    const archiveKey = `${dailyPrefix}${dateStr}.json`;
    const existing = (await store.get(archiveKey, { type: "json" })) as DailyArchive | null;
    const vidSet = new Set<string>(existing?.uniqueVids || []);
    const devices: Record<string, number> = Object.assign({}, existing?.devices || {});
    const sources: Record<string, number> = Object.assign({}, existing?.sources || {});
    let views = existing?.views || 0;

    for (const item of items) {
      views += 1;
      vidSet.add(item.vid);
      devices[item.device] = (devices[item.device] || 0) + 1;
      sources[item.source] = (sources[item.source] || 0) + 1;
    }

    const updated: DailyArchive = {
      date: dateStr,
      views,
      uniqueVids: Array.from(vidSet),
      devices,
      sources,
    };
    await store.setJSON(archiveKey, updated);

    for (let i = 0; i < items.length; i += 25) {
      const batch = items.slice(i, i + 25);
      await Promise.all(batch.map((entry) => store.delete(entry.key)));
    }
  }
}

async function buildSummary(context: Context, siteId: SiteId) {
  const store = openScopedStore(ANALYTICS_STORE_NAME, context);
  const { rawPrefix, dailyPrefix } = getAnalyticsPrefixes(siteId);
  const { dateStr: todayWib } = getJakartaDateParts(new Date());
  const cutoff7d = shiftCalendarDate(todayWib, -6);
  const cutoff14d = shiftCalendarDate(todayWib, -13);
  const cutoff30d = shiftCalendarDate(todayWib, -29);

  const rawEntries: Array<NonNullable<ReturnType<typeof parseRawVisitKey>>> = [];
  for await (const page of store.list({ prefix: rawPrefix, paginate: true })) {
    for (const blob of page.blobs) {
      const parsed = parseRawVisitKey(blob.key, rawPrefix);
      if (parsed) rawEntries.push(parsed);
    }
  }

  // Keep raw keys bounded cleanly by compacting events older than 30 days on read
  const hasOldRaw = rawEntries.some((entry) => entry.date < cutoff30d);
  if (hasOldRaw) {
    await archiveClosedOldDays(store, rawEntries, cutoff30d, dailyPrefix);
  }

  const archiveKeys: string[] = [];
  for await (const page of store.list({ prefix: dailyPrefix, paginate: true })) {
    for (const blob of page.blobs) {
      archiveKeys.push(blob.key);
    }
  }

  const recentRaw = rawEntries.filter((entry) => entry.date >= cutoff30d);

  // Counters
  let todayViews = 0;
  let views7d = 0;
  let views30d = 0;
  let allViews = 0;

  const todayVids = new Set<string>();
  const vids7d = new Set<string>();
  const vids30d = new Set<string>();
  const allVids = new Set<string>();

  const dailyMap = new Map<string, { views: number; vids: Set<string> }>();
  for (let i = 0; i < 14; i++) {
    const dStr = shiftCalendarDate(cutoff14d, i);
    dailyMap.set(dStr, { views: 0, vids: new Set<string>() });
  }

  const deviceCounts: Record<string, number> = {};
  const sourceCounts: Record<string, number> = {};

  for (const item of recentRaw) {
    allViews += 1;
    allVids.add(item.vid);
    deviceCounts[item.device] = (deviceCounts[item.device] || 0) + 1;
    sourceCounts[item.source] = (sourceCounts[item.source] || 0) + 1;

    if (item.date >= cutoff30d) {
      views30d += 1;
      vids30d.add(item.vid);
    }
    if (item.date >= cutoff7d) {
      views7d += 1;
      vids7d.add(item.vid);
    }
    if (item.date === todayWib) {
      todayViews += 1;
      todayVids.add(item.vid);
    }
    const dayBucket = dailyMap.get(item.date);
    if (dayBucket) {
      dayBucket.views += 1;
      dayBucket.vids.add(item.vid);
    }
  }

  // Include archived (>30d) days in all-time metrics
  if (archiveKeys.length > 0) {
    for (let i = 0; i < archiveKeys.length; i += 25) {
      const batch = archiveKeys.slice(i, i + 25);
      const archives = await Promise.all(
        batch.map((k) => store.get(k, { type: "json" }) as Promise<DailyArchive | null>)
      );
      for (const arc of archives) {
        if (!arc) continue;
        allViews += arc.views || 0;
        if (Array.isArray(arc.uniqueVids)) {
          for (const v of arc.uniqueVids) allVids.add(v);
        }
        for (const [dev, count] of Object.entries(arc.devices || {})) {
          deviceCounts[dev] = (deviceCounts[dev] || 0) + (Number(count) || 0);
        }
        for (const [src, count] of Object.entries(arc.sources || {})) {
          sourceCounts[src] = (sourceCounts[src] || 0) + (Number(count) || 0);
        }
      }
    }
  }

  // Sort newest first by timestamp and fetch up to 25 records for location breakdown & recent visits feed
  recentRaw.sort((a, b) => b.ts - a.ts);
  const sampleItems = recentRaw.slice(0, 25);
  const sampleRecords = (
    await Promise.all(
      sampleItems.map((entry) =>
        store.get(entry.key, { type: "json" }) as Promise<VisitRecord | null>
      )
    )
  ).filter((rec): rec is VisitRecord => !!rec);

  const locationCounts: Record<string, number> = {};
  for (const rec of sampleRecords) {
    const loc =
      rec.city && rec.country
        ? `${rec.city}, ${rec.country}`
        : rec.country || rec.city || "Tidak diketahui";
    locationCounts[loc] = (locationCounts[loc] || 0) + 1;
  }

  const daily = Array.from(dailyMap.entries()).map(([date, info]) => ({
    date,
    label: formatShortWibDay(date),
    dayNum: date.slice(8, 10),
    isToday: date === todayWib,
    views: info.views,
    visitors: info.vids.size,
  }));

  const toRankedList = (counts: Record<string, number>, limit: number) =>
    Object.entries(counts)
      .sort((a, b) => b[1] - a[1])
      .slice(0, limit)
      .map(([name, count]) => ({ name, count }));

  return {
    ok: true,
    todayDate: todayWib,
    generatedAt: new Date().toISOString(),
    totals: {
      todayViews,
      todayVisitors: todayVids.size,
      views7d,
      visitors7d: vids7d.size,
      views30d,
      visitors30d: vids30d.size,
      allViews,
      allVisitors: allVids.size,
    },
    daily,
    sources: toRankedList(sourceCounts, 6),
    devices: toRankedList(deviceCounts, 4),
    locations: toRankedList(locationCounts, 5),
    recent: sampleRecords.slice(0, 8).map((rec) => ({
      date: rec.date,
      timeWib: rec.timeWib,
      device: rec.device,
      source: rec.source,
      location:
        rec.city && rec.country
          ? `${rec.city}, ${rec.country}`
          : rec.country || rec.city || "—",
    })),
  };
}

export default async (req: Request, context: Context): Promise<Response> => {
  try {
    let body: Record<string, unknown>;
    try {
      body = (await req.json()) as Record<string, unknown>;
    } catch {
      return jsonResponse({ ok: false, error: "Body JSON tidak valid" }, 400);
    }

    const action = typeof body.action === "string" ? body.action : "track";
    const siteId = normalizeSiteId(body.site, body.path);
    const { rawPrefix, dailyPrefix } = getAnalyticsPrefixes(siteId);

    if (action === "track") {
      const ua = req.headers.get("user-agent") || "";
      if (BOT_UA_RE.test(ua)) {
        return jsonResponse({ ok: true, ignored: true });
      }

      const now = new Date();
      const ts = now.getTime();
      const { dateStr, hhmmss, timeWib } = getJakartaDateParts(now);
      const vid = computeVidHash8(body.vid, req, context, dateStr);
      const device = normalizeDevice(body.device);
      const source = normalizeSource(body.source);
      const rand = randomBytes(2).toString("hex");

      const country = String(
        context.geo?.country?.name || context.geo?.country?.code || ""
      ).slice(0, 48);
      const city = String(context.geo?.city || "").slice(0, 48);
      const path =
        typeof body.path === "string" ? body.path.slice(0, 80) : "/";

      const key = `${rawPrefix}${dateStr}/${hhmmss}_${ts}_${rand}_${vid}_${device.code}_${source.code}`;
      const record: VisitRecord = {
        ts,
        date: dateStr,
        timeWib,
        vid,
        device: device.label,
        source: source.label,
        country,
        city,
        path,
      };

      const store = openScopedStore(ANALYTICS_STORE_NAME, context);
      await store.setJSON(key, record, { onlyIfNew: true });
      return jsonResponse({ ok: true, site: siteId });
    }

    const password =
      typeof body.password === "string" ? body.password.trim() : "";
    if (!password) {
      return jsonResponse({ ok: false, error: "Password wajib diisi" }, 401);
    }
    const isAuthorized = await verifyAdminPassword(context, password);
    if (!isAuthorized) {
      return jsonResponse({ ok: false, error: "Password tidak sesuai" }, 401);
    }

    if (action === "summary") {
      const summary = await buildSummary(context, siteId);
      return jsonResponse({ ...summary, site: siteId });
    }

    if (action === "reset") {
      const store = openScopedStore(ANALYTICS_STORE_NAME, context);
      const keys: string[] = [];
      for await (const page of store.list({ prefix: rawPrefix, paginate: true })) {
        for (const blob of page.blobs) {
          keys.push(blob.key);
        }
      }
      for await (const page of store.list({ prefix: dailyPrefix, paginate: true })) {
        for (const blob of page.blobs) {
          keys.push(blob.key);
        }
      }
      for (let i = 0; i < keys.length; i += 25) {
        const batch = keys.slice(i, i + 25);
        await Promise.all(batch.map((k) => store.delete(k)));
      }
      return jsonResponse({ ok: true, site: siteId, deleted: keys.length });
    }

    return jsonResponse({ ok: false, error: "Aksi tidak dikenal" }, 400);
  } catch (err) {
    console.error("analytics function error:", err);
    return jsonResponse(
      { ok: false, error: "Terjadi kesalahan pada server" },
      500
    );
  }
};

export const config: Config = {
  path: "/api/analytics",
  method: ["POST"],
};
