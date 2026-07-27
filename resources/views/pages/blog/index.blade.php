@extends('layouts.public')

@section('title', 'Blog & Tips Kebersihan - PHC Pekanbaru')
@section('meta_description', 'Temukan artikel, panduan, dan tips praktis seputar menjaga kebersihan rumah, kasur, AC, dan ruangan Anda dari tim ahli PHC Pekanbaru.')

@section('content')
<div class="bg-surface py-12 border-b border-border">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-text-primary tracking-tight">Tips & Info Kebersihan</h1>
        <p class="mt-3 text-text-secondary max-w-xl mx-auto text-sm leading-relaxed">Berbagai tips praktis untuk merawat kebersihan tempat tinggal Anda agar selalu sehat dan nyaman ditinggali bersama keluarga.</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($posts as $post)
        <article class="card-hover bg-white rounded-lg border border-border overflow-hidden flex flex-col justify-between shadow-sm">
            <div>
                <div class="aspect-[16/10] bg-gradient-to-br from-blue-50 to-blue-100 flex items-center justify-center overflow-hidden border-b border-border">
                    @if($post->gambar_utama)
                        <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-full h-full object-cover">
                    @else
                        <i class="ri-article-line text-blue-600 text-5xl opacity-40"></i>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs text-text-secondary">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                        <span class="text-xs text-border">•</span>
                        <span class="text-xs text-primary font-bold">Kebersihan</span>
                    </div>
                    <h3 class="text-base font-bold text-text-primary leading-snug line-clamp-2">{{ $post->judul }}</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed line-clamp-3">{{ $post->excerpt }}</p>
                </div>
            </div>
            <div class="px-5 pb-5 pt-2">
                <a href="/blog/{{ $post->slug }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                    Baca selengkapnya
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-12 text-sm text-gray-500 font-medium">Belum ada artikel yang dipublikasikan saat ini.</div>
        @endforelse
    </div>

    @if($posts->hasPages())
    <div class="mt-8 pt-6 border-t border-gray-100">
        {{ $posts->links() }}
    </div>
    @endif
</div>
@endsection
