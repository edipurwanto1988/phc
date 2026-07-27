<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        $categories = ServiceCategory::orderBy('urutan')->get();
        return view('admin.service-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.service-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ]);

        ServiceCategory::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon ?? 'ri-sparkling-line',
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.service-categories.index')->with('success', 'Kategori jasa berhasil ditambahkan.');
    }

    public function edit(ServiceCategory $serviceCategory)
    {
        return view('admin.service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, ServiceCategory $serviceCategory)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'urutan' => 'integer',
        ]);

        $serviceCategory->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'icon' => $request->icon ?? 'ri-sparkling-line',
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.service-categories.index')->with('success', 'Kategori jasa berhasil diubah.');
    }

    public function destroy(ServiceCategory $serviceCategory)
    {
        $serviceCategory->delete();
        return redirect()->route('admin.service-categories.index')->with('success', 'Kategori jasa berhasil dihapus.');
    }
}
