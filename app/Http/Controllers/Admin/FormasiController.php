<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formasi;
use Illuminate\Http\Request;

class FormasiController extends Controller
{
    public function index()
    {
        $formasis = Formasi::withCount('lokeres')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.formasi.index', compact('formasis'));
    }

    public function create()
    {
        return view('admin.formasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $formasi = Formasi::create([
            'nama' => $request->nama,
        ]);

        // Dipanggil dari modal (AJAX) di form periode loker
        if ($request->wantsJson()) {
            return response()->json([
                'id' => $formasi->id,
                'nama' => $formasi->nama,
            ], 201);
        }

        return redirect()->route('admin.formasi.index')->with('success', 'Formasi berhasil ditambahkan.');
    }

    public function edit(Formasi $formasi)
    {
        return view('admin.formasi.edit', compact('formasi'));
    }

    public function update(Request $request, Formasi $formasi)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $formasi->update([
            'nama' => $request->nama,
        ]);

        return redirect()->route('admin.formasi.index')->with('success', 'Formasi berhasil diperbarui.');
    }

    public function destroy(Formasi $formasi)
    {
        if ($formasi->lokeres()->exists()) {
            return redirect()->route('admin.formasi.index')->with('error', 'Formasi ini masih digunakan oleh pelamar, tidak dapat dihapus.');
        }

        $formasi->delete();
        return redirect()->route('admin.formasi.index')->with('success', 'Formasi berhasil dihapus.');
    }
}
