<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LinktreeVisit;
use App\Support\AdminAuth;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    private const BOT_UA_RE = '/(googlebot|bingbot|yandexbot|baiduspider|duckduckbot|slurp|ahrefsbot|semrushbot|mj12bot|dotbot|bytespider|petalbot)/i';

    private const MONTH_SHORT_ID = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des',
    ];

    private function normalizeDevice(mixed $raw): string
    {
        $s = strtolower(trim((string) ($raw ?? '')));
        if ($s === 'mobile' || $s === 'mob') {
            return 'Mobile';
        }
        if ($s === 'tablet' || $s === 'tab') {
            return 'Tablet';
        }

        return 'Desktop';
    }

    private function normalizeSource(mixed $raw): string
    {
        $s = strtolower(trim((string) ($raw ?? '')));
        if ($s === '' || $s === 'direct' || $s === 'langsung' || $s === 'dir') {
            return 'Langsung';
        }
        if (str_contains($s, 'instagram') || $s === 'ig') {
            return 'Instagram';
        }
        if (str_contains($s, 'whatsapp') || $s === 'wa' || str_contains($s, 'wa.me')) {
            return 'WhatsApp';
        }
        if (str_contains($s, 'tiktok') || $s === 'tt') {
            return 'TikTok';
        }
        if (str_contains($s, 'google') || $s === 'ggl') {
            return 'Google';
        }
        if (str_contains($s, 'facebook') || $s === 'fb' || $s === 'fbclid') {
            return 'Facebook';
        }
        if (str_contains($s, 'youtube') || $s === 'yt' || str_contains($s, 'youtu.be')) {
            return 'YouTube';
        }

        $cleanHost = preg_replace('/^https?:\/\//', '', $s);
        $cleanHost = preg_replace('/^www\./', '', (string) $cleanHost);
        $parts = explode('/', (string) $cleanHost);
        $host = preg_replace('/[^a-z0-9.-]/', '-', $parts[0] ?? '');
        $host = substr(trim((string) $host, '-'), 0, 24);

        return $host !== '' ? $host : 'Langsung';
    }

    private function computeVidHash8(mixed $rawVid, Request $request, string $dateStr): string
    {
        $cleaned = is_string($rawVid) ? substr(trim($rawVid), 0, 64) : '';
        if ($cleaned !== '' && preg_match('/^[a-zA-Z0-9_-]{6,64}$/', $cleaned)) {
            $seed = 'vid:' . $cleaned;
        } else {
            $seed = 'anon:' . ($request->ip() ?? '0.0.0.0') . '|' . ($request->userAgent() ?? '') . '|' . $dateStr;
        }

        return substr(hash('sha256', $seed), 0, 8);
    }

    private function formatShortWibDay(string $yyyyMmDd): string
    {
        $parts = explode('-', $yyyyMmDd);
        if (count($parts) !== 3) {
            return $yyyyMmDd;
        }
        $day = str_pad((string) ((int) $parts[2]), 2, '0', STR_PAD_LEFT);
        $monthNum = (int) $parts[1];
        $monthName = self::MONTH_SHORT_ID[$monthNum] ?? $parts[1];

        return $day . ' ' . $monthName;
    }

    private function buildSummary(string $siteId): array
    {
        $nowWib = Carbon::now('Asia/Jakarta');
        $todayWib = $nowWib->format('Y-m-d');
        $cutoff7d = $nowWib->copy()->subDays(6)->format('Y-m-d');
        $cutoff14d = $nowWib->copy()->subDays(13)->format('Y-m-d');
        $cutoff30d = $nowWib->copy()->subDays(29)->format('Y-m-d');

        $allVisits = LinktreeVisit::where('site', $siteId)
            ->orderByDesc('visited_at_ms')
            ->get();

        $todayViews = 0;
        $views7d = 0;
        $views30d = 0;
        $allViews = $allVisits->count();

        $todayVids = [];
        $vids7d = [];
        $vids30d = [];
        $allVids = [];

        $dailyMap = [];
        for ($i = 0; $i < 14; $i++) {
            $dStr = Carbon::createFromFormat('Y-m-d', $cutoff14d, 'Asia/Jakarta')
                ->addDays($i)
                ->format('Y-m-d');
            $dailyMap[$dStr] = [
                'views' => 0,
                'vids' => [],
            ];
        }

        $deviceCounts = [];
        $sourceCounts = [];
        $locationCounts = [];

        foreach ($allVisits as $idx => $visit) {
            $vid = (string) $visit->vid;
            $date = (string) $visit->visit_date;
            $dev = (string) ($visit->device ?: 'Desktop');
            $src = (string) ($visit->source ?: 'Langsung');

            $allVids[$vid] = true;
            $deviceCounts[$dev] = ($deviceCounts[$dev] ?? 0) + 1;
            $sourceCounts[$src] = ($sourceCounts[$src] ?? 0) + 1;

            if ($date >= $cutoff30d) {
                $views30d++;
                $vids30d[$vid] = true;
            }
            if ($date >= $cutoff7d) {
                $views7d++;
                $vids7d[$vid] = true;
            }
            if ($date === $todayWib) {
                $todayViews++;
                $todayVids[$vid] = true;
            }
            if (isset($dailyMap[$date])) {
                $dailyMap[$date]['views']++;
                $dailyMap[$date]['vids'][$vid] = true;
            }

            if ($idx < 25) {
                $city = trim((string) ($visit->city ?? ''));
                $country = trim((string) ($visit->country ?? ''));
                $loc = ($city !== '' && $country !== '')
                    ? ($city . ', ' . $country)
                    : ($country !== '' ? $country : ($city !== '' ? $city : 'Indonesia'));
                $locationCounts[$loc] = ($locationCounts[$loc] ?? 0) + 1;
            }
        }

        $daily = [];
        foreach ($dailyMap as $date => $info) {
            $daily[] = [
                'date' => $date,
                'label' => $this->formatShortWibDay($date),
                'dayNum' => substr($date, 8, 2),
                'isToday' => $date === $todayWib,
                'views' => $info['views'],
                'visitors' => count($info['vids']),
            ];
        }

        $toRankedList = function (array $counts, int $limit): array {
            arsort($counts);
            $sliced = array_slice($counts, 0, $limit, true);
            $out = [];
            foreach ($sliced as $name => $count) {
                $out[] = ['name' => (string) $name, 'count' => (int) $count];
            }

            return $out;
        };

        $recent = [];
        foreach ($allVisits->take(8) as $rec) {
            $city = trim((string) ($rec->city ?? ''));
            $country = trim((string) ($rec->country ?? ''));
            $loc = ($city !== '' && $country !== '')
                ? ($city . ', ' . $country)
                : ($country !== '' ? $country : ($city !== '' ? $city : 'Indonesia'));
            $recent[] = [
                'date' => $rec->visit_date,
                'timeWib' => $rec->time_wib,
                'device' => $rec->device,
                'source' => $rec->source,
                'location' => $loc,
            ];
        }

        return [
            'ok' => true,
            'site' => $siteId,
            'todayDate' => $todayWib,
            'generatedAt' => now()->toIso8601String(),
            'totals' => [
                'todayViews' => $todayViews,
                'todayVisitors' => count($todayVids),
                'views7d' => $views7d,
                'visitors7d' => count($vids7d),
                'views30d' => $views30d,
                'visitors30d' => count($vids30d),
                'allViews' => $allViews,
                'allVisitors' => count($allVids),
            ],
            'daily' => $daily,
            'sources' => $toRankedList($sourceCounts, 6),
            'devices' => $toRankedList($deviceCounts, 4),
            'locations' => $toRankedList($locationCounts, 5),
            'recent' => $recent,
        ];
    }

    public function handle(Request $request): JsonResponse
    {
        $body = $request->json()->all();
        if (! is_array($body)) {
            return response()->json(['ok' => false, 'error' => 'Body JSON tidak valid'], 400);
        }

        $action = isset($body['action']) && is_string($body['action']) ? $body['action'] : 'track';
        $siteId = AdminAuth::normalizeSiteId($body['site'] ?? null, $body['path'] ?? null);

        if ($action === 'track') {
            $ua = (string) ($request->userAgent() ?? '');
            if ($ua !== '' && preg_match(self::BOT_UA_RE, $ua)) {
                return response()->json(['ok' => true, 'ignored' => true]);
            }

            $nowWib = Carbon::now('Asia/Jakarta');
            $dateStr = $nowWib->format('Y-m-d');
            $timeWib = $nowWib->format('H:i');
            $tsMs = (int) round(microtime(true) * 1000);

            $vid = $this->computeVidHash8($body['vid'] ?? null, $request, $dateStr);
            $device = $this->normalizeDevice($body['device'] ?? null);
            $source = $this->normalizeSource($body['source'] ?? null);
            $path = isset($body['path']) && is_string($body['path'])
                ? substr($body['path'], 0, 120)
                : ($siteId === 'lombok' ? '/lombok' : '/');

            $country = substr((string) ($request->header('CF-IPCountry') ?? $request->header('X-Country') ?? 'Indonesia'), 0, 64);
            $city = substr((string) ($request->header('CF-IPCity') ?? $request->header('X-City') ?? ''), 0, 64);

            LinktreeVisit::create([
                'site' => $siteId,
                'visit_date' => $dateStr,
                'time_wib' => $timeWib,
                'vid' => $vid,
                'device' => $device,
                'source' => $source,
                'country' => $country,
                'city' => $city,
                'path' => $path,
                'visited_at_ms' => $tsMs,
            ]);

            return response()->json(['ok' => true, 'site' => $siteId]);
        }

        $password = isset($body['password']) && is_string($body['password']) ? trim($body['password']) : '';
        if ($password === '') {
            return response()->json(['ok' => false, 'error' => 'Password wajib diisi'], 401);
        }

        if (! AdminAuth::verifyPassword($password)) {
            return response()->json(['ok' => false, 'error' => 'Password tidak sesuai'], 401);
        }

        if ($action === 'summary') {
            return response()->json($this->buildSummary($siteId));
        }

        if ($action === 'reset') {
            $deleted = LinktreeVisit::where('site', $siteId)->delete();

            return response()->json([
                'ok' => true,
                'site' => $siteId,
                'deleted' => $deleted,
            ]);
        }

        return response()->json(['ok' => false, 'error' => 'Aksi tidak dikenal'], 400);
    }
}
