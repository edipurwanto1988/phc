<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Post::with('author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(6);

        return view('pages.blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = Post::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Recent posts sidebar
        $recentPosts = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderBy('published_at', 'desc')
            ->limit(4)
            ->get();

        return view('pages.blog.show', compact('post', 'recentPosts'));
    }
}
