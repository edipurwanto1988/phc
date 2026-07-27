<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'PHC — Pekanbaru Home Cleaning'); ?></title>
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'PHC (Pekanbaru Home Cleaning) — Jasa cleaning rumah profesional, terpercaya, dan terjangkau di Pekanbaru.'); ?>">
    <meta name="keywords" content="jasa cleaning Pekanbaru, cleaning rumah Pekanbaru, deep cleaning, cuci sofa, cuci AC, PHC">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('favicon.png')); ?>">
    <!-- Search Engine Verification -->
    <?php if($googleVerification = \App\Models\Setting::get('google_search_console')): ?>
    <meta name="google-site-verification" content="<?php echo e($googleVerification); ?>" />
    <?php endif; ?>
    <?php if($bingVerification = \App\Models\Setting::get('bing_webmaster')): ?>
    <meta name="msvalidate.01" content="<?php echo e($bingVerification); ?>" />
    <?php endif; ?>

    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', 'PHC — Jasa Cleaning Profesional #1 di Pekanbaru'); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', 'Layanan cleaning rumah profesional dan terjangkau di Pekanbaru. Hubungi kami sekarang!'); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">

    <!-- Assets: Tailwind CSS (lokal, dikompres via Vite) + Remix Icon -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

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
        }
        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

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
            transition: color 0.15s ease;
        }
        .nav-link:hover {
            color: #1A73E8;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #1A73E8;
            transition: width 0.2s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-white text-text-primary antialiased">
    <?php
        $siteName = \App\Models\Setting::get('site_name', 'PHC Pekanbaru');
        $whatsapp = \App\Models\Setting::get('whatsapp', '6281234567890');
        $phone = \App\Models\Setting::get('phone', '0761-12345');
        $email = \App\Models\Setting::get('email', 'info@phc-pekanbaru.com');
        $address = \App\Models\Setting::get('address', 'Jl. HR. Soebrantas, Pekanbaru, Riau');
        $instagram = \App\Models\Setting::get('instagram', '@phc.pekanbaru');
        $facebook = \App\Models\Setting::get('facebook', 'PHCPekanbaru');
        $tiktok = \App\Models\Setting::get('tiktok', '@phc.pekanbaru');
    ?>

    <!-- ============================================= -->
    <!-- NAVBAR                                        -->
    <!-- ============================================= -->
    <nav class="bg-white border-b border-border sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="/" class="flex items-center">
                    <img src="<?php echo e(asset('header.png')); ?>" alt="<?php echo e($siteName); ?>" class="h-10 w-auto object-contain">
                </a>

                <!-- Desktop Navigation Group (Rata Kanan) -->
                <div class="hidden md:flex items-center gap-8 ml-auto">
                    <!-- Desktop Nav Links -->
                    <div class="flex items-center gap-8">
                        <?php $__empty_1 = true; $__currentLoopData = $headerMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($hMenu->children && $hMenu->children->count() > 0): ?>
                                <div class="relative nav-dropdown">
                                    <a href="<?php echo e($hMenu->url); ?>" target="<?php echo e($hMenu->target); ?>" class="nav-link text-sm text-text-secondary font-medium flex items-center gap-1.5 py-4">
                                        <?php if($hMenu->icon): ?><i class="<?php echo e($hMenu->icon); ?> text-base"></i><?php endif; ?>
                                        <?php echo e($hMenu->nama); ?>

                                        <i class="ri-arrow-down-s-line"></i>
                                    </a>
                                    <div class="absolute left-0 top-full -mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-lg z-50 overflow-hidden nav-dropdown-menu">
                                        <div class="py-1">
                                            <?php $__currentLoopData = $hMenu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <a href="<?php echo e($child->url); ?>" target="<?php echo e($child->target); ?>" class="block px-4 py-2.5 text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                                                    <?php if($child->icon): ?><i class="<?php echo e($child->icon); ?> mr-1"></i><?php endif; ?>
                                                    <?php echo e($child->nama); ?>

                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <a href="<?php echo e($hMenu->url); ?>" target="<?php echo e($hMenu->target); ?>" class="nav-link text-sm text-text-secondary font-medium flex items-center gap-1.5 py-4">
                                    <?php if($hMenu->icon): ?><i class="<?php echo e($hMenu->icon); ?> text-base"></i><?php endif; ?>
                                    <?php echo e($hMenu->nama); ?>

                                </a>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <a href="/#layanan" class="nav-link text-sm text-text-secondary font-medium">Layanan</a>
                            <a href="/#tentang" class="nav-link text-sm text-text-secondary font-medium">Tentang</a>
                            <a href="/blog" class="nav-link text-sm text-text-secondary font-medium">Blog</a>
                            <a href="/#kontak" class="nav-link text-sm text-text-secondary font-medium">Kontak</a>
                        <?php endif; ?>
                    </div>

                    <!-- Auth Buttons -->
                    <?php if(auth()->guard()->check()): ?>
                    <div class="flex items-center gap-3 pl-4 border-l border-border">
                        <?php if(auth()->user()->role_id == 5): ?>
                            <a href="/customer/dashboard" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark px-4 py-2 border border-primary rounded-lg transition-all">
                                <i class="ri-user-line"></i> Dashboard
                            </a>
                        <?php else: ?>
                            <a href="/admin" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark px-4 py-2 border border-primary rounded-lg transition-all">
                                <i class="ri-dashboard-line"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-750 px-3 py-2">Logout</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMenu()" class="md:hidden p-2 rounded-lg hover:bg-surface transition-colors" aria-label="Toggle menu">
                    <i class="ri-menu-line text-2xl text-text-secondary" id="menu-icon"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu md:hidden border-t border-border" id="mobile-menu">
            <div class="px-4 py-3 space-y-1 bg-white">
                <?php $__empty_1 = true; $__currentLoopData = $headerMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if($hMenu->children && $hMenu->children->count() > 0): ?>
                        <div class="space-y-1">
                            <a href="<?php echo e($hMenu->url); ?>" target="<?php echo e($hMenu->target); ?>" class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                                <span class="flex items-center">
                                    <?php if($hMenu->icon): ?><i class="<?php echo e($hMenu->icon); ?> mr-1.5"></i><?php endif; ?>
                                    <?php echo e($hMenu->nama); ?>

                                </span>
                                <i class="ri-arrow-down-s-line"></i>
                            </a>
                            <div class="pl-4 space-y-1 pb-1">
                                <?php $__currentLoopData = $hMenu->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e($child->url); ?>" target="<?php echo e($child->target); ?>" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                                        <?php if($child->icon): ?><i class="<?php echo e($child->icon); ?> mr-1.5"></i><?php endif; ?>
                                        <?php echo e($child->nama); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo e($hMenu->url); ?>" target="<?php echo e($hMenu->target); ?>" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">
                            <?php if($hMenu->icon): ?><i class="<?php echo e($hMenu->icon); ?> mr-1.5"></i><?php endif; ?>
                            <?php echo e($hMenu->nama); ?>

                        </a>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <a href="/#layanan" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">Layanan</a>
                    <a href="/#tentang" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">Tentang</a>
                    <a href="/blog" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">Blog</a>
                    <a href="/#kontak" class="block px-3 py-2 rounded-lg text-sm text-text-secondary hover:bg-surface hover:text-primary transition-colors">Kontak</a>
                <?php endif; ?>
                
                <div class="pt-2 border-t border-border">
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->role_id == 5): ?>
                            <a href="/customer/dashboard" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface">
                                <i class="ri-user-line"></i> Dashboard
                            </a>
                        <?php else: ?>
                            <a href="/admin" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-semibold text-primary hover:bg-surface">
                                <i class="ri-dashboard-line"></i> Admin Panel
                            </a>
                        <?php endif; ?>
                        <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full text-left px-3 py-2 rounded-lg text-sm text-red-500 hover:bg-red-50">Logout</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main View Contents -->
    <?php echo $__env->yieldContent('content'); ?>

    <!-- ============================================= -->
    <!-- FOOTER                                        -->
    <!-- ============================================= -->
    <footer class="bg-surface border-t border-border">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 md:py-16">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Branding -->
                <div class="space-y-4">
                    <a href="/" class="flex items-center">
                        <img src="<?php echo e(asset('header.png')); ?>" alt="<?php echo e($siteName); ?>" class="h-10 w-auto object-contain">
                    </a>
                    <p class="text-sm text-text-secondary leading-relaxed">
                        Layanan kebersihan rumah dan kantor profesional terbaik di Pekanbaru. Hubungi kami sekarang untuk solusi kebersihan Anda.
                    </p>
                </div>

                <!-- Links -->
                <div>
                    <h4 class="text-sm font-semibold text-text-primary mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <?php $__empty_1 = true; $__currentLoopData = $footerMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li>
                                <a href="<?php echo e($fMenu->url); ?>" target="<?php echo e($fMenu->target); ?>" class="text-text-secondary hover:text-primary transition-colors">
                                    <?php if($fMenu->icon): ?><i class="<?php echo e($fMenu->icon); ?> mr-1"></i><?php endif; ?>
                                    <?php echo e($fMenu->nama); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li><a href="/#layanan" class="text-text-secondary hover:text-primary transition-colors">Layanan Kami</a></li>
                            <li><a href="/#tentang" class="text-text-secondary hover:text-primary transition-colors">Tentang Kami</a></li>
                            <li><a href="/blog" class="text-text-secondary hover:text-primary transition-colors">Blog / Tips</a></li>
                            <li><a href="/#kontak" class="text-text-secondary hover:text-primary transition-colors">Kontak</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Social links -->
                <div>
                    <h4 class="text-sm font-semibold text-text-primary mb-4">Hubungi Kami</h4>
                    <ul class="space-y-2.5 text-sm text-text-secondary">
                        <li class="flex items-start gap-2">
                            <i class="ri-map-pin-line text-lg text-primary shrink-0"></i>
                            <span><?php echo e($address); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ri-phone-line text-lg text-primary shrink-0"></i>
                            <span><?php echo e($phone); ?></span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="ri-mail-line text-lg text-primary shrink-0"></i>
                            <span><?php echo e($email); ?></span>
                        </li>
                    </ul>
                </div>

                <!-- Socials -->
                <div>
                    <h4 class="text-sm font-semibold text-text-primary mb-4">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="https://instagram.com/<?php echo e(ltrim($instagram, '@')); ?>" target="_blank" class="w-10 h-10 rounded-full border border-border flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="Instagram">
                            <i class="ri-instagram-line text-lg"></i>
                        </a>
                        <a href="https://facebook.com/<?php echo e($facebook); ?>" target="_blank" class="w-10 h-10 rounded-full border border-border flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="Facebook">
                            <i class="ri-facebook-box-line text-lg"></i>
                        </a>
                        <a href="https://tiktok.com/<?php echo e($tiktok); ?>" target="_blank" class="w-10 h-10 rounded-full border border-border flex items-center justify-center hover:bg-primary hover:text-white hover:border-primary transition-colors text-text-secondary" title="TikTok">
                            <i class="ri-tiktok-line text-lg"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Credits -->
            <div class="pt-8 border-t border-border text-center md:flex md:justify-between md:text-left">
                <p class="text-xs text-text-secondary">&copy; <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. Hak Cipta Dilindungi.</p>
                <p class="text-xs text-text-secondary mt-2 md:mt-0">Design inspired by Team IT PHC.</p>
            </div>
        </div>
    </footer>

    <!-- Floating WA Button -->
    <a href="https://wa.me/<?php echo e($whatsapp); ?>?text=Halo%20PHC,%20saya%20ingin%20pesan%20jasa%20cleaning" target="_blank" class="fixed bottom-6 right-6 w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-lg hover:bg-[#20ba5a] transition-all hover:scale-105 z-50 wa-pulse" aria-label="Hubungi WhatsApp">
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
    </script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/layouts/public.blade.php ENDPATH**/ ?>