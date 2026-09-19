@extends('layouts.admin')

@section('title', 'Tambah Periode Loker')
@section('header')
<i class="ri-add-circle-line"></i> Tambah Periode Loker
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200 max-w-2xl">
    <form method="POST" action="{{ route('admin.periode-loker.store') }}">
        @csrf

        <div class="mb-4">
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Periode <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Periode 1" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
        </div>

        <div class="mb-6">
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Hanya periode dengan status "Aktif" dan dalam rentang tanggal yang akan menerima lamaran dari halaman publik.</p>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Periode
            </button>
            <a href="{{ route('admin.periode-loker.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
