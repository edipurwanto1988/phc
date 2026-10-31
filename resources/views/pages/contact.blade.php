@extends('layouts.public')

@section('title', 'Hubungi Kami - PHC Pekanbaru')

@section('content')
@php
    $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
    $phone = \App\Models\Setting::get('phone', '0761-12345');
    $email = \App\Models\Setting::get('email', 'info@phc-pekanbaru.com');
    $address = \App\Models\Setting::get('address', 'Jl. HR. Soebrantas, Pekanbaru, Riau');
@endphp

<div class="page-hero">
    <div class="absolute inset-0 bg-dots mask-fade-b pointer-events-none"></div>
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-10 pb-12 md:pt-14 md:pb-16">
        <nav class="crumbs mb-6">
            <a href="/">Home</a>
            <i class="ri-arrow-right-s-line"></i>
            <span class="text-text-primary font-semibold">Kontak</span>
        </nav>
        <div class="max-w-2xl">
            <span class="eyebrow">Kontak</span>
            <h1 class="section-title">Hubungi Kami</h1>
            <p class="section-lead">
                Kami siap melayani kebutuhan kebersihan rumah, kantor, dan fasilitas Anda di wilayah Pekanbaru. Hubungi kami melalui informasi di bawah ini.
            </p>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-14 md:py-20">
    <!-- Main Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        <!-- Info Kontak (5 Columns) -->
        <div class="lg:col-span-5 space-y-5">
            <!-- WhatsApp (primary) -->
            <a href="https://wa.me/{{ $whatsapp }}" target="_blank" class="card-soft lift-card p-6 flex items-center gap-4 group" data-reveal>
                <div class="w-12 h-12 rounded-2xl bg-[#25D366]/10 flex items-center justify-center text-[#25D366] shrink-0">
                    <i class="ri-whatsapp-line text-2xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-[11px] font-semibold text-text-secondary uppercase tracking-wider">WhatsApp</h4>
                    <p class="text-base font-bold text-text-primary mt-0.5 group-hover:text-primary transition-colors">+{{ $whatsapp }}</p>
                </div>
                <i class="ri-arrow-right-up-line text-xl text-text-secondary group-hover:text-primary transition-colors"></i>
            </a>

            <!-- No Telepon / HP -->
            <a href="tel:{{ $phone }}" class="card-soft lift-card p-6 flex items-center gap-4 group" data-reveal data-reveal-delay="1">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-secondary shrink-0">
                    <i class="ri-phone-line text-2xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-[11px] font-semibold text-text-secondary uppercase tracking-wider">Nomor Telepon</h4>
                    <p class="text-base font-bold text-text-primary mt-0.5 group-hover:text-primary transition-colors">{{ $phone }}</p>
                </div>
                <i class="ri-arrow-right-up-line text-xl text-text-secondary group-hover:text-primary transition-colors"></i>
            </a>

            <!-- Email -->
            <a href="mailto:{{ $email }}" class="card-soft lift-card p-6 flex items-center gap-4 group" data-reveal data-reveal-delay="2">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center text-accent-dark shrink-0">
                    <i class="ri-mail-line text-2xl"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="text-[11px] font-semibold text-text-secondary uppercase tracking-wider">Email Bisnis</h4>
                    <p class="text-base font-bold text-text-primary mt-0.5 break-all group-hover:text-primary transition-colors">{{ $email }}</p>
                </div>
                <i class="ri-arrow-right-up-line text-xl text-text-secondary group-hover:text-primary transition-colors"></i>
            </a>

            <!-- Alamat -->
            <div class="card-soft p-6 flex items-start gap-4" data-reveal data-reveal-delay="3">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-primary shrink-0">
                    <i class="ri-map-pin-2-line text-2xl"></i>
                </div>
                <div class="min-w-0">
                    <h4 class="text-[11px] font-semibold text-text-secondary uppercase tracking-wider">Alamat Kantor</h4>
                    <p class="text-sm font-semibold text-text-primary mt-1 leading-relaxed">{{ $address }}</p>
                </div>
            </div>

            <!-- Jam Operasional Widget -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary via-primary to-secondary p-6 text-white" data-reveal data-reveal-delay="4">
                <div class="absolute inset-0 bg-dots-light pointer-events-none"></div>
                <div class="relative">
                    <h4 class="text-sm font-bold flex items-center gap-2 mb-2">
                        <i class="ri-time-line text-accent"></i> Jam Operasional
                    </h4>
                    <p class="text-sm text-blue-100 leading-relaxed">
                        Senin – Minggu: 07:00 – 18:00 WIB<br>
                        Hari Libur Nasional tetap buka (dengan reservasi H-1).
                    </p>
                </div>
            </div>
        </div>

        <!-- Google Maps Embed (7 Columns) -->
        <div class="lg:col-span-7" data-reveal>
            <div class="card-soft p-3 h-full flex flex-col">
                <div class="w-full rounded-2xl overflow-hidden flex-1 min-h-[350px] lg:min-h-[520px] [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:min-h-[350px]">
                    {!! \App\Models\Setting::get('google_maps_embed', '<div class="w-full h-full bg-surface flex items-center justify-center text-text-secondary font-semibold">Map tidak terkonfigurasi</div>') !!}
                </div>
                <div class="text-xs text-text-secondary mt-3 mb-1 text-center">
                    Gunakan peta interaktif di atas untuk menemukan rute terbaik menuju lokasi basecamp kami.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
