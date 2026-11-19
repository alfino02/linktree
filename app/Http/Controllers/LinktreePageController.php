<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LinktreePageController extends Controller
{
    private function noCacheView(string $view, array $data = []): Response
    {
        return response()
            ->view($view, $data)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function piknik(Request $request): Response
    {
        $site = strtolower((string) $request->query('site', 'piknik')) === 'lombok' ? 'lombok' : 'piknik';

        return $this->noCacheView('linktree', ['site' => $site]);
    }

    public function lombok(): Response
    {
        return $this->noCacheView('linktree', ['site' => 'lombok']);
    }

    public function admin(): Response
    {
        return $this->noCacheView('admin');
    }
}
