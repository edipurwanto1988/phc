@extends('layouts.public')

@section('title', $post->meta_title ?? $post->judul . ' - PHC Pekanbaru')
@section('meta_description', $post->meta_description ?? $post->excerpt)

@section('content')
<div class="page-hero">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-10 md:pt-14 md:pb-14">
        <!-- Breadcrumb -->
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/blog">Blog</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold truncate max-w-[14rem] sm:max-w-none">{{ $post->judul }}</span>
        </nav>

        <div class="max-w-3xl">
            <!-- Meta information -->
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs text-text-secondary mb-4">
                <span class="pill text-primary border-primary/20 bg-primary/5">Tips & Edukasi</span>
                <span class="flex items-center gap-1"><i class="ri-user-3-line"></i> {{ $post->author->name ?? 'Admin' }}</span>
                <span class="text-border">•</span>
                <span class="flex items-center gap-1"><i class="ri-calendar-line"></i> {{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}</span>
            </div>

            <!-- Judul -->
            <h1 class="text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-text-primary leading-[1.15] tracking-tight">
                {{ $post->judul }}
            </h1>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 md:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Article Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Featured Image -->
            @if($post->gambar_utama)
            <div class="rounded-3xl overflow-hidden ring-1 ring-border/70 shadow-[0_24px_50px_-24px_rgba(27,69,111,0.35)]">
                <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-full h-80 md:h-96 object-cover">
            </div>
            @endif

            <article class="card-soft p-6 md:p-10">
                <!-- Excerpt -->
                @if($post->excerpt)
                <p class="text-base md:text-lg text-text-primary/80 font-medium border-l-4 border-accent pl-5 mb-8 leading-relaxed">
                    {{ $post->excerpt }}
                </p>
                @endif

                <!-- Body Content -->
                <div class="prose prose-slate max-w-none prose-headings:tracking-tight prose-a:text-secondary prose-img:rounded-2xl text-[15px] leading-relaxed">
                    {!! $post->konten !!}
                </div>
            </article>
        </div>

        <!-- Sidebar (1/3 width) -->
        <div class="space-y-6 lg:sticky lg:top-24 self-start">
            <!-- Recent articles -->
            <div class="card-soft p-6">
                <h3 class="text-sm font-bold text-text-primary flex items-center gap-2 mb-5">
                    <i class="ri-article-line text-secondary"></i> Artikel Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse($recentPosts as $rPost)
                    <a href="/blog/{{ $rPost->slug }}" class="flex gap-3 group">
                        @if($rPost->gambar_utama_thumbnail)
                        <img src="{{ asset('storage/' . $rPost->gambar_utama_thumbnail) }}" alt="{{ $rPost->judul }}" class="w-16 h-16 object-cover rounded-xl border border-border/70 shrink-0">
                        @elseif($rPost->gambar_utama)
                        <img src="{{ asset('storage/' . $rPost->gambar_utama) }}" alt="{{ $rPost->judul }}" class="w-16 h-16 object-cover rounded-xl border border-border/70 shrink-0">
                        @else
                        <div class="w-16 h-16 bg-surface border border-border/70 rounded-xl flex items-center justify-center text-text-secondary/60 shrink-0">
                            <i class="ri-article-line text-xl"></i>
                        </div>
                        @endif
                        <div class="min-w-0">
                            <span class="text-sm font-semibold text-text-primary group-hover:text-primary leading-snug line-clamp-2 block transition-colors">{{ $rPost->judul }}</span>
                            <span class="text-xs text-text-secondary block mt-1">{{ $rPost->published_at ? $rPost->published_at->format('d M Y') : $rPost->created_at->format('d M Y') }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-xs text-text-secondary font-medium py-3">Belum ada artikel lainnya.</div>
                    @endforelse
                </div>
            </div>

            <!-- Booking call-out widget -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-primary to-secondary p-7 text-white shadow-[0_24px_50px_-24px_rgba(27,69,111,0.6)]">
                <div class="absolute inset-0 bg-dots-light pointer-events-none"></div>
                <div class="relative">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-accent">Butuh Jasa Cleaning?</span>
                    <h4 class="text-xl font-bold mt-2">Rumah bersih tanpa repot</h4>
                    <p class="text-sm text-blue-100 leading-relaxed mt-2 mb-5">
                        Jadwalkan layanan kebersihan profesional bersama kami. Bersih, wangi, dan bergaransi!
                    </p>
                    <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp', '6281234567890') }}?text=Halo%20PHC,%20saya%20ingin%20memesan%20jasa%20cleaning" target="_blank" class="btn-pill bg-white text-primary hover:bg-blue-50 w-full">
                        Pesan Sekarang <i class="ri-whatsapp-line text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
