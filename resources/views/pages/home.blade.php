@extends('layouts.public')

@section('title', 'PHC — Pekanbaru Home Cleaning | Jasa Cleaning Profesional #1 di Pekanbaru')

@section('content')
    <!-- ============================================= -->
    <!-- HERO SECTION                                  -->
    <!-- ============================================= -->
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <div class="flex flex-col md:flex-row items-center gap-10 md:gap-12">

                <!-- Left Content (2/3) -->
                <div class="w-full md:w-2/3 text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-text-primary leading-tight tracking-tight">
                        Jasa Cleaning
                        <span class="text-primary"> Profesional</span>
                        <br>#1 di Pekanbaru
                    </h1>
                    <p class="mt-6 text-lg md:text-xl text-text-secondary leading-relaxed max-w-xl">
                        Rumah bersih, hati tenang. Kami hadir dengan layanan cleaning profesional, terpercaya, dan harga terjangkau.
                    </p>
                    <div class="mt-10 flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                        @php
                            $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
                            $phone = \App\Models\Setting::get('phone', '0761-12345');
                        @endphp
                        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%2520cleaning"
                           target="_blank"
                           class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white font-medium px-8 py-3 rounded-lg transition-colors text-sm">
                            <i class="ri-whatsapp-line text-lg"></i>
                            Hubungi via WhatsApp
                        </a>
                        <a href="tel:{{ $phone }}"
                           class="inline-flex items-center justify-center gap-2 bg-white border border-border hover:bg-surface text-text-primary font-medium px-8 py-3 rounded-lg transition-colors text-sm">
                            <i class="ri-phone-line text-lg"></i>
                            {{ $phone }}
                        </a>
                    </div>
                </div>

                <!-- Right Photo (1/3) -->
                <div class="w-full md:w-1/3 flex justify-center md:justify-end">
                    <div class="relative">
                        <img src="{{ asset('images/phc-team.png') }}"
                             alt="Tim profesional PHC Pekanbaru Home Cleaning"
                             class="w-full max-w-xs md:max-w-none rounded-2xl shadow-lg object-cover aspect-[3/4]"
                             loading="eager">
                        <!-- Decorative accent -->
                        <div class="absolute -bottom-3 -right-3 w-24 h-24 bg-primary/10 rounded-2xl -z-10"></div>
                        <div class="absolute -top-3 -left-3 w-16 h-16 bg-secondary/10 rounded-xl -z-10"></div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- LAYANAN UNGGULAN                              -->
    <!-- ============================================= -->
    <section id="layanan" class="bg-surface">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <!-- Section Header -->
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-text-primary tracking-tight">Layanan Kami</h2>
                <p class="mt-3 text-text-secondary max-w-lg mx-auto">Berbagai layanan kebersihan profesional untuk rumah dan kantor Anda</p>
            </div>

            <!-- Service Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($services as $service)
                @php
                    // Determine category specific design/icon color
                    $catSlug = $service->category->slug ?? '';
                    $bgClass = 'bg-blue-50 text-primary';
                    $iconClass = 'ri-sparkling-line';

                    if (str_contains($catSlug, 'rumah') || str_contains($catSlug, 'cleaning')) {
                        $bgClass = 'bg-blue-50 text-blue-600';
                        $iconClass = 'ri-home-4-line';
                    } elseif (str_contains($catSlug, 'sofa') || str_contains($catSlug, 'karpet')) {
                        $bgClass = 'bg-orange-50 text-orange-600';
                        $iconClass = 'ri-sofa-line';
                    } elseif (str_contains($catSlug, 'ac') || str_contains($catSlug, 'pendingin')) {
                        $bgClass = 'bg-cyan-50 text-cyan-600';
                        $iconClass = 'ri-temp-cold-line';
                    } elseif (str_contains($catSlug, 'poles') || str_contains($catSlug, 'lantai')) {
                        $bgClass = 'bg-amber-50 text-amber-600';
                        $iconClass = 'ri-magic-line';
                    } elseif (str_contains($catSlug, 'fumigasi') || str_contains($catSlug, 'hama')) {
                        $bgClass = 'bg-purple-50 text-purple-600';
                        $iconClass = 'ri-bubble-chart-line';
                    }
                @endphp
                <div class="card-hover bg-white rounded-lg border border-border p-6 flex flex-col justify-between">
                    <a href="/layanan/{{ $service->slug }}" class="block group">
                        <div class="w-10 h-10 {{ $bgClass }} rounded-lg flex items-center justify-center mb-4">
                            <i class="{{ $iconClass }} text-xl"></i>
                        </div>
                        <h3 class="text-base font-semibold text-text-primary group-hover:text-primary transition-colors">{{ $service->nama }}</h3>
                        <p class="mt-1 text-sm text-text-secondary leading-relaxed line-clamp-3">{{ $service->deskripsi_singkat ?? $service->deskripsi }}</p>
                    </a>
                    <div class="mt-4 flex items-baseline gap-1 pt-4 border-t border-gray-50">
                        <span class="text-lg font-bold text-primary">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                        <span class="text-xs text-text-secondary">/ {{ $service->satuan }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-gray-550">Belum ada layanan tersedia.</div>
                @endforelse
            </div>

            <!-- View All Link -->
            <div class="mt-8 text-center">
                <a href="/layanan" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:text-primary-dark transition-colors">
                    Lihat semua layanan
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- KENAPA PILIH PHC?                             -->
    <!-- ============================================= -->
    <section id="tentang" class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-text-primary tracking-tight">Kenapa Pilih Pekanbaru Home Cleaning?</h2>
                <p class="mt-3 text-text-secondary max-w-lg mx-auto">Kami berkomitmen memberikan layanan terbaik untuk Anda</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Item 1 -->
                <div class="text-center p-6 bg-surface border border-border rounded-xl">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-checkbox-circle-line text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-text-primary">Profesional</h3>
                    <p class="mt-2 text-xs text-text-secondary leading-relaxed">Tim terlatih dengan standar kerja yang tinggi dan peralatan modern.</p>
                </div>

                <!-- Item 2 -->
                <div class="text-center p-6 bg-surface border border-border rounded-xl">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-wallet-3-line text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-text-primary">Harga Terjangkau</h3>
                    <p class="mt-2 text-xs text-text-secondary leading-relaxed">Harga transparan tanpa biaya tersembunyi. Sesuai budget Anda.</p>
                </div>

                <!-- Item 3 -->
                <div class="text-center p-6 bg-surface border border-border rounded-xl">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-award-line text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-text-primary">Berpengalaman</h3>
                    <p class="mt-2 text-xs text-text-secondary leading-relaxed">Pengalaman bertahun-tahun melayani ratusan rumah di Pekanbaru.</p>
                </div>

                <!-- Item 4 -->
                <div class="text-center p-6 bg-surface border border-border rounded-xl">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-thumb-up-line text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-text-primary">Garansi Kepuasan</h3>
                    <p class="mt-2 text-xs text-text-secondary leading-relaxed">Tidak puas? Kami kerjakan ulang tanpa biaya tambahan secara profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CARA PESAN                                    -->
    <!-- ============================================= -->
    <section class="bg-surface">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-text-primary tracking-tight">Cara Memesan</h2>
                <p class="mt-3 text-text-secondary max-w-lg mx-auto">Cukup 3 langkah mudah untuk rumah bersih</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-3xl mx-auto">
                <!-- Step 1 -->
                <div class="text-center relative">
                    <div class="w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 relative z-10 font-bold text-lg">
                        1
                    </div>
                    <!-- Connector line (desktop) -->
                    <div class="hidden md:block absolute top-7 left-[60%] w-[80%] h-[2px] bg-border"></div>
                    <h3 class="text-sm font-bold text-text-primary">Hubungi Kami</h3>
                    <p class="mt-2 text-xs text-text-secondary">Hubungi via WhatsApp atau telepon untuk konsultasi kebutuhan layanan.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center relative">
                    <div class="w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 relative z-10 font-bold text-lg">
                        2
                    </div>
                    <div class="hidden md:block absolute top-7 left-[60%] w-[80%] h-[2px] bg-border"></div>
                    <h3 class="text-sm font-bold text-text-primary">Jadwalkan</h3>
                    <p class="mt-2 text-xs text-text-secondary">Tentukan tanggal dan waktu yang sesuai dengan aktivitas harian Anda.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-14 h-14 bg-secondary text-white rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ri-check-line text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-text-primary">Bersih!</h3>
                    <p class="mt-2 text-xs text-text-secondary">Tim kami datang tepat waktu. Rumah Anda kembali bersih berkilau dan nyaman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- TESTIMONI                                     -->
    <!-- ============================================= -->
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-text-primary tracking-tight">Apa Kata Mereka</h2>
                <p class="mt-3 text-text-secondary max-w-lg mx-auto">Testimoni dari pelanggan yang sudah merasakan layanan PHC</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($testimonials as $testimonial)
                <div class="bg-surface rounded-xl border border-border p-6 flex flex-col justify-between shadow-sm">
                    <div>
                        <div class="flex gap-0.5 mb-3 text-yellow-500">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="ri-star-fill text-sm"></i>
                                @else
                                    <i class="ri-star-line text-sm"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-text-secondary leading-relaxed italic">
                            "{{ $testimonial->konten }}"
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 pt-4 border-t border-gray-100">
                        <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xs shrink-0">
                            {{ strtoupper(substr($testimonial->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-text-primary leading-none">{{ $testimonial->nama }}</p>
                            <p class="text-[10px] text-text-secondary mt-1">Verified Customer</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-6 text-gray-500">Belum ada ulasan testimoni.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CUPLIKAN ARTIKEL                              -->
    <!-- ============================================= -->
    <section id="artikel" class="bg-surface">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl font-semibold text-text-primary tracking-tight">Tips & Artikel Kebersihan</h2>
                <p class="mt-3 text-text-secondary max-w-lg mx-auto">Tips dan informasi seputar kebersihan rumah untuk kenyamanan Anda</p>
            </div>

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
                            <p class="mt-2 text-sm text-text-secondary leading-relaxed line-clamp-2">{{ $post->excerpt }}</p>
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
                <div class="col-span-3 text-center py-6 text-gray-500">Belum ada artikel dipublikasikan.</div>
                @endforelse
            </div>

            <!-- View All Articles -->
            <div class="mt-8 text-center">
                <a href="/blog" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                    Lihat semua artikel
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CTA SECTION                                   -->
    <!-- ============================================= -->
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-20">
            <div class="bg-primary rounded-xl p-8 md:p-12 text-center shadow-lg">
                <h2 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">Rumah Bersih, Hati Senang</h2>
                <p class="mt-3 text-blue-100 max-w-md mx-auto text-sm leading-relaxed">
                    Jadwalkan layanan cleaning sekarang juga. Tim profesional kami siap membuat rumah Anda kembali berkilau dan nyaman dihuni.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%2520jasa%2520cleaning"
                       target="_blank"
                       class="inline-flex items-center justify-center gap-2 bg-white text-primary hover:bg-blue-50 font-bold px-8 py-3 rounded-lg transition-colors text-sm shadow-md">
                        <i class="ri-whatsapp-line text-lg"></i>
                        Hubungi via WhatsApp
                    </a>
                    <a href="tel:{{ $phone }}"
                       class="inline-flex items-center justify-center gap-2 bg-transparent border border-white/40 text-white hover:bg-white/10 font-bold px-8 py-3 rounded-lg transition-colors text-sm">
                        <i class="ri-phone-line text-lg"></i>
                        Telepon Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
