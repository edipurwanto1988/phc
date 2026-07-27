@extends('layouts.admin')

@section('title', 'Tulis Artikel Baru')
@section('header')
<i class="ri-add-circle-line"></i> Tulis Artikel Baru
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left form fields (2/3 width) -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Judul -->
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-gray-700 mb-1">Judul Artikel</label>
                        <input type="text" name="judul" id="judul" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 5 Tips Ampuh Membersihkan Noda Sofa" required oninput="generateSlug()">
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-semibold text-gray-700 mb-1">Slug URL</label>
                        <div class="flex gap-2">
                            <input type="text" name="slug" id="slug" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" required placeholder="auto-generate-dari-judul">
                            <button type="button" onclick="regenerateSlug()" class="px-3 py-2 text-sm bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 shrink-0" title="Regenerate dari Judul">
                                <i class="ri-refresh-line"></i>
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Auto-generate dari judul, bisa diedit manual.</p>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-semibold text-gray-700 mb-1">Kutipan Ringkas (Excerpt)</label>
                        <textarea name="excerpt" id="excerpt" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Deskripsi singkat artikel untuk cuplikan halaman utama..."></textarea>
                    </div>

                    <!-- Konten / TinyMCE -->
                    <div>
                        <label for="tinymce" class="block text-sm font-semibold text-gray-700 mb-1">Isi Konten Artikel</label>
                        <textarea name="konten" id="tinymce" rows="14" data-rich-text="true" required></textarea>
                    </div>
                </div>

                <!-- Right sidebar configuration (1/3 width) -->
                <div class="space-y-6">
                    <!-- Status & Image card -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-4">
                        <h4 class="text-xs font-bold text-blue-600 uppercase border-b border-gray-150 pb-1.5">Penerbitan</h4>
                        
                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status Rilis</label>
                            <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs bg-white" required>
                                <option value="draft" selected>Draft (Simpan dulu)</option>
                                <option value="published">Published (Terbit langsung)</option>
                            </select>
                        </div>

                        <!-- Gambar utama -->
                        <div>
                            <label for="gambar_utama" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Gambar Utama</label>
                            <input type="file" name="gambar_utama" id="gambar_utama" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-[10px] text-gray-400 mt-1">Maks. 2MB (jpg/png).</p>
                        </div>
                    </div>

                    <!-- SEO metadata card -->
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl space-y-4">
                        <h4 class="text-xs font-bold text-blue-600 uppercase border-b border-gray-150 pb-1.5">SEO Meta Tags</h4>
                        
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Meta Title (SEO)</label>
                            <input type="text" name="meta_title" id="meta_title" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Judul pencarian Google...">
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-xs font-semibold text-gray-500 uppercase mb-1">Meta Description (SEO)</label>
                            <textarea name="meta_description" id="meta_description" rows="3" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-xs" placeholder="Deskripsi pencarian Google..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-3 pt-6 border-t border-gray-100 mt-6">
                <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                    Simpan Artikel
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
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
        document.getElementById('slug').value = slugify(document.getElementById('judul').value);
    }
}
function regenerateSlug() {
    slugManuallyEdited = false;
    generateSlug();
}
document.getElementById('slug').addEventListener('input', function () {
    slugManuallyEdited = true;
});

// ---- Form submit: sync TinyMCE before POST ----
document.getElementById('post-form').addEventListener('submit', function () {
    if (window.tinymce) {
        tinymce.triggerSave();
    }
});

// ---- TinyMCE v8 init ----
if (typeof tinymce !== 'undefined') {
    tinymce.init({
        selector: '#tinymce',
        license_key: 'gpl',
        base_url: '/tinymce',
        suffix: '.min',
        height: 480,
        menubar: true,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table wordcount',
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright | bullist numlist | link image table | code preview fullscreen',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        content_style: 'body { font-family: Inter, sans-serif; font-size: 14px; line-height: 1.7; }'
    });
}
</script>
@endsection
