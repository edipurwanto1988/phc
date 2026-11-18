@extends('layouts.admin')

@section('title', 'Detail Alat')
@section('header')
<i class="ri-tools-line"></i> {{ $asset->nama }}
@endsection

@php
    $kondisiBadge = [
        'baik' => 'bg-green-100 text-green-700',
        'rusak' => 'bg-red-100 text-red-700',
        'dalam_perbaikan' => 'bg-amber-100 text-amber-700',
        'tidak_aktif' => 'bg-gray-100 text-gray-700',
    ];
@endphp

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kiri: detail alat -->
    <div class="lg:col-span-2 space-y-6">
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-gray-100">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-information-line text-blue-600"></i> Informasi Alat
                </h3>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $kondisiBadge[$asset->kondisi] ?? 'bg-gray-100 text-gray-700' }}">{{ $asset->kondisi_label }}</span>
                    <a href="{{ route('admin.master-alat.edit', $asset) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium"><i class="ri-edit-line"></i> Edit</a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Kode Alat</span>
                    <span class="font-semibold text-gray-800 block mt-0.5">{{ $asset->kode_alat ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Kategori</span>
                    <span class="font-semibold text-gray-800 block mt-0.5">{{ $asset->kategori?->nama ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Merk / Tipe</span>
                    <span class="font-semibold text-gray-800 block mt-0.5">{{ $asset->merk ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Lokasi / Penanggung Jawab</span>
                    <span class="font-semibold text-gray-800 block mt-0.5">{{ $asset->lokasi ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Tanggal Beli</span>
                    <span class="font-semibold text-gray-800 block mt-0.5">{{ $asset->tanggal_beli?->translatedFormat('d F Y') ?? '-' }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase">Nilai Perolehan</span>
                    <span class="font-bold text-blue-600 block mt-0.5">Rp {{ number_format($asset->harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 text-sm">
                <span class="block text-xs font-semibold text-gray-400 uppercase">Pengeluaran Pembelian</span>
                @if($asset->expense)
                    <a href="{{ route('admin.expenses.show', $asset->expense) }}" class="font-semibold text-blue-600 hover:underline block mt-0.5">
                        #{{ $asset->expense->id }} · {{ $asset->expense->kategori_biaya }} · {{ $asset->expense->tanggal->format('d/m/Y') }} · Rp {{ number_format($asset->expense->jumlah, 0, ',', '.') }}
                    </a>
                @else
                    <span class="text-gray-500 block mt-0.5">Belum dikaitkan ke pengeluaran.</span>
                @endif
            </div>

            @if($asset->keterangan)
            <div class="mt-4 pt-4 border-t border-gray-100 text-sm">
                <span class="block text-xs font-semibold text-gray-400 uppercase">Keterangan</span>
                <p class="text-gray-700 mt-1 whitespace-pre-line">{{ $asset->keterangan }}</p>
            </div>
            @endif
        </div>

        <!-- Riwayat Kerusakan & Perbaikan -->
        <div class="card bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="p-5 border-b border-gray-200">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="ri-tools-fill text-amber-600"></i> Riwayat Kerusakan & Perbaikan
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="py-3 px-5 text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="py-3 px-5 text-sm font-semibold text-gray-600">Jenis</th>
                            <th class="py-3 px-5 text-sm font-semibold text-gray-600">Keterangan</th>
                            <th class="py-3 px-5 text-sm font-semibold text-gray-600 text-right">Biaya</th>
                            <th class="py-3 px-5 text-sm font-semibold text-gray-600 w-12"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asset->maintenances as $m)
                        <tr class="border-b border-gray-100 align-top">
                            <td class="py-3 px-5 text-sm text-gray-700 whitespace-nowrap">{{ $m->tanggal->format('d/m/Y') }}</td>
                            <td class="py-3 px-5 text-sm">
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full {{ $m->jenis === 'kerusakan' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">{{ $m->jenis_label }}</span>
                                @if($m->kondisi_setelah)
                                    <div class="text-xs text-gray-500 mt-1">Kondisi jadi: {{ \App\Models\Asset::KONDISI[$m->kondisi_setelah] }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-sm text-gray-700">
                                {{ $m->deskripsi }}
                                @if($m->expense)
                                    <div class="text-xs text-blue-500 mt-1"><i class="ri-links-line"></i> Pengeluaran #{{ $m->expense->id }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-5 text-sm text-right font-medium text-gray-800 whitespace-nowrap">{{ $m->biaya > 0 ? 'Rp ' . number_format($m->biaya, 0, ',', '.') : '-' }}</td>
                            <td class="py-3 px-5 text-center">
                                <form method="POST" action="{{ route('admin.master-alat.perbaikan.destroy', $m) }}" onsubmit="return confirm('Hapus catatan ini? Pengeluaran biaya terkait juga ikut dihapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><i class="ri-delete-bin-line text-lg"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada catatan kerusakan atau perbaikan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($asset->maintenances->isNotEmpty())
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="py-3 px-5 text-sm font-semibold text-gray-700 text-right">Total biaya perbaikan</td>
                            <td class="py-3 px-5 text-sm font-bold text-gray-800 text-right">Rp {{ number_format($totalPerbaikan, 0, ',', '.') }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Kanan: ringkasan & form catat -->
    <div class="space-y-6">
        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <span class="block text-xs font-semibold text-gray-400 uppercase">Total Investasi Alat</span>
            <span class="block text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalInvestasi, 0, ',', '.') }}</span>
            <p class="text-xs text-gray-500 mt-2">Nilai perolehan + total biaya perbaikan.</p>
        </div>

        <div class="card p-6 bg-white border border-gray-200 rounded-xl">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="ri-add-circle-line text-blue-600"></i> Catat Kerusakan / Perbaikan
            </h3>
            <form method="POST" action="{{ route('admin.master-alat.perbaikan.store', $asset) }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', now()->format('Y-m-d')) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jenis</label>
                    <select name="jenis" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        @foreach($jenisOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('jenis') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Motor vacuum tidak menyala, diganti kabel" required>{{ old('deskripsi') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Biaya (Rp)</label>
                    <input type="number" name="biaya" min="0" step="0.01" value="{{ old('biaya', 0) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                    <p class="text-xs text-gray-500 mt-1">Jika diisi lebih dari 0, otomatis dicatat sebagai pengeluaran "Perbaikan Alat".</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi Setelah</label>
                    <select name="kondisi_setelah" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                        <option value="">— Otomatis —</option>
                        @foreach($kondisiOptions as $value => $label)
                            <option value="{{ $value }}" @selected(old('kondisi_setelah') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Kerusakan otomatis menandai alat "Rusak" bila dikosongkan.</p>
                </div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Catatan
                </button>
            </form>
        </div>

        <a href="{{ route('admin.master-alat.index') }}" class="block text-center text-sm text-gray-600 hover:text-gray-900"><i class="ri-arrow-left-line"></i> Kembali ke Master Alat</a>
    </div>
</div>
@endsection
