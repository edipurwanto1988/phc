@extends('layouts.admin')

@section('title', 'Edit Formasi')
@section('header')
<i class="ri-edit-line"></i> Edit Formasi
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200 max-w-2xl">
    <form method="POST" action="{{ route('admin.formasi.update', $formasi) }}">
        @csrf
        @method('PUT')

        <div class="mb-6">
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Formasi <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $formasi->nama) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Cleaning Service" required>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.formasi.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
