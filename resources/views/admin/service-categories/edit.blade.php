@extends('layouts.admin')

@section('title', 'Edit Kategori Jasa')
@section('header')
<i class="ri-edit-box-line"></i> Edit Kategori Jasa
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.service-categories.update', $serviceCategory) }}">
            @csrf
            @method('PUT')

            <!-- Nama Kategori -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Cleaning Rumah" value="{{ $serviceCategory->nama }}" required>
            </div>

            <!-- Deskripsi -->
            <div class="mb-4">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Deskripsi singkat mengenai kategori jasa ini...">{{ $serviceCategory->deskripsi }}</textarea>
            </div>

            <!-- Icon -->
            <div class="mb-4">
                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-1">Remix Icon Class</label>
                <input type="text" name="icon" id="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: ri-home-4-line" value="{{ $serviceCategory->icon }}">
                <p class="text-xs text-gray-500 mt-1">Gunakan class icon dari <a href="https://remixicon.com" target="_blank" class="text-blue-600 hover:underline">Remix Icon</a>.</p>
            </div>

            <!-- Urutan & Active -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" id="urutan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $serviceCategory->urutan }}" min="0">
                </div>
                <div class="flex items-center pl-2 pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ $serviceCategory->is_active ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.service-categories.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
