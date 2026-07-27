@extends('layouts.admin')

@section('title', 'Edit Item Menu')
@section('header')
<i class="ri-edit-box-line"></i> Edit Item Menu: {{ $menu->nama }}
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200" x-data="{ selectedUrl: '{{ $menu->url }}' }">
        <form method="POST" action="{{ route('admin.menu.update', $menu) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Nama Menu -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Menu</label>
                    <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $menu->nama }}" required>
                </div>

                <!-- Icon -->
                <div>
                    <label for="icon" class="block text-sm font-semibold text-gray-700 mb-1">Icon Remix (opsional)</label>
                    <input type="text" name="icon" id="icon" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $menu->icon }}" placeholder="Contoh: ri-home-4-line">
                    <p class="text-[10px] text-gray-400 mt-1">Gunakan nama class dari <a href="https://remixicon.com" target="_blank" class="text-blue-600 hover:underline">remixicon.com</a>.</p>
                </div>
            </div>

            <!-- URL Picker & Input -->
            <div class="mb-4">
                <label for="url_picker" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Link / URL Cepat (opsional)</label>
                <select id="url_picker" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white mb-2" x-model="selectedUrl" @change="document.getElementById('url').value = selectedUrl">
                    <option value="">-- Ketik URL Kustom Sendiri --</option>
                    @foreach($urlOptions as $group => $opts)
                    <optgroup label="{{ $group }}">
                        @foreach($opts as $opt)
                        <option value="{{ $opt['value'] }}" {{ $menu->url == $opt['value'] ? 'selected' : '' }}>{{ $opt['label'] }} ({{ $opt['value'] }})</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>

                <label for="url" class="block text-sm font-semibold text-gray-700 mb-1">Link URL</label>
                <input type="text" name="url" id="url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $menu->url }}" placeholder="Contoh: /, /layanan, https://google.com" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Parent Menu -->
                <div>
                    <label for="parent_id" class="block text-sm font-semibold text-gray-700 mb-1">Sub-Menu Dari (Parent)</label>
                    <select name="parent_id" id="parent_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                        <option value="">-- Menu Utama (Tidak ada Parent) --</option>
                        @foreach($parents as $parent)
                        <option value="{{ $parent->id }}" {{ $menu->parent_id == $parent->id ? 'selected' : '' }}>{{ $parent->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Posisi -->
                <div>
                    <label for="posisi" class="block text-sm font-semibold text-gray-700 mb-1">Posisi Menu</label>
                    <select name="posisi" id="posisi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="header" {{ $menu->posisi === 'header' ? 'selected' : '' }}>Header (Navigasi Atas)</option>
                        <option value="footer" {{ $menu->posisi === 'footer' ? 'selected' : '' }}>Footer (Kaki Halaman)</option>
                        <option value="sidebar" {{ $menu->posisi === 'sidebar' ? 'selected' : '' }}>Sidebar Admin</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <!-- Target -->
                <div>
                    <label for="target" class="block text-sm font-semibold text-gray-700 mb-1">Target Buka Link</label>
                    <select name="target" id="target" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="_self" {{ $menu->target === '_self' ? 'selected' : '' }}>_self (Tab saat ini)</option>
                        <option value="_blank" {{ $menu->target === '_blank' ? 'selected' : '' }}>_blank (Tab baru)</option>
                    </select>
                </div>

                <!-- Urutan -->
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-1">No. Urut Urutan</label>
                    <input type="number" name="urutan" id="urutan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $menu->urutan }}" min="0" required>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status Keaktifan</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="active" {{ $menu->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $menu->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.menu.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
