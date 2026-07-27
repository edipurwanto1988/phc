@extends('layouts.admin')

@section('title', 'Tambah Testimoni Offline')
@section('header')
<i class="ri-add-circle-line"></i> Tambah Testimoni Offline
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf

            <!-- Pengirim -->
            <div class="mb-4">
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Pengirim</label>
                <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Budi Santoso" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <!-- Customer link -->
                <div>
                    <label for="customer_id" class="block text-sm font-semibold text-gray-700 mb-1">Hubungkan ke Profil Customer (opsional)</label>
                    <select name="customer_id" id="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                        <option value="">-- Tanpa Hubungan Profil --</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Rating -->
                <div>
                    <label for="rating" class="block text-sm font-semibold text-gray-700 mb-1">Rating Bintang</label>
                    <select name="rating" id="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                        <option value="5" selected>★ ★ ★ ★ ★ (5 Bintang)</option>
                        <option value="4">★ ★ ★ ★ ☆ (4 Bintang)</option>
                        <option value="3">★ ★ ★ ☆ ☆ (3 Bintang)</option>
                        <option value="2">★ ★ ☆ ☆ ☆ (2 Bintang)</option>
                        <option value="1">★ ☆ ☆ ☆ ☆ (1 Bintang)</option>
                    </select>
                </div>
            </div>

            <!-- Konten Ulasan -->
            <div class="mb-4">
                <label for="konten" class="block text-sm font-semibold text-gray-700 mb-1">Konten Ulasan (Testimoni)</label>
                <textarea name="konten" id="konten" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Tulis ulasan customer di sini..." required></textarea>
            </div>

            <!-- Toggles -->
            <div class="grid grid-cols-2 gap-4 mb-6 pt-2">
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_approved" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">Setujui Tampil Web</span>
                    </label>
                </div>
                <div class="flex items-center">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">Featured (Tampil di Home)</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Testimoni
                </button>
                <a href="{{ route('admin.testimonials.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
