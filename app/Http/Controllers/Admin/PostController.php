<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('author')->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $posts = $query->paginate(10)->appends($request->all());
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,published',
        ]);

        $gambarPath = null;
        $thumbnailPath = null;
        if ($request->hasFile('gambar_utama')) {
            $gambarPath = $request->file('gambar_utama')->store('posts', 'public');
            try {
                $thumbnailPath = $this->generateThumbnail($request->file('gambar_utama'), $gambarPath);
            } catch (\Exception $e) {
                // Fail-safe if image resizing fails
                $thumbnailPath = null;
            }
        }

        Post::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . rand(1000, 9999),
            'konten' => $request->konten,
            'excerpt' => $request->excerpt,
            'gambar_utama' => $gambarPath,
            'gambar_utama_thumbnail' => $thumbnailPath,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status,
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diterbitkan.');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'gambar_utama' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,published',
        ]);

        $gambarPath = $post->gambar_utama;
        $thumbnailPath = $post->gambar_utama_thumbnail;
        if ($request->hasFile('gambar_utama')) {
            if ($gambarPath) {
                Storage::disk('public')->delete($gambarPath);
            }
            if ($thumbnailPath) {
                Storage::disk('public')->delete($thumbnailPath);
            }
            $gambarPath = $request->file('gambar_utama')->store('posts', 'public');
            try {
                $thumbnailPath = $this->generateThumbnail($request->file('gambar_utama'), $gambarPath);
            } catch (\Exception $e) {
                $thumbnailPath = null;
            }
        }

        $publishedAt = $post->published_at;
        if ($request->status === 'published' && !$publishedAt) {
            $publishedAt = now();
        } elseif ($request->status === 'draft') {
            $publishedAt = null;
        }

        $post->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul) . '-' . substr($post->slug, -4),
            'konten' => $request->konten,
            'excerpt' => $request->excerpt,
            'gambar_utama' => $gambarPath,
            'gambar_utama_thumbnail' => $thumbnailPath,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'status' => $request->status,
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil diperbarui.');
    }


    public function destroy(Post $post)
    {
        if ($post->gambar_utama) {
            Storage::disk('public')->delete($post->gambar_utama);
        }
        if ($post->gambar_utama_thumbnail) {
            Storage::disk('public')->delete($post->gambar_utama_thumbnail);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Artikel berhasil dihapus.');
    }

    private function generateThumbnail($file, $path)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->cover(56, 56);
        $thumbnailName = 'posts/thumb_' . basename($path);
        Storage::disk('public')->put($thumbnailName, $image->toPng());
        return $thumbnailName;
    }
}
