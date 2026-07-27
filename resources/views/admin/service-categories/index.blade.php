@extends('layouts.admin')

@section('title', 'Kategori Jasa')
@section('header')
<i class="ri-folder-line"></i> Kategori Jasa (Layanan)
@endsection

@section('content')
<div class="card">
    <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-white rounded-t-xl">
        <h3 class="font-semibold text-gray-800">Daftar Kategori Jasa</h3>
        <a href="{{ route('admin.service-categories.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg flex items-center gap-1.5 shadow-sm text-sm">
            <i class="ri-add-line text-lg"></i> Tambah Kategori
        </a>
    </div>
    <div class="overflow-x-auto bg-white rounded-b-xl">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-16 text-center">Urutan</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-12 text-center">Icon</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Kategori</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600">Deskripsi</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-24 text-center">Status</th>
                    <th class="py-3 px-6 text-sm font-semibold text-gray-600 w-28 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm text-gray-500 text-center font-medium">{{ $category->urutan }}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600">
                            <i class="{{ $category->icon }} text-lg"></i>
                        </span>
                    </td>
                    <td class="py-4 px-6 text-sm font-semibold text-gray-800">
                        {{ $category->nama }}
                        <div class="text-xs text-gray-400 font-normal">slug: {{ $category->slug }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600 max-w-xs truncate">{{ $category->deskripsi ?? '-' }}</td>
                    <td class="py-4 px-6 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('admin.service-categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                <i class="ri-edit-line text-lg"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.service-categories.destroy', $category) }}" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua jasa di bawahnya akan kehilangan kategori.')">
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
                    <td colspan="6" class="py-8 text-center text-sm text-gray-500 font-medium">Belum ada data kategori jasa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
