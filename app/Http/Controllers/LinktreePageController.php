<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LinktreePageController extends Controller
{
    public function piknik(Request $request): View
    {
        $site = strtolower((string) $request->query('site', 'piknik')) === 'lombok' ? 'lombok' : 'piknik';

        return view('linktree', ['site' => $site]);
    }

    public function lombok(): View
    {
        return view('linktree', ['site' => 'lombok']);
    }

    public function admin(): View
    {
        return view('admin');
    }
}
