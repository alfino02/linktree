import { createHash, timingSafeEqual } from "node:crypto";
import { getDeployStore, getStore } from "@netlify/blobs";
import type { Context } from "@netlify/functions";

export const CONTENT_STORE_NAME = "piknik-linktree";
export const ANALYTICS_STORE_NAME = "piknik-analytics";
export const PASSWORD_KEY = "admin-password";

export type SiteId = "piknik" | "lombok";

export function normalizeSiteId(raw: unknown, fallbackPath?: unknown): SiteId {
  const s = String(raw || "").trim().toLowerCase();
  if (s === "lombok") return "lombok";
  if (s === "piknik") return "piknik";
  const p = String(fallbackPath || "").trim().toLowerCase();
  if (p.startsWith("/lombok")) return "lombok";
  return "piknik";
}

export function getContentBlobKey(siteId: SiteId): string {
  return siteId === "lombok" ? "site-content:lombok" : "site-content";
}

export function getAnalyticsPrefixes(siteId: SiteId): {
  rawPrefix: string;
  dailyPrefix: string;
} {
  if (siteId === "lombok") {
    return { rawPrefix: "lombok/v/", dailyPrefix: "lombok/d/" };
  }
  return { rawPrefix: "v/", dailyPrefix: "d/" };
}

export const JSON_HEADERS = {
  "content-type": "application/json; charset=utf-8",
  "cache-control": "no-store",
};

export function jsonResponse(payload: unknown, status = 200): Response {
  return new Response(JSON.stringify(payload), { status, headers: JSON_HEADERS });
}

export function hashPassword(password: string): string {
  return createHash("sha256").update(`piknik-lt:${password}`).digest("hex");
}

export function safeEqualHex(a: string, b: string): boolean {
  const bufA = Buffer.from(a, "utf8");
  const bufB = Buffer.from(b, "utf8");
  if (bufA.length !== bufB.length) return false;
  return timingSafeEqual(bufA, bufB);
}

export function openScopedStore(storeName: string, context: Context) {
  const deployCtx = context.deploy?.context;
  if (deployCtx && deployCtx !== "production") {
    return getDeployStore({ name: storeName, consistency: "strong" });
  }
  return getStore({ name: storeName, consistency: "strong" });
}

export async function verifyAdminPassword(
  context: Context,
  candidatePassword: string
): Promise<boolean> {
  const store = openScopedStore(CONTENT_STORE_NAME, context);
  const stored = (await store.get(PASSWORD_KEY, { type: "json" })) as {
    passwordHash?: string;
  } | null;

  const expectedHash =
    stored && typeof stored.passwordHash === "string"
      ? stored.passwordHash
      : hashPassword(Netlify.env.get("ADMIN_PASSWORD") || "langsungakses");

  return safeEqualHex(hashPassword(candidatePassword), expectedHash);
}

export async function updateAdminPassword(
  context: Context,
  newPassword: string
): Promise<void> {
  const store = openScopedStore(CONTENT_STORE_NAME, context);
  await store.setJSON(PASSWORD_KEY, {
    passwordHash: hashPassword(newPassword),
  });
}
