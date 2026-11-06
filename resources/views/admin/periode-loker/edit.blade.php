@extends('layouts.admin')

@section('title', 'Edit Periode Loker')
@section('header')
<i class="ri-edit-line"></i> Edit Periode Loker
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200 max-w-2xl">
    <form method="POST" action="{{ route('admin.periode-loker.update', $periodeLoker) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Periode <span class="text-red-500">*</span></label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $periodeLoker->nama) }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Periode 1" required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $periodeLoker->tanggal_mulai ? $periodeLoker->tanggal_mulai->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <div>
                <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $periodeLoker->tanggal_selesai ? $periodeLoker->tanggal_selesai->format('Y-m-d') : '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
        </div>

        <div class="mb-4">
            <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-1">Gambar</label>

            @if($periodeLoker->gambar)
                <div class="mb-2 flex items-start gap-3">
                    <img src="{{ asset('storage/' . $periodeLoker->gambar) }}" alt="Gambar {{ $periodeLoker->nama }}" class="w-32 h-20 object-cover rounded-lg border border-gray-200">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mt-1">
                        <input type="checkbox" name="hapus_gambar" value="1" {{ old('hapus_gambar') ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        Hapus gambar saat ini
                    </label>
                </div>
            @endif

            <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/webp" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <p class="text-xs text-gray-400 mt-1">Format JPG, PNG, atau WEBP. Maksimal 2 MB. Mengunggah gambar baru akan mengganti gambar lama.</p>
            @error('gambar')
                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        @include('admin.periode-loker._formasi-picker', [
            'formasis' => $formasis,
            'selectedIds' => session()->hasOldInput()
                ? old('formasi_ids', [])
                : $periodeLoker->formasis->pluck('id')->all(),
        ])

        @php
            $syaratRows = session()->hasOldInput()
                ? collect(old('syarats', []))->map(fn ($s) => [
                    'id' => isset($s['id']) ? (int) $s['id'] : null,
                    'nama' => $s['nama'] ?? '',
                    'wajib' => !empty($s['wajib']),
                ])->all()
                : $periodeLoker->syarats->map(fn ($s) => [
                    'id' => $s->id,
                    'nama' => $s->nama,
                    'wajib' => $s->wajib,
                ])->all();
        @endphp
        @include('admin.periode-loker._syarat-builder', ['syaratRows' => $syaratRows])

        <div class="mb-6">
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                <option value="aktif" {{ old('status', $periodeLoker->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ old('status', $periodeLoker->status) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
            </select>
            <p class="text-xs text-gray-400 mt-1">Hanya periode dengan status "Aktif" dan dalam rentang tanggal yang akan menerima lamaran dari halaman publik.</p>
        </div>

        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.periode-loker.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
