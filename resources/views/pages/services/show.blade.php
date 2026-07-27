@extends('layouts.public')

@section('title', $service->nama . ' - PHC Pekanbaru')
@section('meta_description', $service->deskripsi_singkat ?? $service->deskripsi)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-6 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <a href="/layanan" class="hover:text-primary transition-colors">Layanan</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold truncate">{{ $service->nama }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Service Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-6 md:p-8 border border-border rounded-xl shadow-sm space-y-6">
                <!-- Header -->
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                        <i class="{{ $service->category->icon ?? 'ri-sparkling-line' }} text-2xl"></i>
                    </div>
                    <div>
                        <span class="text-xs bg-slate-100 text-slate-650 px-2 py-0.5 rounded font-bold uppercase">{{ $service->category->nama }}</span>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-text-primary leading-tight mt-1">{{ $service->nama }}</h1>
                    </div>
                </div>

                <!-- Service Image -->
                @if($service->gambar)
                <div class="w-full h-80 rounded-xl overflow-hidden border border-border">
                    <img src="{{ asset('storage/' . $service->gambar) }}" alt="{{ $service->nama }}" class="w-full h-full object-cover">
                </div>
                @endif

                <!-- Pricing Card Row -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-150">
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Harga Layanan</span>
                        <span class="text-base font-extrabold text-blue-600">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                        <span class="text-xs text-gray-550">/ {{ $service->satuan }}</span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Estimasi Durasi</span>
                        <span class="text-sm font-bold text-gray-800 flex items-center gap-1 mt-0.5">
                            <i class="ri-time-line"></i> {{ $service->durasi_estimasi ?? '60' }} menit
                        </span>
                    </div>
                    <div>
                        <span class="text-[10px] text-gray-400 font-bold uppercase block">Garansi Kepuasan</span>
                        <span class="text-sm font-bold text-green-600 flex items-center gap-1 mt-0.5">
                            <i class="ri-shield-check-line"></i> Tersedia
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-1.5">Deskripsi Layanan</h3>
                    <p class="text-sm text-gray-650 leading-relaxed">{{ $service->deskripsi }}</p>
                </div>
            </div>
        </div>

        <!-- Sidebar (1/3 width) -->
        <div class="space-y-6">
            <!-- Order Widget -->
            <div class="card p-6 bg-blue-55 border border-blue-200 rounded-xl shadow-sm text-center">
                <h4 class="text-sm font-bold text-blue-800 mb-2">Pesan Layanan Sekarang</h4>
                <p class="text-xs text-blue-900/80 leading-relaxed mb-6">
                    Mulai pembersihan rumah atau kantor Anda hari ini bersama tim profesional kami.
                </p>
                
                @php
                    $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
                    $text = urlencode("Halo PHC, saya ingin memesan layanan *" . $service->nama . "*.");
                @endphp
                
                <a href="https://wa.me/{{ $whatsapp }}?text={{ $text }}" target="_blank" class="w-full btn bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2.5 rounded-lg flex items-center justify-center gap-1.5 shadow-sm transition-all mb-3">
                    Hubungi via WhatsApp <i class="ri-whatsapp-line text-sm"></i>
                </a>
                
                <a href="tel:{{ \App\Models\Setting::get('phone', '0761-12345') }}" class="w-full btn border border-gray-300 hover:bg-white text-gray-700 text-xs font-bold py-2.5 rounded-lg flex items-center justify-center gap-1.5 transition-all bg-white">
                    Hubungi via Telepon <i class="ri-phone-line text-sm"></i>
                </a>
            </div>

            <!-- Related Services -->
            <div class="card p-6 bg-white border border-border rounded-xl">
                <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-2.5 mb-4 flex items-center gap-1.5">
                    <i class="ri-sparkling-line text-blue-600"></i> Jasa Terkait Lainnya
                </h3>
                <div class="space-y-4">
                    @forelse($relatedServices as $rService)
                    <div class="flex gap-3 overflow-hidden items-center">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                            <i class="{{ $rService->category->icon ?? 'ri-sparkling-line' }} text-lg"></i>
                        </div>
                        <div class="overflow-hidden">
                            <a href="/layanan/{{ $rService->slug }}" class="text-sm font-semibold text-text-primary hover:text-primary leading-snug line-clamp-1 block">{{ $rService->nama }}</a>
                            <span class="text-xs font-bold text-blue-600 block mt-0.5">Rp {{ number_format($rService->harga, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-xs text-gray-400 font-medium py-3 text-center">Tidak ada jasa terkait lainnya.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
