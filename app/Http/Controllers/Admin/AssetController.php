<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetMaintenance;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $kondisi = $request->query('kondisi');

        $assets = Asset::with(['expense', 'kategori'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode_alat', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhereHas('kategori', fn ($k) => $k->where('nama', 'like', "%{$search}%"));
                });
            })
            ->when($kondisi && array_key_exists($kondisi, Asset::KONDISI), fn ($q) => $q->where('kondisi', $kondisi))
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total_unit' => Asset::count(),
            'total_nilai' => (float) Asset::sum('harga'),
            'bermasalah' => Asset::whereIn('kondisi', ['rusak', 'dalam_perbaikan'])->count(),
            'total_biaya_perbaikan' => (float) AssetMaintenance::sum('biaya'),
        ];

        $perKategori = Asset::query()
            ->leftJoin('asset_categories', 'asset_categories.id', '=', 'assets.kategori_id')
            ->selectRaw('asset_categories.nama as kategori, COUNT(assets.id) as jumlah, SUM(assets.harga) as nilai')
            ->groupBy('asset_categories.nama')
            ->orderByDesc('jumlah')
            ->get();

        return view('admin.master-alat.index', compact('assets', 'stats', 'perKategori', 'search', 'kondisi'));
    }

    public function create()
    {
        return view('admin.master-alat.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->prepareData($this->validateAsset($request));

        if (empty($data['kode_alat'])) {
            $data['kode_alat'] = $this->nextKodeAlat();
        }

        Asset::create($data);

        return redirect()->route('admin.master-alat.index')->with('success', 'Data alat berhasil ditambahkan.');
    }

    public function show(Asset $masterAlat)
    {
        $masterAlat->load(['expense', 'kategori', 'maintenances.expense']);

        $totalPerbaikan = (float) $masterAlat->maintenances->sum('biaya');

        return view('admin.master-alat.show', [
            'asset' => $masterAlat,
            'totalPerbaikan' => $totalPerbaikan,
            'totalInvestasi' => (float) $masterAlat->harga + $totalPerbaikan,
            'kondisiOptions' => Asset::KONDISI,
            'jenisOptions' => AssetMaintenance::JENIS,
        ]);
    }

    public function edit(Asset $masterAlat)
    {
        return view('admin.master-alat.edit', array_merge($this->formData(), ['asset' => $masterAlat]));
    }

    public function update(Request $request, Asset $masterAlat)
    {
        $data = $this->prepareData($this->validateAsset($request, $masterAlat));

        // Kode yang dikosongkan saat edit tetap memakai kode lama
        if (empty($data['kode_alat'])) {
            $data['kode_alat'] = $masterAlat->kode_alat ?: $this->nextKodeAlat();
        }

        $masterAlat->update($data);

        return redirect()->route('admin.master-alat.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    /**
     * Tambah kategori alat baru dari popup form (JSON).
     */
    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100|unique:asset_categories,nama',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        $category = AssetCategory::create(['nama' => trim($data['nama'])]);

        return response()->json([
            'id' => $category->id,
            'nama' => $category->nama,
        ], 201);
    }

    public function destroy(Asset $masterAlat)
    {
        // Riwayat perbaikan ikut terhapus (cascade); pengeluaran terkait tetap dipertahankan.
        $masterAlat->delete();

        return redirect()->route('admin.master-alat.index')->with('success', 'Data alat berhasil dihapus.');
    }

    /**
     * Catat kerusakan / perbaikan. Jika ada biaya, otomatis dibuat sebagai pengeluaran.
     */
    public function storeMaintenance(Request $request, Asset $masterAlat)
    {
        $data = $request->validate([
            'tanggal' => 'required|date',
            'jenis' => ['required', Rule::in(array_keys(AssetMaintenance::JENIS))],
            'deskripsi' => 'required|string',
            'biaya' => 'nullable|numeric|min:0',
            'kondisi_setelah' => ['nullable', Rule::in(array_keys(Asset::KONDISI))],
        ]);

        $biaya = (float) ($data['biaya'] ?? 0);

        DB::transaction(function () use ($data, $biaya, $masterAlat) {
            $maintenance = AssetMaintenance::create([
                'asset_id' => $masterAlat->id,
                'tanggal' => $data['tanggal'],
                'jenis' => $data['jenis'],
                'deskripsi' => $data['deskripsi'],
                'biaya' => $biaya,
                'kondisi_setelah' => $data['kondisi_setelah'] ?? null,
            ]);

            if ($biaya > 0) {
                $expense = Expense::create([
                    'tanggal' => $data['tanggal'],
                    'kategori_biaya' => 'Perbaikan Alat',
                    'jumlah' => $biaya,
                    'keterangan' => "{$maintenance->jenis_label} {$masterAlat->nama} ({$masterAlat->kode_alat}): {$data['deskripsi']}",
                    'user_id' => auth()->id(),
                    'is_gaji' => false,
                ]);
                $maintenance->update(['expense_id' => $expense->id]);
            }

            if (!empty($data['kondisi_setelah'])) {
                $masterAlat->update(['kondisi' => $data['kondisi_setelah']]);
            } elseif ($data['jenis'] === 'kerusakan') {
                $masterAlat->update(['kondisi' => 'rusak']);
            }
        });

        return redirect()->route('admin.master-alat.show', $masterAlat)->with('success', 'Catatan ' . strtolower(AssetMaintenance::JENIS[$data['jenis']]) . ' berhasil ditambahkan.');
    }

    public function destroyMaintenance(AssetMaintenance $maintenance)
    {
        $assetId = $maintenance->asset_id;

        DB::transaction(function () use ($maintenance) {
            $expenseId = $maintenance->expense_id;
            $maintenance->delete();

            if ($expenseId) {
                Expense::whereKey($expenseId)->delete();
            }
        });

        return redirect()->route('admin.master-alat.show', $assetId)->with('success', 'Catatan perbaikan berhasil dihapus.');
    }

    private function validateAsset(Request $request, ?Asset $asset = null): array
    {
        return $request->validate([
            'kode_alat' => ['nullable', 'string', 'max:50', Rule::unique('assets', 'kode_alat')->ignore($asset?->id)],
            'nama' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:asset_categories,id',
            'merk' => 'nullable|string|max:100',
            'tanggal_beli' => 'nullable|date',
            'harga' => 'nullable|numeric|min:0',
            'expense_id' => 'nullable|exists:expenses,id',
            'kondisi' => ['required', Rule::in(array_keys(Asset::KONDISI))],
            'lokasi' => 'nullable|string|max:150',
            'keterangan' => 'nullable|string',
        ]);
    }

    /**
     * Jika alat dikaitkan ke pengeluaran dan harga/tanggal belum diisi, ambil dari pengeluaran tersebut.
     */
    private function prepareData(array $data): array
    {
        if (!empty($data['expense_id'])) {
            $expense = Expense::find($data['expense_id']);

            if ($expense) {
                if (!isset($data['harga']) || $data['harga'] === '') {
                    $data['harga'] = $expense->jumlah;
                }
                if (empty($data['tanggal_beli'])) {
                    $data['tanggal_beli'] = $expense->tanggal;
                }
            }
        }

        $data['harga'] = (float) ($data['harga'] ?? 0);
        $data['kode_alat'] = ($data['kode_alat'] ?? null) ?: null;
        $data['expense_id'] = ($data['expense_id'] ?? null) ?: null;

        return $data;
    }

    /**
     * Kode berikutnya berdasarkan nomor urut tertinggi, contoh: ALT-0007.
     */
    private function nextKodeAlat(): string
    {
        $max = Asset::where('kode_alat', 'like', 'ALT-%')
            ->pluck('kode_alat')
            ->map(fn (string $kode) => (int) substr($kode, 4))
            ->max() ?? 0;

        return 'ALT-' . str_pad((string) ($max + 1), 4, '0', STR_PAD_LEFT);
    }

    private function formData(): array
    {
        return [
            'kondisiOptions' => Asset::KONDISI,
            'suggestedKode' => $this->nextKodeAlat(),
            'categories' => AssetCategory::orderBy('nama')->get(['id', 'nama']),
            'expenseOptions' => Expense::where('is_gaji', false)
                ->orderByDesc('tanggal')
                ->limit(300)
                ->get(['id', 'tanggal', 'kategori_biaya', 'jumlah', 'keterangan'])
                ->map(fn (Expense $e) => [
                    'id' => (string) $e->id,
                    'label' => $e->tanggal->format('d/m/Y') . ' · ' . $e->kategori_biaya . ' · Rp ' . number_format($e->jumlah, 0, ',', '.'),
                    'search' => mb_strtolower(implode(' ', [
                        $e->tanggal->format('d/m/Y'),
                        $e->tanggal->format('Y-m-d'),
                        $e->kategori_biaya,
                        (string) $e->jumlah,
                        number_format($e->jumlah, 0, ',', '.'),
                        (string) $e->keterangan,
                    ])),
                ])
                ->values()
                ->all(),
        ];
    }
}
