@extends('layouts.public')

@section('title', $halaman->judul . ' - PHC Pekanbaru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-6 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold truncate">{{ $halaman->judul }}</span>
    </nav>

    <article class="bg-white p-6 md:p-10 border border-border rounded-2xl shadow-sm">
        <!-- Judul -->
        <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-text-primary leading-tight tracking-tight mb-6">
            {{ $halaman->judul }}
        </h1>

        <!-- Banner Laman -->
        @if($halaman->featured_image)
        <div class="w-full h-64 md:h-96 rounded-xl overflow-hidden mb-8 border border-border">
            <img src="{{ asset('storage/' . $halaman->featured_image) }}" alt="{{ $halaman->judul }}" class="w-full h-full object-cover">
        </div>
        @endif

        <!-- Isi Konten Laman -->
        <div class="prose max-w-none text-sm text-gray-700 leading-relaxed space-y-4">
            {!! $halaman->isi !!}
        </div>
    </article>
</div>
@endsection
