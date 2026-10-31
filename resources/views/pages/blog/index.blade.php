@extends('layouts.public')

@section('title', 'Blog & Tips Kebersihan - PHC Pekanbaru')
@section('meta_description', 'Temukan artikel, panduan, dan tips praktis seputar menjaga kebersihan rumah, kasur, AC, dan ruangan Anda dari tim ahli PHC Pekanbaru.')

@section('content')
<div class="page-hero">
    <div class="absolute inset-0 bg-dots mask-fade-b pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-12 md:pt-14 md:pb-16">
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold">Blog</span>
        </nav>
        <div class="max-w-2xl">
            <span class="eyebrow">Blog</span>
            <h1 class="section-title">Tips & Info Kebersihan</h1>
            <p class="section-lead">Berbagai tips praktis untuk merawat kebersihan tempat tinggal Anda agar selalu sehat dan nyaman ditinggali bersama keluarga.</p>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 md:py-20">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        @forelse($posts as $post)
        <article class="card-soft lift-card overflow-hidden flex flex-col justify-between group" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
            <div>
                <a href="/blog/{{ $post->slug }}" class="block aspect-[16/10] bg-gradient-to-br from-blue-50 to-surface flex items-center justify-center overflow-hidden img-zoom">
                    @if($post->gambar_utama)
                        <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-full h-full object-cover">
                    @else
                        <i class="ri-article-line text-primary text-5xl opacity-30"></i>
                    @endif
                </a>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="pill !py-0.5 text-primary border-primary/20 bg-primary/5">Kebersihan</span>
                        <span class="text-xs text-text-secondary">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-text-primary leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                        <a href="/blog/{{ $post->slug }}">{{ $post->judul }}</a>
                    </h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                </div>
            </div>
            <div class="px-6 pb-6">
                <a href="/blog/{{ $post->slug }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                    Baca selengkapnya
                    <i class="ri-arrow-right-line transition-transform group-hover:translate-x-0.5"></i>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-12 text-sm text-text-secondary font-medium">Belum ada artikel yang dipublikasikan saat ini.</div>
        @endforelse
    </div>

    @if($posts->hasPages())
    <div class="mt-10 pt-6 border-t border-border/60">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
