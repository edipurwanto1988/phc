@extends('layouts.admin')

@section('title', 'Master Alat')
@section('header')
<i class="ri-tools-line"></i> Master Alat
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
<!-- Ringkasan -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-5 bg-white rounded-xl border border-gray-200">
        <span class="block text-xs font-semibold text-gray-400 uppercase">Total Unit Alat</span>
        <span class="block text-2xl font-bold text-gray-800 mt-1">{{ number_format($stats['total_unit'], 0, ',', '.') }}</span>
    </div>
    <div class="card p-5 bg-white rounded-xl border border-gray-200">
        <span class="block text-xs font-semibold text-gray-400 uppercase">Total Nilai Perolehan</span>
        <span class="block text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($stats['total_nilai'], 0, ',', '.') }}</span>
    </div>
    <div class="card p-5 bg-white rounded-xl border border-gray-200">
        <span class="block text-xs font-semibold text-gray-400 uppercase">Rusak / Dalam Perbaikan</span>
        <span class="block text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['bermasalah'], 0, ',', '.') }}</span>
    </div>
    <div class="card p-5 bg-white rounded-xl border border-gray-200">
        <span class="block text-xs font-semibold text-gray-400 uppercase">Total Biaya Perbaikan</span>
        <span class="block text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($stats['total_biaya_perbaikan'], 0, ',', '.') }}</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Daftar Alat -->
    <div class="lg:col-span-2 card bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="p-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <h3 class="font-semibold text-gray-800">Daftar Alat</h3>
            <a href="{{ route('admin.master-alat.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm w-fit">
                <i class="ri-add-line text-lg"></i> Tambah Alat
            </a>
        </div>

        <form method="GET" action="{{ route('admin.master-alat.index') }}" class="p-5 border-b border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-3">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama, kode, merk, kategori" class="md:col-span-2 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <select name="kondisi" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" onchange="this.form.submit()">
                <option value="">Semua kondisi</option>
                @foreach($kondisiBadge as $value => $_)
                    <option value="{{ $value }}" @selected($kondisi === $value)>{{ \App\Models\Asset::KONDISI[$value] }}</option>
                @endforeach
            </select>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="py-3 px-5 text-sm font-semibold text-gray-600">Alat</th>
                        <th class="py-3 px-5 text-sm font-semibold text-gray-600 text-right">Nilai</th>
                        <th class="py-3 px-5 text-sm font-semibold text-gray-600 text-center">Kondisi</th>
                        <th class="py-3 px-5 text-sm font-semibold text-gray-600 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assets as $asset)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-5 text-sm">
                            <a href="{{ route('admin.master-alat.show', $asset) }}" class="font-semibold text-gray-800 hover:text-blue-600">{{ $asset->nama }}</a>
                            <div class="text-xs text-gray-400">{{ $asset->kode_alat }} · {{ $asset->kategori?->nama ?? 'Tanpa kategori' }}{{ $asset->merk ? ' · ' . $asset->merk : '' }}</div>
                            @if($asset->expense)
                                <div class="text-xs text-blue-500 mt-0.5"><i class="ri-links-line"></i> Pengeluaran #{{ $asset->expense->id }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-5 text-sm text-right font-medium text-gray-800">Rp {{ number_format($asset->harga, 0, ',', '.') }}</td>
                        <td class="py-4 px-5 text-center">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $kondisiBadge[$asset->kondisi] ?? 'bg-gray-100 text-gray-700' }}">{{ $asset->kondisi_label }}</span>
                        </td>
                        <td class="py-4 px-5 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.master-alat.show', $asset) }}" class="text-gray-600 hover:text-gray-900" title="Detail & Perbaikan"><i class="ri-file-list-3-line text-lg"></i></a>
                                <a href="{{ route('admin.master-alat.edit', $asset) }}" class="text-blue-600 hover:text-blue-800" title="Edit"><i class="ri-edit-line text-lg"></i></a>
                                <form method="POST" action="{{ route('admin.master-alat.destroy', $asset) }}" onsubmit="return confirm('Hapus alat ini? Riwayat perbaikannya ikut terhapus.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus"><i class="ri-delete-bin-line text-lg"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data alat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($assets->hasPages())
        <div class="p-4 border-t border-gray-100">{{ $assets->links() }}</div>
        @endif
    </div>

    <!-- Rekap per Kategori -->
    <div class="card bg-white rounded-xl border border-gray-200 h-fit">
        <div class="p-5 border-b border-gray-200">
            <h3 class="font-semibold text-gray-800">Rekap per Kategori</h3>
        </div>
        <div class="p-5 space-y-3">
            @forelse($perKategori as $row)
            <div class="flex items-center justify-between text-sm">
                <div>
                    <div class="font-semibold text-gray-800">{{ $row->kategori ?: 'Tanpa kategori' }}</div>
                    <div class="text-xs text-gray-500">{{ $row->jumlah }} unit</div>
                </div>
                <div class="font-medium text-gray-700">Rp {{ number_format($row->nilai, 0, ',', '.') }}</div>
            </div>
            @empty
            <p class="text-sm text-gray-500">Belum ada data.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
