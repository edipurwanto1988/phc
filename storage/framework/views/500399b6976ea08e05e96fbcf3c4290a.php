<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Admin'); ?> - PHC Pekanbaru</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('favicon.png')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <?php
        $siteName = \App\Models\Setting::get('site_name', 'PHC Pekanbaru');
    ?>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 fixed h-full flex flex-col z-30">
            <div class="p-6 border-b border-gray-200 shrink-0">
                <a href="/admin" class="block">
                    <img src="<?php echo e(asset('header.png')); ?>" alt="<?php echo e($siteName); ?>" class="h-12 w-auto object-contain mx-auto">
                </a>
                <p class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-wider text-center bg-gray-50 py-1 rounded border border-gray-150"><?php echo e(auth()->user()->role->name ?? 'User'); ?></p>
            </div>
            
            <nav class="p-4 space-y-1 overflow-y-auto flex-1">
                <!-- Dashboard -->
                <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin') && !request()->is('admin/*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-dashboard-line text-xl"></i><span>Dashboard</span>
                </a>

                <!-- Orders (Pesanan) -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'manage_orders')): ?>
                <a href="/admin/orders" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/orders*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-calendar-todo-line text-xl"></i><span>Pesanan (Orders)</span>
                </a>
                <?php endif; ?>

                <!-- Customers -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'manage_customers')): ?>
                <a href="/admin/customers" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/customers*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-team-line text-xl"></i><span>Customer</span>
                </a>
                <?php endif; ?>

                <!-- Services Master (Jasa) -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'manage_services')): ?>
                <?php
                    $servicesActive = request()->is('admin/services*') || request()->is('admin/service-categories*');
                ?>
                <div x-data="{ open: <?php echo e($servicesActive ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg <?php echo e($servicesActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <span class="flex items-center gap-3">
                            <i class="ri-sparkling-line text-xl"></i><span>Master Jasa</span>
                        </span>
                        <i class="ri-arrow-down-s-line text-lg transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="/admin/services" class="flex items-center gap-3 px-4 py-2 rounded-lg <?php echo e(request()->is('admin/services') || request()->is('admin/services/*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                            <i class="ri-checkbox-blank-circle-line text-xs"></i><span>Daftar Jasa</span>
                        </a>
                        <a href="/admin/service-categories" class="flex items-center gap-3 px-4 py-2 rounded-lg <?php echo e(request()->is('admin/service-categories*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                            <i class="ri-checkbox-blank-circle-line text-xs"></i><span>Kategori Jasa</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Users & Roles -->
                <?php if(auth()->user()->role && auth()->user()->role->name === 'Super Admin'): ?>
                <?php
                    $usersActive = request()->is('admin/users*') || request()->is('admin/roles*');
                ?>
                <div x-data="{ open: <?php echo e($usersActive ? 'true' : 'false'); ?> }">
                    <button @click="open = !open" class="w-full flex items-center justify-between gap-3 px-4 py-2.5 rounded-lg <?php echo e($usersActive ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-100'); ?>">
                        <span class="flex items-center gap-3">
                            <i class="ri-user-settings-line text-xl"></i><span>Tim & Hak Akses</span>
                        </span>
                        <i class="ri-arrow-down-s-line text-lg transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 pl-4">
                        <a href="/admin/users" class="flex items-center gap-3 px-4 py-2 rounded-lg <?php echo e(request()->is('admin/users*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                            <i class="ri-user-line text-sm"></i><span>Daftar User</span>
                        </a>
                        <a href="/admin/roles" class="flex items-center gap-3 px-4 py-2 rounded-lg <?php echo e(request()->is('admin/roles*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'); ?>">
                            <i class="ri-shield-line text-sm"></i><span>Role & Hak Akses</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Blog/Posts -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'manage_blog')): ?>
                <a href="/admin/posts" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/posts*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-article-line text-xl"></i><span>Blog / Tips</span>
                </a>
                <a href="/admin/halaman" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/halaman*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-pages-line text-xl"></i><span>Laman Statis</span>
                </a>
                <?php endif; ?>

                <!-- Testimonials -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'manage_services')): ?>
                <a href="/admin/testimonials" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/testimonials*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-chat-voice-line text-xl"></i><span>Testimoni</span>
                </a>
                <?php endif; ?>

                <!-- Financial Reports -->
                <?php if (\Illuminate\Support\Facades\Blade::check('hasperm', 'view_reports')): ?>
                <a href="/admin/reports" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/reports*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-file-chart-line text-xl"></i><span>Laporan Ringkas</span>
                </a>
                <?php endif; ?>

                <!-- Settings -->
                <?php if(auth()->user()->role && auth()->user()->role->name === 'Super Admin'): ?>
                <a href="/admin/menu" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/menu*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-menu-line text-xl"></i><span>Menu Publik</span>
                </a>
                <a href="/admin/settings" class="flex items-center gap-3 px-4 py-2.5 rounded-lg <?php echo e(request()->is('admin/settings*') ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-700 hover:bg-gray-100'); ?>">
                    <i class="ri-settings-3-line text-xl"></i><span>Pengaturan Web</span>
                </a>
                <?php endif; ?>
            </nav>
            
            <!-- Bottom user profile logout -->
            <div class="p-4 border-t border-gray-200 shrink-0 bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold shrink-0">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-gray-800 truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-xs text-gray-500 truncate"><?php echo e(auth()->user()->email); ?></p>
                    </div>
                </div>
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="shrink-0">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Logout">
                        <i class="ri-logout-box-r-line text-lg"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Content Area -->
        <div class="flex-1 ml-64 flex flex-col min-h-screen">
            <!-- Header bar -->
            <header class="bg-white border-b border-gray-200 h-16 px-8 flex justify-between items-center sticky top-0 z-20 shadow-sm shrink-0">
                <div class="flex items-center">
                    <h2 class="text-base font-semibold text-gray-700 flex items-center gap-2">
                        <?php echo $__env->yieldContent('header'); ?>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full font-medium">Local Time: <?php echo e(now()->format('H:i')); ?></span>
                    <a href="/" target="_blank" class="text-sm text-blue-600 hover:underline flex items-center gap-1 font-medium">
                        <i class="ri-external-link-line"></i> Kunjungi Situs
                    </a>
                </div>
            </header>

            <!-- Main view contents -->
            <main class="p-8 flex-1">
                <?php if(session('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2">
                        <i class="ri-checkbox-circle-line text-lg"></i>
                        <span><?php echo e(session('success')); ?></span>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                        <div class="flex items-center gap-2 mb-2 font-medium">
                            <i class="ri-error-warning-line text-lg"></i>
                            <span>Terjadi kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/layouts/admin.blade.php ENDPATH**/ ?>