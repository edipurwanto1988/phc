@extends('layouts.public')

@section('title', $halaman->judul . ' - PHC Pekanbaru')

@section('content')
<div class="page-hero">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-10 md:pt-14 md:pb-14">
        <!-- Breadcrumb -->
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold truncate max-w-[14rem] sm:max-w-none">{{ $halaman->judul }}</span>
        </nav>

        <!-- Judul -->
        <h1 class="max-w-3xl text-3xl md:text-4xl lg:text-[2.75rem] font-bold text-text-primary leading-[1.15] tracking-tight">
            {{ $halaman->judul }}
        </h1>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 sm:px-6 py-10 md:py-14">
    <!-- Banner Laman -->
    @if($halaman->featured_image)
    <div class="rounded-3xl overflow-hidden ring-1 ring-border/70 shadow-[0_24px_50px_-24px_rgba(27,69,111,0.35)] mb-8">
        <img src="{{ asset('storage/' . $halaman->featured_image) }}" alt="{{ $halaman->judul }}" class="w-full h-64 md:h-96 object-cover">
    </div>
    @endif

    <article class="card-soft p-6 md:p-10">
        <!-- Isi Konten Laman -->
        <div class="prose prose-slate max-w-none prose-headings:tracking-tight prose-a:text-secondary prose-img:rounded-2xl text-[15px] leading-relaxed">
            {!! $halaman->isi !!}
        </div>
    </article>
</div>
@endsection
