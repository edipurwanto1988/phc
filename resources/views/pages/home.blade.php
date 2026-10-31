@extends('layouts.public')

@section('title', 'PHC — Pekanbaru Home Cleaning | Jasa Cleaning Profesional #1 di Pekanbaru')

@section('content')
    @php
        $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
        $phone = \App\Models\Setting::get('phone', '0761-12345');
    @endphp

    <!-- ============================================= -->
    <!-- HERO SECTION                                  -->
    <!-- ============================================= -->
    <section class="hero-wash relative overflow-hidden">
        <div class="absolute inset-0 bg-dots mask-fade-b pointer-events-none"></div>
        <div class="absolute -top-24 -right-24 w-[420px] h-[420px] rounded-full bg-secondary/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-20 w-[360px] h-[360px] rounded-full bg-accent/10 blur-3xl pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 pt-14 pb-16 md:pt-24 md:pb-28">
            <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-12 md:gap-8">

                <!-- Left Content -->
                <div class="md:col-span-7 text-center md:text-left">
                    <span class="pill !bg-white/80 backdrop-blur text-primary border-primary/15">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent"></span>
                        Jasa Cleaning Profesional di Pekanbaru
                    </span>

                    <h1 class="mt-6 text-4xl sm:text-5xl lg:text-[3.6rem] font-bold text-text-primary leading-[1.08] tracking-tight">
                        Rumah Bersih,
                        <span class="relative inline-block text-primary">
                            Hati Tenang
                            <svg class="absolute -bottom-2 left-0 w-full h-3 text-accent" viewBox="0 0 200 12" fill="none" preserveAspectRatio="none" aria-hidden="true">
                                <path d="M2 9C40 3 120 1 198 7" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
                            </svg>
                        </span>
                        <br class="hidden sm:block"> bersama PHC.
                    </h1>

                    <p class="mt-7 text-lg md:text-xl text-text-secondary leading-relaxed max-w-xl mx-auto md:mx-0">
                        Jasa cleaning profesional #1 di Pekanbaru. Tim terlatih, peralatan modern, harga terjangkau, dan garansi kepuasan untuk rumah maupun kantor Anda.
                    </p>

                    <div class="mt-9 flex flex-col sm:flex-row gap-3 justify-center md:justify-start">
                        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%2520cleaning"
                           target="_blank"
                           class="btn-pill btn-pill-primary !px-7 !py-3.5">
                            <i class="ri-whatsapp-line text-lg"></i>
                            Hubungi via WhatsApp
                        </a>
                        <a href="tel:{{ $phone }}" class="btn-pill btn-pill-outline !px-7 !py-3.5">
                            <i class="ri-phone-line text-lg text-primary"></i>
                            {{ $phone }}
                        </a>
                    </div>

                    <!-- Trust row -->
                    <div class="mt-10 flex flex-wrap items-center justify-center md:justify-start gap-x-8 gap-y-4">
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-full bg-white border border-border/70 shadow-sm flex items-center justify-center text-accent"><i class="ri-star-fill"></i></span>
                            <div class="text-left leading-tight">
                                <p class="text-sm font-bold text-text-primary">Rating 4.9</p>
                                <p class="text-xs text-text-secondary">Ulasan pelanggan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-full bg-white border border-border/70 shadow-sm flex items-center justify-center text-secondary"><i class="ri-home-smile-line"></i></span>
                            <div class="text-left leading-tight">
                                <p class="text-sm font-bold text-text-primary">500+ Rumah</p>
                                <p class="text-xs text-text-secondary">Sudah kami bersihkan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="w-9 h-9 rounded-full bg-white border border-border/70 shadow-sm flex items-center justify-center text-green-600"><i class="ri-shield-check-line"></i></span>
                            <div class="text-left leading-tight">
                                <p class="text-sm font-bold text-text-primary">Bergaransi</p>
                                <p class="text-xs text-text-secondary">Tidak puas, kerjakan ulang</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Photo -->
                <div class="md:col-span-5 flex justify-center md:justify-end">
                    <div class="relative w-full max-w-[340px] md:max-w-[380px]">
                        <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-secondary/15 via-transparent to-accent/20 blur-xl pointer-events-none"></div>
                        <div class="relative rounded-[1.75rem] overflow-hidden ring-1 ring-border/70 shadow-[0_30px_60px_-20px_rgba(27,69,111,0.35)] bg-white">
                            <img src="{{ asset('images/phc-team.webp') }}"
                                 alt="Tim profesional PHC Pekanbaru Home Cleaning"
                                 class="w-full object-cover aspect-[3/4]"
                                 loading="eager">
                        </div>

                        <!-- Floating badge: garansi -->
                        <div class="absolute -left-6 sm:-left-10 bottom-10 card-soft px-4 py-3 flex items-center gap-3 float-slow">
                            <span class="w-9 h-9 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><i class="ri-checkbox-circle-fill text-xl"></i></span>
                            <div class="leading-tight">
                                <p class="text-xs text-text-secondary">Garansi</p>
                                <p class="text-sm font-bold text-text-primary">Kepuasan 100%</p>
                            </div>
                        </div>

                        <!-- Floating badge: jadwal -->
                        <div class="absolute -right-4 sm:-right-8 top-8 card-soft px-4 py-3 flex items-center gap-3 float-slower">
                            <span class="w-9 h-9 rounded-full bg-blue-50 text-secondary flex items-center justify-center"><i class="ri-calendar-check-line text-xl"></i></span>
                            <div class="leading-tight">
                                <p class="text-xs text-text-secondary">Buka</p>
                                <p class="text-sm font-bold text-text-primary">Setiap Hari</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- LAYANAN UNGGULAN                              -->
    <!-- ============================================= -->
    <section id="layanan" class="bg-white border-t border-border/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12" data-reveal>
                <div class="max-w-xl">
                    <span class="eyebrow">Layanan Kami</span>
                    <h2 class="section-title">Semua kebutuhan kebersihan, satu tempat</h2>
                    <p class="section-lead">Berbagai layanan kebersihan profesional untuk rumah, apartemen, dan kantor Anda.</p>
                </div>
                <a href="/layanan" class="btn-pill btn-pill-outline shrink-0 self-start md:self-auto">
                    Lihat semua layanan
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>

            <!-- Service Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
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
                <div class="card-soft lift-card p-6 flex flex-col justify-between group" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                    <a href="/layanan/{{ $service->slug }}" class="block">
                        <div class="flex items-start justify-between">
                            <div class="w-12 h-12 {{ $bgClass }} rounded-2xl flex items-center justify-center mb-5">
                                <i class="{{ $iconClass }} text-2xl"></i>
                            </div>
                            <span class="w-9 h-9 rounded-full border border-border/70 flex items-center justify-center text-text-secondary group-hover:bg-primary group-hover:border-primary group-hover:text-white transition-colors">
                                <i class="ri-arrow-right-up-line"></i>
                            </span>
                        </div>
                        <h3 class="text-lg font-semibold text-text-primary group-hover:text-primary transition-colors">{{ $service->nama }}</h3>
                        <p class="mt-2 text-sm text-text-secondary leading-relaxed line-clamp-3">{{ strip_tags($service->deskripsi_singkat ?? $service->deskripsi) }}</p>
                    </a>
                    <div class="mt-6 flex items-baseline gap-1 pt-4 border-t border-border/60">
                        <span class="text-xs text-text-secondary mr-1">Mulai</span>
                        <span class="text-lg font-bold text-primary">Rp {{ number_format($service->harga, 0, ',', '.') }}</span>
                        <span class="text-xs text-text-secondary">/ {{ $service->satuan }}</span>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-8 text-text-secondary">Belum ada layanan tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- KENAPA PILIH PHC?                             -->
    <!-- ============================================= -->
    <section id="tentang" class="bg-surface/70 border-y border-border/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <div class="text-center max-w-2xl mx-auto mb-12" data-reveal>
                <span class="eyebrow eyebrow-center justify-center">Kenapa PHC</span>
                <h2 class="section-title">Kenapa Pilih Pekanbaru Home Cleaning?</h2>
                <p class="section-lead">Kami berkomitmen memberikan layanan terbaik untuk Anda, dari kunjungan pertama hingga hasil akhir.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Item 1 -->
                <div class="card-soft lift-card p-7" data-reveal data-reveal-delay="0">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-5">
                        <i class="ri-checkbox-circle-line text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Profesional</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed">Tim terlatih dengan standar kerja yang tinggi dan peralatan modern.</p>
                </div>

                <!-- Item 2 -->
                <div class="card-soft lift-card p-7" data-reveal data-reveal-delay="1">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-5">
                        <i class="ri-wallet-3-line text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Harga Terjangkau</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed">Harga transparan tanpa biaya tersembunyi. Sesuai budget Anda.</p>
                </div>

                <!-- Item 3 -->
                <div class="card-soft lift-card p-7" data-reveal data-reveal-delay="2">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-5">
                        <i class="ri-award-line text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Berpengalaman</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed">Pengalaman bertahun-tahun melayani ratusan rumah di Pekanbaru.</p>
                </div>

                <!-- Item 4 -->
                <div class="card-soft lift-card p-7" data-reveal data-reveal-delay="3">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mb-5">
                        <i class="ri-thumb-up-line text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Garansi Kepuasan</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed">Tidak puas? Kami kerjakan ulang tanpa biaya tambahan secara profesional.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CARA PESAN                                    -->
    <!-- ============================================= -->
    <section class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <div class="text-center max-w-2xl mx-auto mb-14" data-reveal>
                <span class="eyebrow eyebrow-center justify-center">Cara Memesan</span>
                <h2 class="section-title">Cukup 3 langkah mudah</h2>
                <p class="section-lead">Dari pertama menghubungi sampai rumah kembali bersih, semuanya kami buat sederhana.</p>
            </div>

            <div class="relative grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-8 max-w-4xl mx-auto stepper-line" data-reveal>
                <!-- Step 1 -->
                <div class="relative text-center">
                    <div class="w-14 h-14 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center mx-auto mb-5 relative z-10 font-bold text-lg shadow-[0_8px_20px_-8px_rgba(27,69,111,0.5)]">
                        1
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Hubungi Kami</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed max-w-xs mx-auto">Hubungi via WhatsApp atau telepon untuk konsultasi kebutuhan layanan.</p>
                </div>

                <!-- Step 2 -->
                <div class="relative text-center">
                    <div class="w-14 h-14 bg-white border-2 border-primary text-primary rounded-full flex items-center justify-center mx-auto mb-5 relative z-10 font-bold text-lg shadow-[0_8px_20px_-8px_rgba(27,69,111,0.5)]">
                        2
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Jadwalkan</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed max-w-xs mx-auto">Tentukan tanggal dan waktu yang sesuai dengan aktivitas harian Anda.</p>
                </div>

                <!-- Step 3 -->
                <div class="relative text-center">
                    <div class="w-14 h-14 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-5 relative z-10 shadow-[0_8px_20px_-8px_rgba(27,69,111,0.6)]">
                        <i class="ri-check-line text-2xl"></i>
                    </div>
                    <h3 class="text-base font-bold text-text-primary">Bersih!</h3>
                    <p class="mt-2 text-sm text-text-secondary leading-relaxed max-w-xs mx-auto">Tim kami datang tepat waktu. Rumah Anda kembali bersih berkilau dan nyaman.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- TESTIMONI                                     -->
    <!-- ============================================= -->
    <section class="bg-surface/70 border-y border-border/60">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <div class="text-center max-w-2xl mx-auto mb-12" data-reveal>
                <span class="eyebrow eyebrow-center justify-center">Testimoni</span>
                <h2 class="section-title">Apa kata mereka</h2>
                <p class="section-lead">Cerita dari pelanggan yang sudah merasakan layanan PHC.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse($testimonials as $testimonial)
                <div class="card-soft lift-card p-7 flex flex-col justify-between relative overflow-hidden" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                    <i class="ri-double-quotes-l absolute -top-2 right-5 text-7xl text-primary/[0.06] pointer-events-none"></i>
                    <div>
                        <div class="flex gap-0.5 mb-4 text-accent">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="ri-star-fill text-base"></i>
                                @else
                                    <i class="ri-star-line text-base"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-[15px] text-text-primary/90 leading-relaxed">
                            "{{ strip_tags($testimonial->konten) }}"
                        </p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 pt-5 border-t border-border/60">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-secondary rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr($testimonial->nama, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-text-primary leading-none">{{ $testimonial->nama }}</p>
                            <p class="text-xs text-text-secondary mt-1 flex items-center gap-1"><i class="ri-verified-badge-fill text-secondary"></i> Verified Customer</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-6 text-text-secondary">Belum ada ulasan testimoni.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CUPLIKAN ARTIKEL                              -->
    <!-- ============================================= -->
    <section id="artikel" class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 md:py-24">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-12" data-reveal>
                <div class="max-w-xl">
                    <span class="eyebrow">Blog</span>
                    <h2 class="section-title">Tips & artikel kebersihan</h2>
                    <p class="section-lead">Tips dan informasi seputar kebersihan rumah untuk kenyamanan Anda.</p>
                </div>
                <a href="/blog" class="btn-pill btn-pill-outline shrink-0 self-start md:self-auto">
                    Lihat semua artikel
                    <i class="ri-arrow-right-line"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                @forelse($posts as $post)
                <article class="card-soft lift-card overflow-hidden flex flex-col justify-between group" data-reveal data-reveal-delay="{{ $loop->index % 3 }}">
                    <div>
                        <a href="/blog/{{ $post->slug }}" class="block aspect-[16/10] bg-gradient-to-br from-blue-50 to-surface flex items-center justify-center overflow-hidden img-zoom">
                            @if($post->gambar_utama)
                                <img src="{{ asset('storage/' . $post->gambar_utama) }}" alt="{{ $post->judul }}" class="w-full h-full object-cover">
                            @else
                                <i class="ri-article-line text-primary text-5xl opacity-30"></i>
                            @endif
                        </a>
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="pill !py-0.5 text-primary border-primary/20 bg-primary/5">Kebersihan</span>
                                <span class="text-xs text-text-secondary">{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-lg font-semibold text-text-primary leading-snug line-clamp-2 group-hover:text-primary transition-colors">
                                <a href="/blog/{{ $post->slug }}">{{ $post->judul }}</a>
                            </h3>
                            <p class="mt-2 text-sm text-text-secondary leading-relaxed line-clamp-2">{{ strip_tags($post->excerpt ?? $post->konten) }}</p>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="/blog/{{ $post->slug }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark transition-colors">
                            Baca selengkapnya
                            <i class="ri-arrow-right-line transition-transform group-hover:translate-x-0.5"></i>
                        </a>
                    </div>
                </article>
                @empty
                <div class="col-span-3 text-center py-6 text-text-secondary">Belum ada artikel dipublikasikan.</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- ============================================= -->
    <!-- CTA SECTION                                   -->
    <!-- ============================================= -->
    <section id="kontak" class="bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-16 md:pb-24">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-primary via-primary to-secondary p-8 md:p-14 text-center shadow-[0_30px_60px_-25px_rgba(27,69,111,0.6)]" data-reveal>
                <div class="absolute inset-0 bg-dots-light pointer-events-none"></div>
                <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-white/10 blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-16 w-64 h-64 rounded-full bg-accent/20 blur-3xl pointer-events-none"></div>

                <div class="relative">
                    <span class="inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-accent">Siap melayani Anda</span>
                    <h2 class="mt-3 text-3xl md:text-4xl font-bold text-white tracking-tight">Rumah Bersih, Hati Senang</h2>
                    <p class="mt-4 text-blue-100 max-w-lg mx-auto text-base leading-relaxed">
                        Jadwalkan layanan cleaning sekarang juga. Tim profesional kami siap membuat rumah Anda kembali berkilau dan nyaman dihuni.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%2520jasa%2520cleaning"
                           target="_blank"
                           class="btn-pill bg-white text-primary hover:bg-blue-50 !px-7 !py-3.5 shadow-md">
                            <i class="ri-whatsapp-line text-lg"></i>
                            Hubungi via WhatsApp
                        </a>
                        <a href="tel:{{ $phone }}"
                           class="btn-pill border border-white/40 text-white hover:bg-white/10 !px-7 !py-3.5">
                            <i class="ri-phone-line text-lg"></i>
                            Telepon Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
