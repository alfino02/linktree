<?php

namespace App\Support;

use App\Models\LinktreeSetting;

class AdminAuth
{
    public const PASSWORD_KEY = 'admin-password';

    public static function normalizeSiteId(mixed $raw, mixed $fallbackPath = null): string
    {
        $s = strtolower(trim((string) ($raw ?? '')));
        if ($s === 'lombok') {
            return 'lombok';
        }
        if ($s === 'piknik') {
            return 'piknik';
        }
        $p = strtolower(trim((string) ($fallbackPath ?? '')));
        if (str_starts_with($p, '/lombok')) {
            return 'lombok';
        }
        return 'piknik';
    }

    public static function hashPassword(string $password): string
    {
        return hash('sha256', 'piknik-lt:' . $password);
    }

    public static function verifyPassword(string $candidatePassword): bool
    {
        $setting = LinktreeSetting::where('key', self::PASSWORD_KEY)->first();
        $expectedHash = $setting && is_string($setting->value) && $setting->value !== ''
            ? $setting->value
            : self::hashPassword((string) env('ADMIN_PASSWORD', 'langsungakses'));

        return hash_equals($expectedHash, self::hashPassword($candidatePassword));
    }

    public static function updatePassword(string $newPassword): void
    {
        LinktreeSetting::updateOrCreate(
            ['key' => self::PASSWORD_KEY],
            ['value' => self::hashPassword($newPassword)]
        );
    }
}
