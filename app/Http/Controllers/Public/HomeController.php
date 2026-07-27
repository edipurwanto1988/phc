<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\Post;

class HomeController extends Controller
{
    public function index()
    {
        $services = Service::with('category')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->limit(6)
            ->get();

        $testimonials = Testimonial::where('is_approved', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $posts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('pages.home', compact('services', 'testimonials', 'posts'));
    }

    public function about()
    {
        return view('pages.about');
    }
}
