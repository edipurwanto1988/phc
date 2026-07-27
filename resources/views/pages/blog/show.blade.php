@extends('layouts.public')

@section('title', $post->meta_title ?? $post->judul . ' - PHC Pekanbaru')
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-6 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <a href="/blog" class="hover:text-primary transition-colors">Blog</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold truncate">{{ $post->judul }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Article Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <article class="bg-white p-6 md:p-8 border border-border rounded-xl shadow-sm">
                <!-- Meta information -->
                <div class="flex items-center gap-3 text-xs text-text-secondary mb-4">
                    <span class="bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-bold">Tips & Edukasi</span>
                    <span>•</span>
                    <span>Diposting oleh: <b>{{ $post->author->name ?? 'Admin' }}</b></span>
                    <span>•</span>
                    <span>{{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}</span>
                </div>

                <!-- Judul -->
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-text-primary leading-tight tracking-tight mb-6">
                    {{ $post->judul }}
                </h1>

                <!-- Featured Image -->
                @if($post->gambar_utama)
                <div class="w-full h-80 rounded-xl overflow-hidden mb-6 border border-border">
                    <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-full h-full object-cover">
                </div>
                @endif

                <!-- Excerpt -->
                @if($post->excerpt)
                <p class="text-base text-gray-650 font-medium italic border-l-4 border-primary pl-4 mb-6 leading-relaxed">
                    "{{ $post->excerpt }}"
                </p>
                @endif

                <!-- Body Content -->
                <div class="prose max-w-none text-sm text-gray-700 leading-relaxed space-y-4">
                    {!! $post->konten !!}
                </div>
            </article>
        </div>

        <!-- Sidebar (1/3 width) -->
        <div class="space-y-6">
            <!-- Recent articles -->
            <div class="card p-6 bg-white border border-border rounded-xl">
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2.5 mb-4 flex items-center gap-1.5">
                    <i class="ri-article-line text-blue-600"></i> Artikel Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse($recentPosts as $rPost)
                    <div class="flex gap-3 overflow-hidden">
                        @if($rPost->gambar_utama_thumbnail)
                        <img src="{{ asset('storage/' . $rPost->gambar_utama_thumbnail) }}" alt="{{ $rPost->judul }}" class="w-14 h-14 object-cover rounded-lg border border-border shrink-0">
                        @elseif($rPost->gambar_utama)
                        <img src="{{ asset('storage/' . $rPost->gambar_utama) }}" alt="{{ $rPost->judul }}" class="w-14 h-14 object-cover rounded-lg border border-border shrink-0">
                        @else
                        <div class="w-14 h-14 bg-gray-150 border border-border rounded-lg flex items-center justify-center text-gray-400 shrink-0">
                            <i class="ri-article-line text-xl"></i>
                        </div>
                        @endif
                        <div class="overflow-hidden">
                            <a href="/blog/{{ $rPost->slug }}" class="text-sm font-semibold text-text-primary hover:text-primary leading-snug line-clamp-2 block">{{ $rPost->judul }}</a>
                            <span class="text-[10px] text-gray-400 block mt-1">{{ $rPost->published_at ? $rPost->published_at->format('d M Y') : $rPost->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 font-medium py-3">Belum ada artikel lainnya.</div>
                    @endforelse
                </div>
            </div>

            <!-- Booking call-out widget -->
            <div class="card p-6 bg-blue-50 border border-blue-100 rounded-xl">
                <h4 class="text-sm font-bold text-blue-800 mb-2">Butuh Jasa Cleaning?</h4>
                <p class="text-xs text-blue-900/80 leading-relaxed mb-4">
                    Jadwalkan layanan kebersihan profesional bersama kami. Bersih, wangi, dan bergaransi!
                </p>
                <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp', '6281234567890') }}?text=Halo%20PHC,%20saya%20ingin%20memesan%20jasa%20cleaning" target="_blank" class="w-full btn bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded-lg flex items-center justify-center gap-1.5 shadow-sm transition-all">
                    Pesan Sekarang <i class="ri-whatsapp-line text-sm"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
