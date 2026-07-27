@extends('layouts.public')

@section('title', 'Hubungi Kami - PHC Pekanbaru')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12">
    <!-- Breadcrumb -->
    <nav class="flex text-xs text-gray-500 mb-8 font-medium">
        <a href="/" class="hover:text-primary transition-colors">Home</a>
        <span class="mx-2 text-gray-300">/</span>
        <span class="text-gray-700 font-bold truncate">Kontak</span>
    </nav>

    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-extrabold text-text-primary tracking-tight">Hubungi Kami</h1>
        <p class="mt-3 text-text-secondary max-w-lg mx-auto text-sm leading-relaxed">
            Kami siap melayani kebutuhan kebersihan rumah, kantor, dan fasilitas Anda di wilayah Pekanbaru. Hubungi kami melalui informasi di bawah ini.
        </p>
    </div>

    <!-- Main Content Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Info Kontak (5 Columns) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 md:p-8 border border-border rounded-2xl shadow-sm space-y-6">
                <h3 class="text-lg font-bold text-text-primary border-b border-gray-100 pb-3">Informasi Kontak</h3>

                <!-- Alamat -->
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-primary shrink-0">
                        <i class="ri-map-pin-2-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-wider">Alamat Kantor</h4>
                        <p class="text-sm font-semibold text-text-primary mt-1 leading-relaxed">
                            {{ \App\Models\Setting::get('address', 'Jl. HR. Soebrantas, Pekanbaru, Riau') }}
                        </p>
                    </div>
                </div>

                <!-- No Telepon / HP -->
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-600 shrink-0">
                        <i class="ri-phone-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-wider">Nomor Telepon</h4>
                        <p class="text-sm font-semibold text-text-primary mt-1">
                            {{ \App\Models\Setting::get('phone', '0761-12345') }}
                        </p>
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                        <i class="ri-whatsapp-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-wider">WhatsApp</h4>
                        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp', '6281234567890') }}" target="_blank" class="text-sm font-bold text-primary hover:underline mt-1 block">
                            +{{ \App\Models\Setting::get('whatsapp', '6281234567890') }}
                        </a>
                    </div>
                </div>

                <!-- Email -->
                <div class="flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500 shrink-0">
                        <i class="ri-mail-line text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-text-secondary uppercase tracking-wider">Email Bisnis</h4>
                        <a href="mailto:{{ \App\Models\Setting::get('email', 'info@phc-pekanbaru.com') }}" class="text-sm font-semibold text-text-primary hover:text-primary transition-colors mt-1 block">
                            {{ \App\Models\Setting::get('email', 'info@phc-pekanbaru.com') }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Jam Operasional Widget -->
            <div class="bg-blue-50 p-6 border border-blue-100 rounded-2xl">
                <h4 class="text-sm font-bold text-blue-800 flex items-center gap-1.5 mb-2">
                    <i class="ri-time-line"></i> Jam Operasional
                </h4>
                <p class="text-xs text-blue-900/80 leading-relaxed">
                    Senin – Minggu: 07:00 – 18:00 WIB<br>
                    Hari Libur Nasional tetap buka (dengan reservasi H-1).
                </p>
            </div>
        </div>

        <!-- Google Maps Embed (7 Columns) -->
        <div class="lg:col-span-7">
            <div class="bg-white p-4 border border-border rounded-2xl shadow-sm h-full flex flex-col justify-between">
                <div class="w-full rounded-xl overflow-hidden border border-gray-100 flex-1 min-h-[350px] lg:min-h-[450px]">
                    {!! \App\Models\Setting::get('google_maps_embed', '<div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400 font-semibold">Map tidak terkonfigurasi</div>') !!}
                </div>
                <div class="text-[11px] text-gray-500 mt-3 text-center">
                    Gunakan peta interaktif di atas untuk menemukan rute terbaik menuju lokasi basecamp kami.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
