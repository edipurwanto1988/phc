@extends('layouts.public')

@section('title', 'Layanan Kami - PHC Pekanbaru')
@section('meta_description', 'Daftar lengkap layanan kebersihan Pekanbaru Home Cleaning. Dari general cleaning, deep cleaning, cuci AC, cuci sofa, poles lantai hingga fumigasi.')

@section('content')
<div class="bg-surface py-12 border-b border-border">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-extrabold text-text-primary tracking-tight">Daftar Lengkap Layanan Jasa</h1>
        <p class="mt-3 text-text-secondary max-w-xl mx-auto text-sm leading-relaxed">Pilih layanan kebersihan profesional terbaik yang sesuai dengan kebutuhan rumah, apartemen, kos-kosan, atau kantor Anda.</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
    <div class="space-y-16">
        @foreach($categories as $category)
        <div class="space-y-6">
            <!-- Category Title & Header -->
            <div class="flex items-center gap-3 border-b border-gray-100 pb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i class="{{ $category->icon ?? 'ri-sparkling-line' }} text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-850">{{ $category->nama }}</h2>
                    <p class="text-xs text-text-secondary mt-0.5">{{ $category->deskripsi }}</p>
                </div>
            </div>

            <!-- Services Grid inside Category -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($category->services as $service)
                <div class="card-hover bg-white rounded-lg border border-border p-6 flex flex-col justify-between shadow-sm">
                    <a href="/layanan/{{ $service->slug }}" class="block group">
                        @if($service->gambar)
                        <div class="w-full h-40 rounded-lg overflow-hidden mb-4 border border-border">
                            <img src="{{ asset('storage/' . $service->gambar) }}" alt="{{ $service->nama }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <h3 class="text-base font-bold text-text-primary group-hover:text-primary transition-colors">{{ $service->nama }}</h3>
                        <p class="mt-2 text-xs text-text-secondary leading-relaxed leading-normal">{{ \Illuminate\Support\Str::words(strip_tags($service->deskripsi_singkat ?? $service->deskripsi), 50, '...') }}</p>
                    </a>
                    <div class="mt-6 pt-4 border-t border-gray-150 flex items-center justify-between">
                        <div>
                            <span class="text-lg font-bold text-primary">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                            <span class="text-xs text-text-secondary">/ {{ $service->satuan }}</span>
                        </div>
                        <a href="https://wa.me/{{ \App\Models\Setting::get('whatsapp', '6281234567890') }}?text=Halo%20PHC,%20saya%20tertarik%20dengan%20layanan%20*{{ $service->nama }}*" target="_blank" class="inline-flex items-center gap-1 bg-primary hover:bg-primary-dark text-white text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors shadow-sm">
                            Pesan <i class="ri-whatsapp-line"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-6 text-sm text-gray-400 font-medium">Belum ada layanan tersedia pada kategori ini.</div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
