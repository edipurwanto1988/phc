<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with('category')->orderBy('urutan')->get();
        return view('admin.services.index', compact('services'));
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');
        if (is_array($order)) {
            foreach ($order as $item) {
                Service::where('id', $item['id'])->update(['urutan' => $item['urutan']]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function create()
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('urutan')->get();
        return view('admin.services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:service_categories,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'durasi_estimasi' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'urutan' => 'integer',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('services', 'public');
        }

        Service::create([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
            'durasi_estimasi' => $request->durasi_estimasi,
            'gambar' => $gambarPath,
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Master jasa berhasil ditambahkan.');
    }

    public function edit(Service $service)
    {
        $categories = ServiceCategory::where('is_active', true)->orderBy('urutan')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'kategori_id' => 'required|exists:service_categories,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'harga' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:50',
            'durasi_estimasi' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'urutan' => 'integer',
        ]);

        $gambarPath = $service->gambar;
        if ($request->hasFile('gambar')) {
            if ($gambarPath) {
                Storage::disk('public')->delete($gambarPath);
            }
            $gambarPath = $request->file('gambar')->store('services', 'public');
        }

        $service->update([
            'kategori_id' => $request->kategori_id,
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'deskripsi' => $request->deskripsi,
            'deskripsi_singkat' => $request->deskripsi_singkat,
            'harga' => $request->harga,
            'satuan' => $request->satuan,
            'durasi_estimasi' => $request->durasi_estimasi,
            'gambar' => $gambarPath,
            'is_active' => $request->has('is_active'),
            'urutan' => $request->urutan ?? 0,
        ]);

        return redirect()->route('admin.services.index')->with('success', 'Master jasa berhasil diubah.');
    }

    public function destroy(Service $service)
    {
        if ($service->gambar) {
            Storage::disk('public')->delete($service->gambar);
        }
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Master jasa berhasil dihapus.');
    }
}
