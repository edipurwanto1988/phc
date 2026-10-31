@extends('layouts.public')

@section('title', 'Layanan Kami - PHC Pekanbaru')
@section('meta_description', 'Daftar lengkap layanan kebersihan Pekanbaru Home Cleaning. Dari general cleaning, deep cleaning, cuci AC, cuci sofa, poles lantai hingga fumigasi.')

@section('content')
@php
    $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
@endphp

<div class="page-hero">
    <div class="absolute inset-0 bg-dots mask-fade-b pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-12 md:pt-14 md:pb-16">
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold">Layanan</span>
        </nav>
        <div class="max-w-2xl">
            <span class="eyebrow">Layanan Kami</span>
            <h1 class="section-title">Daftar Lengkap Layanan Jasa</h1>
            <p class="section-lead">Pilih layanan kebersihan profesional terbaik yang sesuai dengan kebutuhan rumah, apartemen, kos-kosan, atau kantor Anda.</p>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 md:py-20">
    <div class="space-y-16 md:space-y-20">
        @foreach($categories as $category)
        <div data-reveal>
            <!-- Category Title & Header -->
            <div class="flex items-center gap-4 mb-7">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-primary flex items-center justify-center shrink-0">
                    <i class="{{ $category->icon ?? 'ri-sparkling-line' }} text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl md:text-2xl font-bold text-text-primary tracking-tight">{{ $category->nama }}</h2>
                    <p class="text-sm text-text-secondary mt-0.5">{{ $category->deskripsi }}</p>
                </div>
            </div>

            <!-- Services Grid inside Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($category->services as $service)
                <div class="card-soft lift-card overflow-hidden flex flex-col justify-between group">
                    <a href="/layanan/{{ $service->slug }}" class="block">
                        @if($service->gambar)
                        <div class="aspect-[16/10] overflow-hidden img-zoom border-b border-border/60 bg-surface">
                            <img src="{{ asset('storage/' . $service->gambar) }}" alt="{{ $service->nama }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="p-6 pb-0">
                            <h3 class="text-lg font-semibold text-text-primary group-hover:text-primary transition-colors leading-snug">{{ $service->nama }}</h3>
                            <p class="mt-2 text-sm text-text-secondary leading-relaxed">{{ \Illuminate\Support\Str::words(strip_tags($service->deskripsi_singkat ?? $service->deskripsi), 50, '...') }}</p>
                        </div>
                    </a>
                    <div class="p-6 pt-5 mt-5 border-t border-border/60 flex items-center justify-between gap-3">
                        <div class="leading-tight">
                            <span class="text-xs text-text-secondary block">Mulai dari</span>
                            <span class="text-lg font-bold text-primary">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                            <span class="text-xs text-text-secondary">/ {{ $service->satuan }}</span>
                        </div>
                        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20tertarik%20dengan%20layanan%20*{{ $service->nama }}*" target="_blank" class="btn-pill btn-pill-primary !px-4 !py-2 text-xs shrink-0">
                            Pesan <i class="ri-whatsapp-line text-base"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-6 text-sm text-text-secondary font-medium">Belum ada layanan tersedia pada kategori ini.</div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
