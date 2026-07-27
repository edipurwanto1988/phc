@extends('layouts.admin')

@section('title', 'Master Jasa')
@section('header')
<i class="ri-sparkling-line"></i> Master Jasa (Layanan)
@endsection

@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Layanan Jasa</h3>
        <a href="{{ route('admin.services.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Jasa Baru
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-16 text-center">Urutan</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24">Gambar</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Jasa</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Kategori</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 text-right">Harga Dasar</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24 text-center">Status</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500 text-center font-medium">{{ $service->urutan }}</td>
                    <td class="py-4 px-6">
                        @if($service->gambar)
                        <img src="{{ asset('storage/' . $service->gambar) }}" alt="{{ $service->nama }}" class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                        @else
                        <div class="w-12 h-12 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                            <i class="ri-image-line text-xl"></i>
                        </div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                        {{ $service->nama }}
                        <div class="text-xs text-gray-400 font-normal">slug: {{ $service->slug }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-700">
                            {{ $service->category->nama ?? 'Tanpa Kategori' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm font-bold text-gray-800 text-right">
                        Rp {{ number_format($service->harga, 0, ',', '.') }} <span class="text-xs text-gray-400 font-normal">/ {{ $service->satuan }}</span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $service->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.services.edit', $service) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jasa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Hapus">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data master jasa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
