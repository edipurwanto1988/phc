@extends('layouts.public')

@section('title', $service->nama . ' - PHC Pekanbaru')
@section('meta_description', $service->deskripsi_singkat ?? $service->deskripsi)

@section('content')
@php
    $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
    $phone = \App\Models\Setting::get('phone', '0761-12345');
    $text = urlencode("Halo PHC, saya ingin memesan layanan *" . $service->nama . "*.");
@endphp

<div class="page-hero">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-10 md:pt-14 md:pb-14">
        <!-- Breadcrumb -->
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <a href="/layanan">Layanan</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold truncate max-w-[14rem] sm:max-w-none">{{ $service->nama }}</span>
        </nav>

        <div class="flex items-start gap-4">
            <div class="w-14 h-14 bg-blue-50 text-primary rounded-2xl flex items-center justify-center shrink-0">
                <i class="{{ $service->category->icon ?? 'ri-sparkling-line' }} text-2xl"></i>
            </div>
            <div class="min-w-0">
                <span class="pill text-primary border-primary/20 bg-primary/5 uppercase tracking-wider !text-[11px]">{{ $service->category->nama }}</span>
                <h1 class="text-3xl md:text-4xl font-bold text-text-primary leading-tight tracking-tight mt-3">{{ $service->nama }}</h1>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 md:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Service Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Service Image -->
            @if($service->gambar)
            <div class="rounded-3xl overflow-hidden ring-1 ring-border/70 shadow-[0_24px_50px_-24px_rgba(27,69,111,0.35)]">
                <img src="{{ asset('storage/' . $service->gambar) }}" alt="{{ $service->nama }}" class="w-full h-80 object-cover">
            </div>
            @endif

            <!-- Pricing Card Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="card-soft p-5">
                    <span class="text-[11px] text-text-secondary font-semibold uppercase tracking-wider block mb-1">Harga Layanan</span>
                    <span class="text-xl font-bold text-primary">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                    <span class="text-xs text-text-secondary">/ {{ $service->satuan }}</span>
                </div>
                <div class="card-soft p-5">
                    <span class="text-[11px] text-text-secondary font-semibold uppercase tracking-wider block mb-1">Estimasi Durasi</span>
                    <span class="text-base font-bold text-text-primary flex items-center gap-1.5">
                        <i class="ri-time-line text-secondary"></i> {{ $service->durasi_estimasi ?? '60' }} menit
                    </span>
                </div>
                <div class="card-soft p-5">
                    <span class="text-[11px] text-text-secondary font-semibold uppercase tracking-wider block mb-1">Garansi Kepuasan</span>
                    <span class="text-base font-bold text-green-600 flex items-center gap-1.5">
                        <i class="ri-shield-check-line"></i> Tersedia
                    </span>
                </div>
            </div>

            <!-- Description -->
            <div class="card-soft p-6 md:p-8">
                <span class="eyebrow">Deskripsi Layanan</span>
                <div class="mt-4 text-[15px] text-text-primary/85 leading-relaxed wysiwyg-content">{!! $service->deskripsi !!}</div>
            </div>
        </div>

        <!-- Sidebar (1/3 width) -->
        <div class="space-y-6 lg:sticky lg:top-24 self-start">
            <!-- Order Widget -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-primary to-secondary p-7 text-white shadow-[0_24px_50px_-24px_rgba(27,69,111,0.6)]">
                <div class="absolute inset-0 bg-dots-light pointer-events-none"></div>
                <div class="relative">
                    <span class="text-[11px] font-semibold uppercase tracking-[0.2em] text-accent">Pesan Sekarang</span>
                    <h4 class="text-xl font-bold mt-2">Siap bersihkan hari ini?</h4>
                    <p class="text-sm text-blue-100 leading-relaxed mt-2 mb-6">
                        Mulai pembersihan rumah atau kantor Anda hari ini bersama tim profesional kami.
                    </p>

                    <a href="https://wa.me/{{ $whatsapp }}?text={{ $text }}" target="_blank" class="btn-pill bg-white text-primary hover:bg-blue-50 w-full mb-3">
                        <i class="ri-whatsapp-line text-lg"></i> Hubungi via WhatsApp
                    </a>

                    <a href="tel:{{ $phone }}" class="btn-pill border border-white/40 text-white hover:bg-white/10 w-full">
                        <i class="ri-phone-line text-lg"></i> Hubungi via Telepon
                    </a>
                </div>
            </div>

            <!-- Related Services -->
            <div class="card-soft p-6">
                <h3 class="text-sm font-bold text-text-primary flex items-center gap-2 mb-5">
                    <i class="ri-sparkling-line text-secondary"></i> Jasa Terkait Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse($relatedServices as $rService)
                    <a href="/layanan/{{ $rService->slug }}" class="flex gap-3 items-center group">
                        <div class="w-12 h-12 bg-blue-50 text-primary rounded-2xl flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
                            <i class="{{ $rService->category->icon ?? 'ri-sparkling-line' }} text-lg"></i>
                        </div>
                        <div class="min-w-0">
                            <span class="text-sm font-semibold text-text-primary group-hover:text-primary leading-snug line-clamp-1 block transition-colors">{{ $rService->nama }}</span>
                            <span class="text-xs font-bold text-secondary block mt-0.5">Rp {{ number_format($rService->harga, 0, ',', '.') }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-xs text-text-secondary font-medium py-3 text-center">Tidak ada jasa terkait lainnya.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
