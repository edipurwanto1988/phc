<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Service;

class SitemapController extends Controller
{
    public function index()
    {
        $posts    = Post::where('status', 'published')
                       ->orderByDesc('updated_at')
                       ->get(['slug', 'updated_at']);

        $services = Service::where('is_active', true)
                           ->orderByDesc('updated_at')
                           ->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('posts', 'services'))
            ->header('Content-Type', 'text/xml; charset=UTF-8');
    }
}
