<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Halaman;

class HalamanController extends Controller
{
    public function show($slug)
    {
        $halaman = Halaman::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('pages.halaman', compact('halaman'));
    }
}
