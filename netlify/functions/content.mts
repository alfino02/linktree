import type { Config, Context } from "@netlify/functions";
import {
  CONTENT_STORE_NAME,
  getContentBlobKey,
  jsonResponse,
  normalizeSiteId,
  openScopedStore,
  updateAdminPassword,
  verifyAdminPassword,
} from "../lib/admin-auth";

function sanitizeStoredContent(raw: Record<string, unknown>) {
  const cloned = JSON.parse(JSON.stringify(raw)) as Record<string, unknown>;
  if (cloned.settings && typeof cloned.settings === "object") {
    const settingsObj = cloned.settings as Record<string, unknown>;
    delete settingsObj.adminPin;
    delete settingsObj.adminPassword;
    delete settingsObj.showAdminLink;
  }
  return cloned;
}

export default async (req: Request, context: Context): Promise<Response> => {
  try {
    const store = openScopedStore(CONTENT_STORE_NAME, context);
    const reqUrl = new URL(req.url);

    if (req.method === "GET") {
      const siteId = normalizeSiteId(reqUrl.searchParams.get("site"));
      const blobKey = getContentBlobKey(siteId);
      const content = (await store.get(blobKey, { type: "json" })) as Record<
        string,
        unknown
      > | null;
      if (content === null) {
        return jsonResponse({ site: siteId, exists: false, content: null });
      }
      return jsonResponse({
        site: siteId,
        exists: true,
        content: sanitizeStoredContent(content),
      });
    }

    if (req.method === "POST") {
      let body: Record<string, unknown>;
      try {
        body = (await req.json()) as Record<string, unknown>;
      } catch {
        return jsonResponse({ ok: false, error: "Body JSON tidak valid" }, 400);
      }

      const action = typeof body.action === "string" ? body.action : "";
      const siteId = normalizeSiteId(body.site || reqUrl.searchParams.get("site"));
      const blobKey = getContentBlobKey(siteId);
      const password =
        typeof body.password === "string" ? body.password.trim() : "";

      if (!password) {
        return jsonResponse({ ok: false, error: "Password wajib diisi" }, 401);
      }

      const isAuthorized = await verifyAdminPassword(context, password);
      if (!isAuthorized) {
        return jsonResponse({ ok: false, error: "Password tidak sesuai" }, 401);
      }

      if (action === "verify") {
        return jsonResponse({ ok: true });
      }

      if (action === "save") {
        const content = body.content;
        if (
          !content ||
          typeof content !== "object" ||
          Array.isArray(content) ||
          !("profile" in content) ||
          !Array.isArray((content as Record<string, unknown>).destinations)
        ) {
          return jsonResponse(
            { ok: false, error: "Struktur konten tidak valid" },
            400
          );
        }
        const clean = sanitizeStoredContent(content as Record<string, unknown>);
        await store.setJSON(blobKey, clean);
        return jsonResponse({
          ok: true,
          site: siteId,
          savedAt: new Date().toISOString(),
        });
      }

      if (action === "change-password") {
        const newPassword =
          typeof body.newPassword === "string" ? body.newPassword.trim() : "";
        if (newPassword.length < 4 || newPassword.length > 64) {
          return jsonResponse(
            { ok: false, error: "Password baru minimal 4 karakter" },
            400
          );
        }
        await updateAdminPassword(context, newPassword);
        return jsonResponse({ ok: true });
      }

      if (action === "reset") {
        await store.delete(blobKey);
        return jsonResponse({ ok: true, site: siteId });
      }

      return jsonResponse({ ok: false, error: "Aksi tidak dikenal" }, 400);
    }

    return jsonResponse({ ok: false, error: "Method tidak didukung" }, 405);
  } catch (err) {
    console.error("content function error:", err);
    return jsonResponse(
      { ok: false, error: "Terjadi kesalahan pada server" },
      500
    );
  }
};

export const config: Config = {
  path: "/api/content",
  method: ["GET", "POST"],
};
