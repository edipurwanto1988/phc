<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formasi;
use App\Models\PeriodeLoker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $formasis = Formasi::orderBy('nama')->get();
        return view('admin.periode-loker.create', compact('formasis'));
    }

    /**
     * Aturan validasi bersama untuk create & update.
     */
    private function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,nonaktif',
            'gambar' => [
                'nullable',
                'file',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:2048',
            ],
            'hapus_gambar' => 'nullable|boolean',
            'formasi_ids' => 'nullable|array|max:100',
            'formasi_ids.*' => 'integer|distinct|exists:formasis,id',
            'syarats' => 'nullable|array|max:30',
            'syarats.*.id' => 'nullable|integer',
            'syarats.*.nama' => 'nullable|string|max:255',
            'syarats.*.wajib' => 'nullable|boolean',
        ];
    }

    /**
     * Sinkronkan daftar syarat periode: update yang id-nya dikenal, buat yang baru,
     * dan hapus yang tidak dikirim lagi. Baris tanpa nama diabaikan.
     */
    private function syncSyarats(PeriodeLoker $periode, array $rows): void
    {
        $keepIds = [];
        $urutan = 0;

        foreach ($rows as $row) {
            $nama = trim((string) ($row['nama'] ?? ''));
            if ($nama === '') {
                continue;
            }

            $payload = [
                'nama' => $nama,
                'wajib' => filter_var($row['wajib'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'urutan' => $urutan++,
            ];

            $existing = isset($row['id'])
                ? $periode->syarats()->whereKey((int) $row['id'])->first()
                : null;

            if ($existing) {
                $existing->update($payload);
                $keepIds[] = $existing->id;
            } else {
                $keepIds[] = $periode->syarats()->create($payload)->id;
            }
        }

        $periode->syarats()->whereNotIn('id', $keepIds)->delete();
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        $formasiIds = collect($validated['formasi_ids'] ?? [])->map(fn ($id) => (int) $id)->values()->all();

        // Nama file di-generate sendiri oleh Laravel (hash), jadi nama asli dari user tidak dipakai.
        $gambarPath = $request->hasFile('gambar')
            ? $request->file('gambar')->store('periode-loker', 'public')
            : null;

        try {
            DB::transaction(function () use ($validated, $formasiIds, $gambarPath) {
                $periode = PeriodeLoker::create([
                    'nama' => $validated['nama'],
                    'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                    'status' => $validated['status'],
                    'gambar' => $gambarPath,
                ]);

                $periode->formasis()->sync($formasiIds);
                $this->syncSyarats($periode, $validated['syarats'] ?? []);
            });
        } catch (\Throwable $e) {
            if ($gambarPath) {
                Storage::disk('public')->delete($gambarPath);
            }
            throw $e;
        }

        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil ditambahkan.');
    }

    public function edit(PeriodeLoker $periodeLoker)
    {
        $periodeLoker->load(['formasis', 'syarats']);
        $formasis = Formasi::orderBy('nama')->get();
        return view('admin.periode-loker.edit', compact('periodeLoker', 'formasis'));
    }

    public function update(Request $request, PeriodeLoker $periodeLoker)
    {
        $validated = $request->validate($this->rules());

        $formasiIds = collect($validated['formasi_ids'] ?? [])->map(fn ($id) => (int) $id)->values()->all();

        $gambarLama = $periodeLoker->gambar;
        $gambarBaru = $gambarLama;
        $gambarUpload = null;

        if ($request->hasFile('gambar')) {
            $gambarUpload = $request->file('gambar')->store('periode-loker', 'public');
            $gambarBaru = $gambarUpload;
        } elseif ($request->boolean('hapus_gambar')) {
            $gambarBaru = null;
        }

        try {
            DB::transaction(function () use ($validated, $formasiIds, $gambarBaru, $periodeLoker) {
                $periodeLoker->update([
                    'nama' => $validated['nama'],
                    'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
                    'status' => $validated['status'],
                    'gambar' => $gambarBaru,
                ]);

                $periodeLoker->formasis()->sync($formasiIds);
                $this->syncSyarats($periodeLoker, $validated['syarats'] ?? []);
            });
        } catch (\Throwable $e) {
            // Rollback: hapus file baru yang sudah terlanjur tersimpan
            if ($gambarUpload) {
                Storage::disk('public')->delete($gambarUpload);
            }
            throw $e;
        }

        // Hapus file lama hanya setelah DB berhasil diperbarui
        if ($gambarLama && $gambarLama !== $gambarBaru) {
            Storage::disk('public')->delete($gambarLama);
        }

        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil diperbarui.');
    }

    public function destroy(PeriodeLoker $periodeLoker)
    {
        if ($periodeLoker->lokeres()->exists()) {
            return redirect()->route('admin.periode-loker.index')->with('error', 'Periode ini masih memiliki pelamar terkait, tidak dapat dihapus.');
        }

        $gambar = $periodeLoker->gambar;
        $periodeLoker->delete();

        if ($gambar) {
            Storage::disk('public')->delete($gambar);
        }

        return redirect()->route('admin.periode-loker.index')->with('success', 'Periode loker berhasil dihapus.');
    }
}
