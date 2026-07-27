@extends('layouts.admin')

@section('title', 'Tambah Jasa Baru')
@section('header')
<i class="ri-add-circle-line"></i> Tambah Jasa Baru
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Nama Jasa -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Jasa / Layanan</label>
                    <input type="text" name="nama" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: General Cleaning Rumah" required oninput="generateSlug()">
                </div>
                <!-- Kategori Jasa -->
                <div>
                    <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-1">Kategori Jasa</label>
                    <select name="kategori_id" id="kategori_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Slug URL -->
            <div class="mb-4">
                <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">Slug URL</label>
                <div class="flex gap-2">
                    <input type="text" name="slug" id="slug" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required placeholder="auto-generate-dari-nama">
                    <button type="button" onclick="regenerateSlug()" class="px-3 py-2 text-sm bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 shrink-0" title="Regenerate dari Nama">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
                <p class="text-[11px] text-gray-500 mt-1">Auto-generate dari nama jasa, bisa diedit manual.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <!-- Harga -->
                <div>
                    <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">Harga Dasar (Rp)</label>
                    <input type="number" name="harga" id="harga" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 350000" min="0" required>
                </div>
                <!-- Satuan -->
                <div>
                    <label for="satuan" class="block text-sm font-semibold text-gray-700 mb-1">Satuan Harga</label>
                    <input type="text" name="satuan" id="satuan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: sesi, m², unit" required>
                </div>
                <!-- Estimasi Durasi -->
                <div>
                    <label for="durasi_estimasi" class="block text-sm font-semibold text-gray-700 mb-1">Estimasi Durasi (Menit)</label>
                    <input type="number" name="durasi_estimasi" id="durasi_estimasi" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 120" min="0">
                </div>
            </div>

            <!-- Deskripsi Singkat -->
            <div class="mb-4">
                <label for="deskripsi_singkat" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Singkat (Card / Preview)</label>
                <input type="text" name="deskripsi_singkat" id="deskripsi_singkat" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Deskripsi singkat yang tampil di card layanan (maks. 500 karakter)" maxlength="500">
            </div>

            <!-- Deskripsi Lengkap -->
            <div class="mb-4">
                <label for="tinymce" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi Detail</label>
                <textarea name="deskripsi" id="tinymce" rows="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Jelaskan secara mendetail apa saja yang dikerjakan pada layanan jasa ini..."></textarea>
            </div>

            <!-- Gambar Layanan -->
            <div class="mb-4">
                <label for="gambar" class="block text-sm font-semibold text-gray-700 mb-1">Gambar Layanan</label>
                <input type="file" name="gambar" id="gambar" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" accept="image/*">
                <p class="text-xs text-gray-500 mt-1">Ukuran maksimal file: 2MB (format: JPG, JPEG, PNG).</p>
            </div>

            <!-- Urutan & Active -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="urutan" class="block text-sm font-semibold text-gray-700 mb-1">Urutan Tampil</label>
                    <input type="number" name="urutan" id="urutan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="0" min="0">
                </div>
                <div class="flex items-center pl-2 pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        <span class="ml-3 text-sm font-semibold text-gray-700">Aktif</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Jasa
                </button>
                <a href="{{ route('admin.services.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script src="/tinymce/tinymce.min.js"></script>
<script>
// ---- Slug generator ----
function slugify(text) {
    return text.toString().toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-')
        .replace(/^-+/, '')
        .replace(/-+$/, '');
}
let slugManuallyEdited = false;
function generateSlug() {
    if (!slugManuallyEdited) {
        document.getElementById('slug').value = slugify(document.getElementById('nama').value);
    }
}
function regenerateSlug() {
    slugManuallyEdited = false;
    generateSlug();
}
document.getElementById('slug').addEventListener('input', function () {
    slugManuallyEdited = true;
});

if (typeof tinymce !== 'undefined') {
    tinymce.init({
        selector: '#tinymce',
        license_key: 'gpl',
        base_url: '/tinymce',
        suffix: '.min',
        height: 400,
        menubar: true,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.7; }'
    });
}

// Sync TinyMCE on form submit
document.querySelector('form').addEventListener('submit', function () {
    if (window.tinymce) {
        tinymce.triggerSave();
    }
});
</script>
@endsection
