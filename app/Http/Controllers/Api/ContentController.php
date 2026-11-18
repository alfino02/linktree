<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinktreeContent;
use App\Support\AdminAuth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    private function sanitizeStoredContent(array $raw): array
    {
        $cloned = $raw;
        if (isset($cloned['settings']) && is_array($cloned['settings'])) {
            unset(
                $cloned['settings']['adminPin'],
                $cloned['settings']['adminPassword'],
                $cloned['settings']['showAdminLink']
            );
        }

        return $cloned;
    }

    public function show(Request $request): JsonResponse
    {
        $siteId = AdminAuth::normalizeSiteId($request->query('site'));
        $record = LinktreeContent::where('site', $siteId)->first();

        if (! $record || ! is_array($record->content)) {
            return response()->json([
                'site' => $siteId,
                'exists' => false,
                'content' => null,
            ])->header('Cache-Control', 'no-store');
        }

        return response()->json([
            'site' => $siteId,
            'exists' => true,
            'content' => $this->sanitizeStoredContent($record->content),
        ])->header('Cache-Control', 'no-store');
    }

    public function handle(Request $request): JsonResponse
    {
        $body = $request->json()->all();
        if (! is_array($body)) {
            return response()->json(['ok' => false, 'error' => 'Body JSON tidak valid'], 400);
        }

        $action = isset($body['action']) && is_string($body['action']) ? $body['action'] : '';
        $siteId = AdminAuth::normalizeSiteId($body['site'] ?? $request->query('site'));
        $password = isset($body['password']) && is_string($body['password']) ? trim($body['password']) : '';

        if ($password === '') {
            return response()->json(['ok' => false, 'error' => 'Password wajib diisi'], 401);
        }

        if (! AdminAuth::verifyPassword($password)) {
            return response()->json(['ok' => false, 'error' => 'Password tidak sesuai'], 401);
        }

        if ($action === 'verify') {
            return response()->json(['ok' => true]);
        }

        if ($action === 'save') {
            $content = $body['content'] ?? null;
            if (
                ! is_array($content) ||
                ! isset($content['profile']) ||
                ! isset($content['destinations']) ||
                ! is_array($content['destinations'])
            ) {
                return response()->json(['ok' => false, 'error' => 'Struktur konten tidak valid'], 400);
            }

            $clean = $this->sanitizeStoredContent($content);
            LinktreeContent::updateOrCreate(
                ['site' => $siteId],
                ['content' => $clean]
            );

            return response()->json([
                'ok' => true,
                'site' => $siteId,
                'savedAt' => now()->toIso8601String(),
            ]);
        }

        if ($action === 'change-password') {
            $newPassword = isset($body['newPassword']) && is_string($body['newPassword'])
                ? trim($body['newPassword'])
                : '';
            if (strlen($newPassword) < 4 || strlen($newPassword) > 64) {
                return response()->json(['ok' => false, 'error' => 'Password baru minimal 4 karakter'], 400);
            }

            AdminAuth::updatePassword($newPassword);

            return response()->json(['ok' => true]);
        }

        if ($action === 'reset') {
            LinktreeContent::where('site', $siteId)->delete();

            return response()->json(['ok' => true, 'site' => $siteId]);
        }

        return response()->json(['ok' => false, 'error' => 'Aksi tidak dikenal'], 400);
    }
}
