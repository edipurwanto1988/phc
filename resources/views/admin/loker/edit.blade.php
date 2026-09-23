@extends('layouts.admin')

@section('title', 'Edit Pelamar Loker')
@section('header')
<i class="ri-edit-line"></i> Edit Pelamar Loker
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.loker.update', $loker) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $loker->nama) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $loker->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
        </div>

        <div class="mb-4">
            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
            <textarea name="alamat" id="alamat" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>{{ old('alamat', $loker->alamat) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="periode_id" class="block text-sm font-semibold text-gray-700 mb-1">Periode Loker</label>
                <select name="periode_id" id="periode_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="">-- Tanpa Periode --</option>
                    @foreach($periodes as $periode)
                    <option value="{{ $periode->id }}" {{ old('periode_id', $loker->periode_id) == $periode->id ? 'selected' : '' }}>
                        {{ $periode->nama }} ({{ $periode->status }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $loker->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $loker->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="no_wa" class="block text-sm font-semibold text-gray-700 mb-1">No. WhatsApp <span class="text-red-500">*</span></label>
                <input type="text" name="no_wa" id="no_wa" value="{{ old('no_wa', $loker->no_wa) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
            </div>
            <div>
                <label for="ktp" class="block text-sm font-semibold text-gray-700 mb-1">Upload KTP</label>
                @if($loker->ktp)
                <div class="mb-2">
                    <a href="{{ route('admin.loker.ktp.view', $loker) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-800">
                        <i class="ri-id-card-line"></i> Lihat KTP Saat Ini
                    </a>
                </div>
                @endif
                <input type="file" name="ktp" id="ktp" accept="image/*" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>
        </div>

        <div class="mb-4">
            <label for="pengalaman" class="block text-sm font-semibold text-gray-700 mb-1">Pengalaman</label>
            <textarea name="pengalaman" id="pengalaman" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('pengalaman', $loker->pengalaman) }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="keahlian_khusus" class="block text-sm font-semibold text-gray-700 mb-1">Keahlian Khusus</label>
                <input type="text" name="keahlian_khusus" id="keahlian_khusus" value="{{ old('keahlian_khusus', $loker->keahlian_khusus) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: kaca, poles lantai, dll">
            </div>
            <div>
                <label for="rating" class="block text-sm font-semibold text-gray-700 mb-1">Rating</label>
                <select name="rating" id="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white">
                    <option value="">-- Pilih Rating --</option>
                    <option value="High Potential" {{ old('rating', $loker->rating) == 'High Potential' ? 'selected' : '' }}>High Potential — Potensi sangat tinggi</option>
                    <option value="Potential" {{ old('rating', $loker->rating) == 'Potential' ? 'selected' : '' }}>Potential — Potensi baik</option>
                    <option value="Moderate Potential" {{ old('rating', $loker->rating) == 'Moderate Potential' ? 'selected' : '' }}>Moderate Potential — Potensi sedang</option>
                    <option value="Low Potential" {{ old('rating', $loker->rating) == 'Low Potential' ? 'selected' : '' }}>Low Potential — Potensi rendah</option>
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label for="cerita" class="block text-sm font-semibold text-gray-700 mb-1">Cerita</label>
            <textarea name="cerita" id="cerita" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">{{ old('cerita', $loker->cerita) }}</textarea>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.loker.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
