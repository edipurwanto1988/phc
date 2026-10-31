<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PHC — Pekanbaru Home Cleaning')</title>
    <meta name="description" content="@yield('meta_description', 'PHC (Pekanbaru Home Cleaning) — Jasa cleaning rumah profesional, terpercaya, dan terjangkau di Pekanbaru.')">
    <meta name="keywords" content="jasa cleaning Pekanbaru, cleaning rumah Pekanbaru, deep cleaning, cuci sofa, cuci AC, PHC">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <!-- Search Engine Verification -->
    @if($googleVerification = \App\Models\Setting::get('google_search_console'))
    <meta name="google-site-verification" content="{{ $googleVerification }}" />
    @endif
    @if($bingVerification = \App\Models\Setting::get('bing_webmaster'))
    <meta name="msvalidate.01" content="{{ $bingVerification }}" />
    @endif

    <!-- Pinterest Domain Verification -->
    <meta name="p:domain_verify" content="b7916750cf2e49978a722617b66e9357"/>

    <!-- Open Graph -->
    <meta property="og:title" content="@yield('title', 'PHC — Jasa Cleaning Profesional #1 di Pekanbaru')">
    <meta property="og:description" content="@yield('meta_description', 'Layanan cleaning rumah profesional dan terjangkau di Pekanbaru. Hubungi kami sekarang!')">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">

    <!-- Assets: Tailwind CSS (lokal, dikompres via Vite) + Remix Icon -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }

        /* Subtle hover lift */
        .card-hover {
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .card-hover:hover {
            box-shadow: 0 1px 6px rgba(32,33,36,0.18);
            transform: translateY(-2px);
        }

        /* Mobile menu */
        .mobile-menu { display: none; }
        .mobile-menu.active { display: block; }

        /* Dropdown Menu */
        .nav-dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            min-width: 13rem;
            width: max-content;
        }
        .nav-dropdown-menu a {
            white-space: nowrap;
        }
        .nav-dropdown:hover .nav-dropdown-menu,
        .nav-dropdown:focus-within .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        /* WYSIWYG Content Styles */
        .wysiwyg-content h1 { font-size: 1.75rem; font-weight: 800; margin-top: 1.5rem; margin-bottom: 1rem; color: #111827; }
        .wysiwyg-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.5rem; margin-bottom: 0.75rem; color: #1f2937; }
        .wysiwyg-content h3 { font-size: 1.25rem; font-weight: 700; margin-top: 1.25rem; margin-bottom: 0.5rem; color: #1f2937; }
        .wysiwyg-content p { margin-bottom: 1rem; }
        .wysiwyg-content p:last-child { margin-bottom: 0; }
        .wysiwyg-content ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem; }
        .wysiwyg-content ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 1rem; }
        .wysiwyg-content li { margin-bottom: 0.25rem; }
        .wysiwyg-content strong, .wysiwyg-content b { font-weight: 700; color: #111827; }
        .wysiwyg-content a { color: #2563eb; text-decoration: underline; }
        .wysiwyg-content blockquote { border-left: 4px solid #e5e7eb; padding-left: 1rem; font-style: italic; color: #4b5563; margin-top: 1rem; margin-bottom: 1rem; }

        /* WA button pulse */
        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 1; }
            100% { transform: scale(1.4); opacity: 0; }
        }
        .wa-pulse::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: #25D366;
            animation: pulse-ring 1.5s ease-out infinite;
            z-index: -1;
        }

        /* Step connector line */
        .step-connector {
            position: relative;
        }
        .step-connector::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: #DADCE0;
            z-index: 0;
        }
        .step-connector:last-child::after {
            display: none;
        }

        /* Active nav link */
        .nav-link {
            position: relative;
            transition: color 0.15s ease, background-color 0.15s ease;
            border-radius: 9999px;
        }
        .nav-link:hover {
            color: #1b456f;
            background: rgba(27, 69, 111, 0.06);
        }
    </style>
</head>
<body class="bg-white text-text-primary antialiased">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'PHC Pekanbaru');
        $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
        $phone = \App\Models\Setting::get('phone', '0761-12345');
        $email = \App\Models\Setting::get('email', 'info@phc-pekanbaru.com');
        $address = \App\Models\Setting::get('address', 'Jl. HR. Soebrantas, Pekanbaru, Riau');
        $instagram = \App\Models\Setting::get('instagram', '@phc.pekanbaru');
        $facebook = \App\Models\Setting::get('facebook', 'PHCPekanbaru');
        $tiktok = \App\Models\Setting::get('tiktok', '@phc.pekanbaru');
    @endphp

    <!-- ============================================= -->
    <!-- NAVBAR                                        -->
    <!-- ============================================= -->
    <nav class="nav-glass border-b border-border/70 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-[72px]">
                <!-- Logo -->
                <a href="/" class="flex items-center shrink-0">
                    <img src="{{ asset('header.png') }}" alt="{{ $siteName }}" class="h-10 w-auto object-contain">
                </a>

                <!-- Desktop Navigation Group (Rata Kanan) -->
                <div class="hidden md:flex items-center gap-6 ml-auto">
                    <!-- Desktop Nav Links -->
                    <div class="flex items-center gap-1">
                        @forelse($headerMenus as $hMenu)
                            @if($hMenu->children && $hMenu->children->count() > 0)
                                <div class="relative nav-dropdown">
                                    <a href="{{ $hMenu->url }}" target="{{ $hMenu->target }}" class="nav-link text-sm text-text-secondary font-medium flex items-center gap-1.5 px-3.5 py-2">
                                        @if($hMenu->icon)<i class="{{ $hMenu->icon }} text-base"></i>@endif
                                        {{ $hMenu->nama }}
                                        <i class="ri-arrow-down-s-line text-base"></i>
                                    </a>
                                    <div class="absolute left-0 top-full pt-2 z-50 nav-dropdown-menu">
                                        <div class="bg-white border border-border/70 rounded-2xl shadow-[0_18px_45px_rgba(15,23,42,0.12)] overflow-hidden p-1.5">
                                            @foreach($hMenu->children as $child)
                                                <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-3.5 py-2.5 rounded-xl text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                                                    @if($child->icon)<i class="{{ $child->icon }} mr-1"></i>@endif
                                                    {{ $child->nama }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @else
                                <a href="{{ $hMenu->url }}" target="{{ $hMenu->target }}" class="nav-link text-sm text-text-secondary font-medium flex items-center gap-1.5 px-3.5 py-2">
                                    @if($hMenu->icon)<i class="{{ $hMenu->icon }} text-base"></i>@endif
                                    {{ $hMenu->nama }}
                                </a>
                            @endif
                        @empty
                            <a href="/#layanan" class="nav-link text-sm text-text-secondary font-medium px-3.5 py-2">Layanan</a>
                            <a href="/#tentang" class="nav-link text-sm text-text-secondary font-medium px-3.5 py-2">Tentang</a>
                            <a href="/blog" class="nav-link text-sm text-text-secondary font-medium px-3.5 py-2">Blog</a>
                            <a href="/loker" class="nav-link text-sm text-text-secondary font-medium px-3.5 py-2">Loker</a>
                            <a href="/#kontak" class="nav-link text-sm text-text-secondary font-medium px-3.5 py-2">Kontak</a>
                        @endforelse
                    </div>

                    <!-- Auth Buttons -->
                    @auth
                    <div class="flex items-center gap-2 pl-4 border-l border-border/70">
                        @if(auth()->user()->role_id == 5)
                            <a href="/customer/dashboard" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:bg-primary hover:text-white px-4 py-2 border border-primary/40 rounded-full transition-all">
                                <i class="ri-user-line"></i> Dashboard
                            </a>
                        @else
                            <a href="/admin" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:bg-primary hover:text-white px-4 py-2 border border-primary/40 rounded-full transition-all">
                                <i class="ri-dashboard-line"></i> Admin Panel
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 px-3 py-2">Logout</button>
                        </form>
                    </div>
                    @else
                    <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%20cleaning" target="_blank" class="btn-pill btn-pill-primary !py-2.5 !px-5">
                        <i class="ri-whatsapp-line text-base"></i> Pesan Sekarang
                    </a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMenu()" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full hover:bg-surface transition-colors" aria-label="Toggle menu">
                    <i class="ri-menu-line text-2xl text-text-secondary" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu md:hidden border-t border-border/70 bg-white" id="mobile-menu">
            <div class="px-4 py-3 space-y-1">
                @forelse($headerMenus as $hMenu)
                    @if($hMenu->children && $hMenu->children->count() > 0)
                        <div class="space-y-1">
                            <a href="{{ $hMenu->url }}" target="{{ $hMenu->target }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">
                                <span class="flex items-center">
                                    @if($hMenu->icon)<i class="{{ $hMenu->icon }} mr-1.5"></i>@endif
                                    {{ $hMenu->nama }}
                                </span>
                                <i class="ri-arrow-down-s-line"></i>
                            </a>
                            <div class="pl-4 space-y-1 pb-1">
                                @foreach($hMenu->children as $child)
                                    <a href="{{ $child->url }}" target="{{ $child->target }}" class="block px-3 py-2 rounded-xl text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                                        @if($child->icon)<i class="{{ $child->icon }} mr-1.5"></i>@endif
                                        {{ $child->nama }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $hMenu->url }}" target="{{ $hMenu->target }}" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">
                            @if($hMenu->icon)<i class="{{ $hMenu->icon }} mr-1.5"></i>@endif
                            {{ $hMenu->nama }}
                        </a>
                    @endif
                @empty
                    <a href="/#layanan" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">Layanan</a>
                    <a href="/#tentang" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">Tentang</a>
                    <a href="/blog" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">Blog</a>
                    <a href="/#kontak" class="block px-3 py-2.5 rounded-xl text-sm font-medium text-text-primary hover:bg-surface hover:text-primary transition-colors">Kontak</a>
                @endforelse

                <div class="pt-3 mt-2 border-t border-border/70 space-y-2">
                    @auth
                        @if(auth()->user()->role_id == 5)
                            <a href="/customer/dashboard" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-primary hover:bg-surface">
                                <i class="ri-user-line"></i> Dashboard
                            </a>
                        @else
                            <a href="/admin" class="flex items-center gap-2 px-3 py-2.5 rounded-xl text-sm font-semibold text-primary hover:bg-surface">
                                <i class="ri-dashboard-line"></i> Admin Panel
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="block">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2.5 rounded-xl text-sm text-red-500 hover:bg-red-50">Logout</button>
                        </form>
                    @else
                        <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%20cleaning" target="_blank" class="btn-pill btn-pill-primary w-full">
                            <i class="ri-whatsapp-line text-base"></i> Pesan via WhatsApp
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main View Contents -->
    @yield('content')

    <!-- ============================================= -->
    <!-- FOOTER                                        -->
    <!-- ============================================= -->
    <footer class="bg-white border-t border-border/70">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 pt-14 pb-8 md:pt-16">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 mb-12">
                <!-- Branding -->
                <div class="md:col-span-5 space-y-5">
                    <a href="/" class="inline-flex items-center">
                        <img src="{{ asset('header.png') }}" alt="{{ $siteName }}" class="h-10 w-auto object-contain">
                    </a>
                    <p class="text-sm text-text-secondary leading-relaxed max-w-sm">
                        Layanan kebersihan rumah dan kantor profesional terbaik di Pekanbaru. Hubungi kami sekarang untuk solusi kebersihan Anda.
                    </p>
                    <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%20cleaning" target="_blank" class="btn-pill btn-pill-wa !py-2.5 !px-5">
                        <i class="ri-whatsapp-line text-base"></i> Chat WhatsApp
                    </a>
                </div>

                <!-- Links -->
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.18em] text-text-primary mb-5">Navigasi</h4>
                    <ul class="space-y-3 text-sm">
                        @forelse($footerMenus as $fMenu)
                            <li>
                                <a href="{{ $fMenu->url }}" target="{{ $fMenu->target }}" class="text-text-secondary hover:text-primary transition-colors">
                                    @if($fMenu->icon)<i class="{{ $fMenu->icon }} mr-1"></i>@endif
                                    {{ $fMenu->nama }}
                                </a>
                            </li>
                        @empty
                            <li><a href="/#layanan" class="text-text-secondary hover:text-primary transition-colors">Layanan Kami</a></li>
                            <li><a href="/#tentang" class="text-text-secondary hover:text-primary transition-colors">Tentang Kami</a></li>
                            <li><a href="/blog" class="text-text-secondary hover:text-primary transition-colors">Blog / Tips</a></li>
                            <li><a href="/loker" class="text-text-secondary hover:text-primary transition-colors">Loker</a></li>
                            <li><a href="/#kontak" class="text-text-secondary hover:text-primary transition-colors">Kontak</a></li>
                        @endforelse
                    </ul>
                </div>

                <!-- Contact -->
                <div class="md:col-span-3">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.18em] text-text-primary mb-5">Hubungi Kami</h4>
                    <ul class="space-y-3.5 text-sm text-text-secondary">
                        <li class="flex items-start gap-3">
                            <span class="w-8 h-8 rounded-full bg-surface border border-border/70 flex items-center justify-center text-primary shrink-0"><i class="ri-map-pin-line"></i></span>
                            <span class="leading-relaxed pt-1">{{ $address }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-surface border border-border/70 flex items-center justify-center text-primary shrink-0"><i class="ri-phone-line"></i></span>
                            <span>{{ $phone }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-surface border border-border/70 flex items-center justify-center text-primary shrink-0"><i class="ri-mail-line"></i></span>
                            <span class="break-all">{{ $email }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Socials -->
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold uppercase tracking-[0.18em] text-text-primary mb-5">Ikuti Kami</h4>
                    <div class="flex gap-2.5">
                        <a href="https://instagram.com/{{ ltrim($instagram, '@') }}" target="_blank" class="w-10 h-10 rounded-full border border-border/80 bg-white flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="Instagram">
                            <i class="ri-instagram-line text-lg"></i>
                        </a>
                        <a href="https://facebook.com/{{ $facebook }}" target="_blank" class="w-10 h-10 rounded-full border border-border/80 bg-white flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="Facebook">
                            <i class="ri-facebook-box-line text-lg"></i>
                        </a>
                        <a href="https://tiktok.com/{{ $tiktok }}" target="_blank" class="w-10 h-10 rounded-full border border-border/80 bg-white flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="TikTok">
                            <i class="ri-tiktok-line text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Credits -->
            <div class="pt-6 border-t border-border/70 flex flex-col md:flex-row items-center justify-between gap-2 text-center md:text-left">
                <p class="text-xs text-text-secondary">&copy; {{ date('Y') }} {{ $siteName }}. Hak Cipta Dilindungi.</p>
                <p class="text-xs text-text-secondary">Design inspired by Team IT PHC.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WA Button -->
    <a href="https://wa.me/{{ $whatsapp }}?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%20cleaning" target="_blank" class="fixed bottom-6 right-6 w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-lg hover:bg-[#20ba5a] transition-all hover:scale-105 z-50 wa-pulse" aria-label="Hubungi WhatsApp">
        <i class="ri-whatsapp-line text-3xl"></i>
    </a>

    <!-- Navbar mobile script -->
    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            menu.classList.toggle('active');

            if (menu.classList.contains('active')) {
                icon.classList.remove('ri-menu-line');
                icon.classList.add('ri-close-line');
            } else {
                icon.classList.remove('ri-close-line');
                icon.classList.add('ri-menu-line');
            }
        }

        (function () {
            const items = document.querySelectorAll('[data-reveal]');
            if (!items.length) return;
            if (!('IntersectionObserver' in window)) return;

            items.forEach(function (el) { el.classList.add('section-reveal'); });

            const observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

            items.forEach(function (el) { observer.observe(el); });
        })();
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
