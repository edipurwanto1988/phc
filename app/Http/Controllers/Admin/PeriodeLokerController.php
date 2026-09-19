<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeLoker;
use Illuminate\Http\Request;

class PeriodeLokerController extends Controller
{
    public function index()
    {
        $periodes = PeriodeLoker::withCount('lokeres')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.periode-loker.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode-loker.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        PeriodeLoker::create([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil ditambahkan.');
    }

    public function edit(PeriodeLoker $periodeLoker)
    {
        return view('admin.periode-loker.edit', compact('periodeLoker'));
    }

    public function update(Request $request, PeriodeLoker $periodeLoker)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $periodeLoker->update([
            'nama' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil diperbarui.');
    }

    public function destroy(PeriodeLoker $periodeLoker)
    {
        if ($periodeLoker->lokeres()->exists()) {
            return redirect()->route('admin.periode-loker.index')->with('error', 'Periode ini masih memiliki pelamar terkait, tidak dapat dihapus.');
        }

        $periodeLoker->delete();
        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil dihapus.');
    }
}
