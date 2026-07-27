<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Halaman;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class HalamanController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $halamans = Halaman::when($search, function ($q) use ($search) {
                return $q->where('judul', 'like', "%{$search}%");
            })
            ->when($status, function ($q) use ($status) {
                return $q->where('status', $status);
            })
            ->orderBy('judul', 'asc')
            ->paginate(10);

        return view('admin.halaman.index', compact('halamans', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.halaman.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:halaman,slug',
            'isi' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('halaman', 'public');
        }

        Halaman::create([
            'judul' => $request->judul,
            'slug' => Str::slug($request->slug),
            'isi' => $request->isi,
            'featured_image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman baru berhasil disimpan.');
    }

    public function edit(Halaman $halaman)
    {
        return view('admin.halaman.edit', compact('halaman'));
    }

    public function update(Request $request, Halaman $halaman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:halaman,slug,' . $halaman->id,
            'isi' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = $halaman->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($halaman->featured_image) {
                Storage::disk('public')->delete($halaman->featured_image);
            }
            $imagePath = $request->file('featured_image')->store('halaman', 'public');
        }

        $halaman->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->slug),
            'isi' => $request->isi,
            'featured_image' => $imagePath,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman berhasil diperbarui.');
    }

    public function destroy(Halaman $halaman)
    {
        if ($halaman->featured_image) {
            Storage::disk('public')->delete($halaman->featured_image);
        }
        $halaman->delete();

        return redirect()->route('admin.halaman.index')->with('success', 'Halaman berhasil dihapus.');
    }
}
